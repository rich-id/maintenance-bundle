<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Tests\UserInterface\Controller;

use RichCongress\TestSuite\TestCase\ControllerTestCase;
use RichId\MaintenanceBundle\Domain\Event\WebsiteClosedEvent;
use RichId\MaintenanceBundle\Domain\Event\WebsiteOpenedEvent;
use RichId\MaintenanceBundle\Domain\UseCase\IsWebsiteClosed;
use RichId\MaintenanceBundle\Infrastructure\Adapter\MaintenanceManager;
use RichId\MaintenanceBundle\Infrastructure\FormType\MaintenanceFormType;
use RichId\MaintenanceBundle\Tests\Resources\Entity\DummyUser;
use RichId\MaintenanceBundle\Tests\Resources\Fixtures\DummyUserFixtures;
use RichId\MaintenanceBundle\Tests\Resources\Stubs\EventDispatcherStub;
use RichId\MaintenanceBundle\Tests\Resources\Stubs\LoggerStub;
use Symfony\Component\HttpFoundation\Response;

/**
 * @covers \RichId\MaintenanceBundle\Infrastructure\Rule\HasAccessToAdministration
 * @covers \RichId\MaintenanceBundle\UserInterface\Controller\MaintenanceAdminRoute
 */
final class MaintenanceAdminRouteTest extends ControllerTestCase
{
    /** @var IsWebsiteClosed */
    public $isWebsiteClosed;

    /** @var EventDispatcherStub */
    public $eventDispatcherStub;

    /** @var LoggerStub */
    public $loggerStub;

    /** @var MaintenanceManager */
    public $maintenanceManager;

    public function testRouteNotLogged(): void
    {
        $response = $this->getClient()->get('/administration/maintenance');
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testRouteBadRole(): void
    {
        $user = $this->getReference(DummyUser::class, DummyUserFixtures::USER);
        $this->authenticateUser($user);

        $response = $this->getClient()->get('/administration/maintenance');
        $this->assertSame(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testRouteAsAdmin(): void
    {
        $user = $this->getReference(DummyUser::class, DummyUserFixtures::USER_ADMIN);
        $this->authenticateUser($user);

        $response = $this->getClient()->get('/administration/maintenance');
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $content = $response->getContent() !== false ? $response->getContent() : '';

        $this->assertStringContainsString('Save', $content);
        $this->assertStringContainsString('Site in maintenance', $content);
        $this->assertStringNotContainsString('Please note that you are not on the list of authorised IPs. If you decide to close the site, you will no longer have access to it.', $content);
    }

    public function testRouteAsAdminAndNotAuthorizedIp(): void
    {
        $user = $this->getReference(DummyUser::class, DummyUserFixtures::USER_ADMIN);
        $this->authenticateUser($user);

        $this->getClient()->getBrowser()->setServerParameter('REMOTE_ADDR', '12.12.12.12');
        $response = $this->getClient()->get('/administration/maintenance');

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $content = $response->getContent() !== false ? $response->getContent() : '';

        $this->assertStringContainsString('Save', $content);
        $this->assertStringContainsString('Site in maintenance', $content);
        $this->assertStringContainsString('Please note that you are not on the list of authorised IPs. If you decide to close the site, you will no longer have access to it.', $content);
    }

    public function testRouteBadRolePost(): void
    {
        $user = $this->getReference(DummyUser::class, DummyUserFixtures::USER);
        $this->authenticateUser($user);

        $response = $this->getClient()->post(
            '/administration/maintenance',
            [],
            [
                'maintenance_form' => [
                        'isClosed' => '1',
                    ],
            ]
        );

        $this->assertSame(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testRouteAsAdminPostClose(): void
    {
        $this->assertFalse(($this->isWebsiteClosed)());

        $user = $this->getReference(DummyUser::class, DummyUserFixtures::USER_ADMIN);
        $this->authenticateUser($user);

        $response = $this->getClient()->post(
            '/administration/maintenance',
            [],
            [
                'maintenance_form' => [
                    'isClosed' => '1',
                    'save'     => '',
                    '_token'   => $this->getCsrfToken(MaintenanceFormType::class),
                ],
            ]
        );

        $this->assertSame(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertSame('/administration/maintenance', $response->headers->get('location'));
        $this->assertTrue(($this->isWebsiteClosed)());

        $this->assertCount(1, $this->eventDispatcherStub->getEvents());
        $this->assertInstanceOf(WebsiteClosedEvent::class, $this->eventDispatcherStub->getEvents()[0]);

        $this->assertCount(1, $this->loggerStub->getLogs());

        $log = $this->loggerStub->getLogs()[0];
        $this->assertSame('info', $log[0]);
        $this->assertStringContainsString('The site is under maintenance.', $log[1]);
        $this->assertStringContainsString('Date: ', $log[1]);
        $this->assertStringContainsString('User: my_user_2.', $log[1]);
    }

    public function testRouteAsAdminPostCloseAlreadyClosed(): void
    {
        $this->maintenanceManager->lock();
        $this->assertTrue(($this->isWebsiteClosed)());

        $user = $this->getReference(DummyUser::class, DummyUserFixtures::USER_ADMIN);
        $this->authenticateUser($user);

        $response = $this->getClient()->post(
            '/administration/maintenance',
            [],
            [
                'maintenance_form' => [
                    'isClosed' => '1',
                    'save'     => '',
                    '_token'   => $this->getCsrfToken(MaintenanceFormType::class),
                ],
            ]
        );

        $this->assertSame(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertSame('/administration/maintenance', $response->headers->get('location'));
        $this->assertTrue(($this->isWebsiteClosed)());

        $this->assertEmpty($this->eventDispatcherStub->getEvents());
        $this->assertEmpty($this->loggerStub->getLogs());
    }

    public function testRouteAsAdminPostOpen(): void
    {
        $this->maintenanceManager->lock();
        $this->assertTrue(($this->isWebsiteClosed)());

        $user = $this->getReference(DummyUser::class, DummyUserFixtures::USER_ADMIN);
        $this->authenticateUser($user);

        $response = $this->getClient()->post(
            '/administration/maintenance',
            [],
            [
                'maintenance_form' => [
                    'save'   => '',
                    '_token' => $this->getCsrfToken(MaintenanceFormType::class),
                ],
            ]
        );

        $this->assertSame(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertSame('/administration/maintenance', $response->headers->get('location'));
        $this->assertFalse(($this->isWebsiteClosed)());

        $this->assertCount(1, $this->eventDispatcherStub->getEvents());
        $this->assertInstanceOf(WebsiteOpenedEvent::class, $this->eventDispatcherStub->getEvents()[0]);

        $this->assertCount(1, $this->loggerStub->getLogs());

        $log = $this->loggerStub->getLogs()[0];
        $this->assertSame('info', $log[0]);
        $this->assertStringContainsString('The site is no longer under maintenance.', $log[1]);
        $this->assertStringContainsString('Date: ', $log[1]);
        $this->assertStringContainsString('User: my_user_2.', $log[1]);
    }

    public function testRouteAsAdminPostOpenAlreadyOpened(): void
    {
        $this->assertFalse(($this->isWebsiteClosed)());

        $user = $this->getReference(DummyUser::class, DummyUserFixtures::USER_ADMIN);
        $this->authenticateUser($user);

        $response = $this->getClient()->post(
            '/administration/maintenance',
            [],
            [
                'maintenance_form' => [
                    'save'   => '',
                    '_token' => $this->getCsrfToken(MaintenanceFormType::class),
                ],
            ]
        );

        $this->assertSame(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertSame('/administration/maintenance', $response->headers->get('location'));
        $this->assertFalse(($this->isWebsiteClosed)());

        $this->assertEmpty($this->eventDispatcherStub->getEvents());
        $this->assertEmpty($this->loggerStub->getLogs());
    }
}

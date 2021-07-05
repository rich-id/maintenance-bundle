<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Tests\Domain\EventListener;

use RichCongress\TestFramework\TestConfiguration\Annotation\TestConfig;
use RichCongress\TestSuite\TestCase\TestCase;
use RichId\MaintenanceBundle\Domain\Event\WebsiteClosedEvent;
use RichId\MaintenanceBundle\Domain\EventListener\WebsiteClosedEventListener;
use RichId\MaintenanceBundle\Tests\Resources\Entity\DummyUser;
use RichId\MaintenanceBundle\Tests\Resources\Fixtures\DummyUserFixtures;
use RichId\MaintenanceBundle\Tests\Resources\Stubs\LoggerStub;

/**
 * @covers \RichId\MaintenanceBundle\Domain\EventListener\WebsiteClosedEventListener
 * @TestConfig("fixtures")
 */
final class WebsiteClosedEventListenerTest extends TestCase
{
    /** @var WebsiteClosedEventListener */
    public $listener;

    /** @var LoggerStub */
    public $loggerStub;

    public function testListener(): void
    {
        $user = $this->getReference(DummyUser::class, DummyUserFixtures::USER);
        $this->authenticateUser($user);

        $event = new WebsiteClosedEvent();

        ($this->listener)($event);
        $this->assertCount(1, $this->loggerStub->getLogs());

        $log = $this->loggerStub->getLogs()[0];
        $this->assertSame('info', $log[0]);
        $this->assertStringContainsString('The site is under maintenance.', $log[1]);
        $this->assertStringContainsString('Date: ', $log[1]);
        $this->assertStringContainsString('User: my_user_1.', $log[1]);
    }
}

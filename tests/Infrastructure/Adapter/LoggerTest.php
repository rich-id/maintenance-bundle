<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Tests\Infrastructure\Adapter;

use RichCongress\TestFramework\TestConfiguration\Annotation\TestConfig;
use RichCongress\TestSuite\TestCase\TestCase;
use RichId\MaintenanceBundle\Infrastructure\Adapter\Logger;
use RichId\MaintenanceBundle\Tests\Resources\Entity\DummyUser;
use RichId\MaintenanceBundle\Tests\Resources\Fixtures\DummyUserFixtures;
use RichId\MaintenanceBundle\Tests\Resources\Stubs\LoggerStub;

/**
 * @covers \RichId\MaintenanceBundle\Infrastructure\Adapter\Logger
 * @TestConfig("fixtures")
 */
final class LoggerTest extends TestCase
{
    /** @var Logger */
    public $adapter;

    /** @var LoggerStub */
    public $loggerStub;

    public function testLogSiteClosed(): void
    {
        $user = $this->getReference(DummyUser::class, DummyUserFixtures::USER);
        $this->authenticateUser($user);

        $this->adapter->logSiteClosed();

        $this->assertCount(1, $this->loggerStub->getLogs());

        $log = $this->loggerStub->getLogs()[0];
        $this->assertSame('info', $log[0]);
        $this->assertStringContainsString('The site is under maintenance.', $log[1]);
        $this->assertStringContainsString('Date: ', $log[1]);
        $this->assertStringContainsString('User: my_user_1.', $log[1]);

        $this->assertSame(
            [
                'extra' => [
                    '_event'  => 'maintenance.status_change',
                    '_closed' => true,
                    '_user'   => 'my_user_1',
                ],
            ],
            $log[2]
        );
    }

    public function testLogSiteOpened(): void
    {
        $user = $this->getReference(DummyUser::class, DummyUserFixtures::USER);
        $this->authenticateUser($user);

        $this->adapter->logSiteOpened();

        $this->assertCount(1, $this->loggerStub->getLogs());

        $log = $this->loggerStub->getLogs()[0];
        $this->assertSame('info', $log[0]);
        $this->assertStringContainsString('The site is no longer under maintenance.', $log[1]);
        $this->assertStringContainsString('Date: ', $log[1]);
        $this->assertStringContainsString('User: my_user_1.', $log[1]);

        $this->assertSame(
            [
                'extra' => [
                    '_event'  => 'maintenance.status_change',
                    '_closed' => false,
                    '_user'   => 'my_user_1',
                ],
            ],
            $log[2]
        );
    }
}

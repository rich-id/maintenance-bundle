<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Tests\Domain\EventListener;

use RichCongress\TestFramework\TestConfiguration\Attribute\TestConfig;
use RichCongress\TestSuite\TestCase\TestCase;
use RichId\MaintenanceBundle\Domain\Event\WebsiteOpenedEvent;
use RichId\MaintenanceBundle\Domain\EventListener\WebsiteOpenedEventListener;
use RichId\MaintenanceBundle\Tests\Resources\Entity\DummyUser;
use RichId\MaintenanceBundle\Tests\Resources\Fixtures\DummyUserFixtures;
use RichId\MaintenanceBundle\Tests\Resources\Stubs\LoggerStub;

/** @covers \RichId\MaintenanceBundle\Domain\EventListener\WebsiteOpenedEventListener */
#[TestConfig('fixtures')]
final class WebsiteOpenedEventListenerTest extends TestCase
{
    /** @var WebsiteOpenedEventListener */
    public $listener;

    /** @var LoggerStub */
    public $loggerStub;

    public function testListener(): void
    {
        $this->authenticate(DummyUser::class, DummyUserFixtures::USER);

        $event = new WebsiteOpenedEvent();

        ($this->listener)($event);
        $this->assertCount(1, $this->loggerStub->getLogs());

        $log = $this->loggerStub->getLogs()[0];
        $this->assertSame('info', $log[0]);
        $this->assertStringContainsString('The site is no longer under maintenance.', $log[1]);
        $this->assertStringContainsString('Date: ', $log[1]);
        $this->assertStringContainsString('User: my_user_1.', $log[1]);
    }
}

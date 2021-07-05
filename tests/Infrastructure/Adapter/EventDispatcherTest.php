<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Tests\Infrastructure\Adapter;

use RichCongress\TestFramework\TestConfiguration\Annotation\TestConfig;
use RichCongress\TestSuite\TestCase\TestCase;
use RichId\MaintenanceBundle\Domain\Event\WebsiteClosedEvent;
use RichId\MaintenanceBundle\Domain\Event\WebsiteOpenedEvent;
use RichId\MaintenanceBundle\Infrastructure\Adapter\EventDispatcher;
use RichId\MaintenanceBundle\Tests\Resources\Stubs\EventDispatcherStub;

/**
 * @covers \RichId\MaintenanceBundle\Infrastructure\Adapter\EventDispatcher
 * @TestConfig("kernel")
 */
final class EventDispatcherTest extends TestCase
{
    /** @var EventDispatcher */
    public $adapter;

    /** @var EventDispatcherStub */
    public $eventDispatcherStub;

    public function testDispatchWebsiteClosedEvent(): void
    {
        $event = new WebsiteClosedEvent();

        $this->adapter->dispatchWebsiteClosedEvent($event);

        $this->assertCount(1, $this->eventDispatcherStub->getEvents());
        $this->assertSame($event, $this->eventDispatcherStub->getEvents()[0]);
    }

    public function testDispatchWebsiteOpenedEvent(): void
    {
        $event = new WebsiteOpenedEvent();

        $this->adapter->dispatchWebsiteOpenedEvent($event);

        $this->assertCount(1, $this->eventDispatcherStub->getEvents());
        $this->assertSame($event, $this->eventDispatcherStub->getEvents()[0]);
    }
}

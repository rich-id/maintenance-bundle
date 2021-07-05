<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Infrastructure\Adapter;

use RichId\MaintenanceBundle\Domain\Event\WebsiteClosedEvent;
use RichId\MaintenanceBundle\Domain\Event\WebsiteOpenedEvent;
use RichId\MaintenanceBundle\Domain\Port\EventDispatcherInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcherInterface;

class EventDispatcher implements EventDispatcherInterface
{
    /** @var SymfonyEventDispatcherInterface */
    protected $eventDispatcher;

    public function __construct(SymfonyEventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function dispatchWebsiteClosedEvent(WebsiteClosedEvent $event): void
    {
        $this->eventDispatcher->dispatch($event);
    }

    public function dispatchWebsiteOpenedEvent(WebsiteOpenedEvent $event): void
    {
        $this->eventDispatcher->dispatch($event);
    }
}

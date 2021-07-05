<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Domain\UseCase;

use RichId\MaintenanceBundle\Domain\Event\WebsiteOpenedEvent;
use RichId\MaintenanceBundle\Domain\Exception\WebsiteAlreadyOpenedException;
use RichId\MaintenanceBundle\Domain\Port\EventDispatcherInterface;
use RichId\MaintenanceBundle\Domain\Port\LoggerInterface;
use RichId\MaintenanceBundle\Domain\Port\MaintenanceDriverInterface;

class OpenWebsite
{
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var LoggerInterface */
    protected $logger;

    /** @var MaintenanceDriverInterface */
    protected $maintenanceDriver;

    public function __construct(EventDispatcherInterface $eventDispatcher, LoggerInterface $logger, MaintenanceDriverInterface $maintenanceDriver)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
        $this->maintenanceDriver = $maintenanceDriver;
    }

    public function __invoke(): void
    {
        $driver = $this->maintenanceDriver->getMaintenanceDriver();

        if (!$driver->decide()) {
            throw new WebsiteAlreadyOpenedException();
        }

        $driver->unlock();
        $this->eventDispatcher->dispatchWebsiteOpenedEvent(new WebsiteOpenedEvent());
    }
}

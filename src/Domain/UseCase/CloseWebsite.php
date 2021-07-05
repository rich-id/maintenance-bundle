<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Domain\UseCase;

use RichId\MaintenanceBundle\Domain\Event\WebsiteClosedEvent;
use RichId\MaintenanceBundle\Domain\Exception\WebsiteAlreadyClosedException;
use RichId\MaintenanceBundle\Domain\Port\EventDispatcherInterface;
use RichId\MaintenanceBundle\Domain\Port\LoggerInterface;
use RichId\MaintenanceBundle\Domain\Port\MaintenanceDriverInterface;

class CloseWebsite
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

        if ($driver->decide()) {
            throw new WebsiteAlreadyClosedException();
        }

        $driver->lock();
        $this->eventDispatcher->dispatchWebsiteClosedEvent(new WebsiteClosedEvent());
    }
}

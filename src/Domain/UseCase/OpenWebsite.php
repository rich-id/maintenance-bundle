<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Domain\UseCase;

use RichId\MaintenanceBundle\Domain\Event\WebsiteOpenedEvent;
use RichId\MaintenanceBundle\Domain\Exception\WebsiteAlreadyOpenedException;
use RichId\MaintenanceBundle\Domain\Port\EventDispatcherInterface;
use RichId\MaintenanceBundle\Domain\Port\LoggerInterface;
use RichId\MaintenanceBundle\Domain\Port\MaintenanceManagerInterface;

class OpenWebsite
{
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var LoggerInterface */
    protected $logger;

    /** @var MaintenanceManagerInterface */
    protected $maintenanceManager;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger,
        MaintenanceManagerInterface $maintenanceManager
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
        $this->maintenanceManager = $maintenanceManager;
    }

    public function __invoke(): void
    {
        if (!$this->maintenanceManager->isLocked()) {
            throw new WebsiteAlreadyOpenedException();
        }

        $this->maintenanceManager->unlock();
        $this->eventDispatcher->dispatchWebsiteOpenedEvent(new WebsiteOpenedEvent());
    }
}

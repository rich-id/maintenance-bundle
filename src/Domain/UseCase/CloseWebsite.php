<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Domain\UseCase;

use RichId\MaintenanceBundle\Domain\Event\WebsiteClosedEvent;
use RichId\MaintenanceBundle\Domain\Exception\WebsiteAlreadyClosedException;
use RichId\MaintenanceBundle\Domain\Port\EventDispatcherInterface;
use RichId\MaintenanceBundle\Domain\Port\LoggerInterface;
use RichId\MaintenanceBundle\Domain\Port\MaintenanceManagerInterface;

class CloseWebsite
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
        if ($this->maintenanceManager->isLocked()) {
            throw new WebsiteAlreadyClosedException();
        }

        $this->maintenanceManager->lock();
        $this->eventDispatcher->dispatchWebsiteClosedEvent(new WebsiteClosedEvent());
    }
}

<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Domain\EventListener;

use RichId\MaintenanceBundle\Domain\Event\WebsiteOpenedEvent;
use RichId\MaintenanceBundle\Domain\Port\LoggerInterface;

final class WebsiteOpenedEventListener
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(WebsiteOpenedEvent $event): void
    {
        $this->logger->logSiteOpened();
    }
}

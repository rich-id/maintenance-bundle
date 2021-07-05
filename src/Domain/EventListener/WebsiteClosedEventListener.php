<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Domain\EventListener;

use RichId\MaintenanceBundle\Domain\Event\WebsiteClosedEvent;
use RichId\MaintenanceBundle\Domain\Port\LoggerInterface;

final class WebsiteClosedEventListener
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(WebsiteClosedEvent $event): void
    {
        $this->logger->logSiteClosed();
    }
}

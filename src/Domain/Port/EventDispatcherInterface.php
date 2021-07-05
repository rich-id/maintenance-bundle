<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Domain\Port;

use RichId\MaintenanceBundle\Domain\Event\WebsiteClosedEvent;
use RichId\MaintenanceBundle\Domain\Event\WebsiteOpenedEvent;

interface EventDispatcherInterface
{
    public function dispatchWebsiteClosedEvent(WebsiteClosedEvent $event): void;

    public function dispatchWebsiteOpenedEvent(WebsiteOpenedEvent $event): void;
}

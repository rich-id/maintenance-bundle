<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Domain\Port;

interface LoggerInterface
{
    public function logSiteOpened(): void;

    public function logSiteClosed(): void;
}

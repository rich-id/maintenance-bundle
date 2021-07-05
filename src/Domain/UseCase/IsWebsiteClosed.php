<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Domain\UseCase;

use RichId\MaintenanceBundle\Domain\Port\MaintenanceDriverInterface;

class IsWebsiteClosed
{
    /** @var MaintenanceDriverInterface */
    protected $maintenanceDriver;

    public function __construct(MaintenanceDriverInterface $maintenanceDriver)
    {
        $this->maintenanceDriver = $maintenanceDriver;
    }

    public function __invoke(): bool
    {
        return $this->maintenanceDriver->getMaintenanceDriver()->decide();
    }
}

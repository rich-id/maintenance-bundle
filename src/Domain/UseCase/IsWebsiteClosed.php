<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Domain\UseCase;

use RichId\MaintenanceBundle\Domain\Port\MaintenanceManagerInterface;

class IsWebsiteClosed
{
    /** @var MaintenanceManagerInterface */
    protected $maintenanceManager;

    public function __construct(MaintenanceManagerInterface $maintenanceManager)
    {
        $this->maintenanceManager = $maintenanceManager;
    }

    public function __invoke(): bool
    {
        return $this->maintenanceManager->isLocked();
    }
}

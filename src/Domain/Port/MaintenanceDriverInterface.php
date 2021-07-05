<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Domain\Port;

use Lexik\Bundle\MaintenanceBundle\Drivers\AbstractDriver;

interface MaintenanceDriverInterface
{
    public function getMaintenanceDriver(): AbstractDriver;
}

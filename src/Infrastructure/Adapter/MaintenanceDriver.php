<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Infrastructure\Adapter;

use Lexik\Bundle\MaintenanceBundle\Drivers\AbstractDriver;
use Lexik\Bundle\MaintenanceBundle\Drivers\DriverFactory;
use RichId\MaintenanceBundle\Domain\Port\MaintenanceDriverInterface;

class MaintenanceDriver implements MaintenanceDriverInterface
{
    /** @var DriverFactory */
    protected $driverFactory;

    public function __construct(DriverFactory $driverFactory)
    {
        $this->driverFactory = $driverFactory;
    }

    public function getMaintenanceDriver(): AbstractDriver
    {
        return $this->driverFactory->getDriver();
    }
}

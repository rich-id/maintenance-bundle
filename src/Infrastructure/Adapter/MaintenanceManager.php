<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Infrastructure\Adapter;

use Lexik\Bundle\MaintenanceBundle\Drivers\AbstractDriver;
use Lexik\Bundle\MaintenanceBundle\Drivers\DriverFactory;
use RichId\MaintenanceBundle\Domain\Port\MaintenanceManagerInterface;

class MaintenanceManager implements MaintenanceManagerInterface
{
    /** @var DriverFactory */
    protected $driverFactory;

    /** @var AbstractDriver */
    protected $driver;

    public function __construct(DriverFactory $driverFactory)
    {
        $this->driverFactory = $driverFactory;
    }

    public function lock(): void
    {
        $this->getDriver()->lock();
    }

    public function unlock(): void
    {
        $this->getDriver()->unlock();
    }

    public function isLocked(): bool
    {
        return $this->getDriver()->decide();
    }

    protected function getDriver(): AbstractDriver
    {
        if ($this->driver === null) {
            $this->driver = $this->driverFactory->getDriver();
        }

        return $this->driver;
    }
}

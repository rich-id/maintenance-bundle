<?php declare(strict_types=1);

namespace RichId\MaintenanceBundle\Utility;

use Lexik\Bundle\MaintenanceBundle\Drivers\DriverFactory;
use RichId\MaintenanceBundle\Model\MaintenanceModel;

class MaintenanceUtility
{
    /** @var DriverFactory */
    protected $driverFactory;

    public function __construct(DriverFactory $driverFactory)
    {
        $this->driverFactory = $driverFactory;
    }

    public function buildMaintenanceModel(): MaintenanceModel
    {
        return new MaintenanceModel($this->driverFactory->getDriver()->decide());
    }

    public function updateMaintenanceStatus(MaintenanceModel $model): void
    {
        $driver = $this->driverFactory->getDriver();

        if ($model->isLocked()) {
            $driver->lock();
        } else {
            $driver->unlock();
        }

        if ($driver->decide() === !$model->isLocked()) {
            throw new \Exception();
        }
    }
}
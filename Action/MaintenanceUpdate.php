<?php declare(strict_types=1);

namespace RichId\MaintenanceBundle\Action;

use Lexik\Bundle\MaintenanceBundle\Drivers\DriverFactory;
use RichId\MaintenanceBundle\Exception\MaintenanceStatusException;
use RichId\MaintenanceBundle\Model\MaintenanceModel;

/**
 * Class MaintenanceUpdate.
 *
 * @package   RichId\MaintenanceBundle\Action
 * @author    Hugo Dumazeau <hugo.dumazeau@rich-id.fr>
 * @copyright 2014 - 2021 RichId (https://www.rich-id.fr)
 */
class MaintenanceUpdate
{
    /** @var DriverFactory */
    protected $driverFactory;

    public function __construct(DriverFactory $driverFactory)
    {
        $this->driverFactory = $driverFactory;
    }

    public function __invoke(MaintenanceModel $model): void
    {
        $driver = $this->driverFactory->getDriver();

        if ($model->isLocked()) {
            $driver->lock();
        } else {
            $driver->unlock();
        }

        if ($driver->decide() === !$model->isLocked()) {
            throw new MaintenanceStatusException();
        }
    }
}

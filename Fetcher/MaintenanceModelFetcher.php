<?php declare(strict_types=1);

namespace RichId\MaintenanceBundle\Fetcher;

use Lexik\Bundle\MaintenanceBundle\Drivers\DriverFactory;
use RichId\MaintenanceBundle\Model\MaintenanceModel;

/**
 * Class MaintenanceModelFetcher.
 *
 * @package   RichId\MaintenanceBundle\Fetcher
 * @author    Hugo Dumazeau <hugo.dumazeau@rich-id.fr>
 * @copyright 2014 - 2021 RichId (https://www.rich-id.fr)
 */
class MaintenanceModelFetcher
{
    /** @var DriverFactory */
    protected $driverFactory;

    public function __construct(DriverFactory $driverFactory)
    {
        $this->driverFactory = $driverFactory;
    }

    public function __invoke(): MaintenanceModel
    {
        return new MaintenanceModel($this->driverFactory->getDriver()->decide());
    }
}

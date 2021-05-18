<?php declare(strict_types=1);

namespace RichId\MaintenanceBundle\Exception;

/**
 * Class MaintenanceStatusException.
 *
 * @package   RichId\MaintenanceBundle\Exception
 * @author    Hugo Dumazeau <hugo.dumazeau@rich-id.fr>
 * @copyright 2014 - 2021 RichId (https://www.rich-id.fr)
 */
class MaintenanceStatusException extends MaintenanceException
{
    public function __construct()
    {
        parent::__construct('The maintenance status could not be changed');
    }
}

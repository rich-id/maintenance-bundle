<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Domain\Exception;

class WebsiteAlreadyClosedException extends MaintenanceException
{
    public function __construct()
    {
        parent::__construct('The website is already closed.');
    }
}

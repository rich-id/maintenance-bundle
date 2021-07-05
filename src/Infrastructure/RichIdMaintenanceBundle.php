<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Infrastructure;

use RichCongress\BundleToolbox\Configuration\AbstractBundle;

class RichIdMaintenanceBundle extends AbstractBundle
{
    public const COMPILER_PASSES = [];
    public const ROLE_MAINTENANCE_ADMIN = 'ROLE_MAINTENANCE_ADMIN';
}

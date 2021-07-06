<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Domain\Port;

interface MaintenanceManagerInterface
{
    public function lock(): void;

    public function unlock(): void;

    public function isLocked(): bool;
}

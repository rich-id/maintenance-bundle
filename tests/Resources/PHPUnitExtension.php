<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Tests\Resources;

use PHPUnit\Runner\AfterTestHook;
use PHPUnit\Runner\BeforeTestHook;

final class PHPUnitExtension implements BeforeTestHook, AfterTestHook
{
    public function executeBeforeTest(string $test): void
    {
        @\unlink(__DIR__ . '/../maintenance-lock');
    }

    public function executeAfterTest(string $test, float $time): void
    {
        @\unlink(__DIR__ . '/../maintenance-lock');
    }
}

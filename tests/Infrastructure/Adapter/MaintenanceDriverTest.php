<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Tests\Infrastructure\Adapter;

use Lexik\Bundle\MaintenanceBundle\Drivers\FileDriver;
use RichCongress\TestFramework\TestConfiguration\Annotation\TestConfig;
use RichCongress\TestSuite\TestCase\TestCase;
use RichId\MaintenanceBundle\Infrastructure\Adapter\MaintenanceDriver;

/**
 * @covers \RichId\MaintenanceBundle\Infrastructure\Adapter\MaintenanceDriver
 * @TestConfig("kernel")
 */
final class MaintenanceDriverTest extends TestCase
{
    /** @var MaintenanceDriver */
    public $adapter;

    public function testGetMaintenanceDriver(): void
    {
        $driver = $this->adapter->getMaintenanceDriver();

        $this->assertInstanceOf(FileDriver::class, $driver);
    }
}

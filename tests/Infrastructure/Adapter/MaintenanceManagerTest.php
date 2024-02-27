<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Tests\Infrastructure\Adapter;

use RichCongress\TestFramework\TestConfiguration\Attribute\TestConfig;
use RichCongress\TestSuite\TestCase\TestCase;
use RichId\MaintenanceBundle\Infrastructure\Adapter\MaintenanceManager;

/** @covers \RichId\MaintenanceBundle\Infrastructure\Adapter\MaintenanceManager */
#[TestConfig('kernel')]
final class MaintenanceManagerTest extends TestCase
{
    /** @var MaintenanceManager */
    public $adapter;

    public function testLockAndUnlock(): void
    {
        self::assertFalse($this->adapter->isLocked());

        $this->adapter->lock();
        self::assertTrue($this->adapter->isLocked());

        $this->adapter->unlock();
        self::assertFalse($this->adapter->isLocked());
    }
}

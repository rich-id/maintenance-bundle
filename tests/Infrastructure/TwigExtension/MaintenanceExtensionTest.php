<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Tests\Infrastructure\TwigExtension;

use RichCongress\TestFramework\TestConfiguration\Annotation\TestConfig;
use RichCongress\TestSuite\TestCase\TestCase;
use RichId\MaintenanceBundle\Infrastructure\Adapter\MaintenanceDriver;
use RichId\MaintenanceBundle\Infrastructure\TwigExtension\MaintenanceExtension;
use RichId\MaintenanceBundle\Tests\Resources\Entity\DummyUser;
use RichId\MaintenanceBundle\Tests\Resources\Fixtures\DummyUserFixtures;

/**
 * @covers \RichId\MaintenanceBundle\Infrastructure\TwigExtension\MaintenanceExtension
 * @TestConfig("fixtures")
 */
class MaintenanceExtensionTest extends TestCase
{
    /** @var MaintenanceExtension */
    public $extension;

    /** @var MaintenanceDriver */
    public $maintenanceDriver;

    public function testGetFunctions(): void
    {
        $this->assertCount(2, $this->extension->getFunctions());
        $this->assertEquals('isWebsiteInMaintenance', $this->extension->getFunctions()[0]->getName());
        $this->assertEquals('hasAccessToMaintenanceAdministration', $this->extension->getFunctions()[1]->getName());
    }

    public function testIsNotWebsiteInMaintenance(): void
    {
        $this->assertFalse($this->extension->isWebsiteInMaintenance());
    }

    public function testIsWebsiteInMaintenance(): void
    {
        $this->maintenanceDriver->getMaintenanceDriver()->lock();

        $this->assertTrue($this->extension->isWebsiteInMaintenance());
    }

    public function testHasntAccessToMaintenanceAdministration(): void
    {
        $this->authenticate(DummyUser::class, DummyUserFixtures::USER);
        $this->assertFalse($this->extension->hasAccessToMaintenanceAdministration());
    }

    public function testHasAccessToMaintenanceAdministration(): void
    {
        $this->authenticate(DummyUser::class, DummyUserFixtures::USER_ADMIN);
        $this->assertTrue($this->extension->hasAccessToMaintenanceAdministration());
    }
}

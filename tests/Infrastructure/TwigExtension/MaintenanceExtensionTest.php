<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Tests\Infrastructure\TwigExtension;

use RichCongress\TestFramework\TestConfiguration\Annotation\TestConfig;
use RichCongress\TestSuite\TestCase\TestCase;
use RichId\MaintenanceBundle\Infrastructure\Adapter\MaintenanceDriver;
use RichId\MaintenanceBundle\Infrastructure\TwigExtension\MaintenanceExtension;
use RichId\MaintenanceBundle\Tests\Resources\Entity\DummyUser;
use RichId\MaintenanceBundle\Tests\Resources\Fixtures\DummyUserFixtures;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

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

    /** @var RequestStack */
    public $requestStack;

    public function testGetFunctions(): void
    {
        $this->assertCount(3, $this->extension->getFunctions());
        $this->assertEquals('isCurrentIpAuthorizedToAccessToClosedWebsite', $this->extension->getFunctions()[0]->getName());
        $this->assertEquals('isWebsiteInMaintenance', $this->extension->getFunctions()[1]->getName());
        $this->assertEquals('hasAccessToMaintenanceAdministration', $this->extension->getFunctions()[2]->getName());
    }

    public function testIsCurrentIpAuthorizedToAccessToClosedWebsiteWithoutRequest(): void
    {
        $this->assertFalse($this->extension->isCurrentIpAuthorizedToAccessToClosedWebsite());
    }

    public function testIsCurrentIpAuthorizedToAccessToClosedWebsite(): void
    {
        $request = new Request([], [], [], [], [], ['REMOTE_ADDR' => '127.0.0.1']);
        $this->requestStack->push($request);

        $this->assertTrue($this->extension->isCurrentIpAuthorizedToAccessToClosedWebsite());
    }

    public function testIsNotCurrentIpAuthorizedToAccessToClosedWebsite(): void
    {
        $request = new Request([], [], [], [], [], ['REMOTE_ADDR' => '12.12.12.12']);
        $this->requestStack->push($request);

        $this->assertFalse($this->extension->isCurrentIpAuthorizedToAccessToClosedWebsite());
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

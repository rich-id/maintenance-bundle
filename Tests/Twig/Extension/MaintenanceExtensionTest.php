<?php declare(strict_types=1);

namespace RichId\MaintenanceBundle\Tests\Twig\Extension;

use RichCongress\TestFramework\TestConfiguration\Annotation\TestConfig;
use RichCongress\TestSuite\TestCase\TestCase;
use RichId\MaintenanceBundle\Twig\Extension\MaintenanceExtension;

/**
 * Class MaintenanceExtensionTest.
 *
 * @package   RichId\MaintenanceBundle\Tests\Twig\Extension
 * @author    Hugo Dumazeau <hugo.dumazeau@rich-id.fr>
 * @copyright 2014 - 2021 RichId (https://www.rich-id.fr)
 *
 * @covers \RichId\MaintenanceBundle\Twig\Extension\MaintenanceExtension
 *
 * @TestConfig("kernel")
 */
class MaintenanceExtensionTest extends TestCase
{
    /** @var MaintenanceExtension */
    public $extension;

    public function testGetFunctions(): void
    {
        $this->assertCount(2, $this->extension->getFunctions());
        $this->assertEquals('isWebsiteInMaintenance', $this->extension->getFunctions()[0]->getName());
        $this->assertEquals('hasAccessToAdministration', $this->extension->getFunctions()[1]->getName());
    }

    public function testHasAccessToAdministration(): void
    {
        $this->assertTrue($this->extension->hasAccessToAdministration());
    }
}

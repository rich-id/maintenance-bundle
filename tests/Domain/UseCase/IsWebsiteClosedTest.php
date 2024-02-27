<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Tests\Domain\UseCase;

use RichCongress\TestFramework\TestConfiguration\Attribute\TestConfig;
use RichCongress\TestSuite\TestCase\TestCase;
use RichId\MaintenanceBundle\Domain\UseCase\IsWebsiteClosed;
use RichId\MaintenanceBundle\Infrastructure\Adapter\MaintenanceManager;

/** @covers \RichId\MaintenanceBundle\Domain\UseCase\IsWebsiteClosed */
#[TestConfig('kernel')]
final class IsWebsiteClosedTest extends TestCase
{
    /** @var IsWebsiteClosed */
    public $useCase;

    /** @var MaintenanceManager */
    public $maintenanceManager;

    public function testUseCaseClosed(): void
    {
        $this->assertFalse(($this->useCase)());
    }

    public function testUseCaseOpened(): void
    {
        $this->maintenanceManager->lock();

        $this->assertTrue(($this->useCase)());
    }
}

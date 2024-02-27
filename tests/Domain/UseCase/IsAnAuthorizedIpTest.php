<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Tests\Domain\UseCase;

use RichCongress\TestFramework\TestConfiguration\Attribute\TestConfig;
use RichCongress\TestSuite\TestCase\TestCase;
use RichId\MaintenanceBundle\Domain\UseCase\IsAnAuthorizedIp;

/** @covers \RichId\MaintenanceBundle\Domain\UseCase\IsAnAuthorizedIp */
#[TestConfig('fixtures')]
final class IsAnAuthorizedIpTest extends TestCase
{
    /** @var IsAnAuthorizedIp */
    public $useCase;

    public function testUseCaseWithAuthorisedIpV6(): void
    {
        $this->assertTrue(($this->useCase)('2001:0db8:3c4d:0015:0000:0000:1a2f:1a2b'));
    }

    public function testUseCaseWithAuthorisedIpV4(): void
    {
        $this->assertTrue(($this->useCase)('10.11.12.13'));
    }

    public function testUseCaseWithUnauthorisedIp(): void
    {
        $this->assertFalse(($this->useCase)('0.0.0.0'));
    }
}

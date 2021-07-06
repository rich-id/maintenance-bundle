<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Tests\Domain\UseCase;

use RichCongress\TestFramework\TestConfiguration\Annotation\TestConfig;
use RichCongress\TestSuite\TestCase\TestCase;
use RichId\MaintenanceBundle\Domain\Event\WebsiteOpenedEvent;
use RichId\MaintenanceBundle\Domain\Exception\WebsiteAlreadyOpenedException;
use RichId\MaintenanceBundle\Domain\UseCase\OpenWebsite;
use RichId\MaintenanceBundle\Infrastructure\Adapter\MaintenanceManager;
use RichId\MaintenanceBundle\Tests\Resources\Stubs\EventDispatcherStub;
use RichId\MaintenanceBundle\Tests\Resources\Stubs\LoggerStub;

/**
 * @covers \RichId\MaintenanceBundle\Domain\UseCase\OpenWebsite
 * @TestConfig("kernel")
 */
final class OpenWebsiteTest extends TestCase
{
    /** @var OpenWebsite */
    public $useCase;

    /** @var EventDispatcherStub */
    public $eventDispatcherStub;

    /** @var LoggerStub */
    public $loggerStub;

    /** @var MaintenanceManager */
    public $maintenanceManager;

    public function testUseCaseAlreadyOpened(): void
    {
        $this->expectException(WebsiteAlreadyOpenedException::class);
        $this->expectExceptionMessage('The website is already opened.');

        ($this->useCase)();
    }

    public function testUseCase(): void
    {
        $this->maintenanceManager->lock();
        $this->assertTrue($this->maintenanceManager->isLocked());

        ($this->useCase)();

        $this->assertFalse($this->maintenanceManager->isLocked());

        $this->assertCount(1, $this->eventDispatcherStub->getEvents());
        $this->assertInstanceOf(WebsiteOpenedEvent::class, $this->eventDispatcherStub->getEvents()[0]);

        $this->assertCount(1, $this->loggerStub->getLogs());

        $log = $this->loggerStub->getLogs()[0];
        $this->assertSame('info', $log[0]);
        $this->assertStringContainsString('The site is no longer under maintenance.', $log[1]);
    }
}

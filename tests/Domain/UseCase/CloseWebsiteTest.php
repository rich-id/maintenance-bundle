<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Tests\Domain\UseCase;

use RichCongress\TestFramework\TestConfiguration\Annotation\TestConfig;
use RichCongress\TestSuite\TestCase\TestCase;
use RichId\MaintenanceBundle\Domain\Event\WebsiteClosedEvent;
use RichId\MaintenanceBundle\Domain\Exception\WebsiteAlreadyClosedException;
use RichId\MaintenanceBundle\Domain\UseCase\CloseWebsite;
use RichId\MaintenanceBundle\Infrastructure\Adapter\MaintenanceManager;
use RichId\MaintenanceBundle\Tests\Resources\Stubs\EventDispatcherStub;
use RichId\MaintenanceBundle\Tests\Resources\Stubs\LoggerStub;

/**
 * @covers \RichId\MaintenanceBundle\Domain\UseCase\CloseWebsite
 * @TestConfig("kernel")
 */
final class CloseWebsiteTest extends TestCase
{
    /** @var CloseWebsite */
    public $useCase;

    /** @var EventDispatcherStub */
    public $eventDispatcherStub;

    /** @var LoggerStub */
    public $loggerStub;

    /** @var MaintenanceManager */
    public $maintenanceManager;

    public function testUseCaseAlreadyClose(): void
    {
        $this->expectException(WebsiteAlreadyClosedException::class);
        $this->expectExceptionMessage('The website is already closed.');

        $this->maintenanceManager->lock();

        ($this->useCase)();
    }

    public function testUseCase(): void
    {
        $this->assertFalse($this->maintenanceManager->isLocked());

        ($this->useCase)();

        $this->assertTrue($this->maintenanceManager->isLocked());

        $this->assertCount(1, $this->eventDispatcherStub->getEvents());
        $this->assertInstanceOf(WebsiteClosedEvent::class, $this->eventDispatcherStub->getEvents()[0]);

        $this->assertCount(1, $this->loggerStub->getLogs());

        $log = $this->loggerStub->getLogs()[0];
        $this->assertSame('info', $log[0]);
        $this->assertStringContainsString('The site is under maintenance.', $log[1]);
    }
}

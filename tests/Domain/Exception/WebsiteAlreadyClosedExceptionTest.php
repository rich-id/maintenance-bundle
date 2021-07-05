<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Tests\Domain\Exception;

use RichCongress\TestSuite\TestCase\TestCase;
use RichId\MaintenanceBundle\Domain\Exception\MaintenanceException;
use RichId\MaintenanceBundle\Domain\Exception\WebsiteAlreadyClosedException;

/**
 * @covers \RichId\MaintenanceBundle\Domain\Exception\WebsiteAlreadyClosedException
 */
final class WebsiteAlreadyClosedExceptionTest extends TestCase
{
    public function testException(): void
    {
        $exception = new WebsiteAlreadyClosedException();

        $this->assertInstanceOf(MaintenanceException::class, $exception);
        $this->assertSame('The website is already closed.', $exception->getMessage());
    }
}

<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Tests\Domain\Exception;

use RichCongress\TestSuite\TestCase\TestCase;
use RichId\MaintenanceBundle\Domain\Exception\MaintenanceException;
use RichId\MaintenanceBundle\Domain\Exception\WebsiteAlreadyOpenedException;

/**
 * @covers \RichId\MaintenanceBundle\Domain\Exception\WebsiteAlreadyOpenedException
 */
final class WebsiteAlreadyOpenedExceptionTest extends TestCase
{
    public function testException(): void
    {
        $exception = new WebsiteAlreadyOpenedException();

        $this->assertInstanceOf(MaintenanceException::class, $exception);
        $this->assertSame('The website is already opened.', $exception->getMessage());
    }
}

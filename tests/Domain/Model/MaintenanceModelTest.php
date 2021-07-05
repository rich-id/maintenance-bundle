<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Tests\Domain\Model;

use RichCongress\TestSuite\TestCase\TestCase;
use RichId\MaintenanceBundle\Domain\Model\MaintenanceModel;

/**
 * @covers \RichId\MaintenanceBundle\Domain\Model\MaintenanceModel
 */
final class MaintenanceModelTest extends TestCase
{
    public function testModel(): void
    {
        $model = new MaintenanceModel(false);

        $this->assertFalse($model->isClosed());

        $model->setIsClosed(true);
        $this->assertTrue($model->isClosed());
    }
}

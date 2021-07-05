<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Tests\Infrastructure\FormType;

use RichCongress\TestFramework\TestConfiguration\Annotation\TestConfig;
use RichId\MaintenanceBundle\Domain\Model\MaintenanceModel;
use RichId\MaintenanceBundle\Infrastructure\FormType\MaintenanceFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * @covers \RichId\MaintenanceBundle\Infrastructure\FormType\MaintenanceFormType
 * @TestConfig("kernel")
 */
final class MaintenanceFormTypeTest extends TypeTestCase
{
    /** @var FormFactoryInterface */
    public $formFactory;

    public function testSubmitValidData(): void
    {
        $model = new MaintenanceModel(false);

        $form = $this->factory->create(MaintenanceFormType::class, $model);

        $form->submit(['isClosed' => true]);
        $this->assertTrue($form->isSynchronized());

        $output = $form->getData();
        $this->assertTrue($output->isClosed());
    }
}

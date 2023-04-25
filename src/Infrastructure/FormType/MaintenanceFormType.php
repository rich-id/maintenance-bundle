<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Infrastructure\FormType;

use RichId\MaintenanceBundle\Domain\Model\MaintenanceModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaintenanceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'isClosed',
                CheckboxType::class,
                [
                    'required'           => false,
                    'label'              => 'maintenance.administration.is_locked',
                    'translation_domain' => 'maintenance',
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label'              => 'maintenance.administration.save',
                    'translation_domain' => 'maintenance',
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => MaintenanceModel::class,
            ]
        );
    }
}

<?php

namespace RichId\MaintenanceBundle\Twig\Extension;

use Lexik\Bundle\MaintenanceBundle\Drivers\DriverFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MaintenanceExtension extends AbstractExtension
{
    /** @var DriverFactory  */
    protected $driverFactory;

    public function __construct(ContainerInterface $container)
    {
        $this->driverFactory = $container->get('lexik_maintenance.driver.factory');
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('isLocked', [$this, 'isLocked']),
        ];
    }

    public function isLocked(): bool
    {
        return $this->driverFactory->getDriver()->decide();
    }
}

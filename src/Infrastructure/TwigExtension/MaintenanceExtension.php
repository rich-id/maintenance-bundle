<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Infrastructure\TwigExtension;

use RichId\MaintenanceBundle\Domain\UseCase\IsWebsiteClosed;
use RichId\MaintenanceBundle\Infrastructure\RichIdMaintenanceBundle;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MaintenanceExtension extends AbstractExtension
{
    /** @var IsWebsiteClosed */
    protected $isWebsiteClosed;

    /** @var Security */
    protected $security;

    public function __construct(IsWebsiteClosed $isWebsiteClosed, Security $security)
    {
        $this->isWebsiteClosed = $isWebsiteClosed;
        $this->security = $security;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('isWebsiteInMaintenance', [$this, 'isWebsiteInMaintenance']),
            new TwigFunction('hasAccessToAdministration', [$this, 'hasAccessToAdministration']),
        ];
    }

    public function isWebsiteInMaintenance(): bool
    {
        return ($this->isWebsiteClosed)();
    }

    public function hasAccessToAdministration(): bool
    {
        return $this->security->isGranted(RichIdMaintenanceBundle::ROLE_MAINTENANCE_ADMIN);
    }
}

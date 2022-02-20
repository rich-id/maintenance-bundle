<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Infrastructure\TwigExtension;

use RichId\MaintenanceBundle\Domain\UseCase\IsAnAuthorizedIp;
use RichId\MaintenanceBundle\Domain\UseCase\IsWebsiteClosed;
use RichId\MaintenanceBundle\Infrastructure\Rule\HasAccessToAdministration;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MaintenanceExtension extends AbstractExtension
{
    /** @var IsWebsiteClosed */
    protected $isWebsiteClosed;

    /** @var IsAnAuthorizedIp */
    protected $isAnAuthorizedIp;

    /** @var HasAccessToAdministration */
    protected $hasAccessToAdministration;

    /** @var RequestStack */
    protected $requestStack;

    public function __construct(IsWebsiteClosed $isWebsiteClosed, IsAnAuthorizedIp $isAnAuthorizedIp, HasAccessToAdministration $hasAccessToAdministration, RequestStack $requestStack)
    {
        $this->isWebsiteClosed = $isWebsiteClosed;
        $this->isAnAuthorizedIp = $isAnAuthorizedIp;
        $this->hasAccessToAdministration = $hasAccessToAdministration;
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('isCurrentIpAuthorizedToAccessToClosedWebsite', [$this, 'isCurrentIpAuthorizedToAccessToClosedWebsite']),
            new TwigFunction('isWebsiteInMaintenance', [$this, 'isWebsiteInMaintenance']),
            new TwigFunction('hasAccessToMaintenanceAdministration', [$this, 'hasAccessToMaintenanceAdministration']),
        ];
    }

    public function isCurrentIpAuthorizedToAccessToClosedWebsite(): bool
    {
        $request = $this->requestStack->getMasterRequest();
        $ip = $request ? $request->getClientIp() : null;

        return $ip !== null && ($this->isAnAuthorizedIp)($ip);
    }

    public function isWebsiteInMaintenance(): bool
    {
        return ($this->isWebsiteClosed)();
    }

    public function hasAccessToMaintenanceAdministration(): bool
    {
        return ($this->hasAccessToAdministration)();
    }
}

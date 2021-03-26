<?php declare(strict_types=1);

namespace RichId\MaintenanceBundle\Twig\Extension;

use Lexik\Bundle\MaintenanceBundle\Drivers\DriverFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\AccessMapInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class MaintenanceExtension.
 *
 * @package   RichId\MaintenanceBundle\Twig\Extension
 * @author    Hugo Dumazeau <hugo.dumazeau@rich-id.fr>
 * @copyright 2014 - 2021 RichId (https://www.rich-id.fr)
 */
class MaintenanceExtension extends AbstractExtension
{
    /** @var DriverFactory */
    protected $driverFactory;

    /** @var Security */
    protected $security;

    /** @var UrlGeneratorInterface */
    protected $generator;

    /** @var AccessMapInterface */
    protected $accessMap;

    public function __construct(DriverFactory $driverFactory, Security $security, UrlGeneratorInterface $generator, AccessMapInterface $accessMap)
    {
        $this->driverFactory = $driverFactory;
        $this->security = $security;
        $this->generator = $generator;
        $this->accessMap = $accessMap;
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
        return $this->driverFactory->getDriver()->decide();
    }

    public function hasAccessToAdministration(): bool
    {
        $request = Request::create($this->generator->generate('rich_id_maintenance_administration_maintenance'));
        $administrationsRoles = $this->accessMap->getPatterns($request)[0] ?? [];

        return $this->security->isGranted($administrationsRoles);
    }
}

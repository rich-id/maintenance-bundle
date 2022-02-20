<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Infrastructure\Rule;

use RichId\MaintenanceBundle\Infrastructure\RichIdMaintenanceBundle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Security;

class HasAccessToAdministration
{
    /** @var Security */
    protected $security;

    /** @var ParameterBagInterface */
    protected $parameterBag;

    public function __construct(Security $security, ParameterBagInterface $parameterBag)
    {
        $this->security = $security;
        $this->parameterBag = $parameterBag;
    }

    public function __invoke(): bool
    {
        $role = $this->parameterBag->get('rich_id_maintenance.admistration_role') ?? RichIdMaintenanceBundle::ROLE_MAINTENANCE_ADMIN;

        return $this->security->isGranted($role);
    }
}

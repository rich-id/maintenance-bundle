<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Domain\UseCase;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\IpUtils;

class IsAnAuthorizedIp
{
    /** @var array<string> */
    protected $ipAddresses;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->ipAddresses = $parameterBag->get('lexik_maintenance.authorized.ips');
    }

    public function __invoke(string $ip): bool
    {
        return IpUtils::checkIp($ip, $this->ipAddresses ?? []);
    }
}

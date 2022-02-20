<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Infrastructure\DependencyInjection;

use RichCongress\BundleToolbox\Configuration\AbstractConfiguration;
use RichId\MaintenanceBundle\Infrastructure\RichIdMaintenanceBundle;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class Configuration extends AbstractConfiguration
{
    public const CONFIG_NODE = 'rich_id_maintenance';

    protected function buildConfiguration(ArrayNodeDefinition $rootNode): void
    {
        $children = $rootNode->children();
        $this->buildConfig($children);
        $children->end();
    }

    protected function buildConfig(NodeBuilder $nodeBuilder): void
    {
        $this->administrationRole($nodeBuilder);
    }

    protected function administrationRole(NodeBuilder $nodeBuilder): void
    {
        $nodeBuilder
            ->scalarNode('admistration_role')
            ->defaultValue(RichIdMaintenanceBundle::ROLE_MAINTENANCE_ADMIN);
    }
}

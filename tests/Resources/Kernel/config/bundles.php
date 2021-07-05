<?php

declare(strict_types=1);

return [
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class                            => ['test' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class                                     => ['test' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class                                             => ['all' => true],
    Lexik\Bundle\MaintenanceBundle\LexikMaintenanceBundle::class                            => ['test' => true],
    RichCongress\RecurrentFixturesTestBundle\RichCongressRecurrentFixturesTestBundle::class => ['test' => true],
    RichId\MaintenanceBundle\Infrastructure\RichIdMaintenanceBundle::class                  => ['test' => true],
];

<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Tests\Resources\Fixtures;

use RichCongress\RecurrentFixturesTestBundle\DataFixture\AbstractFixture;
use RichId\MaintenanceBundle\Tests\Resources\Entity\DummyUser;

final class DummyUserFixtures extends AbstractFixture
{
    public const USER = 'regular';
    public const USER_ADMIN = 'admin';

    protected function loadFixtures(): void
    {
        $this->createObject(
            DummyUser::class,
            self::USER,
            [
                'username' => 'my_user_1',
                'roles'    => ['ROLE_USER'],
            ]
        );

        $this->createObject(
            DummyUser::class,
            self::USER_ADMIN,
            [
                'username' => 'my_user_2',
                'roles'    => ['ROLE_USER', 'ROLE_MAINTENANCE_ADMIN'],
            ]
        );
    }
}

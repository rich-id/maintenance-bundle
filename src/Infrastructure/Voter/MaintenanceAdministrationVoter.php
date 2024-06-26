<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Infrastructure\Voter;

use RichId\MaintenanceBundle\Infrastructure\Rule\HasAccessToAdministration;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MaintenanceAdministrationVoter extends Voter
{
    const EDIT_ADMINISTRATION_MAINTENANCE = 'EDIT_ADMINISTRATION_MAINTENANCE';

    /** @var HasAccessToAdministration */
    protected $hasAccessToAdministration;

    public function __construct(HasAccessToAdministration $hasAccessToAdministration)
    {
        $this->hasAccessToAdministration = $hasAccessToAdministration;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return \in_array($attribute, [self::EDIT_ADMINISTRATION_MAINTENANCE], true);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        switch ($attribute) {
            case self::EDIT_ADMINISTRATION_MAINTENANCE:
                return ($this->hasAccessToAdministration)();
            default:
                throw new \LogicException('This code should not be reached!');
        }
    }
}

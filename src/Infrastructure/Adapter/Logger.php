<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Infrastructure\Adapter;

use Psr\Log\LoggerInterface as PsrLogger;
use RichId\MaintenanceBundle\Domain\Port\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class Logger implements LoggerInterface
{
    /** @var PsrLogger */
    protected $logger;

    /** @var TranslatorInterface */
    protected $translator;

    /** @var Security */
    protected $security;

    public function __construct(PsrLogger $logger, TranslatorInterface $translator, Security $security)
    {
        $this->logger = $logger;
        $this->translator = $translator;
        $this->security = $security;
    }

    public function logSiteOpened(): void
    {
        $this->logMaintenanceStatusChange(false);
    }

    public function logSiteClosed(): void
    {
        $this->logMaintenanceStatusChange(true);
    }

    private function logMaintenanceStatusChange(bool $isClosed): void
    {
        $user = $this->security->getUser();
        $userUsername = $user !== null ? $user->getUserIdentifier() : '';
        $now = new \DateTime();

        $translationKey = $isClosed ? 'maintenance.log.website_closed' : 'maintenance.log.website_opened';

        $this->logger->info(
            $this->translator->trans(
                $translationKey,
                [
                    '%date%' => $now->format('c'),
                    '%user%' => $userUsername,
                ],
                'maintenance'
            ),
            [
                'extra' => [
                    '_event'  => 'maintenance.status_change',
                    '_closed' => $isClosed,
                    '_user'   => $userUsername,
                ],
            ]
        );
    }
}

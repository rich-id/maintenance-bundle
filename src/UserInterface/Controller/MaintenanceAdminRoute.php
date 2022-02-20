<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\UserInterface\Controller;

use RichId\MaintenanceBundle\Domain\Exception\WebsiteAlreadyClosedException;
use RichId\MaintenanceBundle\Domain\Exception\WebsiteAlreadyOpenedException;
use RichId\MaintenanceBundle\Domain\Model\MaintenanceModel;
use RichId\MaintenanceBundle\Domain\UseCase\CloseWebsite;
use RichId\MaintenanceBundle\Domain\UseCase\IsAnAuthorizedIp;
use RichId\MaintenanceBundle\Domain\UseCase\IsWebsiteClosed;
use RichId\MaintenanceBundle\Domain\UseCase\OpenWebsite;
use RichId\MaintenanceBundle\Infrastructure\FormType\MaintenanceFormType;
use RichId\MaintenanceBundle\Infrastructure\Rule\HasAccessToAdministration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MaintenanceAdminRoute extends AbstractController
{
    /** @var RequestStack */
    protected $requestStack;

    /** @var IsAnAuthorizedIp */
    protected $isAnAuthorizedIp;

    /** @var CloseWebsite */
    protected $closeWebsite;

    /** @var OpenWebsite */
    protected $openWebsite;

    /** @var IsWebsiteClosed */
    protected $isWebsiteClosed;

    /** @var HasAccessToAdministration */
    protected $hasAccessToAdministration;

    public function __construct(
        RequestStack $requestStack,
        IsAnAuthorizedIp $isAnAuthorizedIp,
        CloseWebsite $closeWebsite,
        OpenWebsite $openWebsite,
        IsWebsiteClosed $isWebsiteClosed,
        HasAccessToAdministration $hasAccessToAdministration
    ) {
        $this->requestStack = $requestStack;
        $this->isAnAuthorizedIp = $isAnAuthorizedIp;
        $this->closeWebsite = $closeWebsite;
        $this->openWebsite = $openWebsite;
        $this->isWebsiteClosed = $isWebsiteClosed;
        $this->hasAccessToAdministration = $hasAccessToAdministration;
    }

    public function __invoke(): Response
    {
        if (!($this->hasAccessToAdministration)()) {
            throw new AccessDeniedException();
        }

        $request = $this->requestStack->getCurrentRequest() ?? new Request();
        $isWebsiteClosed = ($this->isWebsiteClosed)();

        $maintenanceModel = new MaintenanceModel();
        $maintenanceModel->setIsClosed($isWebsiteClosed);
        $form = $this
            ->createForm(MaintenanceFormType::class, $maintenanceModel)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var MaintenanceModel $data */
            $data = $form->getData();

            try {
                if ($data->isClosed()) {
                    ($this->closeWebsite)();
                } else {
                    ($this->openWebsite)();
                }
            } catch (WebsiteAlreadyClosedException | WebsiteAlreadyOpenedException $e) {
                // Skip the error
            }

            return $this->redirectToRoute($request->attributes->get('_route'));
        }

        return $this->render(
            '@RichIdMaintenance/admin/main.html.twig',
            [
                'form'           => $form->createView(),
                'isAuthorizedIp' => ($this->isAnAuthorizedIp)($request->getClientIp() ?? ''),
            ]
        );
    }
}

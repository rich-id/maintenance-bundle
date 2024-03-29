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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

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

    public function __construct(
        RequestStack $requestStack,
        IsAnAuthorizedIp $isAnAuthorizedIp,
        CloseWebsite $closeWebsite,
        OpenWebsite $openWebsite,
        IsWebsiteClosed $isWebsiteClosed
    ) {
        $this->requestStack = $requestStack;
        $this->isAnAuthorizedIp = $isAnAuthorizedIp;
        $this->closeWebsite = $closeWebsite;
        $this->openWebsite = $openWebsite;
        $this->isWebsiteClosed = $isWebsiteClosed;
    }

    /** @IsGranted("EDIT_ADMINISTRATION_MAINTENANCE") */
    public function __invoke(): Response
    {
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

<?php declare(strict_types=1);

namespace RichId\MaintenanceBundle\Controller;

use RichId\MaintenanceBundle\Action\MaintenanceUpdate;
use RichId\MaintenanceBundle\Fetcher\MaintenanceModelFetcher;
use RichId\MaintenanceBundle\Model\MaintenanceModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class AdministrationController.
 *
 * @package   RichId\MaintenanceBundle\Controller
 * @author    Hugo Dumazeau <hugo.dumazeau@rich-id.fr>
 * @copyright 2014 - 2021 RichId (https://www.rich-id.fr)
 */
class AdministrationController extends AbstractController
{
    public function maintenance(
        Request $request,
        ParameterBagInterface $parameterBag,
        MaintenanceUpdate $maintenanceUpdate,
        MaintenanceModelFetcher $maintenanceModelFetcher
    ): Response
    {
        if (!$this->isGranted('ROLE_RICH_ID_MAINTENANCE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $maintenceModel = $maintenanceModelFetcher();
        $authorizedIps = $parameterBag->get('lexik_maintenance.authorized.ips');
        $isAuthorizedPeople = IpUtils::checkIp($request->getClientIp(), $authorizedIps);

        $form = $this->buildForm($maintenceModel)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $maintenanceUpdate($form->getData());
            return $this->redirectToRoute($request->attributes->get('_route'));
        }

        return $this->render(
            '@RichIdMaintenance/administration/maintenance.html.twig',
            [
                'form'               => $form->createView(),
                'isAuthorizedPeople' => $isAuthorizedPeople,
                'isInMaintenance'    => $maintenceModel->isLocked(),
            ]
        );
    }

    private function buildForm(MaintenanceModel $model): FormInterface
    {
        return $this->createFormBuilder($model)
            ->add(
                'isLocked',
                CheckboxType::class,
                [
                    'required'           => false,
                    'label'              => 'rich_id_maintenant.administration.is_locked',
                    'translation_domain' => 'maintenance',
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label'              => 'rich_id_maintenant.administration.save',
                    'translation_domain' => 'maintenance',
                ]
            )
            ->getForm();
    }
}

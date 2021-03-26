<?php declare(strict_types=1);

namespace RichId\MaintenanceBundle\Controller;

use RichId\MaintenanceBundle\Model\MaintenanceModel;
use RichId\MaintenanceBundle\Utility\MaintenanceUtility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdministrationController.
 *
 * @package   RichId\MaintenanceBundle\Controller
 * @author    Hugo Dumazeau <hugo.dumazeau@rich-id.fr>
 * @copyright 2014 - 2021 RichId (https://www.rich-id.fr)
 */
class AdministrationController extends AbstractController
{
    public function maintenance(Request $request, ParameterBagInterface $parameterBag, MaintenanceUtility $maintenanceUtility): Response
    {
        $maintenceModel = $maintenanceUtility->buildMaintenanceModel();
        $authorizedIps = $parameterBag->get('lexik_maintenance.authorized.ips');
        $isAuthorizedPeople = IpUtils::checkIp($request->getClientIp(), $authorizedIps);

        $form = $this->buildForm($maintenceModel)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $maintenanceUtility->updateMaintenanceStatus($form->getData());
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

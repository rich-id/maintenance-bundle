<?php declare(strict_types=1);

namespace RichId\MaintenanceBundle\Controller;

use RichId\MaintenanceBundle\Model\MaintenanceModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdministrationController extends AbstractController
{
    /**
     * @return Response
     */
    public function maintenance(Request $request, ParameterBagInterface $parameterBag): Response
    {
        $isAuthorizedPeople = IpUtils::checkIp($request->getClientIp(), $parameterBag->get('lexik_maintenance.authorized.ips'));

        $form = $this->buildForm()->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->updateMaintenanceStatus($form->getData());

            // todo gérer erreur + gérer form beau + droits

            return $this->render(
                '@RichIdMaintenance/administration/maintenance.html.twig',
                [
                    'form'               => $form->createView(),
                    'isAuthorizedPeople' => $isAuthorizedPeople,
                ]
            );
        }

        return $this->render(
            '@RichIdMaintenance/administration/maintenance.html.twig',
            [
                'form'               => $form->createView(),
                'isAuthorizedPeople' => $isAuthorizedPeople,
            ]
        );
    }

    private function buildForm(): FormInterface
    {
        return $this->createFormBuilder($this->buildMaintenanceModel())
            ->add('isLocked', CheckboxType::class, ['required' => false])
            ->add('save', SubmitType::class)
            ->getForm();
    }

    private function buildMaintenanceModel(): MaintenanceModel
    {
        $driverFactory = $this->get('lexik_maintenance.driver.factory');
        $model = new MaintenanceModel();

        $model->isLocked = $driverFactory->getDriver()->decide();

        return $model;
    }

    private function updateMaintenanceStatus(MaintenanceModel $model): void
    {
        $driver = $this->get('lexik_maintenance.driver.factory')->getDriver();

        if ($model->isLocked) {
            $driver->lock();
        } else {
            $driver->unlock();
        }

        if ($driver->decide() === !$model->isLocked) {
            throw new \Exception();
        }
    }
}

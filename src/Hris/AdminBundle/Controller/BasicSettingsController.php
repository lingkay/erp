<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\BaseController;

class BasicSettingsController extends BaseController
{
    public function indexAction()
    {
        $this->checkAccess('hris_admin_basic_settings.view');

        $this->title = 'Settings';
        $params = $this->getViewParams('List', 'hris_admin_basic_settings_index');

        $config = $this->get('hris_settings');
        $params['hris_settings_minimum_work_hours'] = $config->get('hris_settings_minimum_work_hours');
        $params['hris_settings_minimum_hourly_rate'] = $config->get('hris_settings_minimum_hourly_rate');
        $params['hris_settings_minimum_daily_rate'] = $config->get('hris_settings_minimum_daily_rate');

        return $this->render('HrisAdminBundle:BasicSettings:index.html.twig', $params);
    }

    public function indexSubmitAction()
    {
        $this->checkAccess('hris_admin_basic_settings.edit');

        $config = $this->get('hris_settings');
        $data = $this->getRequest()->request->all();
        $em = $this->getDoctrine()->getManager();

        foreach ($data as $key => $value)
            $config->set($key, $value);

        $em->flush();

        $this->addFlash('success', 'Settings have been updated.');

        return $this->redirect($this->generateUrl('hris_admin_basic_settings_index'));
    }
}

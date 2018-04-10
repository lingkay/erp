<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\BaseController;

class InventorySettingsController extends BaseController
{
    public function indexAction()
    {
        $this->checkAccess('gist_inv_settings.view');

        $this->title = 'Settings';
        $params = $this->getViewParams('List', 'gist_inv_settings_index');
        $params['counting_opts'] = $this->get('gist_inventory')->getCountingFormControl();
        $config = $this->get('Gist_configuration');
        $params['gist_pos_counting_system_count_visibility'] = $config->get('gist_pos_counting_system_count_visibility');
        $params['gist_pos_counting_max_submissions'] = $config->get('gist_pos_counting_max_submissions');
        $params['gist_pos_max_refund_days'] = $config->get('gist_pos_max_refund_days');
        $params['gist_pos_refund_code'] = $config->get('gist_pos_refund_code');
        $params['gist_pos_upsell_seconds'] = $config->get('gist_pos_upsell_seconds');

        return $this->render('GistInventoryBundle:InventorySettings:index.html.twig', $params);
    }

    public function indexSubmitAction()
    {
        $this->checkAccess('gist_inv_settings.edit');
        $config = $this->get('Gist_configuration');
        $data = $this->getRequest()->request->all();
        $em = $this->getDoctrine()->getManager();

        foreach ($data as $key => $value)
            $config->set($key, $value);

        $em->flush();
        $this->addFlash('success', 'Settings have been updated.');
        return $this->redirect($this->generateUrl('gist_inv_settings_index'));
    }
}

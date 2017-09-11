<?php

namespace Gist\ConfigurationBundle\Controller;

use Gist\TemplateBundle\Model\BaseController;

class ConfigEntryController extends BaseController
{
    public function indexAction()
    {
        $this->checkAccess('cat_config.view');

        $this->title = 'Settings';
        $params = $this->getViewParams('List', 'cat_config_index');

        $params['cats'] = $this->get('Gist_configuration')->getDisplayEntries();

        return $this->render('GistConfigurationBundle:ConfigEntry:index.html.twig', $params);
    }

    public function submitAction()
    {
        $this->checkAccess('cat_config.edit');

        $config = $this->get('Gist_configuration');
        $data = $this->getRequest()->request->all();
        $em = $this->getDoctrine()->getManager();

        foreach ($data as $key => $value)
            $config->set($key, $value);

        $em->flush();

        $this->addFlash('success', 'Settings have been updated.');

        return $this->redirect($this->generateUrl('cat_config_index'));
    }
}

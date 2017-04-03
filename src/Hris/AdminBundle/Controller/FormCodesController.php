<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
// use Hris\AdminBundle\Entity\FormCodes;

class FormCodesController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_admin_form_codes';
        $this->title = 'HR Form Codes';

        $this->list_title = 'HR Form Codes';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass() 
    {
        // return new FormCodes();
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List');

        $twig_file = 'HrisAdminBundle:FormCodes:form.html.twig';


        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();
        $params['readonly'] = false;
        $params['config'] = $this->get('gist_configuration');

        return $this->render($twig_file, $params);
    }

    public function submitAction()
    {
        $this->checkAccess('cat_config.edit');

        $config = $this->get('gist_configuration');
        $data = $this->getRequest()->request->all();
        $em = $this->getDoctrine()->getManager();

        foreach ($data as $key => $value) {
            if ($key != 'csrf_token') {
                $config->set($key, $value);
            }
        }

        $em->flush();

        $this->addFlash('success', 'Settings have been updated.');

        return $this->redirect($this->generateUrl('hris_admin_form_codes_index'));
    }

    protected function getObjectLabel($obj) 
    {
        // if ($obj == null){
        //     return '';
        // }
        // return $obj->getName();
    }  
}

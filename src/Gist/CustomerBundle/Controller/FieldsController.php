<?php

namespace Gist\CustomerBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Gist\CustomerBundle\Entity\Fields;


class FieldsController extends CrudController
{

    public function __construct()
    {
        $this->route_prefix = 'gist_customer_fields';
        $this->title = 'POS Customer Form Required Fields';

        $this->list_title = 'POS Customer Form Required Fields';
        $this->list_type = 'dynamic';
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');
        $em = $this->getDoctrine()->getManager();
        $params = $this->getViewParams('List');
        $conf = $this->get('gist_configuration');
        
        $this->padFormParams($params, null);

        return $this->render('GistCustomerBundle:Fields:form.html.twig', $params);
    }

    protected function padFormParams(&$params, $user = null)
    {
        $em = $this->getDoctrine()->getManager();
        $cols = $em->getClassMetadata('GistCustomerBundle:Customer')->getColumnNames();
        $values = array();
          foreach($cols as $col){
            $values[$col] = $col;
          }



        $fields_ext = $em->getRepository('GistCustomerBundle:Fields')->findAll(); 


        $params['ecols'] = $fields_ext;

        foreach ($fields_ext as $fe) {
            foreach (array_keys($values, $fe->getFieldName()) as $key) {
                unset($values[$key]);
            }
        }

        $params['cols'] = $values;

        return $params;
    }

    public function indexSubmitAction()
    {

        $this->checkAccess($this->route_prefix . '.add');
        $is_new = false;
        $this->hookPreAction();
 
    }

    protected function update($obj,$is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->all();
        $conf = $this->get('gist_configuration');

        // var_dump($data);
        // die();
        $fields_ext = $em->getRepository('GistCustomerBundle:Fields')->findAll();   
        foreach ($fields_ext as $fe) 
        {
            $em->remove($fe);
        }
        $em->flush();
        

        foreach ($data['fields'] as $key => $value) {
            if ($value != '') {
                $field = new Fields();
                $field->setFieldName($key);
                $field->setRequiredFlag(true);
                $em->persist($field);
            }   
        }

        $em->flush();

    }
    
    

    public function getRequiredFieldsAction()
    {
        $list_opts = [];
        $em = $this->getDoctrine()->getManager();
        $fields_ext = $em->getRepository('GistCustomerBundle:Fields')->findAll();   
        foreach ($fields_ext as $fe) {
            $list_opts[] = $fe->getFieldName();
        }
        return new JsonResponse($list_opts);
    }

    protected function getObjectLabel($obj) 
    {
        if ($obj == null){
            return '';
        }
        return '';
    }

    protected function newBaseClass() {
        return new Fields;
    }

}
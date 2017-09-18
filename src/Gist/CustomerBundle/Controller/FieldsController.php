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
        $this->list_type = 'static';
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


        $params['fields'] = $fields_ext;

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

    public function editSubmit2Action()
    {
        $this->checkAccess($this->route_prefix . '.edit');

        $this->hookPreAction();
        try
        {
            
            $this->update();
            
            //$this->hookPostSave($object);
            // log
            

            $this->addFlash('success', ' edited successfully.');

            return $this->redirect($this->generateUrl('gist_customer_fields_index'));
        }
        catch (ValidationException $e)
        {
            $this->addFlash('Database error occured. Possible duplicate.');
            //return $this->editError($object, $id);
        }
        catch (DBALException $e)
        {
            $this->addFlash('Database error occured. Possible duplicate.');
            error_log($e->getMessage());

            //return $this->editError($object, $id);
        }
    }

    protected function update()
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->all();
        $conf = $this->get('gist_configuration');

        foreach ($data['field_id'] as $key => $id) {
            $field = $em->getRepository('GistCustomerBundle:Fields')->findOneById($id);

            //reset
            $field->setRequiredFlag(false);
            $field->setVisibilityFlag(false);

            //check if required
            if (isset($data['required'][$id])) {
                $isRequired = $data['required'][$id];
                if ($isRequired != null) {
                    $field->setRequiredFlag(true);
                }
            }
        
            //check if visible
            if (isset($data['visible'][$id])) {
                $isVisible = $data['visible'][$id];
                if ($isVisible != null) {
                    $field->setVisibilityFlag(true);
                }
            }
            
            $em->persist($field);
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
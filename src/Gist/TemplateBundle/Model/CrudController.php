<?php

namespace Gist\TemplateBundle\Model;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Gist\ValidationException;
use Doctrine\DBAL\DBALException;

abstract class CrudController extends BaseController
{
    protected $route_prefix;
    protected $route_gen;

    protected $title;

    protected $repo;
    protected $base_view;

    protected $list_title;
    protected $list_type;
    protected $view_path;
    
    protected $submit_redirect = true;
    protected $url_append = "";


    protected function getRouteGen()
    {
        if ($this->route_gen == null)
            $this->route_gen = new RouteGenerator($this->route_prefix);

        return $this->route_gen;
    }

    protected function getGridColumns()
    {
        // override this to setup grid columns
        return array();
    }
    
    protected function getGridSortable()
    {
        $sortable = [];
        $col = 0;
        foreach ($this->getGridColumns() as $column){
            if(!$column->isSortable()){
                $sortable[] = $col;
            }
            $col++;
        }
        return $sortable;
    }

    protected function getGridJoins()
    {
        // override this to setup grid joins
        return array();
    }

    protected function setupGridLoader()
    {
        $data = $this->getRequest()->query->all();
        $grid = $this->get('gist_grid');

        // loader
        $gloader = $grid->newLoader();
        $gloader->processParams($data)
            ->setRepository($this->repo);

        // grid joins
        $gjoins = $this->getGridJoins();
        foreach ($gjoins as $gj)
            $gloader->addJoin($gj);

        // grid columns
        $gcols = $this->getGridColumns();

        // add action column if it's dynamic
        if ($this->list_type == 'dynamic')
            $gcols[] = $grid->newColumn('', 'getID', null, 'o', array($this, 'callbackGrid'), false, false);

        // add columns
        foreach ($gcols as $gc)
            $gloader->addColumn($gc);

        return $gloader;
    }

    protected function hookPreAction()
    {
        $this->getControllerBase();

    }
    
    protected function hookPostSave($obj, $is_new = false)
    {
        //action after save
    }

    protected function getControllerBase()
    {
        $full = $this->getRequest()->get('_controller');

        // parse out the things we need
        // NOTE: this assumes the format: <namespace>\<bundle_name>\Controller\<controller_name>Controller::<action>
        $x_full = explode('\\', $full);

        $bundle = $x_full[0] . $x_full[1];
        $x_cont = explode('Controller:', $x_full[3]);
        $name = $x_cont[0];

        $base = $bundle . ':' . $name;

        // automatically set repo and base view
        if ($this->repo == null)
            $this->repo = $base;
        $this->base_view = $base;

        return $base;
    }

    protected function getViewParams($subtitle = '', $selected = null)
    {
        // default to list route if nothing selected
        if ($selected == null && $this->getRouteGen()->getList() != null)
            $selected = $this->getRouteGen()->getList();

        $params = parent::getViewParams($subtitle, $selected);
        $params['route_list'] = $this->getRouteGen()->getList();
        $params['route_add'] = $this->getRouteGen()->getAdd();
        $params['route_edit'] = $this->getRouteGen()->getEdit();
        $params['route_delete'] = $this->getRouteGen()->getDelete();
        $params['route_grid'] = $this->getRouteGen()->getGrid();
        $params['prefix'] = $this->route_prefix;

        $params['base_view'] = $this->base_view;
        return $params;
    }

    protected function padFormParams(&$params, $object = null)
    {
        // override this to add form parameters
        return $params;
    }

    protected function padListParams(&$params, $object = null)
    {
        // override this to add list parameters
        return $params;
    }

    protected function padGridParams(&$params, $id)
    {
        return $params;
    }

    abstract protected function newBaseClass();
    abstract protected function getObjectLabel($object);

    public function callbackGrid($id)
    {
        $params = array(
            'id' => $id,
            'route_edit' => $this->getRouteGen()->getEdit(),
            'route_delete' => $this->getRouteGen()->getDelete(),
            'prefix' => $this->route_prefix,
        );

        $this->padGridParams($params, $id);

        $engine = $this->get('templating');
        return $engine->render(
            'GistTemplateBundle:Object:action.html.twig',
            $params
        );
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List');

        // figure out if dynamic or static
        if ($this->list_type != 'dynamic')
        {
            $twig_file = 'GistTemplateBundle:Object:list.static.html.twig';
            $this->listStatic($params);
        }
        else
        {
            $twig_file = 'GistTemplateBundle:Object:list.dynamic.html.twig';
        }

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

        return $this->render($twig_file, $params);
    }

    protected function listStatic(&$params)
    {
        // fetch all
        $em = $this->getDoctrine()->getManager();
        $objects = $em->getRepository($this->repo)->findAll();

        $params['objects'] = $objects;

        return $params;
    }

    public function addFormAction()
    {
        $this->checkAccess($this->route_prefix . '.add');

        $this->hookPreAction();
        $obj = $this->newBaseClass();


        $session = $this->getRequest()->getSession();
        $session->set('csrf_token', md5(uniqid()));

        $params = $this->getViewParams('Add');
        $params['object'] = $obj;

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.add');
        $this->padFormParams($params, $obj);

        return $this->render('GistTemplateBundle:Object:add.html.twig', $params);
    }

    protected function addError($obj)
    {
        $params = $this->getViewParams('Add');
        $params['object'] = $obj;

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.add');

        $this->padFormParams($params, $obj);

        return $this->render('GistTemplateBundle:Object:add.html.twig', $params);
    }

    protected function validate($data, $type)
    {
        // type = add / edit
        // $session = $this->getRequest()->getSession();
        // if($data['csrf_token'] != $session->get('csrf_token')){
        //     throw new ValidationException('Duplicate Request');
        // }
        // $session->set('csrf_token', '');
    }

    protected function add($obj)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->all();

        // validate
        $this->validate($data, 'add');

        // update db
        $this->update($obj, $data, true);

        $em->persist($obj);
        $em->flush();
        $this->hookPostSave($obj,true);

        // log
        $odata = $obj->toData();
        $this->logAdd($odata);
    }

    public function ajaxAddAction()
    {
        try
        {
            $obj = $this->newBaseClass();
            $this->add($obj);
            $odata = $obj->toData();

            $res_data = new \stdClass();
            $res_data->data = $odata;
            $res_data->success = true;

            return new JsonResponse($res_data);
        }
        catch (ValidationException $e)
        {
            // TODO: return error
            error_log('validation error' . $e->getMessage());
        }
        catch (DBALException $e)
        {
            // TODO: return error
            error_log('dbal exception: ' . $e->getMessage());
        }
    }

    public function addSubmitAction()
    {
        $this->checkAccess($this->route_prefix . '.add');

        $this->hookPreAction();
        try
        {
            $obj = $this->newBaseClass();
            $this->add($obj);

            $this->addFlash('success', $this->title . ' added successfully.');
            if($this->submit_redirect){
                return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
            }else{
                return $this->redirect($this->generateUrl($this->getRouteGen()->getEdit(),array('id'=>$obj->getID())).$this->url_append);
            }
        }
        catch (ValidationException $e)
        {
             $this->addFlash('error', 'Database error occured. Possible duplicate.');
            return $this->addError($obj);
        }
        catch (DBALException $e)
        {
            $this->addFlash('error', 'Database error occured. Possible duplicate.');
            error_log($e->getMessage());
            return $this->addError($obj);
        }
    }

    public function editFormAction($id)
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository($this->repo)->find($id);

        $session = $this->getRequest()->getSession();
        $session->set('csrf_token', md5(uniqid()));

        $params = $this->getViewParams('Edit');
        $params['object'] = $obj;
        $params['o_label'] = $this->getObjectLabel($obj);

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.edit');

        $this->padFormParams($params, $obj);

        return $this->render('GistTemplateBundle:Object:edit.html.twig', $params);
    }

    protected function editError($obj, $id)
    {
        $params = $this->getViewParams('Edit');
        $params['object'] = $obj;
        $params['o_label'] = $this->getObjectLabel($obj);

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.edit');

        $this->padFormParams($params, $obj);

        return $this->render('GistTemplateBundle:Object:edit.html.twig', $params);
    }

    public function editSubmitAction($id)
    {
        $this->checkAccess($this->route_prefix . '.edit');

        $this->hookPreAction();
        try
        {
            $em = $this->getDoctrine()->getManager();
            $data = $this->getRequest()->request->all();

            $object = $em->getRepository($this->repo)->find($id);
            // validate
            $this->validate($data, 'edit');
            // update db
            $this->update($object, $data);
            $em->flush();
            $this->hookPostSave($object);
            // log
            $odata = $object->toData();
            $this->logUpdate($odata);

            $this->addFlash('success', $this->title . ' ' . $this->getObjectLabel($object) . ' edited successfully.');

            return $this->redirect($this->generateUrl($this->getRouteGen()->getEdit(), array('id' => $id)).$this->url_append);
        }
        catch (ValidationException $e)
        {
            $this->addFlash('Database error occured. Possible duplicate.');
            return $this->editError($object, $id);
        }
        catch (DBALException $e)
        {
            $this->addFlash('Database error occured. Possible duplicate.');
            error_log($e->getMessage());

            return $this->editError($object, $id);
        }
    }

    public function deleteAction($id)
    {
        $this->checkAccess($this->route_prefix . '.delete');

        try
        {
            $this->hookPreAction();
            $em = $this->getDoctrine()->getManager();

            $object = $em->getRepository($this->repo)->find($id);
            $odata = $object->toData();
            $this->logDelete($odata);

            $em->remove($object);
            $em->flush();

            $this->addFlash('success', $this->title . ' ' . $this->getObjectLabel($object) . ' has been deleted.');

            return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
        }
        catch (DBALException $e)
        {
            // $this->addFlash('error', $e->getMessage());
            
            $this->addFlash('error', 'Could not delete ' . $this->title . '.');
            return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
        }
    }

    public function gridAction()
    {
        $this->hookPreAction();

        $gloader = $this->setupGridLoader();

        $gloader->setQBFilterGroup($this->filterGrid());
        $gres = $gloader->load();  
        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }
    protected function filterGrid(){
        $grid = $this->get('gist_grid');
        return $grid->newFilterGroup();
    }

    public function ajaxGetAction($id)
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository($this->repo)->find($id);

        $data = $this->buildData($obj);

        $resp = new Response(json_encode($data));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }
    
    public function viewTemplateAction($id)
    {
        $this->checkAccess($this->route_prefix . '.view');
        $this->hookPreAction();
        $obj = $this->newBaseClass();
        $params['object'] = $obj;

        // check if we have access to form
        $params['readonly'] = true;
        $this->padFormParams($params, $obj);
        return $this->render($this->view_path.':form.html.twig', $params);
  
    }

    protected function logDelete($data)
    {
        $log = $this->get('gist_log');
        $desc = 'deleted ' . $this->title . ' ' . $data->id . '.';
        $log->log($this->route_prefix . '_delete', $desc, $data);
    }

    protected function logUpdate($data)
    {
        $log = $this->get('gist_log');
        $desc = 'updated ' . $this->title . ' ' . $data->id . '.';
        $log->log($this->route_prefix . '_update', $desc, $data);
    }

    protected function logAdd($data)
    {
        $log = $this->get('gist_log');
        error_log(print_r($data, true));
        $desc = 'added ' . $this->title . ' ' . $data->id . '.';
        $log->log($this->route_prefix . '_add', $desc, $data);
    }


    public function exportCSVAction()
    {
        $filename = $this->route_prefix.'.csv';
        $data = $this->getStockReport();

        // redirect file to stdout, store in output buffer and place in $csv
        $file = fopen('php://output', 'p');
        ob_start();
        foreach ($data as $dt)
            fputcsv($file, $dt);
        fclose($file);
        $csv = ob_get_contents();
        ob_end_clean();


        $response = new Response(); 
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);
        $response->setContent($csv);

        return $response;
    }


    public function printAction()
    {
        $this->title = $this->title;
        $params = $this->getViewParams('', $this->route_prefix.'_index');

        $params['grid_cols'] = $this->getGridColumns();
        $params['data'] = $this->getStockReport();

        return $this->render(
            'GistTemplateBundle::print.html.twig', $params);
    }  

    public function formatPrice($price)
    {
        return '<div class="numeric">'.number_format($price, 2).'</div>';
    }

    public function formatDate($date)
    {
        if($date != null)
            return $date->format('m/d/Y');
        else 
            return "";
    }

}

<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\Counting;
use Gist\InventoryBundle\Entity\CountingEntry;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gist\InventoryBundle\Entity\Entry;
use Gist\InventoryBundle\Entity\Transaction;
use Gist\InventoryBundle\Entity\Stock;
use DateTime;


class CountingController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'gist_inv_counting';
        $this->title = 'Counting';

        $this->list_title = 'Stock Counting';
        $this->list_type = 'dynamic';
        $this->repo = "GistInventoryBundle:Counting";
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'gist_inv_counting_index');

        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $this->padFormParams($params, $date_from, $date_to);
        $twig_file = 'GistInventoryBundle:Counting:index.html.twig';


        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();
        return $this->render($twig_file, $params);
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

        $params['entries'] = $em->getRepository('GistInventoryBundle:CountingEntry')->findBy(array('counting'=>$id));

        $params['main_status'] = '';
        if ($obj->getID() != '') {
            $params['main_status'] = $obj->getStatus();
        }

        $this->padFormParams($params, $obj);

        return $this->render('GistTemplateBundle:Object:edit.html.twig', $params);
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
        $params['main_status'] = '';
        $params['entries'] = [];

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.add');
        $this->padFormParams($params, $obj);

        return $this->render('GistTemplateBundle:Object:add.html.twig', $params);
    }

    public function editRollbackFormAction($id)
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
        $params['is_rolled_back'] = 'true';

        if ($obj->getStatus() == 'requested') {
            $params['main_status'] = '';
        } elseif ($obj->getStatus() == 'processed') {
            $params['main_status'] = 'requested';
        } elseif ($obj->getStatus() == 'delivered') {
            $params['main_status'] = 'processed';
        }


        return $this->render('GistTemplateBundle:Object:edit.html.twig', $params);
    }

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
            'GistInventoryBundle:Counting:action.html.twig',
            $params
        );
    }

    protected function getObjectLabel($obj)
    {
        if ($obj == null)
        {
            return '';
        }
        return $obj->getID();
    }

    protected function newBaseClass()
    {
        return new Counting();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('inv','inventory_account','getInventoryAccount'),
            $grid->newJoin('u','user_create','getUserCreate'),
        );
    }


    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('ID','getID','id'),
            $grid->newColumn('Submitted by','getDisplayName','last_name','u'),
            $grid->newColumn('Date Submitted','getDateTimeCreateFormatted','date_create'),
            $grid->newColumn('For Date','getDateSubmittedFormatted','date_submitted'),
//            $grid->newColumn('Type','getCountTimeSlot','id'),
            $grid->newColumn('Status','getStatusFMTD','status'),
            $grid->newColumn('Source','getName','name','inv')
        );
    }

    protected function padFormParams(&$params, $object = NULL)
    {
        $em = $this->getDoctrine()->getManager();
        $um = $this->get('gist_user');
        $params['user_opts'] = $um->getUserFullNameOptions();
        $inv = $this->get('gist_inventory');
        $params['wh_opts'] = array('0'=>'Main Warehouse') + $inv->getPOSLocationTransferOptionsOnly();
        $params['item_opts'] = $inv->getProductOptionsTransfer();

        $filter = array();
        $categories = $em
            ->getRepository('GistInventoryBundle:ProductCategory')
            ->findBy(
                $filter,
                array('name' => 'ASC')
            );

        $cat_opts = array();
        $cat_opts[''] = 'All';
        foreach ($categories as $category)
            $cat_opts[$category->getID()] = $category->getName();

        $params['cat_opts'] = $cat_opts;

        return $params;
    }

    /**
     *
     *
     *
     * @param $pos_loc_id
     * @return JsonResponse
     */
    public function posGridDataAction($pos_loc_id)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_loc_id));
        $countings = $em->getRepository('GistInventoryBundle:Counting')->findBy(array('inventory_account'=>$pos_location->getInventoryAccount()->getID()));

        $list_opts = [];
        foreach ($countings as $counting) {
            $list_opts[] = array(
                'id'=>$counting->getID(),
                'source'=> $counting->getInventoryAccount()->getName(),
                'submitted_by' => $counting->getUserCreate()->getDisplayName(),
                'type'=> $counting->getCountTimeSlot(),
                'date_create'=> $counting->getDateTimeCreateFormatted(),
                'status'=> ucfirst($counting->getStatus()),
            );
        }

        return new JsonResponse($list_opts);
    }
}

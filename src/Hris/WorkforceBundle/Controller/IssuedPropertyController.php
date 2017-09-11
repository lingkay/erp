<?php

namespace Hris\WorkforceBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\ValidationException;

use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\WorkforceBundle\Entity\IssuedProperty;
use Hris\WorkforceBundle\Entity\Employee;

use Symfony\Component\HttpFoundation\JsonResponse;
use DateTime;

class IssuedPropertyController extends CrudController
{
    use TrackCreate;

    public function __construct()
    {
        $this->route_prefix = 'hris_workforce_issued_property';
        $this->title = 'Issued Property';

        $this->list_title = 'Issued Properties';
        $this->list_type = 'dynamic';
        // $this->submit_redirect = false;
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $gl = $this->setupGridIssued();

        $params = $this->getViewParams('List', '');

        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $this->padFormParams($params, $date_from, $date_to);
        $twig_file = 'HrisWorkforceBundle:IssuedProperty:index.html.twig';
        
        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

        return $this->render($twig_file, $params);
    }

    protected function getObjectLabel($obj) 
    {
        if ($obj == null){
            return '';
        }
        return $obj->getItemName();
    }

    protected function newBaseClass()
    {
        return new IssuedProperty();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Item Name', 'getItemName', 'item_name'),
            $grid->newColumn('Issued To', 'getDisplayName', 'first_name', 'e'),
            $grid->newColumn('Date Issued', 'getDateIssued', 'date_issued'),
            $grid->newColumn('Date Returned', 'getDateReturned', 'date_returned'),
        );
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('e', 'employee', 'getEmployee'),
        );
    }

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();

        $emps = $em->getRepository('HrisWorkforceBundle:Employee')->findAll();
        $depts = $em->getRepository('HrisAdminBundle:Department')->findAll();

        $emp_list = array();
        foreach ($emps as $emp)
            $emp_list[$emp->getID()] = $emp->getFirstName().' '.$emp->getLastName();

        $dept_list = array();
        foreach ($depts as $dept)
            $dept_list[$dept->getID()] = $dept->getName();

        $params['emp_list'] = $emp_list;
        $params['dept_list'] = $dept_list;
        $params['prop_type'] = $this->getPropType();

        return $params;
    }

    protected function validate($data, $type)
    {
        if (!isset($data['emp'])) {
            throw new ValidationException('Cannot leave Employee blank.');
        }
    }

    protected function update($o, $data, $is_new = false)
    {
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die();

        if (isset($data['isRet'])) {
            if ($data['isRet'] == 'returned') {
                $o->setDateReturned(new DateTime($data['return_date']));
            }
        }
        else
        {
            $em = $this->getDoctrine()->getManager();
            $media = $this->get('gist_media');

            $emp = $em->getRepository('HrisWorkforceBundle:Employee')->find($data['emp']);

            $o->setEmployee($emp);
            $o->setDateIssued(new DateTime($data['issue_date']));

            if ($data['type'] == 1)
            {
                $car = array();
                foreach($data['car'] as $id => $val){
                    $car[$id] = $val;
                }

                $o->setItemName('Car')
                    ->setItemType($data['type'])
                    ->setAddtlDetails(json_encode($car));
            }
            elseif ($data['type'] == 2)
            {
                $laptop = array();
                foreach($data['laptop'] as $id => $val){
                    $laptop[$id] = $val;                }

                $o->setItemName('Laptop')
                    ->setItemType($data['type'])
                    ->setAddtlDetails(json_encode($laptop));
            }
            elseif ($data['type'] == 3)
            {
                $phone = array();
                foreach($data['phone'] as $id => $val){
                    $phone[$id] = $val; 
                }

                $o->setItemName('Phone')
                    ->setItemType($data['type'])
                    ->setAddtlDetails(json_encode($phone));
            }
            else
            {
                $o->setItemName($data['item_name'])
                    ->setItemType($data['type'])
                    ->setItemCode($data['item_code'])
                    ->setItemDesc($data['item_desc']);
            }

            if($data['picture']!=0 && $data['picture'] != ""){
                $o->setUpload($media->getUpload($data['picture']));
            }


            $this->updateTrackCreate($o, $data, $is_new);
        }
    }

    public function editFormAction($id)
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository($this->repo)->find($id);

        $params = $this->getViewParams('Edit');
        $params['object'] = $obj;
        $params['o_label'] = $this->getObjectLabel($obj);
        $params['details'] = json_decode($obj->getAddtlDetails());
        $date_issued = new DateTime($obj->getDateIssued());
        $params['date_issued'] = $date_issued->format('m/d/Y');

        if ($obj->getDateReturned() != null)
        {
            //disable changes when returned
            $params['readonly'] = true;
        }
        else
        {
            // check if we have access to form
            $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.edit');
        }

        $params['prop_type'] = $this->getPropType();

        $this->padFormParams($params, $obj);

        return $this->render('HrisWorkforceBundle:IssuedProperty:edit.html.twig', $params);
    }

    public function getEmpListAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $emp = $em->getRepository('HrisWorkforceBundle:Employee')->findAll();

        $json = array();
        foreach($emp as $e)
        {
            if($e->getDepartment()->getID() == $id)
            {
                $json[$e->getID()] = [
                'name' => $e->getFirstName().' '.$e->getLastName(),
                ]; 
            }
        }
        return new JsonResponse($json);   
    }

    public function getPropType()
    {
        $prop_type = array(1 => 'Car', 2 => 'Laptop', 3 => 'Phone', 0 => 'Others');
        return $prop_type;
    }

    public function ajaxFilterPropertyAction()
    {
        $data = $this->getRequest()->query->all();
        $em = $this->getDoctrine()->getManager();
        $query = $data['query'];

        $properties = $em->getRepository("HrisWorkforceBundle:IssuedProperty")->createQueryBuilder('o')
           ->where('o.item_name LIKE :item_name')
           ->setParameter('item_name', "%".$query."%")
           ->getQuery()
           ->getResult();

        $list_opts = [];
        foreach ($properties as $property) {
            $list_opts[] = array('id'=>$property->getItemName(), 'name'=> $property->getItemName());
        }
        return new JsonResponse($list_opts);
    }

    protected function getGridIssued()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Item Name', 'getItemName', 'item_name'),
            $grid->newColumn('Issued To', 'getEmployeeName', 'name'),
            $grid->newColumn('Date Issued', 'getDateIssued', 'date_issued'),
            $grid->newColumn('Date Returned', 'getDateReturned', 'date_returned'),
        );
    }

    protected function setupGridIssued()
    {
        $grid = $this->get('gist_grid');
        $data = $this->getRequest()->query->all();
        $em = $this->getDoctrine()->getManager();

        // setup grid
        $gl = $grid->newLoader();
        $gl->processParams($data)
            ->setRepository('HrisWorkforceBundle:IssuedProperty')
            ->enableCountFilter();

        // columns
        $stock_cols = $this->getGridIssued();

        // add action column if it's dynamic
        if ($this->list_type == 'dynamic')
            $stock_cols[] = $grid->newColumn('', 'getID', null, 'o', array($this, 'callbackGrid'), false, false);

        foreach ($stock_cols as $col)
            $gl->addColumn($col);

        return $gl;
    }

    public function gridIssuedAction($id = null, $item_name = null)
    {
        $gl = $this->setupGridIssued();
        $qry = array();

        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();
        // print_r($id .' '. $item_name);
        if ($item_name != null and $item_name != 'null')
        {
            $qry[] = "(o.item_name LIKE '%".$item_name."%')";
        }
        if ($id != null and $id != 'null') {
            $qry[] = "(o.employee = '".$id."')";
        }
        
        // print_r($qry);
        if (!empty($qry))
        {
            $filter = implode(' AND ', $qry);
            $fg->where($filter);
            $gl->setQBFilterGroup($fg);
        }

        $gres = $gl->load();

        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    public function printAction()
    {
        $settings = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        $em = $this->getDoctrine()->getManager();
        $twig = "HrisWorkforceBundle:IssuedProperty:print.html.twig";

        //params here
        $data = $this->getRequest()->query->all();
        $em = $this->getDoctrine()->getManager();

        $properties = $em->getRepository("HrisWorkforceBundle:IssuedProperty")->createQueryBuilder('o')
           ->getQuery()
           ->getResult();

        $conf = $this->get('gist_configuration');
        $media = $this->get('gist_media');
        if ($conf->get('hris_com_logo') != '') 
        {
            $path = $media->getUpload($conf->get('hris_com_logo'));

            $str = $path->getURL();
            $str = parse_url($str, PHP_URL_PATH);
            $str = ltrim($str, '/');

            $params['logo'] = $str;
        }
        else
        {
            $params['logo'] = '';
        }

        $config               = $this  ->get('gist_configuration');
        $params['company_name'] = strtoupper($config->get('hris_com_info_company_name'));
        $params['company_website'] = $config->get('hris_com_info_website');

        if ($config->get('hris_com_info_company_address') != null) 
        {
            $params['company_address'] = $em->getRepository('GistContactBundle:Address')->find($config->get('hris_com_info_company_address'));
        }


        $params['properties'] = $properties;
        //$params['details'] = json_decode($obj->getAddtlDetails());

        $pdf = $this->get('gist_pdf');
        $pdf->newPdf('A4');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
    }

    public function printPropertyAction($id)
    {

        $settings = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        $em = $this->getDoctrine()->getManager();
        $twig = "HrisWorkforceBundle:IssuedProperty:printproperty.html.twig";

        //params here
        $data = $this->getRequest()->query->all();
        $em = $this->getDoctrine()->getManager();

        $property = $em->getRepository('HrisWorkforceBundle:IssuedProperty')->find($id);

        $conf = $this->get('gist_configuration');
        $media = $this->get('gist_media');
        if ($conf->get('hris_com_logo') != '') 
        {
            $path = $media->getUpload($conf->get('hris_com_logo'));

            $str = $path->getURL();
            $str = parse_url($str, PHP_URL_PATH);
            $str = ltrim($str, '/');

            $params['logo'] = $str;
        }
        else
        {
            $params['logo'] = '';
        }

        if ($property->getUpload() != '' && $property->getUpload() != null) 
        {
            $pathx = $property->getUpload();

            $str = $pathx->getURL();
            $str = parse_url($str, PHP_URL_PATH);
            $str = ltrim($str, '/');

            $params['picture'] = $str;
        }
        else
        {
            $params['picture'] = '';
        }


        $params['details'] = json_decode($property->getAddtlDetails());
        $params['object'] = $property;
        //$params['details'] = json_decode($obj->getAddtlDetails());



        $pdf = $this->get('gist_pdf');
        $pdf->newPdf('A4');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
    }
}
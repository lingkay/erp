<?php

namespace Hris\DashboardBundle\Controller;

use Gist\TemplateBundle\Model\CrudController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Hris\WorkforceBundle\Entity\Request;
use DateTime;

class PendingRequestController extends Controller
{
    public function __construct()
    {
        $this->route_prefix = 'hris_dashboard_pending_request';
        $this->title = 'Pending Request';

        $this->list_title = 'Pending Request';
        $this->list_type = 'dynamic';
        $this->repo = 'HrisWorkforceBundle:Request';
    }
    public function indexAction()
    {
        $params = $this->getViewParams('', 'hris_dashboard_pending_request');

        $sample = $this->getUser()->getGroups();

        $gl = $this->setupGridLoader();

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

        $this->padFormParams($params);
        return $this->render('HrisDashboardBundle:Dashboard:pendingrequest.html.twig', $params);
    }
    protected function getObjectLabel($object) {
        if ($obj == null)
        {
        return '';
        }
        return $obj->getID();
    }

    protected function newBaseClass() {

    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
        $grid->newJoin('emp','employee','getEmployee'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Date Filed', 'getDateFiled', 'date_filed','o',array($this,'formatDate')),
            $grid->newColumn('Employee Name', 'getDisplayName', 'last_name','emp'),
            $grid->newColumn('Form Requested', 'getRequestType', 'request_type'),
            $grid->newColumn('Status', 'getStatus', 'status'),                       
        );
    }

    protected function padFormParams(&$params, $object = null)
    {
        // override this to add form parameters
        $params['request_opts'] = [
            Request::TYPE_REIMBURSE    =>   'Reimbursement',
            Request::TYPE_COE          =>   'Certificate of Employment',
            Request::TYPE_PROP         =>   'Property/Item',
            Request::TYPE_RESIGN       =>   'Resignation',
        ];

        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;
        return $params;
    }

    public function gridRequestAction($request = null , $date_from = null, $date_to = null)
    {
        $gl = $this->setupGridLoader();
        $qry = array();
        $dfrom = $date_from=='null'? new DateTime():new DateTime($date_from.'00:00:00');
        $dto = $date_to=='null'? new DateTime():new DateTime($date_to.'23:59:59');

        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();

        $qry[] = "(o.date_filed <= '".$dto->format('Y-m-d H:i:s')."' AND o.date_filed >= '".$dfrom->format('Y-m-d H:i:s')."')";

        if($request != 'null')
        {
            $qry[] = "(o.request_type = '".$request."')";
        }

        $qry[] = "(o.status = '".Request::STATUS_PENDING."')"; 

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

    public function callbackGrid($id)
    {
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository('HrisWorkforceBundle:Request')->find($id);

        switch ($obj->getRequestType()) {
            case Request::TYPE_REIMBURSE:
                $type = 'reimburse';
                $route = 'hris_workforce_reimbursement_edit_form';
                break;
            case Request::TYPE_COE:
                $type = 'coe';
                $route = 'hris_profile_request_edit_form';
                break;
            case Request::TYPE_PROP:
                $type = 'property';
                $route = 'hris_workforce_issued_property_edit_form';
                break;
            case Request::TYPE_RESIGN:
                $type = 'resign';
                $route = 'hris_workforce_resign_edit_form';
                break;
        }

        $params = array(
            'id' => $id,
            'type' => $type,
            'route_edit' => $route,
            'prefix' => $this->route_prefix,
        );

        $this->padGridParams($params, $id);

        $engine = $this->get('templating');
        return $engine->render(
            'HrisDashboardBundle::action.html.twig',
            $params
        );
    }
}
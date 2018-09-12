<?php

namespace Gist\AccountingBundle\Controller;

use Gist\TemplateBundle\Model\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Gist\ValidationException;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Gist\AccountingBundle\Entity\TrialBalance;
use Gist\AccountingBundle\Entity\TrialBalanceSettings;
use DateTime;
use SplFileObject;
use LimitIterator;

class TrialBalanceSettingsController extends BaseController
{
    use TrackCreate;

    protected $date_from;
    protected $date_to;

    public function __construct()
    {
        $this->route_prefix = 'gist_tb_settings';
        $this->title = 'Trial Balance Settings';
        $this->list_title = 'Trial Balance Setting';
        $this->list_type = 'dynamic';
        $this->repo = "GistAccountingBundle:TrialBalanceSettings";
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $am = $this->get('gist_accounting');
        $params = $this->getViewParams('List');

        $twig_file = 'GistAccountingBundle:TrialBalanceSettings:index.html.twig';

        $params['coa_opts'] = $am->getChartOfAccountOptions();
        $params['list_title'] = $this->list_title;
        // $params['grid_cols'] = $gl->getColumns();
        $this->padListParams($params);
        return $this->render($twig_file, $params);
    }

    protected function newBaseClass()
    {
        return new TrialBalanceSettings();
    }
    
    protected function getObjectLabel($obj)
    {
        if ($obj == null){
            return 'Trial Balance Settings';
        }
        return 'Trial Balance Settings';
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('a', 'chart_of_account', 'getAccount'),
            // $grid->newJoin('g', 'group', 'getGroup'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Account Name', 'getNameCode', 'name', 'a'),
            $grid->newColumn('Record Date', 'getRecordDate', 'record_date', 'o', [$this,'formatDate']),
            $grid->newColumn('Particulars', 'getNotes', 'notes'),
     
            $grid->newColumn('Debit', 'getDebit', 'debit', 'o', [$this,'formatPrice']),
            $grid->newColumn('Credit', 'getCredit', 'credit',  'o', [$this,'formatPrice']),
        );
    }

    protected function hookPreAction()
    {
        // $this->getControllerBase();
        if($this->getRequest()->get('date_from') != null){
            $this->date_from = new DateTime($this->getRequest()->get('date_from'));
        }else {
           $date_from = new DateTime();
           $date_from->modify('first day of this month');
           $this->date_from = $date_from;
        }

        if($this->getRequest()->get('date_to') != null){
            $this->date_to = new DateTime($this->getRequest()->get('date_to'));
        }else {
           $date_to = new DateTime();
           $date_to->modify('last day of this month');
           $this->date_to = $date_to;
        }
    }

    protected function padListParams(&$params, $obj = null)
    {
        $am = $this->get('gist_accounting');
        
        $params['date_from'] = $this->date_from->format('m/d/Y'); //$this->date_from->format('m/d/Y'): $date_from->format('m/d/Y');
        $params['date_to'] = $this->date_to->format('m/d/Y');// != null?$this->date_to->format('m/d/Y'): $date_to->format('m/d/Y');
        
        $params['asset_opts_selected'] = $am->findTBSettingsByType(TrialBalanceSettings::TYPE_ASSET);
        $params['liablity_opts_selected'] = $am->findTBSettingsByType(TrialBalanceSettings::TYPE_LIABILITY);
        $params['capital_opts_selected'] = $am->findTBSettingsByType(TrialBalanceSettings::TYPE_CAPITAL);
        $params['netsales_opts_selected'] = $am->findTBSettingsByType(TrialBalanceSettings::TYPE_NET_STALES);
        $params['cos_opts_selected'] = $am->findTBSettingsByType(TrialBalanceSettings::TYPE_COS);
        $params['opex_opts_selected'] = $am->findTBSettingsByType(TrialBalanceSettings::TYPE_OPEX);

        return $params;

    }

    public function saveAction()
    {
        $em = $this->getDoctrine()->getManager();
        $am = $this->get('gist_accounting');
        $data = $this->getRequest()->request->all();

        if(isset($data['assets'])) {
            foreach ($data['assets'] as $key => $account) {
                $id = $am->findChartOfAccount($account);
                $assets = new TrialBalanceSettings();
                $assets->setAccount($id)
                       ->setType(TrialBalanceSettings::TYPE_ASSET)
                       ->setUserCreate($this->getUser());
                $em->persist($assets);
            }
        }

        if(isset($data['liability'])) {
            foreach ($data['liability'] as $key => $account) {
                $id = $am->findChartOfAccount($account);
                $liability = new TrialBalanceSettings();
                $liability->setAccount($id)
                          ->setType(TrialBalanceSettings::TYPE_LIABILITY)
                          ->setUserCreate($this->getUser());
                $em->persist($liability);
            }
        }

        if(isset($data['capital'])) {
            foreach ($data['capital'] as $key => $account) {
                $id = $am->findChartOfAccount($account);
                $capital = new TrialBalanceSettings();
                $capital->setAccount($id)
                        ->setType(TrialBalanceSettings::TYPE_CAPITAL)
                        ->setUserCreate($this->getUser());
                $em->persist($capital);
            }
        }

        if(isset($data['net_sales'])) {
            foreach ($data['net_sales'] as $key => $account) {
                $id = $am->findChartOfAccount($account);
                $net_sales = new TrialBalanceSettings();
                $net_sales->setAccount($id)
                          ->setType(TrialBalanceSettings::TYPE_NET_STALES)
                          ->setUserCreate($this->getUser());
                $em->persist($net_sales);
            }
        }

        if(isset($data['cos'])) {
            foreach ($data['cos'] as $key => $account) {
                $id = $am->findChartOfAccount($account);
                $cos = new TrialBalanceSettings();
                $cos->setAccount($id)
                    ->setType(TrialBalanceSettings::TYPE_COS)
                    ->setUserCreate($this->getUser());
                $em->persist($cos);
            }
        }

        if(isset($data['opex'])) {
            foreach ($data['opex'] as $key => $account) {
                $id = $am->findChartOfAccount($account);
                $opex = new TrialBalanceSettings();
                $opex->setAccount($id)
                     ->setType(TrialBalanceSettings::TYPE_OPEX)
                     ->setUserCreate($this->getUser());
                $em->persist($opex);
            }
        }      

        $em->flush();

        return new JsonResponse($data);
    }
}
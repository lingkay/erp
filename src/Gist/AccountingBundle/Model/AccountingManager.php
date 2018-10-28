<?php

namespace Gist\AccountingBundle\Model;

use Gist\UserBundle\Entity\User;
use Gist\UserBundle\Entity\Group;
use Gist\UserBundle\Entity\ItemsList;
use Doctrine\ORM\EntityManager;
use Gist\AccountingBundle\Entity\JournalEntryAbstract;
use Gist\AccountingBundle\Entity\TrialBalance;
use Gist\AccountingBundle\Entity\CRJJournalEntry;

class AccountingManager
{
    protected $em;
    protected $container;

    public function __construct(EntityManager $em, $container = null)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function getAccountTypeOptions()
    {
        return array(
            'Savings' => 'Savings',
            'Current' => 'Current',
            'Credit Card' => 'Credit Card',
            'Debit Card' => 'Debit Card'
        );
    }

    public function getCurrencyOptions()
    {
        return array(
            'PHP' => 'PHP - Philippine Peso',
            'USD' => 'USD - United States Dollar',
            'HKD' => 'HKD - Hong Kong Dollar',
            'EUR' => 'EUR - European Euro'
        );
    }

    public function getPaymentTypeOptions()
    {
        return array(
            'Quota' => 'Quota',
            'Rental' => 'Rental'
        );
    }

    public function getStatusOptions()
    {
        return array(
            'Active' => 'Active',
            'Inactive' => 'Inactive',
            'Deleted' => 'Deleted'
        );
    }

    public function getTerminalCompanyOptions()
    {
        return array(
            'GHL' => 'GHL',
            'Global Pay' => 'Global Pay',
            'BDO' => 'BDO',
            'Tangent' => 'Tangent'
        );
    }

    // public function getBankOptions()
    // {
    //     return array(
    //         'BDO' => 'BDO',
    //         'BPI' => 'BPI',
    //         'Citibank' => 'Citibank',
    //         'Security Bank' => 'Security Bank',
    //         'HSBC' => 'HSBC',
    //         'Metrobank' => 'Metrobank',
    //         'Unionbank' => 'Unionbank',
    //         'RCBC' => 'RCBC',
    //         'Chinabank' => 'Chinabank'
    //     );
    // }


    protected function getOptionsArray($repo, $filter, $order, $id_method, $value_method)
    {
        $em = $this->em;
        $objects = $em->getRepository($repo)
            ->findBy(
                $filter,
                $order
            );

        $opts = array();
        foreach ($objects as $o)
            $opts[$o->$id_method()] = $o->$value_method();

        return $opts;
    }

    

    public function getBankAccountOptions($filter = array())
    {
        return $this->getOptionsArray(
            'GistAccountingBundle:BankAccount',
            $filter,
            array('name' => 'ASC'),
            'getID',
            'getNameFormatted'
        );
    }

    public function getBankOptions($filter = array())
    {
        return $this->getOptionsArray(
            'GistAccountingBundle:Bank',
            $filter,
            array('id' => 'ASC'),
            'getID',
            'getName'
        );
    }

    public function getPOSLocationOptions($filter = array())
    {
        return $this->getOptionsArray(
            'GistLocationBundle:POSLocations',
            $filter,
            array('id' => 'ASC'),
            'getID',
            'getName'
        );
    }

    public function getTerminalOperatorOptions($filter = array())
    {
        return $this->getOptionsArray(
            'GistAccountingBundle:TerminalOperator',
            $filter,
            array('id' => 'ASC'),
            'getID',
            'getName'
        );
    }

    // public function findUser($id)
    // {
    //     return $this->em->getRepository('GistUserBundle:User')
    //         ->find($id);
    // }

    // public function findGroup($id)
    // {
    //     return $this->em->getRepository('GistUserBundle:Group')
    //         ->find($id);
    // }

    public function getTaxOpts($filter = array())
    {



        $tax['incl'] = 'Inclusive';
        $tax['excl'] = 'Exclusive';

        return $tax;
    }

    public function getChartOfAccountOptions($filter = [])
    {
        
        $objects = $this->em->getRepository('GistAccountingBundle:ChartOfAccount')
            ->findBy(
                $filter,
                ['id' => 'ASC']
            );

        $opts = array();
        foreach ($objects as $o)
            $opts[$o->getID()] = $o->getName()." (".$o->getCode().")";

        return $opts;
    }

    public function getMainAccountOptions($filter = [])
    {
        
        $objects = $this->em->getRepository('GistAccountingBundle:MainAccount')
            ->findBy(
                $filter,
                ['id' => 'ASC']
            );

        $opts = array();
        foreach ($objects as $o)
            $opts[$o->getID()] = $o->getName()." (".$o->getCode().")";

        return $opts;
    }


    public function findChartOfAccount($id)
    {
        return $this->em->getRepository('GistAccountingBundle:ChartOfAccount')->find($id);
    }

    public function findMainAccount($id)
    {
        return $this->em->getRepository('GistAccountingBundle:MainAccount')->find($id);
    }

    public function addTrialBalance(JournalEntryAbstract $entry){
        $month = $entry->getRecordDate()->format('m');
        $year = $entry->getRecordDate()->format('Y');

        $tb = $this->em->getRepository('GistAccountingBundle:TrialBalance')
            ->findOneBy(['month' => $month,
                    'year' => $year,
                    'chart_of_account' => $entry->getAccount()
            ]);

        if($tb == null){
            $tb = new TrialBalance($entry->getAccount());
            $tb->setMonth($month)
                ->setYear($year);
        }

        $credit = $tb->getCredit() + $entry->getCredit();
        $debit = $tb->getDebit() + $entry->getDebit();
        $tb->setCredit($credit)
            ->setDebit($debit);

        $this->em->persist($tb);
        $this->em->flush();
    }

    public function findTBSettingsByType($type){

        $tbs = $this->em->getRepository('GistAccountingBundle:TrialBalanceSettings')
            ->findBy(['type' => $type]);

        $array = [];
        if($tbs != null){
            foreach ($tbs as $key => $t) {
                $array[$t->getAccount()->getID()] = $t->getAccount()->getID();
            }
        }

        return $array;
    }

    public function insertCRJEntry($transaction)
    {
        $sale_approved = ['normal', 'upsell'];
        $conf = $this->container->get('gist_configuration');
        $am = $this->container->get('gist_accounting');
        $crj_conf = json_decode($conf->get('crj_settings'),true);
        $sales_coa = $am->findChartOfAccount($crj_conf['sales_debit']);
        $rcv_coa = $am->findChartOfAccount($crj_conf['receivable_credit']);

        if(in_array($transaction->getTransactionMode(),$sale_approved))
        {
            $sales_entry = new CRJJournalEntry();
            $sales_entry->setAccount($sales_coa)
                    ->setDebit(0)
                    ->setCredit((float)$transaction->getTransactionTotal())
                    ->setNotes($transaction->getTransDisplayId())
                    ->setUserCreate($transaction->getUserCreate())
                    ->setRecordDate($transaction->getDateCreate());
            
            $this->em->persist($sales_entry);
            $this->em->flush();

            $rcv_entry = new CRJJournalEntry();
            $rcv_entry->setAccount($rcv_coa)
                    ->setDebit((float)$transaction->getTransactionTotal())
                    ->setCredit(0)
                    ->setNotes($transaction->getTransDisplayId())
                    ->setUserCreate($transaction->getUserCreate())
                    ->setRecordDate($transaction->getDateCreate());
            
            $this->em->persist($rcv_entry);
            $this->em->flush();
        }
    }

    public function findCashFlowSettingsByType($type){

        $tbs = $this->em->getRepository('GistAccountingBundle:CashFlowSettings')
            ->findBy(['type' => $type]);

        $array = [];
        if($tbs != null){
            foreach ($tbs as $key => $t) {
                $array[$t->getAccount()->getID()] = $t->getAccount()->getID();
            }
        }

        return $array;
    }
}

<?php

namespace Gist\GridBundle\Model\Grid;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Gist\GridBundle\Model\FilterGroup;

class Loader
{
    protected $columns;
    protected $repository;
    protected $start;
    protected $limit;
    protected $sort_col;
    protected $sort_dir;
    protected $s_echo;
    protected $search_string;

    protected $qb_filter_group;
    protected $callbacks;

    protected $joins;
    protected $count_filter;

    protected $res;

    public function __construct(EntityManager $em)
    {
        $this->columns = array();
        $this->em = $em;
        $this->repository = null;
        $this->start = 0;
        $this->limit = 10;
        $this->sort_col = 0;
        $this->sort_dir = 'asc';
        $this->s_echo = 1;
        $this->search_string = '';

        $this->qb_filter_group = null;
        $this->callbacks = array();

        $this->joins = array();
        $this->count_filter = false;

        $this->res = new Response();
    }

    public function addColumn(Column $column)
    {
        $this->columns[] = $column;
        return $this;
    }


    public function addJoin(JoinRepo $join)
    {
        $this->joins[$join->getAlias()] = $join;
        return $this;
    }

    public function addEventHook($event, callable $callback)
    {
        // check for valid event
        if (!Event::isValid($event))
            throw new Exception("Invalid event ($event) used in Grid event hook.");

        if (isset($this->callbacks[$event]))
            $this->callbacks[$event][] = $callback;
        else
            $this->callbacks[$event] = array($callback);

        return $this;
    }

    public function setQBFilterGroup(FilterGroup $filter_group)
    {
        $this->qb_filter_group = $filter_group;
        return $this;
    }

    public function processParams($params)
    {
        if (isset($params['iDisplayStart']))
            $this->start = $params['iDisplayStart'];

        if (isset($params['iDisplayLength']))
            $this->limit = $params['iDisplayLength'];

        if (isset($params['sEcho']))
            $this->s_echo = $params['sEcho'];

        if (isset($params['sSearch']))
            $this->search_string = $params['sSearch'];

        if (isset($params['iSortingCols']))
        {
            // TODO: provide support for multiple column sort
            $col_count = $params['iSortingCols'];

            if ($col_count > 0)
            {
                $this->sort_col = $params['iSortCol_0'];
                $this->sort_dir = $params['sSortDir_0'];
            }
        }

        return $this;
    }

    public function setRepository($repo)
    {
        $this->repository = $repo;
        return $this;
    }

    public function setStart($start)
    {
        $this->start = $start;
        return $this;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }
    
    public function setSortColumn($col)
    {
        $this->sort_col = $col;
        return $this;
    }

    public function setSortDirection($dir)
    {
        $this->sort_dir = $dir;
        return $this;
    }

    public function setSEcho($s_echo)
    {
        $this->s_echo = $s_echo;
        return $this;
    }

    public function enableCountFilter($enable = true)
    {
        $this->count_filter = $enable;
        return $this;
    }

    protected function processCounts()
    {
        // normal total
        $qb = $this->em->createQueryBuilder();
        
        //Check if an entity has multiple identifiers
        $rc = $this->em->getClassMetadata($this->repository)->getIdentifier();
        if(count($rc) <= 1){
            $qb->select('count(o)')
                ->from($this->repository, 'o');
        }else{
            $qb->select('count(identity(o))')
                ->from($this->repository, 'o');
        }

        // joins
        foreach ($this->joins as $alias => $join){
            switch($join->getType()){
                case 'inner': $qb->innerJoin('o.' . $join->getField(), $alias);
                    break;
                case 'left': $qb->leftJoin('o.' . $join->getField(), $alias);
                    break;
            }
        }
        // total count
        if (!$this->count_filter)
            $total = $qb->getQuery()->getSingleScalarResult();

        // filter total
        if ($this->qb_filter_group != null)
            $this->qb_filter_group->apply($qb);

        // search filter
        $this->qbSearchFilter($qb);

        $f_total = $qb->getQuery()->getSingleScalarResult();

        // set total to filter total if count_filter is enabled
        if ($this->count_filter)
            $total = $f_total;

        $this->res->setFilterCount($f_total);
        $this->res->setTotalCount($total);

        return $this;
    }

    protected function qbSearchFilter($qb)
    {
        if (strlen(trim($this->search_string)) <= 0)
            return $this;

        // error_log($this->search_string);
        $orx = $qb->expr()->orx();
        foreach ($this->columns as $col)
        {
            if ($col->isSearchable())
            {
                $orx->add($qb->expr()->like($col->getFullOrderField(), $qb->expr()->literal('%' . $this->search_string . '%')));
            }
        }
        $qb->andWhere($orx);

        return $this;
    }

    protected function processData()
    {
        // query
        $qb = $this->em->createQueryBuilder();
        $qb->select('o')
            ->from($this->repository, 'o');


        // joins
        foreach ($this->joins as $alias => $join){
            switch($join->getType()){
                case 'inner': $qb->innerJoin('o.' . $join->getField(), $alias);
                    break;
                case 'left': $qb->leftJoin('o.' . $join->getField(), $alias);
                    break;
            }
        }
        // filter
        if ($this->qb_filter_group != null)
            $this->qb_filter_group->apply($qb);

        // search
        $this->qbSearchFilter($qb);

        // hooks
        if (isset($this->callbacks[Event::QB_BUILD]))
        {
            foreach ($this->callbacks[Event::QB_BUILD] as $qb_call)
            {
                call_user_func($qb_call, $qb);
            }
        }

        // order
        if (isset($this->columns[$this->sort_col]))
            $qb->orderBy($this->columns[$this->sort_col]->getFullOrderField(), $this->sort_dir);

        // paging
        $qb->setFirstResult($this->start)
            ->setMaxResults($this->limit);

        // debug
        // error_log('dql - ' . $qb->getQuery()->getDQL());

        // process rows
        $result = $qb->getQuery()->getResult();
        foreach ($result as $obj)
        {
            $row = array();

            foreach ($this->columns as $col)
            {
                if (isset($this->joins[$col->getObjectAlias()]))
                {
                    $getter = $this->joins[$col->getObjectAlias()]->getGetter();
                    $join_obj = $obj->$getter();
                    $row[] = $col->getValue($join_obj);
                }
                else
                    $row[] = $col->getValue($obj);
            }

            $this->res->addRow($row);
        }

        return $this;
    }

    protected function processData2($restrict)
    {
        // query
        $qb = $this->em->createQueryBuilder();
        $qb->select('o')
            ->from($this->repository, 'o')
            ->where('o.prodgroup_id != :item')
            ->setParameter('item', $restrict); 


        // joins
        foreach ($this->joins as $alias => $join){
            switch($join->getType()){
                case 'inner': $qb->innerJoin('o.' . $join->getField(), $alias);
                    break;
                case 'left': $qb->leftJoin('o.' . $join->getField(), $alias);
                    break;
            }
        }
        // filter
        if ($this->qb_filter_group != null)
            $this->qb_filter_group->apply($qb);

        // search
        $this->qbSearchFilter($qb);

        // hooks
        if (isset($this->callbacks[Event::QB_BUILD]))
        {
            foreach ($this->callbacks[Event::QB_BUILD] as $qb_call)
            {
                call_user_func($qb_call, $qb);
            }
        }

        // order
        if (isset($this->columns[$this->sort_col]))
            $qb->orderBy($this->columns[$this->sort_col]->getFullOrderField(), $this->sort_dir);

        // paging
        $qb->setFirstResult($this->start)
            ->setMaxResults($this->limit);

        // debug
        // error_log('dql - ' . $qb->getQuery()->getDQL());

        // process rows
        $result = $qb->getQuery()->getResult();
        foreach ($result as $obj)
        {
            $row = array();

            foreach ($this->columns as $col)
            {
                if (isset($this->joins[$col->getObjectAlias()]))
                {
                    $getter = $this->joins[$col->getObjectAlias()]->getGetter();
                    $join_obj = $obj->$getter();
                    $row[] = $col->getValue($join_obj);
                }
                else
                    $row[] = $col->getValue($obj);
            }

            $this->res->addRow($row);
        }

        return $this;
    }


    protected function processData3($date_from,$date_to)
    {

        $date_from = date("Y-m-d", strtotime($date_from));
        $date_to = date("Y-m-d", strtotime($date_to));

        // echo "<script>alert(".$date_from.")</script>";
        //echo "<script>alert('hello')</script>";

        // query
        $qb = $this->em->createQueryBuilder();
        $qb->select('o')
            ->from($this->repository, 'o')
            ->where('o.date_issue between :date and :date2')
            ->setParameter('date', $date_from)
            ->setParameter('date2', $date_to);


        // joins
        foreach ($this->joins as $alias => $join)
        {
            switch($join->getType()){
                case 'inner': $qb->innerJoin('o.' . $join->getField(), $alias);
                    break;
                case 'left': $qb->leftJoin('o.' . $join->getField(), $alias);
                    break;
            }
        }
        // filter
        if ($this->qb_filter_group != null)
            $this->qb_filter_group->apply($qb);

        // search
        $this->qbSearchFilter($qb);

        // hooks
        if (isset($this->callbacks[Event::QB_BUILD]))
        {
            foreach ($this->callbacks[Event::QB_BUILD] as $qb_call)
            {
                call_user_func($qb_call, $qb);
            }
        }

        // order
        if (isset($this->columns[$this->sort_col]))
            $qb->orderBy($this->columns[$this->sort_col]->getFullOrderField(), $this->sort_dir);

        // paging
        $qb->setFirstResult($this->start)
            ->setMaxResults($this->limit);

        // debug
        // error_log('dql - ' . $qb->getQuery()->getDQL());

        // process rows
        $result = $qb->getQuery()->getResult();
        foreach ($result as $obj)
        {
            $row = array();

            foreach ($this->columns as $col)
            {
                if (isset($this->joins[$col->getObjectAlias()]))
                {
                    $getter = $this->joins[$col->getObjectAlias()]->getGetter();
                    $join_obj = $obj->$getter();
                    $row[] = $col->getValue($join_obj);
                }
                else
                    $row[] = $col->getValue($obj);
            }

            $this->res->addRow($row);
        }

        return $this;
    }

    public function load()
    {
        $this->res->setSEcho($this->s_echo);
        $this->processCounts();
        $this->processData();

        return $this->res;
    }

    public function load2($restrict)
    {
        $this->res->setSEcho($this->s_echo);
        $this->processCounts();
        $this->processData2($restrict);

        return $this->res;
    }

    public function load3($date_from,$date_to)
    {
        $this->res->setSEcho($this->s_echo);
        $this->processCounts();
        $this->processData3($date_from,$date_to);

        return $this->res;
    }


    public function getColumns()
    {
        return $this->columns;
    }


    public function getColumns2()
    {
        return $this->columns;
    }


}

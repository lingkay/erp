<?php

namespace Gist\GridBundle\Model\Grid;

class Response
{
    protected $s_echo;
    protected $total_count;
    protected $filter_count;
    protected $rows;

    public function __construct()
    {
        $this->s_echo = 1;
        $this->total_count = 0;
        $this->filter_count = 0;
        $this->rows = array();
    }

    public function setSEcho($s_echo)
    {
        $this->s_echo = $s_echo;
        return $this;
    }

    public function setTotalCount($count)
    {
        $this->total_count = $count;
        return $this;
    }

    public function setFilterCount($count)
    {
        $this->filter_count = $count;
        return $this;
    }

    public function addRow($row)
    {
        $this->rows[] = $row;
        return $this;
    }

    protected function build()
    {
        $res = array(
            'sEcho' => $this->s_echo,
            'iTotalRecords' => $this->total_count,
            'iTotalDisplayRecords' => $this->filter_count,
            'aaData' => $this->rows
        );

        return $res;
    }

    public function getJSON()
    {
        $res = $this->build();
        return json_encode($res);
    }
}

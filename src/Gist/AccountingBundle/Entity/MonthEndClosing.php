<?php

namespace Gist\AccountingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gist\CoreBundle\Template\Entity\HasType;
use Gist\CoreBundle\Template\Entity\HasCode;
use Gist\CoreBundle\Template\Entity\HasStatus;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasNotes;
use Gist\CoreBundle\Template\Entity\TrackCreate;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="acct_month_end")
 */

class MonthEndClosing
{
    use HasGeneratedID;
    use TrackCreate;

    /** @ORM\Column(type="string", length=20) */
    protected $month;

    /** @ORM\Column(type="string", length=4) */
    protected $year;

    /** @ORM\Column(type="boolean") */
    protected $is_closed;

    public function __construct()
    {
        $this->initTrackCreate();
    }

    public function setMonth($month)
    {
        $this->month = $month;
        return $this;
    }

    public function getMonth()
    {
        return $this->month;
    }

    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function setIsClosed($closed = true)
    {
        $this->is_closed = $closed;
        return $this;
    }

    public function getIsClosed()
    {
        return $this->is_closed;
    }
}

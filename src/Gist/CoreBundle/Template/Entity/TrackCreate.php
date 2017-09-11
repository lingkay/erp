<?php

namespace Gist\CoreBundle\Template\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Gist\UserBundle\Entity\User;

trait TrackCreate
{
    /** @ORM\Column(type="datetime") */
    protected $date_create;

    /** 
     * @ORM\ManyToOne(targetEntity="\Gist\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_create_id", referencedColumnName="id")
     */
    protected $user_create;

    public function initTrackCreate()
    {
        $this->date_create = new DateTime();
    }

    public function setDateCreate(DateTime $date)
    {
        $this->date_create = $date;
        return $this;
    }

    public function getDateCreate()
    {
        return $this->date_create;
    }
    
    public function getDateCreateFormatted()
    {
        return $this->date_create->format('m/d/Y');
    }

    public function getDateCreateFormatted2()
    {
        return $this->date_create->format('m/d/Y');
    }

    public function setUserCreate(User $user)
    {
        $this->user_create = $user;
        return $this;
    }

    public function getUserCreate()
    {
        return $this->user_create;
    }

    protected function dataTrackCreate($data)
    {
        $data->date_create = $this->date_create->format('Y-m-d H:i:s');
        $data->user_create = $this->user_create!= null?$this->user_create->toData(): null;
    }
}

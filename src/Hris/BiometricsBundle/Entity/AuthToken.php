<?php

namespace Hris\BiometricsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="auth_tokens")
 */
class AuthToken
{

    use HasGeneratedID;


    /** @ORM\Column(type="date") */
    protected $date;

    /** @ORM\Column(type="text") */
    protected $token;

    public function __construct()
    {

    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    public function getDate()
    {
        return $this->date->format('m/d/Y');;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $data->token = $this->token;
        $data->date = $this->date;
        return $data;
    }

    
}

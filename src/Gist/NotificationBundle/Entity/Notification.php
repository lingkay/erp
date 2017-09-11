<?php

namespace Gist\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\TrackCreate;
use Datetime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="ntf_notification")
 */
class Notification
{
    const TYPE_ALERT = "ALERT";
    const TYPE_UPDATE = "UPDATE";
    const TYPE_CALENDAR = "CALENDAR";

    use HasGeneratedID;
    use TrackCreate;


    /** @ORM\Column(type="string", length=50) */
    protected $source;

    /** @ORM\Column(type="string") */
    protected $link;

    /** @ORM\Column(type="text") */
    protected $message;

    /** @ORM\Column(type="string", length=20) */
    protected $type;

    public function __construct($source, $link, $message, $type){
    	$this->source = $source;
    	$this->link = $link;
    	$this->message = $message;
    	$this->type = $type;
        $this->initTrackCreate();
    }

    public function setLink($link)
    {
    	$this->link = $link;
    	return $this;
    }

    public function getLink()
    {
    	return $this->link; 
    }

    public function setMessage($message)
    {
    	$this->message = $message;
    	return $this;
    }

    public function getMessage()
    {
    	return $this->message;
    }

    public function setType($type)
    {
    	$this->type = $type;
    	return $this;
    }

    public function getType()
    {
    	return $this->type;
    }

    public function getTimePassed()
    {
    	$current = new Datetime();
    	$passed = $current->diff($this->date_create);

    	if($passed->y > 0){
    		return $passed->y." years ago";
    	}
    	if($passed->m > 0){
    		return $passed->m." months ago";
    	}
    	if($passed->d > 0){
    		return $passed->d." days ago";
    	}
    	if($passed->h > 0){
    		return $passed->h." hours ago";
    	}
    	if($passed->i > 0){
    		return $passed->i." minutes ago";
    	}
    	if($passed->s > 0){
    		return $passed->s." seconds ago";
    	}
    }

	public function toData()
    {
        $data = new stdClass();

        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);


        return $data;
    }

}
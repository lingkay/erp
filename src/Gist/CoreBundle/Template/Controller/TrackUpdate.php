<?php

namespace Gist\CoreBundle\Template\Controller;
use DateTime;

trait TrackUpdate
{
	protected function updateTrackUpdate($o, $data)
	{
		$o->setDateUpdate($this->date_update = new DateTime());
		$o->setUserUpdate($this->getUser());
	}
}
<?php

namespace Hris\WorkforceBundle\Model;

use Doctrine\ORM\EntityManager;
use Hris\WorkforceBundle\Entity\Request;
use Hris\AdminBundle\Model\SettingsManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;

use DateTime;

class RequestManager
{
    protected $em;
    use TrackCreate;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;

    }

    public function getRequestCount()
    {
        $request_count = 0;
        $requests = $this->em->getRepository('HrisWorkforceBundle:Request')->findByStatus(Request::STATUS_PENDING);

        foreach($requests as $request)
        {
            $request_count++;
        }

        return $request_count;
    }

}
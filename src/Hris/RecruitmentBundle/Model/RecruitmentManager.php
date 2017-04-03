<?php

namespace Hris\RecruitmentBundle\Model;

use Doctrine\ORM\EntityManager;
use Hris\WorkforceBundle\Entity\Employee;
use Hris\WorkforceBundle\Entity\Profile;
use Gist\UserBundle\Entity\User;
use Hris\RecruitmentBundle\Entity\ManpowerRequest;
use Hris\RecruitmentBundle\Entity\Application;

class RecruitmentManager
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getVacantPositions()
    {
        $vacant = $this->em->getRepository('HrisRecruitmentBundle:ManpowerRequest')->findByStatus(ManpowerRequest::STATUS_APPROVED);
        $list = array();

        foreach ($vacant as $item){
            $list[] = $item->getPosition();
        }

        return $list;
    }

    public function getVacancyCount()
    {
        $vacancy = 0;

        $requests = $this->em->getRepository('HrisRecruitmentBundle:ManpowerRequest')->findByStatus(ManpowerRequest::STATUS_APPROVED);

        foreach ($requests as $request) {
            $vacancy = $vacancy + $request->getVacancy();
        }

        return $vacancy;
    }

    public function getShortlistedCount()
    {
        $shortlist = 0;

        $applications = $this->em->getRepository('HrisRecruitmentBundle:Application')->findAll();

        foreach ($applications as $application) 
        {
            if($application->getStatus() == Application::STATUS_OFFER)
            {
                    $shortlist++;
            }
        }

        return $shortlist;
    }

    public function ManpowerRequestCount($obj)
    {
        $position = $obj->getJobOffer()['position'];

        $query = $this->em->createQueryBuilder();
        $query->from('HrisRecruitmentBundle:ManpowerRequest','o')
              ->join('HrisAdminBundle:JobTitle','jt', 'WITH', 'o.position = jt.id')
              ->where('o.position = :position')
              ->andWhere('o.status = :status')
              ->setParameter('status', ManpowerRequest::STATUS_APPROVED)
              ->setParameter('position', $position);

        $request = $query->select('o')
                         ->getQuery()
                         ->getOneOrNullResult();

        if($request != null)
        {
            $count = $request->getVacancy() - 1;
            if($count <= 0)
            {
                $request->setStatus(ManpowerRequest::STATUS_ARCHIVED);
                $count = 0;
                $request->setVacancy($count);
            }
            else
            {
                $request->setVacancy($count);
            }
        }
    }
}
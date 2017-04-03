<?php

namespace Hris\WorkforceBundle\Model;

use Doctrine\ORM\EntityManager;
use Hris\WorkforceBundle\Entity\Employee;
use Hris\WorkforceBundle\Entity\Profile;
use Hris\WorkforceBundle\Entity\Appraisal;
use Hris\WorkforceBundle\Entity\Evaluator;
use Hris\AdminBundle\Model\SettingsManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;

use DateTime;

class AppraisalManager
{
    protected $em;
    use TrackCreate;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;

    }

    public function checkAppraisals()
    {
        $employees = $this->em->getRepository("HrisWorkforceBundle:Employee")->findByEnabled(true);
        $date = new Datetime();
        $this->sendToUsers($notification, $users);
    }

    public function getEvaluators($app_id)
    {
        $evaluators = $this->em->getRepository('HrisWorkforceBundle:Evaluator')->findBy(array('appraisal' => $app_id));

        return $evaluators;
    }

    public function getAppraisalOverallStatus($app_id)
    {
        $evaluators = $this->getEvaluators($app_id);
        $counter = 0;

        foreach ($evaluators as $evaluation) {
            if ($evaluation->getStatus() !== 'Completed') {
                $counter = $counter + 1;
            }
        }

        if ($counter > 0) {
            return Appraisal::INCOMPLETE;
        }

        return Appraisal::COMPLETE;
    }

    public function getAppraisalGrade($app_id)
    {
        $evaluators = $this->getEvaluators($app_id);
        $counter = 0;
        $grade = 0;

        foreach ($evaluators as $evaluation) {
            if ($evaluation->getStatus() == 'Completed') {
                $counter = $counter + 1;
                $grade = $grade + $evaluation->getQuantiRating();
            }
        }

        if ($counter == 0) {
            return "N/A";
        }
        else{
            $finalGrade = $grade / $counter;
            return round($finalGrade, 2);
        }
    }

    public function getAppraisalRating($app_id)
    {
        $evaluators = $this->getEvaluators($app_id);
        $ratings = $this->em->getRepository('HrisWorkforceBundle:Rating')->findAll();
        $counter = 0;
        $grade = 0;
        $quali = '';

        foreach ($evaluators as $evaluation) {
            if ($evaluation->getStatus() == 'Completed') {
                $counter = $counter + 1;
                $grade = $grade + $evaluation->getQuantiRating();
            }
        }

        if ($counter == 0) {
            return "N/A";
        }
        else{
            $finalGrade = $grade / $counter;
            foreach ($ratings as $rate) {
                if ($finalGrade >= $rate->getRangeStart() && $finalGrade <= $rate->getRangeEnd()) {
                    $quali = $rate->getRating();
                }
            }
            return $quali;
        }
    }

    public function getAppFinalGrade($app_id)
    {
        $evaluators = $this->getEvaluators($app_id);
        $total = 0;
        $average = 0;
        $ctr = 0;

        $status = $this->getAppraisalOverallStatus($app_id);

        if ($status == 'Complete') {
            foreach ($evaluators as $eval) {
                $ctr += 1;
                $total = $total + $eval->getQuantiRating();
            }

            $average = $total / $ctr;
            return round($average, 2);
        }
        else
            return "N/A";
    }

    public function getAppFinalRating($app_id)
    {
        $ratings = $this->em->getRepository('HrisWorkforceBundle:Rating')->findAll();

        $average = $this->getAppFinalGrade($app_id);
        $quali = '';

        $status = $this->getAppraisalOverallStatus($app_id);

        if ($status == 'Complete') {
            foreach ($ratings as $grade) {
                if ($average >= $grade->getRangeStart() && $average <= $grade->getRangeEnd()) {
                    $quali = $grade->getRating();
                }
            }

            return $quali;
        }
        else
            return "N/A";
    }
}
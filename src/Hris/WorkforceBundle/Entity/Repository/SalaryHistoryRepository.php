<?php

namespace Hris\WorkforceBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class SalaryHistoryRepository extends EntityRepository
{
    public function getLatestPayByEmployee($employee)
    {
		return $this->getEntityManager()
            ->createQuery(
                'SELECT h FROM HrisWorkforceBundle:SalaryHistory h WHERE h.employee = :employee  ORDER BY h.date_create DESC'
            )
            ->setParameter('employee', $employee)
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }
}
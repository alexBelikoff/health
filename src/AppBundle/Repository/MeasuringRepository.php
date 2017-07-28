<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MeasuringRepository extends EntityRepository
{
    public function getMeasuringByPatient($patient)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->andWhere('m.patient = :patient')
            ->setParameter('patient', $patient->getId());
        $qb->addOrderBy('m.date');
        return $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }
}
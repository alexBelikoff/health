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

    /*public function getMeasuringByPatient2($patient)
    {
        $connection = $this->em->getConnection();
        $statement = $connection->prepare("select * from public.get_patient_values(patient => :patient,1)");
        $statement->bindValue('patient', $patient, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }*/

    public function getLastMeasuringByPatient($patient)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->andWhere('m.patient = :patient')
            ->setParameter('patient', $patient->getId());
        $qb->addOrderBy('m.date', 'ASC')->setMaxResults('1');;
        return $qb->getQuery()->getResult();
    }

}
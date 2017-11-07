<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

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

    public function getMeasuringByPatientWithStab($patient)
    {
        $entityManager = $this->getEntityManager();
        $connection = $entityManager->getConnection();
        $statement = $connection->prepare("SELECT measure_date, morning_value, evening_value, stability_value 
            FROM public.get_patient_values(p_patient_id => :patient,p_measure_type_id => :measure_type)");
        $statement->bindValue('patient', $patient->getId(), \PDO::PARAM_INT);
        $statement->bindValue('measure_type', 1, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function getLastMeasuringByPatient($patient)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->andWhere('m.patient = :patient')
            ->setParameter('patient', $patient->getId());
        $qb->addOrderBy('m.date', 'ASC')->setMaxResults('1');;
        return $qb->getQuery()->getResult();
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Maria-Alexey
 * Date: 29.07.2017
 * Time: 21:33
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;


class DoctorRepository extends EntityRepository
{
    public function getRandomDoctors()
    {
        $entityManager = $this->getEntityManager();
        $rsm = new ResultSetMappingBuilder($entityManager);
        $rsm->addRootEntityFromClassMetadata('AppBundle\Entity\Doctor', 'd');
        $query = $entityManager->createNativeQuery('SELECT * FROM health_doctor d ORDER BY RANDOM() LIMIT 3 ', $rsm);
        return $query->getResult();
    }

    public function getAllDoctors()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT d FROM AppBundle:Doctor d ORDER BY d.firstName ASC'
            )
            ->getResult();
    }
}
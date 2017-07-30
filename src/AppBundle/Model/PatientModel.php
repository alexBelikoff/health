<?php
/**
 * Created by PhpStorm.
 * User: Maria-Alexey
 * Date: 30.07.2017
 * Time: 19:33
 */

namespace AppBundle\Model;

use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\EntityManager;



class PatientModel
{

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function setCurrentWeight(PersistentCollection $patients)
    {
        $repository = $this->em->getRepository('AppBundle:Measuring');
        foreach ($patients as $patient){
            $patient->setCurrentWeight($repository->getLastMeasuringByPatient($patient));
        }
        return $patients;
    }
}
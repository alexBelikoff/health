<?php
/**
 * Created by PhpStorm.
 * User: Maria-Alexey
 * Date: 30.07.2017
 * Time: 17:46
 */

namespace AppBundle\Model;

use Doctrine\ORM\PersistentCollection;

class DoctorModel
{
    public function getArrayIdDoctors(PersistentCollection $doctors)
    {
        $ids = [];
        foreach ($doctors as $doctor){
            $ids[] = $doctor->getId();
        }
        return $ids;
    }
}
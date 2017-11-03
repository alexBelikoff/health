<?php
/**
 * Created by PhpStorm.
 * User: belikov
 * Date: 28.07.2017
 * Time: 14:30
 */

namespace AppBundle\Model;

use Doctrine\ORM\EntityManager;

class MeasuringModel
{
    protected $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function normalizeMeasuring (array $measuring)
    {
        $normalizedMeasuring = [];
        for($i = 0, $j = count($measuring); $i < $j; ++$i){
            $normalizedMeasuring[] = [($measuring[$i]['date']->getTimestamp()*1000), (float)$measuring[$i]['value']];
        }
        return $normalizedMeasuring;
    }

    public function normalizeMeasuring2 (array $measuring2)
    {
        $normalizedMeasuring2 = [];
        for($i = 0, $j = count($measuring2); $i < $j; ++$i){
            $normalizedMeasuring2[] = [($measuring2[$i]['measure_date']->getTimestamp()*1000), (float)$measuring2[$i]['value']];
        }
        return $normalizedMeasuring2;
    }

    public function getStabilityRange(int $id)
    {
        $connection = $this->em->getConnection();
        $statement = $connection->prepare("SELECT * FROM public.get_stability_range(p_measure_fix_id => :id)");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }
}
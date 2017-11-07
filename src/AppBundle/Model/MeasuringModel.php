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

    public function normalizeMeasuringWithStab (array $measuring)
    {
        $normalizedMeasuring = [];
        for($i = 0, $j = count($measuring); $i < $j; ++$i){
            $d = new \DateTime($measuring[$i]['measure_date']);
            $normalizedMeasuring['morning'][] = [
                ($d->getTimestamp()*1000),
                (float)$measuring[$i]['morning_value'],
                ];
            $normalizedMeasuring['evening'][] = [
                ($d->getTimestamp()*1000),
                (float)$measuring[$i]['evening_value'],
            ];
        }
        dump($normalizedMeasuring);
        return $normalizedMeasuring;
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
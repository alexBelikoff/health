<?php
/**
 * Created by PhpStorm.
 * User: belikov
 * Date: 28.07.2017
 * Time: 14:30
 */

namespace AppBundle\Model;


class MeasuringModel
{
    public function normalizeMeasuring (array $measuring)
    {
        $normalizedMeasuring = [];
        for($i = 0, $j = count($measuring); $i < $j; ++$i){
            $normalizedMeasuring[] = [($measuring[$i]['date']->getTimestamp()*1000), (float)$measuring[$i]['value']];
        }
        return $normalizedMeasuring;
    }
}
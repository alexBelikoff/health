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
    public function normalizeMeasuringDate (array $measuring)
    {
        for($i = 0, $j = count($measuring); $i < $j; ++$i){
            $currentDate = new \DateTime($measuring[$i]['date']);
            $measuring[$i]['date'] = $currentDate->format('d:m:Y H:i');
        }
        return $measuring;
    }
}
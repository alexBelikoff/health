<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\VarDumper\VarDumper;


class MeasuringType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date',TextType::class,['label' => 'Введите дату и время измерения']);
        $builder->get('date')->addModelTransformer(new CallbackTransformer(
                function($date){
                    if(!$date){
                        $date = new \DateTime();
                        //return null;
                    }
                    return $date->format('Y/m/d H:i');
                },
                function($dateString){
                    $date = \DateTime::createFromFormat('Y/m/d H:i', $dateString);
                    $date->format('Y-m-d H:i:s');
                    return $date;
                }
            ));
            $builder->add('value', IntegerType::class, ['label' => 'Введите вес']);
            $builder->add('save', SubmitType::class, ['label' => 'Сохранить']);
    }
}
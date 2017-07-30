<?php
/**
 * Created by PhpStorm.
 * User: Maria-Alexey
 * Date: 30.07.2017
 * Time: 18:01
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DoctorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lastName',TextType::class,['label' => "Фамилия"]);
        $builder->add('firstName', TextType::class, ['label' => "Имя"]);
        $builder->add('secondName', TextType::class, ['label' => "Отчество"]);
        $builder->add('birthDate',TextType::class,['label' => "Дата рождения"]);
        $builder->get('birthDate')->addModelTransformer(new CallbackTransformer(
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

        $builder->add('description', TextareaType::class, ['label' => "Коротко о себе",
            'attr' => ['rows' => '10']]);
        $builder->add('title', TextType::class, ['label' => "Ваш девиз"]);

        $builder->add('edit', ButtonType::class, ['label' => 'Изменить','attr' => ['class' => 'btn btn-success']]);
        $builder->add('save', SubmitType::class,
            [
                'label' => 'Сохранить',
                'attr' => ['class' => 'btn btn-primary', 'style' => 'display: none;']
            ]);
    }
}
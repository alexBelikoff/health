<?php
/**
 * Created by PhpStorm.
 * User: Beluha
 * Date: 10.11.2017
 * Time: 12:19
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MeasuringTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',TextType::class,['label' => 'Тип']);

    }
}
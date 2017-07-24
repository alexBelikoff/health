<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\User;
use AppBundle\Entity\Patient;
use AppBundle\Entity\Measuring;
use AppBundle\Entity\MeasuringType;
class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('patient1');
        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, 'patient1');
        $user->setPassword($password);
        $user->setEnabled(true);



        $type = new MeasuringType();
        $type->setName('Измерение веса');
        $type->setType('weight');

        $manager->persist($type);
        $manager->flush();

        $patient = new Patient();
        $patient->setFirstName('Алексей');
        $patient->setLastName('Алексеев');
        $patient->setSecondName('Алексеевич');
        $patient->setGender(1);
        $patient->setHeight('183');
        $manager->persist($patient);
        $manager->flush();

        $measuringDate = new \DateTime();
        $measuringDate->setDate(2017,01, 02);
        for($i = 0, $j = 797; $i < $j; ++$i){
            $weight = 80;
            $measuring = new Measuring();
            $measuring->setValue($weight + rand(-2, 2));
            $measuring->setType($type);
            $measuringDate->add(new \DateInterval('PT5H30S'));
            $measuringDate->format('Y-m-d H:i:s');
            $measuring->setDate($measuringDate);
            $measuring->setPatient($patient);
            $manager->persist($measuring);
            $manager->flush();
        }



        $user->setPatient($patient);
        $user->setEmail('patient1@mail.ru');



        $manager->persist($user);
        $manager->flush();
    }
}
<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use AppBundle\Entity\User;
use AppBundle\Entity\Patient;
use AppBundle\Entity\Measuring;
use AppBundle\Entity\MeasuringType;
use AppBundle\Entity\Doctor;

class LoadUserData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
        $user->addRole('ROLE_PATIENT');
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
        $patient->setFirstName('Бздашек');
        $patient->setLastName('Западловский');
        $patient->setSecondName('Алексеевич');
        $patient->setGender(1);
        $patient->setHeight('183');
        $patient->setImageName('user1.jpg');
        $patient->setImageSize(16384);
        $patient->setUpdatedAt(new \DateTime('now'));
        $manager->persist($patient);
        $patient->addDoctor($this->getReference('doctor_aibolit'));
        $patient->addDoctor($this->getReference('doctor_hause'));
        $manager->flush();

        $measuringDate = new \DateTime();
        $measuringDate->setDate(2015,01, 02);
        for($i = 0, $j = 397; $i < $j; ++$i){
            $weight = 80;
            $measuring = new Measuring();
            $measuring->setValue($weight + rand(-2, 2));
            $measuring->setType($type);
            $nextDate = rand(10, 160);
            $measuringDate->add(new \DateInterval('PT'.$nextDate.'H30S'));
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


        $user = new User();
        $user->setUsername('patient2');
        $user->addRole('ROLE_PATIENT');
        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, 'patient2');
        $user->setPassword($password);
        $user->setEnabled(true);



        $type = new MeasuringType();
        $type->setName('Измерение веса');
        $type->setType('weight');

        $manager->persist($type);
        $manager->flush();

        $patient = new Patient();
        $patient->setFirstName('Кириякия');
        $patient->setLastName('Никостратиевна');
        $patient->setSecondName('Вышкварка');
        $patient->setGender(2);
        $patient->setHeight('198');
        $patient->setImageName('user2.jpg');
        $patient->setImageSize(16384);
        $patient->setUpdatedAt(new \DateTime('now'));
        $manager->persist($patient);
        $patient->addDoctor($this->getReference('doctor_gipocrat'));
        $manager->flush();

        $measuringDate = new \DateTime();
        $measuringDate->setDate(2013,9, 17);
        for($i = 0, $j = 797; $i < $j; ++$i){
            $weight = 75;
            $nextDate = rand(10, 200);
            $measuring = new Measuring();
            $measuring->setValue($weight + rand(-2, 2));
            $measuring->setType($type);
            $measuringDate->add(new \DateInterval('PT'.$nextDate.'H30S'));
            $measuringDate->format('Y-m-d H:i:s');
            $measuring->setDate($measuringDate);
            $measuring->setPatient($patient);
            $manager->persist($measuring);
            $manager->flush();
        }



        $user->setPatient($patient);
        $user->setEmail('patient2@mail.ru');



        $manager->persist($user);
        $manager->flush();




        $user = new User();
        $user->setUsername('patient3');
        $user->addRole('ROLE_PATIENT');
        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, 'patient3');
        $user->setPassword($password);
        $user->setEnabled(true);



        $type = new MeasuringType();
        $type->setName('Измерение веса');
        $type->setType('weight');

        $manager->persist($type);
        $manager->flush();

        $patient = new Patient();
        $patient->setFirstName('Пьер');
        $patient->setLastName('Духа');
        $patient->setSecondName('Склизкий');
        $patient->setGender(1);
        $patient->setHeight('158');
        $patient->setImageName('user3.jpg');
        $patient->setImageSize(16384);
        $patient->setUpdatedAt(new \DateTime('now'));
        $manager->persist($patient);
        $patient->addDoctor($this->getReference('doctor_sina'));
        $patient->addDoctor($this->getReference('doctor_hause'));
        $manager->flush();

        $measuringDate = new \DateTime();
        $measuringDate->setDate(2015,3, 1);
        for($i = 0, $j = 205; $i < $j; ++$i){
            $weight = 116;
            $nextDate = rand(10, 130);
            $measuring = new Measuring();
            $measuring->setValue($weight + rand(-3, 3));
            $measuring->setType($type);
            $measuringDate->add(new \DateInterval('PT'.$nextDate.'H30S'));
            $measuringDate->format('Y-m-d H:i:s');
            $measuring->setDate($measuringDate);
            $measuring->setPatient($patient);
            $manager->persist($measuring);
            $manager->flush();
        }



        $user->setPatient($patient);
        $user->setEmail('patient3@mail.ru');



        $manager->persist($user);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
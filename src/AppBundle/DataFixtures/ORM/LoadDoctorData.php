<?php
/**
 * Created by PhpStorm.
 * User: Beluha
 * Date: 24.07.2017
 * Time: 21:03
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\User;
use AppBundle\Entity\Doctor;


class LoadDoctorData implements FixtureInterface, ContainerAwareInterface
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
        $user->setUsername('doctor1');
        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, 'doctor1');
        $user->setPassword($password);
        $user->setEnabled(true);
        $doctor = new Doctor();
        $doctor->setFirstName('Айболит');
        $doctor->setLastName('Айболитович');
        $doctor->setSecondName('Айболитов');
        $doctor->setImageName('doctor1.jpg');
        $doctor->setImageSize(16384);
        $doctor->setUpdatedAt(new \DateTime('now'));
        $doctor->setDescription('Родился и жил в Вильне. Окончил медицинский факультет Московского университета. 
        Занимался медицинской практикой в Москве. Затем работал как врач-эпидемиолог в ряде областей России. 
        Участвовал в борьбе с эпидемией холеры в Астрахани. С 1894 года жил в Вильно. 
        Возглавлял одну из местных больниц, получил известность как врач-терапевт. 
        Создал в Вильно отделение Общества охранения здоровья еврейского населения, 
        возглавлял отделение Еврейского колонизационного общества. Затем член городского муниципалитета в Вильно.');
        $doctor->setGender(1);
        $manager->persist($doctor);
        $manager->flush();
        $user->setDoctor($doctor);
        $user->setEmail('doctor1@mail.ru');

        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->setUsername('doctor2');
        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, 'doctor2');
        $user->setPassword($password);
        $user->setEnabled(true);
        $doctor = new Doctor();
        $doctor->setFirstName('Гиппокра́т');
        $doctor->setLastName('Гиппокра́ттович');
        $doctor->setSecondName('Гиппокра́ттов');
        $doctor->setImageName('doctor2.jpg');
        $doctor->setImageSize(16384);
        $doctor->setUpdatedAt(new \DateTime('now'));
        $doctor->setDescription('Уроженец греческого острова Кос, находящегося в восточной части Эгейского моря,
         где он появился на свет примерно в 460 г. до н. э. Он был продолжателем рода асклепиадов, 
         династии врачей, основатель которой, легендарный Асклепий (Эскулап) впоследствии 
         был признан богом медицины. Религиозный характер деятельности представителей 
         этого рода органично сочетался с научными исследованиями и поисками истины. 
         Своими познаниями с Гиппократом делились его тезка-дед и отец-врач Гераклид и мать 
         Фенарета, которая была повитухой. В свою очередь, сам Гиппократ передавал знания и 
         опыт сыновьям Драконту и Фесаллу и зятю Полибу.');
        $doctor->setGender(1);
        $manager->persist($doctor);
        $manager->flush();
        $user->setDoctor($doctor);
        $user->setEmail('doctor2@mail.ru');

        $manager->persist($user);
        $manager->flush();
    }
}
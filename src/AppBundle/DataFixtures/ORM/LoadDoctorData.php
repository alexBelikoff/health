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
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use AppBundle\Entity\User;
use AppBundle\Entity\Doctor;


class LoadDoctorData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
        $user->addRole('ROLE_DOCTOR');
        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, 'doctor1');
        $user->setPassword($password);
        $user->setEnabled(true);
        $doctor = new Doctor();
        $doctor->setFirstName('Айболит');
        $doctor->setLastName('Айболитович');
        $doctor->setSecondName('Айболитов');
        $doctor->setImageName('doctor1.jpg');
        $doctor->setTitle('Приходи ко мне лечиться...');
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

        $this->addReference('doctor_aibolit', $doctor);

        $user = new User();
        $user->setUsername('doctor2');
        $user->addRole('ROLE_DOCTOR');
        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, 'doctor2');
        $user->setPassword($password);
        $user->setEnabled(true);
        $doctor = new Doctor();
        $doctor->setFirstName('Гиппокра́т');
        $doctor->setLastName('Гиппокра́ттович');
        $doctor->setSecondName('Гиппокра́ттов');
        $doctor->setImageName('doctor2.jpg');
        $doctor->setTitle('Всё хорошо, что в меру');
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
        $this->addReference('doctor_gipocrat', $doctor);
        $user->setDoctor($doctor);
        $user->setEmail('doctor2@mail.ru');

        $manager->persist($user);
        $manager->flush();


        $user = new User();
        $user->setUsername('doctor3');
        $user->addRole('ROLE_DOCTOR');
        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, 'doctor3');
        $user->setPassword($password);
        $user->setEnabled(true);
        $doctor = new Doctor();
        $doctor->setFirstName('Грегори');
        $doctor->setLastName('Грегорьевич');
        $doctor->setSecondName('Хаус');
        $doctor->setImageName('doctor3.jpg');
        $doctor->setTitle('Пациент умер или мне надо работать?');
        $doctor->setImageSize(16384);
        $doctor->setUpdatedAt(new \DateTime('now'));
        $doctor->setDescription('Руководитель отделения диагностической медицины госпиталя Принстон-Плейнсборо.
Родился 15 мая1959 года.
В 14 лет Хаусу довелось оказаться в японской больнице, где встретился с членом касты неприкасаемых (буракумин),
 который работал в больнице уборщиком. Несмотря на отвращение, которое испытывали врачи к уборщику, 
 все они прислушивались к его мнению, благодаря его выдающимся знаниям в области медицины. Именно это, 
 по словам самого Хауса, привело к тому, что он решил стать врачом.
Высокие оценки позволили Хаусу поступить в Медицинский колледж имени Джонса Хопкинса, 
однако его исключили после того, как его сокурсник Филипп Вебер поймал его на обмане. 
Несмотря на этот случай, Хауса приняли на медицинское отделение Мичиганского университета, 
где он стал легендой и привлёк внимание молодой студентки Лизы Кадди.');
        $doctor->setGender(1);
        $manager->persist($doctor);
        $manager->flush();
        $user->setDoctor($doctor);
        $this->addReference('doctor_hause', $doctor);
        $user->setEmail('doctor3@mail.ru');

        $manager->persist($user);
        $manager->flush();


        $user = new User();
        $user->setUsername('doctor4');
        $user->addRole('ROLE_DOCTOR');
        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, 'doctor4');
        $user->setPassword($password);
        $user->setEnabled(true);
        $doctor = new Doctor();
        $doctor->setFirstName('Абу́ Али́ Хусе́йн');
        $doctor->setLastName('ибн Абдулла́х ибн аль-Ха́сан');
        $doctor->setSecondName('ибн Али́ ибн Си́на');
        $doctor->setImageName('doctor4.jpg');
        $doctor->setTitle('Умерен будь в еде, поменьше пей вина');
        $doctor->setImageSize(16384);
        $doctor->setUpdatedAt(new \DateTime('now'));
        $doctor->setDescription(' Cредневековый персидскийучёный, философ и врач, представитель восточного аристотелизма. 
        Был придворным врачом саманидских эмиров и дайлемитских султанов, некоторое время был визирем в Хамадане. 
        Всего написал более 450 трудов в 29 областях науки, из которых до нас дошли только 274. 
        Самый известный и влиятельный философ-учёный средневекового исламского мира.');
        $doctor->setGender(1);
        $manager->persist($doctor);
        $manager->flush();
        $user->setDoctor($doctor);
        $this->addReference('doctor_sina', $doctor);
        $user->setEmail('doctor4@mail.ru');

        $manager->persist($user);
        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 1;
    }
}
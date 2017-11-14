<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\VarDumper\VarDumper;
use AppBundle\Entity\User;
use AppBundle\Entity\Doctor;
use AppBundle\Entity\Patient;
use AppBundle\Entity\Measuring;
use AppBundle\Entity\MeasuringType;
use AppBundle\Form\Type\MeasuringType as MeasuringForm;
use AppBundle\Form\Type\PatientType as PatientForm;
use AppBundle\Form\Type\DoctorType as DoctorForm;
/*use Doctrine\ORM\EntityManager;*/
/*
 * TODO: Разнести по разным контроллерам!!
 */

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template("AppBundle:Default:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Doctor::class);
        $specialists = $repository->getRandomDoctors();
        return ['specialists' => $specialists];
    }


    /**
     * @Route("/cabinet", name="cabinet", options = { "expose" = true })
     * @Security("has_role('ROLE_USER')")
     */
    public function cabinetAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PATIENT')) {
            $response = $this->forward('AppBundle:Default:patientCabinet');
        }elseif($this->get('security.authorization_checker')->isGranted('ROLE_DOCTOR')){
            $response = $this->forward('AppBundle:Default:doctorCabinet');
        }
        return $response;
    }

    /**
     * @Route("/cabinet/profile", name="cabinet_profile", options = { "expose" = true })
     * @Security("has_role('ROLE_USER')")
     */
    public function profileAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PATIENT')) {
            $response = $this->forward('AppBundle:Default:patientProfile');
        }elseif($this->get('security.authorization_checker')->isGranted('ROLE_DOCTOR')){
            $response = $this->forward('AppBundle:Default:doctorProfile');
        }
        return $response;
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function patientProfileAction(Request $request)
    {
        $patient = $this->getUser()->getPatient();
        $form = $this->createForm(PatientForm::class, $patient);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $patient = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($patient);
            $em->flush();
            return $this->redirectToRoute('cabinet_profile');
        }

        return $this->render('AppBundle:Cabinet:patient_profile.html.twig',
            [
                'form' => $form->createView(),
                'patient' => $patient,
            ]);

    }


    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function doctorProfileAction(Request $request)
    {
        $doctor = $this->getUser()->getDoctor();
        $form = $this->createForm(DoctorForm::class, $doctor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $doctor = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($doctor);
            $em->flush();
            return $this->redirectToRoute('cabinet_profile');
        }

        return $this->render('AppBundle:Cabinet:doctor_profile.html.twig',['doctor' => $doctor, 'form' => $form->createView(),]);

    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function patientCabinetAction(Request $request)
    {
        $patient = $this->getUser()->getPatient();
        $repository = $this->getDoctrine()->getRepository('AppBundle:Measuring');
        $measuring = $repository->getMeasuringByPatientWithStab($patient);
        if ($request->isXmlHttpRequest()) {
            $measuringModel = $this->get('health.measuring_model');
            $normalizedMeasuring = $measuringModel->normalizeMeasuringWithStab($measuring);
            $response = new JsonResponse(['measuring' => $normalizedMeasuring]);
            return $response;
        }

        $measurin1 = new Measuring();
        $measurin2 = new Measuring();
        $form1 = $this->get('form.factory')->createNamedBuilder('form1name', MeasuringForm::class,  $measurin1)
            ->getForm();

        $form2 = $this->get('form.factory')->createNamedBuilder('form2name',MeasuringForm::class,  $measurin2)
            ->getForm();
        $form1->handleRequest($request);
        $form2->handleRequest($request);
        if('POST' === $request->getMethod()) {
            $patient = $this->getUser()->getPatient();
            if ($request->request->has('form1name')) {
                if ($form1->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $measurin1 = $form1->getData();
                    $em->persist($measurin1);
                    $measurin1->setPatient($patient);
                    $em->flush();
                }
            }

            if ($request->request->has('form2name')) {
                if ($form2->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $measurin2 = $form2->getData();
                    $em->persist($measurin2);
                    $measurin2->setPatient($patient);
                    $em->flush();
                }
            }
            return $this->redirectToRoute('cabinet');
        }
        return $this->render('AppBundle:Cabinet:patient_cabinet.html.twig',
                [
                    'form1' => $form1->createView(),
                    'form2' => $form2->createView(),
                    'measuring' => $measuring]);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function doctorCabinetAction(Request $request)
    {
        $doctor = $this->getUser()->getDoctor();
        return $this->render('AppBundle:Cabinet:doctor_cabinet.html.twig', ['doctor' => $doctor]);

    }

    /**
     * @Route("/cabinet/measuring/add-value", name="cabinet_add_measuring_value", options = { "expose" = true })
     * @Security("has_role('ROLE_USER')")
     */
    public function addMeasuringValueAction(Request $request)
    {
        $measurin1 = new Measuring();
        $measurin2 = new Measuring();
        $form1 = $this->get('form.factory')->createNamedBuilder('form1name', MeasuringForm::class,  $measurin1)
            ->getForm();

        $form2 = $this->get('form.factory')->createNamedBuilder('form2name',MeasuringForm::class,  $measurin2)
            ->getForm();
        //$form = $this->createForm(MeasuringForm::class, $measurin);
        //$form->handleRequest($request);

        /*if ($form->isSubmitted() && $form->isValid()) {
            $patient = $this->getUser()->getPatient();
            $typeRepository = $this->getDoctrine()->getRepository(MeasuringType::class);
            $type = $typeRepository->findOneBy(['type' => 'weight']);
            $em = $this->getDoctrine()->getManager();
            $measurin = $form->getData();
            $em->persist($measurin);
            $measurin->setPatient($patient);
            $measurin->setType($type);
            $em->flush();
            return $this->redirectToRoute('cabinet');
        }*/
        $form1->handleRequest($request);
        $form2->handleRequest($request);
        if('POST' === $request->getMethod()) {
            $typeRepository = $this->getDoctrine()->getRepository(MeasuringType::class);
            $patient = $this->getUser()->getPatient();
            if ($request->request->has('form1name')) {
                if ($form1->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $measurin1 = $form1->getData();
                    $em->persist($measurin1);
                    $measurin1->setPatient($patient);
                    $em->flush();
                }
            }

            if ($request->request->has('form2name')) {
                if ($form2->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $measurin2 = $form2->getData();
                    $em->persist($measurin2);
                    $measurin2->setPatient($patient);
                    $em->flush();

                }
            }
            return $this->redirectToRoute('cabinet');
    }
        return $this->render('AppBundle:Cabinet:measuring_add_value.html.twig',
            ['form1' => $form1->createView(),
            'form2' => $form2->createView(),]);
    }
    /**
     * @Route("/cabinet/specialists", name="cabinet_specialists", options = { "expose" = true })
     * @Security("has_role('ROLE_USER')")
     */
    public function getPatientSpecialistsAction(Request $request)
    {
        $patient = $this->getUser()->getPatient();
        $doctors = $patient->getDoctors();
        $doctorRepository = $this->getDoctrine()->getRepository(Doctor::class);
        $allDoctors = $doctorRepository->getAllDoctors();
        $doctorModel = $this->get('health.doctor_model');
        $myDoctorIds = $doctorModel->getArrayIdDoctors($doctors);
        return $this->render('AppBundle:Cabinet:specialists.html.twig',
            [
                'doctors' => $doctors,
                'all_doctors' => $allDoctors,
                'my_doctor_ids' => $myDoctorIds,
            ]);
    }

    /**
     * @Route("/cabinet/clients", name="cabinet_clients", options = { "expose" = true })
     * @Security("has_role('ROLE_USER')")
     */
    public function getDoctorClietnsAction(Request $request)
    {
        $doctor = $this->getUser()->getDoctor();
        $patientModel = $this->get('health.patient_model');
        $patients = $patientModel->setCurrentWeight($doctor->getPatients());
        //TODO: Подумать над этим!
        /*$measuringArray = [];
        $measuringModel = $this->get('health.measuring_model');
        $repository = $this->getDoctrine()->getRepository('AppBundle:Measuring');
        foreach ($patients as $patient){
            $measuringArray[$patient->getId()] = $measuringModel->normalizeMeasuring($repository->getMeasuringByPatient($patient));
        }*/

        return $this->render('AppBundle:Cabinet:clients.html.twig',
            [
                'patients' => $patients,
                //'measuringArray' => $measuringArray

            ]);
    }

    /**
     * @Route("/cabinet/specialists/remove/{doctor}", name="cabinet_specialists_remove_one", options = { "expose" = true })
     * @Security("has_role('ROLE_PATIENT')")
     */
    public function removePatientSpecialistsAction(Doctor $doctor)
    {
        $em = $this->getDoctrine()->getManager();
        $patient = $this->getUser()->getPatient();
        $patient->removeDoctor($doctor);
        $em->persist($patient);
        $em->flush();
        return $this->redirectToRoute('cabinet_specialists');
    }
    /**
     * @Route("/cabinet/specialists/add/{doctor}", name="cabinet_specialists_add_one", options = { "expose" = true })
     * @Security("has_role('ROLE_PATIENT')")
     */
    public function addPatientSpecialistsAction(Doctor $doctor)
    {
        $em = $this->getDoctrine()->getManager();
        $patient = $this->getUser()->getPatient();
        $patient->addDoctor($doctor);
        $em->persist($patient);
        $em->flush();
        return $this->redirectToRoute('cabinet_specialists');
    }

    /**
     * @Route("/cabinet/measuring/get-stability-range", name="get_stability_range", options = { "expose" = true })
     * @Security("has_role('ROLE_PATIENT')")
     */
    public function getStabilityRangeAction(Request $request)
    {
        $measuringId = $request->request->get('data');
        if ($request->isXmlHttpRequest() && $measuringId) {

            $measuringModel = $this->get('health.measuring_model');
            $stabilityRange = $measuringModel->getStabilityRange($measuringId);
            $response = new JsonResponse(['stabilityRange' => $stabilityRange]);
            return $response;
        }else{
            return $this->redirectToRoute('cabinet');
        }
    }

    /**
     * @Route("/cabinet/specialists/get-patient-stat/{patient}", name="cabinet_specialists_get_patient_stat",
     *      options = { "expose" = true })
     * @Security("has_role('ROLE_USER')")
     */
    public function getPatientStatAction(Request $request, Patient $patient)
    {
        if ($request->isXmlHttpRequest()) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Measuring');
            $measuring = $repository->getMeasuringByPatientWithStab($patient);
            return $this->render('AppBundle:Cabinet:doctor_patients_stat_modal.html.twig',
                [
                    'measuring' => $measuring,
                    'patient'=> $patient,
                ]);
        }
    }

    /**
     * @Route("/cabinet/messages", name="cabinet_messages",
     *      options = { "expose" = true })
     * @Security("has_role('ROLE_USER')")
     */
    public function messagesAction()
    {
        $provider = $this->get('fos_message.provider');
        $threadsIn = $provider->getInboxThreads();
        $threadsOut = $provider->getSentThreads();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PATIENT')){
            $patient = $this->getUser()->getPatient();
            $recipients = $patient->getDoctors();
        }elseif($this->get('security.authorization_checker')->isGranted('ROLE_DOCTOR')){
            $doctor = $this->getUser()->getDoctor();
            $recipients = $doctor->getPatients();
        }
        return $this->render('AppBundle:Cabinet:messages.html.twig',
            [
                'threadsIn' => $threadsIn,
                'threadsOut'=> $threadsOut,
                'recipients' => $recipients,
            ]);

    }


    /**
     * @Route("/cabinet/message/sent", name="cabinet_sent_message",
     *      options = { "expose" = true })
     * @Security("has_role('ROLE_USER')")
     */
    public function sentMessageAction(Request $request)
    {
        if('POST' === $request->getMethod()) {
            $composer = $this->get('fos_message.composer');
            $sender = $this->get('fos_message.sender');
            $recipientId = $request->request->get('messages_recipient');;
            if ($this->get('security.authorization_checker')->isGranted('ROLE_PATIENT')){
                $doctorRepository = $this->getDoctrine()->getRepository(Doctor::class);
                $doctor = $doctorRepository->find($recipientId);
                $recipient = $doctor->getUser();
            }elseif($this->get('security.authorization_checker')->isGranted('ROLE_DOCTOR')){
                $patientRepository = $this->getDoctrine()->getRepository(Patient::class);
                $patient = $patientRepository->find($recipientId);
                $recipient = $patient->getUser();
            }
            $subject = $request->request->get('messages_subjec');
            $body = $request->request->get('messages_body');
            $message = $composer->newThread()
                ->setSender($this->getUser())
                ->addRecipient($recipient)
                ->setSubject($subject)
                ->setBody($body)
                ->getMessage();
            $sender->send($message);
            return $this->redirectToRoute('cabinet_messages');
        }
    }

    /**
     * @Route("/cabinet/message/unread", name="cabinet_get_unread_message",
     *      options = { "expose" = true })
     * @Security("has_role('ROLE_USER')")
     */
    public function getUnreadMessagesAction()
    {
        $provider = $this->get('fos_message.provider');
        $unreadMessages = $provider->getNbUnreadMessages();
        return $this->render('AppBundle:Cabinet:unread_messages.html.twig',
            [
                'unreadMessages' => $unreadMessages,
            ]);
    }

    /**
     * @Route("/cabinet/message/thread/{id}", name="cabinet_get_thread",
     *      options = { "expose" = true })
     * @Security("has_role('ROLE_USER')")
     */
    public function getThreadAction($id)
    {
        $provider = $this->get('fos_message.provider');
        $thread = $provider->getThread($id);
        $userCreated = $thread->getCreatedBy();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PATIENT')){
            $createdBy = $userCreated->getPatient();
        }elseif($this->get('security.authorization_checker')->isGranted('ROLE_DOCTOR')){
            $createdBy = $userCreated->getDoctor();
        }

        return $this->render('AppBundle:Cabinet:thread.html.twig',
            [
                'thread' => $thread,
                'createdBy' => $createdBy,
            ]);
    }


    /**
     * @Route("/cabinet/message/reply/thread/{id}", name="cabinet_message_reply",
     *      options = { "expose" = true })
     * @Security("has_role('ROLE_USER')")
     */
    public function messageReplyAction(Request $request, $id)
    {
        if('POST' === $request->getMethod()) {
            $provider = $this->get('fos_message.provider');
            $thread = $provider->getThread($id);
            $composer = $this->get('fos_message.composer');
            $sender = $this->get('fos_message.sender');

            $body = $request->request->get('messages_body');
            $message = $composer->reply($thread)
                ->setSender($this->getUser())
                ->setBody($body)
                ->getMessage();
            $sender->send($message);
            return $this->redirectToRoute('cabinet_get_thread', ['id' => $id]);
        }
    }
}

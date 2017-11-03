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
        $measuring = $patient->getMeasuring();
        if ($request->isXmlHttpRequest()) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Measuring');
            $measuringModel = $this->get('health.measuring_model');
            $normalizedMeasuring = $measuringModel->normalizeMeasuring($repository->getMeasuringByPatient($patient));

            /*$connection = $this->em->getConnection();
            $statement = $connection->prepare("select * from public.get_patient_values(5,1)");
            $normalizedMeasuring2 = $statement->execute()->fetch();*/

            $normalizedMeasuring2 = [
                [                    1507035520000,                        69.5               ],
                [                    1507036060000,                        69.1               ],
                [                    1507036060000,                        72               ],
                [                    1507036120000,                        74.5               ],
                [                    1507036120000,                        77               ],
                [                    1507036120000,                        78               ],
                [                    1507036180000,                        81               ],
                [                    1507036180000,                        80.5               ],
                [                    1507036180000,                        80.9               ],
                [                    1507045600000,                        80.2               ],
                [                    1507045600000,                        80.1               ],
                [                    1507045600000,                        79               ],
                [                    1507045600000,                        78               ],
                [                    1507045600000,                        79.2               ],
                [                    1507045600000,                        79.5               ],
                [                    1507045600000,                        81               ],
                [                    1507045600000,                        81.3               ]
            ];
            $response = new JsonResponse(['measuring' => $normalizedMeasuring, 'measuring2' => $normalizedMeasuring2]);
            return $response;
        }
        return $this->render('AppBundle:Cabinet:patient_cabinet.html.twig',['measuring' => $measuring]);

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
        $measurin = new Measuring();
        $form = $this->createForm(MeasuringForm::class, $measurin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
        }
        return $this->render('AppBundle:Cabinet:measuring_add_value.html.twig',
            ['form' => $form->createView(),]);
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
}

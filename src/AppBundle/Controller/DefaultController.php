<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\VarDumper\VarDumper;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template("AppBundle:Default:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        return [];
    }


    /**
     * @Route("/cabinet", name="cabinet", options = { "expose" = true })
     * @Security("has_role('ROLE_USER')")
     */
    public function cabinetAction(Request $request)
    {
        //TODO: Развести на доктора и пациента
        //
        $patient = $this->getUser()->getPatient();
        $measuring = $patient->getMeasuring();
        if ($request->isXmlHttpRequest()) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Measuring');
            $measuringModel = $this->get('health.measuring_model');
            $normalizedMeasuring = $measuringModel->normalizeMeasuringDate($repository->getMeasuringByPatient($patient));
            $response = new JsonResponse(['measuring' => $normalizedMeasuring]);
            return $response;
        }
        return $this->render('AppBundle:Cabinet:index.html.twig',['measuring' => $measuring]);

    }
}

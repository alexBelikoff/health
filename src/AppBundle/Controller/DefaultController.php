<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @Route("/cabinet", name="cabinet")
     */
    public function cabinetAction(Request $request)
    {
        //TODO: Развести на доктора и пациента
        $patient = $this->getUser()->getPatient();
        $measuring = $patient->getMeasuring();
        if ($request->isXmlHttpRequest()) {
            $serializer = $this->get('jms_serializer');
            $data = $serializer->serialize($measuring, 'json');
            $response = new JsonResponse(['measuring' => $data]);
            return $response;
        }
        return $this->render('AppBundle:Cabinet:index.html.twig',['measuring' => $measuring]);

    }
}

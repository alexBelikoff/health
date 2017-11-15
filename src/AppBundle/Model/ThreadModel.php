<?php
/**
 * Created by PhpStorm.
 * User: Beluha
 * Date: 15.11.2017
 * Time: 17:31
 */

namespace AppBundle\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\PersistentCollection;

class ThreadModel
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function normalizeThreadMessages($thread)
    {
        $repositoryPatient = $this->em->getRepository('AppBundle:Patient');
        $repositoryDoctor = $this->em->getRepository('AppBundle:Doctor');
        $messages = $thread->getMessages();
        foreach ($messages as $message){

            $patient = $message->getSender()->getPatient();
            $doctor = $message->getSender()->getDoctor();
            if(!empty($patient)){
                $notProxyPatient = $repositoryPatient->find($patient->getId());
                $notProxyPatient->getFirstName();
                $message->getSender()->setPatient($notProxyPatient);
            }
            if(!empty($doctor)){
                $notProxyDoctor = $repositoryDoctor->find($doctor->getId());
                $notProxyDoctor->getFirstName();
                $message->getSender()->setDoctor($notProxyDoctor);
            }
        }
        return $thread;
    }
}
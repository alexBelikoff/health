<?php
/**
 * Created by PhpStorm.
 * User: Beluha
 * Date: 24.07.2017
 * Time: 22:06
 */

namespace AppBundle\Handler;


use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\VarDumper\VarDumper;


class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{

    protected $router;
    protected $security;
    protected $userManager;
    protected $service_container;

    public function __construct(RouterInterface $router, TokenStorage $security, $userManager, $service_container)
    {
        $this->router = $router;
        $this->security = $security;
        $this->userManager = $userManager;
        $this->service_container = $service_container;

    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {
        $dumper = new VarDumper();
        if ($request->isXmlHttpRequest()) {
            $dumper->dump('isXmlHttpRequest');
            $targetUrl = $request->getSession()->get('_security.target_path');
            $result = array('success' => true, 'targetUrl' => targetUrl );
            $response = new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        else {
            $dumper->dump('is NOT! XmlHttpRequest');
            // Create a flash message with the authentication error message
            //$request->getSession()->getFlashBag()->set('error', $exception->getMessage());
            $url = $this->router->generate('homepage');

            return new RedirectResponse($url);
        }

        return new RedirectResponse($this->router->generate('homepage'));
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {

        if ($request->isXmlHttpRequest()) {
            $result = array('success' => false, 'message' => $exception->getMessage());
            $response = new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        return new Response();
    }
}
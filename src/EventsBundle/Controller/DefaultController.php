<?php

namespace EventsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        /** @var $session Session */
        $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;
        $routeName = $request->get('_route');
        $data=array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename'=>$routeName

        );
        return $this->render('EventsBundle:Default:index.html.twig',$data);
    }
//    public function setSessionAction($id ,Request $request){
//        $session = $request->getSession();
//        $sessionArray=$session->get("produits");
//        if($sessionArray==null){
//            $sessionArray = array();
//        }
//        array_push($sessionArray,$id);
//        $session->set("poduits",$sessionArray);
//        return new JsonResponse(array('data' =>$array=$session->get("poduits")));
//    }

//    public function getallSessionAction(Request $request){
//        $session = $request->getSession();
//        $array=$session->get("poduits");
//        $response = new JsonResponse(array('data' => $array));
//        return $response;
//    }
}

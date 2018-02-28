<?php
/**
 * Created by PhpStorm.
 * User: AHMED
 * Date: 03/02/2018
 * Time: 17:00
 */

namespace EventsBundle\Controller;


use Doctrine\DBAL\Types\TextType;
use EventsBundle\Entity\Evenement;
use EventsBundle\Entity\Participer;
use EventsBundle\Form\EvenementType;
use GuzzleHttp\Psr7\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Workflow\Event\Event;
use \Statickidz\GoogleTranslate;
use Symfony\Component\HttpFoundation\Response;

use UserBundle\Entity\User;


class EventsController extends Controller
{
    public function EventAction(Request $request)
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
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName
        );
        return $this->render('EventsBundle::Events.html.twig', $data);
    }


    public function AjoutEAction(Request $request)
    {


        $produit = new Evenement();

        $user = $this->getUser();
        $n = $user->getUsername();
        $idu = $user->getId();


        $produit->setDatedeb(new  \DateTime('now'));

        $form = $this->createFormBuilder($produit)
            ->add('nom', \Symfony\Component\Form\Extension\Core\Type\TextType::class)
            ->add('description', TextareaType::class)
            ->add('type', ChoiceType::class,
                array('choices' => array(
                    'Gratuit' => 'Gratuit',
                    'Payant' => 'Payant',
                ),
                    'choices_as_values' => true, 'multiple' => false, 'expanded' => true))
            ->add('prix', IntegerType::class)
            ->add('adresse', \Symfony\Component\Form\Extension\Core\Type\TextType::class)
            ->add('image', FileType::class, array('data_class' => null))
            ->add('valider', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var UploadedFile $file
             */

            $produit->setDatedeb($request->get('datedeb'));
            $produit->setDatefin($request->get('datefin'));


            $produit->setNomorg($n);

            $produit->setIdu($idu);
            $file = $form['image']->getData();

            $produit = $form->getData();
            $file->move('uploads/images/', $file->getClientOriginalName());
            $produit->setImage("uploads/images/" . $file->getClientOriginalName());
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('ajoutE');


        }


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
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'form' => $form->createView(),


        );
        return $this->render('@Events/ajoutE.html.twig', $data);

    }


    public function listEAction(Request $request)
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();


        $produit = $this->getDoctrine()
            ->getRepository(Evenement::class)
            ->findbest();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $produit,
            $request->query->get('page', 1), 3
        );

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
        $message = 1;
        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;
        $routeName = $request->get('_route');
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'events' => $pagination,
            'allev' => $produit,
            'alert' => $message,
            'users'=>$users
        );
        return $this->render('@Events/listE.html.twig', $data);

    }


    public function DetailEAction(Request $request, $id)
    {


        $em = $this->getDoctrine()->getManager();

        $detaild = $em->getRepository("EventsBundle:Evenement")->find($id);




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
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'detailE' => $detaild,


        );
        return $this->render('@Events/detailE.html.twig', $data);

    }


    public function maeventAction(Request $request)
    {
        $user = $this->getUser();
        $idb = $user->getId();
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
        $maprod = $this->getDoctrine()->getManager()->getRepository(Evenement::class)
            ->findByIdu($idb);


        /*
                $paginator  = $this->get('knp_paginator');
                $pagination = $paginator->paginate(
                    $maprod,
                    $request->query->get('page', 1),
                    3
                );
        */

        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'maevent' => $maprod

        );


        return $this->render('@Events/maevents.html.twig', $data);
    }


    public function mapartAction(Request $request)
    {
        $user = $this->getUser();
        $idb = $user->getId();
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
        $maparti = $this->getDoctrine()->getManager()->getRepository(Participer::class)
            ->findByIdu($idb);


        /*
                $paginator  = $this->get('knp_paginator');
                $pagination = $paginator->paginate(
                    $maprod,
                    $request->query->get('page', 1),
                    3
                );
        */


        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'maparti' => $maparti

        );


        return $this->render('@Events/maparti.html.twig', $data);
    }


    public function suppEAction($id)
    {


        $em = $this->getDoctrine()->getManager();
        $voiture = $em->getRepository("EventsBundle:Evenement")->find($id);
        $em->remove($voiture);

        $em->flush();
        return $this->redirectToRoute("maevent");

    }


    public function participateAction($id)
    {


        $user = $this->getUser();
        $idb = $user->getId();
        $name = $user->getNom();
        $Test = 0;
        $em = $this->getDoctrine()->getManager();
        $voiture = $em->getRepository("EventsBundle:Evenement")->find($id);
        $voiture->setParticiate($voiture->getParticiate() + 1);
        $maparti = $this->getDoctrine()->getManager()->getRepository(Participer::class)
            ->findByIdu($idb);


        $eventsarray = array();

        foreach ($maparti as $particip) {

            $ide = $particip->getIde();
            if ($ide == $id) {
                $Test = 1;
            }
        }
        $message = 0;
        if ($Test == 0) {

            $message = 1;

            $nb = 0;
            $participer = new Participer();
            $participer->setNom($voiture->getNom());
            $participer->setDescription($voiture->getDescription());
            $participer->setType($voiture->getType());
            $participer->setPrix($voiture->getPrix());
            $participer->setIdu($idb);
            $participer->setParticiate($voiture->getParticiate());

            $participer->setDatedeb($voiture->getDatedeb());
            $participer->setDatefin($voiture->getDatefin());

            $participer->setNomorg($name);
            $participer->setAdresse($voiture->getAdresse());
            $participer->setImage($voiture->getImage());
            $participer->setide($id);

            $em->persist($participer);
            $em->flush();


            $data = array(
                'alert' => $message,

            );


        } else {
            $message = 2;
            $data = array(
                'alert' => $message);


        }
        $array=array();
        $response=new JsonResponse(array('data'=>$data));
        return $response;
    }




    public function updateEAction(Request $request, $id)
    {


        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('EventsBundle:Evenement')->find($id);
        $azert = $produit->getImage();
        $form = $this->createForm(EvenementType::class, $produit);
        if ($form->handleRequest($request)->isValid()) {
            $file = $form['image']->getData();


            $produit->setDatedeb($request->get('datedeb'));
            $produit->setDatefin($request->get('datefin'));


            $file->move("images/", $file->getClientOriginalName());
            $produit->setImage("images/" . $file->getClientOriginalName());
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('maevent');
        }
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
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'form' => $form->createView(),
            'aze' => $azert,
            'modele' => $produit

        );
        return $this->render('@Events/updateE.html.twig', $data);


    }


    public function RechercheAction(Request $request)
    {
        $search = $request->get('recherche');
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository("EventsBundle:Evenement")->findNom($search);
        return $this->render("@Events/test.html.twig", array(
            'nom' => $events
        ));
    }






    public function rechercheAjaxAction(Request $request)
    {


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
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,


        );






        $em=$this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()) {
            $search  = $request->get('search');
            dump($search);
            $event = new Evenement();
            $repo  = $em->getRepository('EventsBundle:Evenement');
            $event = $repo->findAjax($search);
            return $this->render('@Events/component.html.twig', array('events' => $event,$data));
        }


    }







    public
    function dqlEAction()
    {

        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('EventsBundle:Evenement')->finddql();
        return $this->render('@Events/dql.html.twig', array('events' => $events));


    }

    public function translateajaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();
            $id=$request->get('id');
            $rows = $em->getRepository("EventsBundle:Evenement")->findBy(array('id'=> $id));
            $tabEnsembles = array();
            $i = 0;
            foreach($rows as $ensemble) {
                $source = 'english';
                $target = 'fr';
                $text = $ensemble->getDescription();
                $trans = new GoogleTranslate();
                $result = $trans->translate($source, $target, $text);
                $tabEnsembles[$i]['new'] = $result;
                $tabEnsembles[$i]['old'] = $ensemble->getDescription();
                $i++;
            }
            $data = json_encode($tabEnsembles);
            $response = new Response();
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent($data);
            return $response;
        }
        return new Response("Erreur: Ce n'est pas une requete ajax",400);
    }
}
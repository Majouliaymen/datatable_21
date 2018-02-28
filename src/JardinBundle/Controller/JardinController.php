<?php
/**
 * Created by PhpStorm.
 * User: SBS
 * Date: 13/02/2018
 * Time: 15:59
 */

namespace JardinBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use JardinBundle\Entity\Messages;
use ShopBundle\Form\JardinType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use JardinBundle\Entity\Jarenfant;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class JardinController extends  Controller
{
    public function JardinAction(Request $request)
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
        //recuperation des jardins
        $jardin = $this->getDoctrine()->getManager()->getRepository(Jarenfant::class)->findAll();
/********************************/
        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['Task', 'Etats des jardins'],
                ['JardinsConfirme',     12],
                ['JardinAnnuler',      3],
                ['Jardinenattente',  7],

            ]
        );
        $pieChart->getOptions()->setTitle('My Daily Activities');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'jardins' => $jardin,
            'piechart'=>$pieChart

        );
        return $this->render('@Jardin/accjardin.html.twig', $data);

    }

    public function MesJardinAction(Request $request)
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
        $id = $this->getUser()->getId();

        $jardin = $this->getDoctrine()->getManager()->getRepository(Jarenfant::class)->findBy(array('proprietaire' => $id));

        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'jardins' => $jardin

        );
        return $this->render('@Jardin/mesjardin.html.twig', $data);

    }

    public function updateJAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $jardin = $em->getRepository('JardinBundle:Jarenfant')->find($id);
        $form = $this->createForm(\JardinBundle\Form\JardinType::class, $jardin);

        if ($form->handleRequest($request)->isValid()) {
            $file = $form['logo']->getData();

            $file->move("images/", $file->getClientOriginalName());
            $jardin->setLogo("images/" . $file->getClientOriginalName());
            $em->persist($jardin);
            $em->flush();
            return $this->redirectToRoute('mesjardin');
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


        $jardin = $em->getRepository('JardinBundle:Jarenfant')->findBy((array('id' => $id)));

        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'form' => $form->createView(),
            'jardins' => $jardin,

        );

        return $this->render('@Jardin/updateJ.html.twig', $data);


    }

    public function AjouterJAction(Request $request)
    {
        $jardin = new Jarenfant();
        $user = $this->getUser();
        $form = $this->createFormBuilder($jardin)
            ->add('nom', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Nom',
                    'class' => 'form-control'
                )))
            ->add('description', TextareaType::class, array(
                'attr' => array(
                    'placeholder' => 'Description',
                    'class' => 'form-control'
                )))
            ->add('adresse', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Adresse',
                    'class' => 'form-control'
                )))
            ->add('adressemail', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Email',
                    'class' => 'form-control'
                )))
            ->add('numtel', NumberType::class, array(
                'attr' => array(
                    'placeholder' => 'Numeru de Telephone',
                    'class' => 'form-control'
                )))
            ->add('logo', FileType::class, array('data_class' => null))
            ->add('save', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $file = $form['logo']->getData();
            $file->move("images/", $file->getClientOriginalName());
            $jardin->setLogo("images/" . $file->getClientOriginalName());
            $id = $this->getUser()->getId();

            $em = $this->getDoctrine()->getManager();
            $jardin->setProprietaire($id);
            $em->persist($jardin);
            $em->flush();


            return $this->redirectToRoute('accjardin');
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
            'form' => $form->createView()

        );
        return $this->render('@Jardin/AjouterJ.html.twig', $data);

    }

    public function deleteJAction(Request $request, $id)
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

        $em = $this->getDoctrine()->getManager();
        $jr = $em->getRepository("JardinBundle:Jarenfant")->find($id);
        $em->remove($jr);
        $em->flush();

        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,

        );
        return $this->redirectToRoute('mesjardin');


    }

    public function SendmsgAction(Request $request,$id)
    {
        $msg = new Messages();
        $msg->setIdrecepteur(1);
        $user = $this->getUser();
        $a = $user->getNom();
        $form = $this->createFormBuilder($msg)
            ->add('objet', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'objet',
                    'class' => 'form-control'
                )))
            ->add('contenuMessage', TextareaType::class, array(
                'attr' => array(
                    'placeholder' => 'Contenu de votre MEssage',
                    'class' => 'form-control'
                )))

            ->add('save', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $id_em = $this->getUser()->getId();
            $id_rec = $id;

            $em = $this->getDoctrine()->getManager();
            $msg->setIdEmetteur($id_em);
            $msg->setIdrecepteur($id_rec);
            $em->persist($msg);
            $em->flush();


            return $this->redirectToRoute('accjardin');

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
            'form' => $form->createView()

        );
        return $this->render('@Jardin/envoiemsg.html.twig', $data);


    }
    public function MesmsgAction(Request $request)
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
        $id = $this->getUser()->getId();

        $messages = $this->getDoctrine()->getManager()->getRepository(Messages::class)->findBy(array('idrecepteur' => $id));

        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'messages' => $messages

        );
        return $this->render('@Jardin/Mymessages.html.twig', $data);

    }

}
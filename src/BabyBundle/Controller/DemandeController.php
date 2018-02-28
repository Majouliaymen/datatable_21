<?php

namespace BabyBundle\Controller;

use BabyBundle\Entity\Demande;
use BabyBundle\Entity\Baby;


//use Doctrine\DBAL\Types\TextType;
use BabyBundle\Form\BabyType;
use FOS\UserBundle\FOSUserBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


//use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
//use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use UserBundle\Entity\User;


class DemandeController extends Controller
{

    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        if ($request->isMethod("post")) {
            $nom = $request->get("nom");
            $prenom = $request->get("prenom");
            $username = $request->get("username");
            $age = $request->get("age");
            $image = $request->get("image");

            $user->setNom($nom);
            $user->setAge($age);
            $user->setImage($image);
            $user->setPrenom($prenom);
            $user->setUsername($username);
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
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
        $user = $this->getUser();

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;
        $routeName = $request->get('_route');

        // $baby = $this->getDoctrine()->getManager()->getRepository(baby::class)->findAll();
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            //'babies' => $baby,
            'user' => $user,


        );


        return $this->render('@Baby/Demande/index.html.twig', $data);
    }


    public function layoutAction(Request $request)
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
        return $this->render('layout.html.twig', $data);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */


    public function AfficheAction(Request $request)
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
        $baby = $this->getDoctrine()->getManager()->getRepository(Demande::class)
            ->findAll();

        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'babys' => $baby

        );


        return $this->render('BabyBundle:Demande:affiche.html.twig', $data);
    }

    public function formulaireAction(Request $request)
    {
        $user = $this->getUser();
        {


            $b = $user->getImage();
            $user = $this->getUser();
            $nom = $user->getNom();
            $username = $user->getUsername();
            $prenom = $user->getprenom();
            $adrese = $user->getAdrese();
            $description = $user->getDescription();

//            $disponibilite = $user->getDisponibilite();
            $form = $this->createFormBuilder($user)
                ->add('nom', TextType::class, array(
                    'attr' => array(
                        'value' => $nom, 'class' => 'form-control border-color-6'

                    )))
                ->add('username', TextType::class, array(
                    'attr' => array(
                        'value' => $username, 'class' => 'form-control border-color-1'

                    )))
                ->add('prenom', TextType::class, array(
                    'attr' => array(
                        'value' => $prenom, 'class' => 'form-control border-color-2'
                    )))

                ->add('adrese', TextType::class, array(
                    'attr' => array(
                        'value' => $adrese, 'class' => 'form-control border-color-4'
                    )))
                ->add('description', TextType::class, array(
                    'attr' => array(
                        'value' => $description, 'class' => 'form-control border-color-5'
                    )))
//                ->add('disponibilite', DateType::class)
                ->add('image', FileType::class, array('data_class' => null))
                ->add('save', SubmitType::class, array(
                    'attr' => array(
                        'class' => 'btn btn-primary'
                    )))
                ->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {


                $file = $form['image']->getData();
                $file->move("images/", $file->getClientOriginalName());
                $nom = $form['nom']->getData();
                $prenom = $form['prenom']->getData();


                $user->setImage("images/" . $file->getClientOriginalName());
                $user->setNom($nom);
                $user->setPrenom($prenom);

                $this->getDoctrine()->getManager()->persist($user);
                $this->getDoctrine()->getManager()->flush();


                return $this->redirectToRoute('babyDemande');
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
                'image' => $b,
                "nom"=>$nom,
                "prenom"=>$prenom,
                "adresse"=>$adrese,
                "description"=>$description,

            );
            return $this->render('@Baby/Demande/formulaire.html.twig', $data);


        }
    }

    public function deletAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $model = $em->getRepository(Demande::class)->find($id);
        $em->remove($model);
        $em->flush();

        return $this->redirectToRoute('affiche2Demande');


    }

    public function AjoutAction(Request $request)
    {
        $baby = new Demande();
        $user = $this->getUser();
        $idb = $user->getId();
        $nomb = $user->getNom();
        $prenomb = $user->getPrenom();
        $email = $user->getEmail();
        $form = $this->createFormBuilder($baby)
            ->add('nom', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Nom',
                    'class' => 'form-control border-color-4',
                    'value' => $nomb
                )))
            ->add('prenom', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Prenom',
                    'class' => 'form-control border-color-6',
                    'value' => $prenomb
                )))
            ->add('adrese', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Adresse',
                    'class' => 'form-control border-color-1'
                )))

            ->add('email', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Nom.Prenom@mail.com',
                    'value' => $email,
                    'class' => 'form-control border-color-3'
                )))
            ->add('prix', IntegerType::class, array(
                'attr' => array(
                    'placeholder' => 'Prix en DT',
                    'class' => 'form-control border-color-5'
                )))
            ->add('description', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Description',
                    'class' => 'form-control border-color-4'
                )))
            ->add('image', FileType::class, array('data_class' => null))
            ->add('save', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-primary'
                )))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $baby->setDisponibilite($request->get('disponibilite'));
            $baby->setDatefin($request->get('datefin'));
            $file = $form['image']->getData();

            $file->move("images/", $file->getClientOriginalName());
            $baby->setImage("images/" . $file->getClientOriginalName());
            $baby->setIdb($idb);
            $em = $this->getDoctrine()->getManager();
            $em->persist($baby);
            $em->flush();


            return $this->redirectToRoute('ajoutDemande');
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

        );
        return $this->render('@Baby/Demande/Ajout.html.twig', $data);


    }

    public function AfficherAction(Request $request)
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
        $baby = $this->getDoctrine()->getManager()->getRepository(Demande::class)
            ->findByIdb($idb);

        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'babys' => $baby,
            'idb' =>$idb

        );


        return $this->render('BabyBundle:Demande:affichemesoffres.html.twig', $data);
    }

    public function modifierAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $produit=$em->getRepository('BabyBundle:Baby')->find($id);
        $form=$this->createForm(DemandeType::class,$produit);
        if($form->handleRequest($request)->isValid()) {
            $file = $form['image']->getData();
            $file->move("images/", $file->getClientOriginalName());
            $produit->setImage("images/".$file->getClientOriginalName());
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('affiche2Demande');
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
        $data=array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename'=>$routeName,
            'form'=>$form->createView()
        );
        return $this->render('BabyBundle:Demande:update.html.twig',$data);
    }


}





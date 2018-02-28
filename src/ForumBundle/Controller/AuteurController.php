<?php
/**
 * Created by PhpStorm.
 * User: ikbel
 * Date: 15/02/2018
 * Time: 17:03
 */

namespace ForumBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use ForumBundle\Entity\Publication;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query\QueryException;
use UserBundle\Entity\User;
use ForumBundle\Entity\Publicationresolu;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class AuteurController extends Controller
{


    public function MespublicationsAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();

        $user=$this->get('security.token_storage')->getToken()->getUser();

        $query = $this->getDoctrine()->getManager()
            ->createQuery('SELECT u FROM ForumBundle:Publication u WHERE u.iduser = :role'
            )->setParameter('role', $user );
        $publications = $query->getResult();

        $publication=$em->getRepository('ForumBundle:Publication')->findAll();
        $categorie=$em->getRepository('ForumBundle:Categorie')->findAll();

        //appel form creation publication
        $publication_create = new Publication();
        $form_creation = $this->createForm('ForumBundle\Form\PublicationCreation', $publication_create);
        $form_creation->handleRequest($request);

        //apres submit form
        if ($form_creation->isSubmitted() && $form_creation->isValid() && $request->isMethod('POST')) {

            $idcategorie=$request->request->get('categorie');
            $categoriee=$em->getRepository('ForumBundle:Categorie')->find($idcategorie);

            $publication_create->setIdcategorie($categoriee);
            $publication_create->setIddcategorie($categoriee->getId());
            $date=date_create();
            $publication_create->setCreated(date_timestamp_get($date));
            $publication_create->setIdUser($this->get('security.token_storage')->getToken()->getUser());
            $publication_create->setNbrReponse(0);
            $publication_create->setNbrVue(0);
            $publication_create->setLocation('en attente');
            $em->persist($publication_create);
            $em->flush();
            return $this->redirectToRoute('mes_publication');
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
            'publication'=>$publication,'publications'=>$publications,'form_creation' => $form_creation->createView(),'categorie'=>$categorie);

        return $this->render('@Forum/User/Mes_publications.html.twig',$data);
    }


    public function MespublicationupdatesAction(Request $request,Publication $publication)
    {
        //form update publication
        $form_update = $this->createForm('ForumBundle\Form\PublicationModification', $publication);
        $form_update->handleRequest($request);

        if ($form_update->isSubmitted() && $form_update->isValid() && $request->isMethod('POST')) {
            $date=date_create();
            $publication->setCreated(date_timestamp_get($date));
            $publication->setLocation('en attente');
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('mes_publication');
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
            'publication'=>$publication,'form_update' => $form_update->createView());
        return $this->render('@Forum/User/update_publication.html.twig',$data);


    }


    public function SupprimerpublicationAction($id_publication)
    {
        $em=$this->getDoctrine()->getManager();
        $publication=$em->getRepository('ForumBundle:Publication')->find($id_publication);
        $em->remove($publication);
        $em->flush();
        return $this->redirect($this->generateUrl('mes_publication'));

    }


    public function publicationresoluAction(Request $request ,$id_publication,$id_reponse)
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $publication = $em->getRepository('ForumBundle:Publication')->find($id_publication);
            $reponse = $em->getRepository('ForumBundle:Reponse')->find($id_reponse);
            $publication->setResolu(1);

            $publicationresolu = new Publicationresolu();
            $publicationresolu->setIdPublication($publication);
            $publicationresolu->setIdPub($publication->getId());
            $publicationresolu->setIdReponse($reponse);

            $em->persist($publication);
            $em->persist($publicationresolu);
            $em->flush();
        }


        return $this->redirect($this->generateUrl('consulter_publicationforum',['id_publication'=>$publication->getId()]));

    }


    public function supprimerpublicationresoluAction(Request $request ,$id_publication_resolu,$id_pub)
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $publicationcourant = $em->getRepository('ForumBundle:Publication')->find($id_pub);
            $publicationcourant->setResolu(0);
            $em->persist($publicationcourant);

            $publicationresolusupp = $em->getRepository('ForumBundle:Publicationresolu')->find($id_publication_resolu);

            $em->remove($publicationresolusupp);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('consulter_publicationforum',['id_publication'=>$publicationcourant->getId()]));

    }
    public function statforumAction(Request $request)
    {
        $query = $this->getDoctrine()->getManager()
            ->createQuery('SELECT u FROM ForumBundle:Publication u WHERE u.iduser = :user '
            )->setParameter('user', $this->get('security.token_storage')->getToken()->getUser());
        $publications = $query->getResult();

        $query = $this->getDoctrine()->getManager()
            ->createQuery("SELECT u FROM ForumBundle:Publication u WHERE u.iduser = :user and u.location = 'approuver' "
            )->setParameter('user', $this->get('security.token_storage')->getToken()->getUser());
        $publicationapprouver = $query->getResult();

        $query = $this->getDoctrine()->getManager()
            ->createQuery("SELECT u FROM ForumBundle:Publication u WHERE u.iduser = :user and u.location = 'deapprouver' "
            )->setParameter('user', $this->get('security.token_storage')->getToken()->getUser());
        $publicationdeapprouver = $query->getResult();

        $query = $this->getDoctrine()->getManager()
            ->createQuery("SELECT u FROM ForumBundle:Publication u WHERE u.iduser = :user and u.location = 'en attente' "
            )->setParameter('user', $this->get('security.token_storage')->getToken()->getUser());
        $publicationenattente = $query->getResult();

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
            'publication' => $publications,
            'publicationapprouver' => $publicationapprouver,
            'publicationdeapprouver' => $publicationdeapprouver,
            'publicationenattente' => $publicationenattente);
        return $this->render('@Forum/MyForum/stat.html.twig',$data);
    }
}
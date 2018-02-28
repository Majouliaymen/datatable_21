<?php
/**
 * Created by PhpStorm.
 * User: ikbel
 * Date: 15/02/2018
 * Time: 17:22
 */

namespace ForumBundle\Controller;


use ForumBundle\Entity\Publication;
use ForumBundle\Repository\vuepublicationcategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use ForumBundle\Entity\Avis;
use ForumBundle\Entity\Reponse;
use ForumBundle\Entity\Vues;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;




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


class ForumController extends Controller
{


    public function consulter_forumAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();

        $categories=$em->getRepository('ForumBundle:Categorie')->findAll();
        $publication=$em->getRepository('ForumBundle:Publication')->findAll();
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
            'categorie'=>$categories,'publication'=>$publication);
        return $this->render('ForumBundle:MyForum:Consulter_forum.html.twig',$data);
    }


    public function Consulter_publicationforumAction(Request $request , $id_publication)
    {
        $em=$this->getDoctrine()->getManager();
        $publicationaffiche=$em->getRepository('ForumBundle:Publication')->find($id_publication);
        if ($publicationaffiche){

            $query = $em->createQuery('SELECT u FROM ForumBundle:Vues u WHERE (u.iduser=:userr AND u.idPublication=:publication AND u.vue=1)'
            )->setParameter('userr', $this->get('security.token_storage')->getToken()->getUser() )
                ->setParameter('publication',$publicationaffiche );
            $vue = $query->getResult();
            if (!$vue) {
                $publicationaffiche->setNbrVue($publicationaffiche->getNbrVue() + 1);
                $vue = new Vues();
                $vue->setIdPublication($publicationaffiche);
                $vue->setIduser($this->get('security.token_storage')->getToken()->getUser());
                $vue->setVue(1);
                $em->persist($vue);
                $em->flush();
            }

        }
        $categories=$em->getRepository('ForumBundle:Categorie')->findAll();
        $publications=$em->getRepository('ForumBundle:Publication')->findAll();

        $query = $em->createQuery('SELECT u FROM ForumBundle:Reponse u ORDER BY u.created DESC ');
        $reponses = $query->getResult();
        $avis=$em->getRepository('ForumBundle:Avis')->findAll();


        //reponse
        $newreponse = new Reponse();
        $formreponse = $this->createForm('ForumBundle\Form\Reponse', $newreponse);
        $formreponse->handleRequest($request);
        if ($formreponse->isSubmitted() && $formreponse->isValid() && $request->getMethod()=='POST') {
            $newreponse->setCreated(new \DateTime("now"));
            $newreponse->setIdPublication($publicationaffiche->getId());

            $newreponse->setIdUser($this->get('security.token_storage')->getToken()->getUser());
            $em->persist($newreponse);
            $em->flush();
            return $this->redirect($request->getUri());
        }

        $publicationresolu=$em->getRepository('ForumBundle:Publicationresolu')->findOneBy(array('idpublication'=>$publicationaffiche->getId()));
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
            'publication_resolu'=>$publicationresolu,
            'avis'=>$avis,
            'newreponse'=>$newreponse,
            'reponseform'=>$formreponse->createView(),
            'reponses'=>$reponses,
            'publicationaffiche'=>$publicationaffiche,
            'categorie'=>$categories,
            'publication'=>$publications);
        return $this->render('ForumBundle:MyForum:Consulter_publicationforum.html.twig',$data);
    }

    public function DonnerAvisreponseAction(Request $request, $id_reponse,$avis)
    {
        $em=$this->getDoctrine()->getManager();
        $id_reponse=$em->getRepository('ForumBundle:Reponse')->find($id_reponse);
        $aviss = new avis();
        $aviss->setIdUser($this->get('security.token_storage')->getToken()->getUser());
        $aviss->setReponse($id_reponse);
        $aviss->setAvis($avis);
        $aviss->setCreated(new \DateTime('now'));
        $em->persist($aviss);
        $em->flush();


        return $this->redirect($this->generateUrl('consulter_publicationforum',['id_publication'=>$id_reponse->getIdPublication()->getId()]));


    }

    public function SupprimerreponseAction(Request $request, $id_reponse, $id_p)
    {
        $em=$this->getDoctrine()->getManager();
        $id_reponsee=$em->getRepository('ForumBundle:Reponse')->find($id_reponse);
        $id_pub=$id_p;

        $query = $em->createQuery(
            'DELETE from
        ForumBundle:Avis rep 
      WHERE 
        rep.id = :repp'
        )
            ->setParameter("repp", $id_reponse);
        $result = $query->execute();

        $em->remove($id_reponsee);
        $this->getDoctrine()->getManager()->flush();


        return $this->redirect($this->generateUrl('consulter_publicationforum',['id_publication'=>$id_pub]));


    }


    public function ModifierreponseAction(Request $request, $id_reponse)
    {
        $em=$this->getDoctrine()->getManager();
        $id_reponsee=$em->getRepository('ForumBundle:Reponse')->find($id_reponse);
        $id_pub=$id_reponsee->getIdPublication();

        $formreponse = $this->createForm('ForumBundle\Form\Reponse', $id_reponsee);
        $formreponse->handleRequest($request);
        if ($formreponse->isSubmitted() && $formreponse->isValid() && $request->getMethod()=='POST') {
            $id_reponsee->setCreated(new \DateTime("now"));
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('consulter_publicationforum',['id_publication'=>$id_pub->getId()]));
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
            'reponse'=>$id_reponsee,'publication'=>$id_pub,'form_update'=>$formreponse->createView());
        return $this->render('@Forum/update_reponse.html.twig',$data);


    }



    //tt user

    public function Consulter_pubrepAction(Request $request,$type,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $publication=null;
        $reponse=null;
        if ($type=='publication'){
            $publication=$em->getRepository('ForumBundle:Publication')->find($id);}
        else{
            $reponse=$em->getRepository('ForumBundle:Reponse')->find($id);}
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
            'publication'=>$publication,
            'reponse'=>$reponse);

        return $this->render('@Forum/affiche_publication_reponse.html.twig',$data);
    }


    public function Consulter_categorieforumAction(Request $request,$typecat,$typeaff,$id_cat)
    {
        $em=$this->getDoctrine()->getManager();
        $categories=$em->getRepository('ForumBundle:Categorie')->findAll();
        $id_cat=$em->getRepository('ForumBundle:Categorie')->find($id_cat);

        $publication=$em->getRepository('ForumBundle:Publication')->findAll();

        if ($typecat=='categorie') {
            $query = $this->getDoctrine()->getManager()
                ->createQuery("SELECT q.titre as titre , q.nbr_vue , q.id as id 
            FROM ForumBundle:publication q
            INNER JOIN ForumBundle:Categorie c WITH q.iddcategorie = c.id
            WHERE q.location='approuver' AND (q.id_categorie=:id OR c.id_cat =:id) 
            ORDER by q.nbr_vue DESC ")->setMaxResults(10)->setParameter('id',$id_cat);
            $publicationpopulaire = $query->getResult();

            $query = $this->getDoctrine()->getManager()
                ->createQuery("SELECT q.titre as titre , q.nbr_vue , q.id as id 
            FROM ForumBundle:Publication q
            INNER JOIN ForumBundle:Categorie c WITH q.iddcategorie = c.id
            WHERE q.location='approuver' AND (q.id_categorie=:id OR c.id_cat =:id) 
            ORDER by q.created DESC ")->setMaxResults(10)->setParameter('id',$id_cat);
            $publicationrecent = $query->getResult();
        }elseif($typecat=='sous_categorie'){
            $query = $em->createQuery("SELECT u FROM ForumBundle:Publication u WHERE u.id_categorie=:cat AND u.location='approuver' ORDER BY u.created DESC "
            )->setMaxResults(10)->setParameter('cat',$id_cat);
            $publicationrecent = $query->getResult();

            $query = $em->createQuery("SELECT u FROM ForumBundle:Publication u WHERE u.id_categorie=:cat AND u.location='approuver' ORDER BY u.nbr_vue DESC "
            )->setMaxResults(10)->setParameter('cat', $id_cat);
            $publicationpopulaire = $query->getResult();
        }else{
            if ($typeaff=='populaires'){
                $query = $this->getDoctrine()->getManager()
                    ->createQuery("SELECT q.titre as titre , q.nbr_vue , q.id as id 
            FROM ForumBundle:Publication q
            INNER JOIN ForumBundle:Categorie c WITH q.iddcategorie = c.id
            WHERE q.location='approuver' AND (q.id_categorie=:id OR c.id_cat =:id) 
            ORDER by q.nbr_vue DESC ")->setParameter('id',$id_cat);
                $publicationpopulaire = $query->getResult();
                $publicationrecent='vide';
            }else{
                $query = $this->getDoctrine()->getManager()
                    ->createQuery("SELECT q.titre as titre , q.nbr_vue , q.id as id 
            FROM ForumBundle:Publication q
            INNER JOIN ForumBundle:Categorie c WITH q.iddcategorie = c.id
            WHERE q.location='approuver' AND (q.id_categorie=:id OR c.id_cat =:id) 
            ORDER by q.created DESC ")->setParameter('id',$id_cat);
                $publicationrecent = $query->getResult();
                $publicationpopulaire='vide';
            }
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
            'typeaff'=>$typeaff,'publication'=>$publication,'categorie'=>$categories,'curentcategory'=>$id_cat,'publicationpopulaire'=>$publicationpopulaire,'publicationrecent'=>$publicationrecent);
        return $this->render('@Forum/MyForum/Consulter_categorieforum.html.twig',$data);

    }
}
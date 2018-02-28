<?php
/**
 * Created by PhpStorm.
 * User: ikbel
 * Date: 15/02/2018
 * Time: 17:21
 */

namespace ForumBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use ForumBundle\Entity\Categorie;
use ForumBundle\Entity\Publication;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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


class AdminController extends Controller
{
    /**
     * @Route("/gerer_question" , name="gerer_question")
     */
    public function GererpublicationAction(Request $request)
    {
        $query = $this->getDoctrine()->getManager()
            ->createQuery("SELECT u FROM ForumBundle:Publication u WHERE u.location = 'en attente' ORDER BY u.created DESC ");
        $publications = $query->getResult();
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
            'publication'=>$publications);

        return $this->render('@Forum/Admin/Gerer_publications.html.twig',$data);
    }


    public function ActionPublicationAction(Request $request,$id_pub,$action)
    {
        if($request->isMethod('POST')){
            $em=$this->getDoctrine()->getManager();
            $publication=$em->getRepository('ForumBundle:Publication')->find($id_pub);
            if($action == 'approuver'){
                $publication->setLocation('approuver');
                $em->flush();
            }else{
                $publication->setLocation('deapprouver');
                $em->flush();
            }
        }

        return $this->redirect($this->generateUrl('gerer_publication'));
    }


    public function GererreponseAction(Request $request)
    {

        $query = $this->getDoctrine()->getManager()
            ->createQuery("SELECT u FROM ForumBundle:Reponse u  ORDER BY u.created DESC ");
        $questions = $query->getResult();
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
            'reponse'=>$questions);
        return $this->render('@Forum/Admin/Gerer_reponses.html.twig',$data);
    }



    public function updatecategorieAction(Request $request  , Categorie $categorie , $type_select)
    {

        $em = $this->getDoctrine()->getManager();
        $categories=$em->getRepository('ForumBundle:Categorie')->findAll();

        $updateform = $this->createForm('ForumBundle\Form\Categorie', $categorie);
        $updateform->handleRequest($request);


        if ($updateform->isSubmitted() && $updateform->isValid() && $request->isMethod("POST")) {

            $categorieidcat=$em->getRepository('ForumBundle:Categorie')->find($request->request->get('categorie'));
            if ($request->request->get('categorie') == 'nv'){
                $categorie->setType('categorie');
                $categorie->setIdCat();
            }else{
                if ($categorie->getType() != 'categorie' or ($type_select == 'categorie' and $categorie->getType()=='categorie')) {
                    $categorie->setIdCat($categorieidcat);
                    $categorie->setType('sous-categorie');
                }}



            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('gerer_categories');
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
            'form_update'=>$updateform->createView(),'categories'=>$categories,'categorie'=>$categorie);

        return $this->render('@Forum/Admin/update_categorie.html.twig',$data);
    }


    public function GerercategoriesAction(Request $request)
    {
        $categorie = new Categorie();
        $formajout = $this->createForm('ForumBundle\Form\Categorie', $categorie);
        $formajout->handleRequest($request);


        $em = $this->getDoctrine()->getManager();
        if ($formajout->isSubmitted() && $formajout->isValid() && $request->isMethod("POST")) {

            $valuecat=$request->request->get('categorie');
            if($valuecat == 'nv'){
                $categorie->setNbrPublications(0);
                $categorie->setType('categorie');
                $em->persist($categorie);
                $em->flush();

            }
            else{
                $categoriee=$em->getRepository('ForumBundle:Categorie')->find($valuecat);
                $categorie->setNbrPublications(0);
                $categorie->setIdCat($categoriee);
                $categorie->setType('sous-categorie');
                $em->persist($categorie);
                $em->flush();
            }



            return $this->redirectToRoute('gerer_categories');
        }

        $categories=$em->getRepository('ForumBundle:Categorie')->findAll();
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
            'categories' => $categories,'formajout' => $formajout->createView());
        return $this->render('@Forum/Admin/Gerer_categories.html.twig',$data);
    }



    public function GererSupprimercategoriesAction(Request $request,$id,$type_select)
    {
        $em=$this->getDoctrine()->getManager();
        $cat=$em->getRepository('ForumBundle:Categorie')->find($id);

        if ($type_select =='sous-categorie'){
            $query = $this->getDoctrine()->getManager()
                ->createQuery("SELECT u FROM ForumBundle:Reponse u WHERE ");
            $reponses = $query->getResult();

            $query = $this->getDoctrine()->getManager()
                ->createQuery("SELECT u FROM ForumBundle:Publication u WHERE u.location = 'en attente' ORDER BY u.created DESC ");
            $publications = $query->getResult();

        }
        else{

        }

        $em->remove($cat);
        $em->flush();

        return $this->redirectToRoute('gerer_categories');
    }
}
<?php

namespace ShopBundle\Controller;

use blackknight467\StarRatingBundle\Form\RatingType;
use ShopBundle\Entity\Commande;
use ShopBundle\Entity\Rating;
use ShopBundle\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ShopBundle\Entity\Produit;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


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
        $Produits = $this->getDoctrine()->getManager()->getRepository(produit::class)->findAll();
//        $form = $this->createFormBuilder()
//            ->add('rating', RatingType::class, [
//                'label' => 'Rating'])
//            ->getForm();
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'Produits' => $Produits,
//            'form'=>$form->createView()

        );


        return $this->render('@Shop/Default/index.html.twig', $data);
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

//    public function formulaireAction(Request $request)
//    {
//        $produit = new Produit();
//        $user=$this->getUser();
//        $a=$user->getNom();
//            $form = $this->createFormBuilder($produit)
//                ->add('nom', TextType::class, array(
//                    'attr' => array(
//                        'placeholder' => 'Nom',
//                        'class' => 'form-control'
//                    )))
//                ->add('prix', IntegerType::class, array(
//                    'attr' => array(
//                        'placeholder' => 'Prix',
//                        'class' => 'form-control'
//                    )))
//                ->add('age', IntegerType::class, array(
//                    'attr' => array(
//                        'placeholder' => 'Age',
//                        'class' => 'form-control'
//                    )))
//                ->add('stock', IntegerType::class, array(
//                    'attr' => array(
//                        'placeholder' => 'Stock',
//                        'class' => 'form-control'
//                    )))
//                ->add('description', TextType::class, array(
//                    'attr' => array(
//                        'placeholder' => 'Description',
//                        'class' => 'form-control'
//                    )))
//                ->add('categorie', TextType::class, array(
//                    'attr' => array(
//                        'placeholder' => 'Categorie',
//                        'class' => 'form-control'
//                    )))
//                ->add('image', FileType::class,array('data_class' => null))
//                ->add('save', SubmitType::class)
//                ->getForm();
//            $form->handleRequest($request);
//            if ($form->isSubmitted() && $form->isValid()) {
//
//
//                $file = $form['image']->getData();
//                $file->move("images/", $file->getClientOriginalName());
//                $produit->setImage("images/" . $file->getClientOriginalName());
//
//                $em = $this->getDoctrine()->getManager();
//                $em->persist($produit);
//                $em->flush();
//
//
//                return $this->redirectToRoute('shop');
//            }
//
//            /** @var $session Session */
//            $session = $request->getSession();
//
//            $authErrorKey = Security::AUTHENTICATION_ERROR;
//            $lastUsernameKey = Security::LAST_USERNAME;
//
//            // get the error if any (works with forward and redirect -- see below)
//            if ($request->attributes->has($authErrorKey)) {
//                $error = $request->attributes->get($authErrorKey);
//            } elseif (null !== $session && $session->has($authErrorKey)) {
//                $error = $session->get($authErrorKey);
//                $session->remove($authErrorKey);
//            } else {
//                $error = null;
//            }
//
//            if (!$error instanceof AuthenticationException) {
//                $error = null; // The value does not come from the security component.
//            }
//
//            // last username entered by the user
//            $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);
//
//            $csrfToken = $this->has('security.csrf.token_manager')
//                ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
//                : null;
//            $routeName = $request->get('_route');
//            $data = array(
//                'last_username' => $lastUsername,
//                'error' => $error,
//                'csrf_token' => $csrfToken,
//                'routename' => $routeName,
//                'form'=>$form->createView()
//
//            );
//            return $this->render('@Shop/Default/formulaire.html.twig', $data);
//
//        }



    public function SupprimerProduitAction($id)
    {

        $m = $this->getDoctrine()->getManager();
        $produit = $m->getRepository('ShopBundle:Produit')->find($id);
        $m->remove($produit);
        $m->flush();
        return $this->redirectToRoute('gestion_boutique');
    }




    public function gestion_boutiqueAction(Request $request)
    {
        $produits = new Produit();
        $user=$this->getUser();
        $idf=$user->getId();
        $m=$this->getDoctrine()->getManager();
        $produit=$m->getRepository('ShopBundle:Produit')->findByIdf($idf);
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

        $form=$this->createFormBuilder($produits)
            ->add('nom', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
                'attr' => array(
                    'placeholder' => 'nom du produit',
                    'class'=>'form-control border-color-1'
                )))
            ->add('stock',IntegerType::class, array(
                'attr' => array(
                    'placeholder' => 'Stock',
                    'class'=>'form-control border-color-2'
                )) )
            ->add('prix',\Symfony\Component\Form\Extension\Core\Type\IntegerType::class, array(
                'attr' => array(
                    'placeholder' => 'Prix',
                    'class'=>'form-control border-color-4'
                )))
            ->add('genre', ChoiceType::class, array(
                //'class'=>'form-control border-color-4',
                'choices' => array(

                    'Garçon' => 'Garçon',
                    'Fille' => 'Fille',
                ),
                'attr' => array(
                    'placeholder' => 'Sexe',
                    'class'=>'form-control border-color-3'
                )))

            ->add('age',IntegerType::class, array(
                'attr' => array(
                    'placeholder' => 'Age',
                    'class'=>'form-control border-color-5'
                )))
            ->add('description',TextareaType::class, array(
                'attr' => array(
                    'placeholder' => 'Description',
                    'class'=>'form-control border-color-1'
                )) )

            ->add('image', FileType::class,array('data_class' => null))
            ->add('Ajouter',SubmitType::class, array(
                'attr' => array(

                    'class'=>'btn btn-primary'
                )))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $file = $form['image']->getData();

            $file->move("images/", $file->getClientOriginalName());
            $produits->setImage("images/".$file->getClientOriginalName());
            $produits->setIdf($idf);
            $em = $this->getDoctrine()->getManager();

            $em->persist($produits);
            $em->flush();


            return $this->redirectToRoute('gestion_boutique');
        }
        $routeName = $request->get('_route');
        $cmds=array();
        $m=$this->getDoctrine()->getManager();
        $user=$this->getUser();
        $iduser=$user->getId();
        $commande=$m->getRepository('ShopBundle:Commande')->findByIdf($iduser);

        if(!(empty($commande)|| is_null($commande))){
            $cmds[0]=array();
            array_push($cmds[0],$commande[0]);
            for ($x = 1; $x < count($commande); $x++) {

                $pos=$this->checkIfExist($cmds,$commande[$x]);
                if($pos==-1){
                    array_push($cmds,array($commande[$x])) ;
                }else{
                    array_push($cmds[$pos],$commande[$x]);
                }
            }
        }
        else{$cmds[0]=array();}
        $data=array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename'=>$routeName,
            'produit'=>$produit,
            'form'=>$form->createView(),
            'commande'=>$cmds
        );
        //return $this->render('@Boutique/Default/boutique/affiche_produit.html.twig',$data);


        return $this->render('@Shop/Default/gestion_boutique.html.twig',$data);
    }

    public function modifierAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $produit=$em->getRepository('ShopBundle:Produit')->find($id);
        $form=$this->createForm(ProduitType::class,$produit);
        if($form->handleRequest($request)->isValid()) {
            $file = $form['image']->getData();

            $file->move("images/", $file->getClientOriginalName());
            $produit->setImage("images/".$file->getClientOriginalName());
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('gestion_boutique');
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
        return $this->render('@Shop/Default/modfier_produit.html.twig',$data);
    }


    public function getallProduitFromSessionAction(Request $request){
        $session = $request->getSession();
        $array=$session->get("produits");
        $response = new JsonResponse(array('data' => $array));
        return $response;
    }


    public function delsessionAction(Request $request,$id,$quantite){
$i=0;
$quantite=$quantite+1;
$j=0;
        $session = $request->getSession();
        $cartProductsId=$session->get("produits");
        foreach ($cartProductsId as $a)
        {

            if($a==$id)
            {

                $i=$i+1;
                if($i==$quantite)
                {
                    unset($cartProductsId[$j]);
//                    if($j==count($cartProductsId))
//                    {
//                        unset($cartProductsId[$j]);
//                    }
//                    else {
//                        for ($x = $j; $x < count( $cartProductsId ); $x++) {
//                            array_push($cartProductsId[$x],$cartProductsId[$x + 1]);
//                        }
//
//                        unset( $cartProductsId[count( $cartProductsId )] );
//                    }
                }
            }
            $j=$j+1;
        }
        $a=array_values($cartProductsId);
        $session->set("produits",$a);

        return new JsonResponse(array('data' =>$array=$session->get("produits")));
    }


    public function setProduitToSessionAction($id,Request $request){
        $session = $request->getSession();
        $sessionArray=$session->get("produits");
        if($sessionArray==null){
            $sessionArray = array();
            $session->set("produits",$sessionArray);

        }
        array_push($sessionArray,$id);
        $session->set("produits",$sessionArray);
        return new JsonResponse(array('data' =>$array=$session->get("produits")));

    }
    public function getProductByIdAction($id){
        $em=$this->getDoctrine()->getManager();
        $encoders = array(new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $product=$em->getRepository(Produit::class)->find($id);

        $response = new Response($serializer->serialize($product, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }


    public function AfficherPanierAction(Request $request)
    {
        $session = $request->getSession();
        $produitsArray=array();
        $table = array();
        $cartProductsId=$session->get("produits");
        $m = $this->getDoctrine()->getManager();
        $prixtotal=0;
        if($cartProductsId!=null)
        {
            foreach($cartProductsId as $idproduit)
            {
                array_push($produitsArray,$m->getRepository('ShopBundle:Produit')->find($idproduit));
            }

            foreach($produitsArray as $tab)
            {

                if (in_array($tab, $table)){

                    $tab->setQuantite($tab->getQuantite()+1);
                }

                else{

                    array_push($table,$tab);
                    $tab->setQuantite($tab->getQuantite()+1);

                }

            }
            foreach($table as $total)
            {
                $prixtotal=$prixtotal+($total->getQuantite()*$total->getPrix());
            }

        }

        $session = $request->getSession();

        $lastUsernameKey = Security::LAST_USERNAME;
        $authErrorKey = Security::AUTHENTICATION_ERROR;

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
            'produits' => $table,
            'prixtotal' =>$prixtotal
        );
        return $this->render('@Shop/Default/cart_page.html.twig', $data);

    }


    public function okAction(Request $request)
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

        $Produits = $this->getDoctrine()->getManager()->getRepository(produit::class)->findAll();
        $evaluation = new Produit();
        $form = $this->createFormBuilder()
            ->add('rating', RatingType::class, [
                'label' => 'Rating'])
            ->getForm();
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'Produits' => $Produits,
            'form'=>$form

        );

        return $this->render('@Shop/Default/ok.html.twig',$data);

    }
    public function AjoutCommandeAction(Request $request)
    {    $em=$this->getDoctrine()->getManager();
        $user=$this->getUser();
        $iduser=$user->getId();
        $session = $request->getSession();
        $produitsArray=array();
        $table = array();
        $cartProductsId=$session->get("produits");
        $m = $this->getDoctrine()->getManager();
        $prixtotal=0;
        if($cartProductsId!=null)
        {
            foreach($cartProductsId as $idproduit)
            {
                array_push($produitsArray,$m->getRepository('ShopBundle:Produit')->find($idproduit));
            }

            foreach($produitsArray as $tab)
            {

                if (in_array($tab, $table)){

                    $tab->setQuantite($tab->getQuantite()+1);
                }

                else{

                    array_push($table,$tab);
                    $tab->setQuantite($tab->getQuantite()+1);

                }

            }
            foreach($table as $total)
            {        $commande=new Commande();

                $commande->setIdp($total->getId());
                $commande->setNom($total->getNom());
                $commande->setAge($total->getAge());
                $commande->setPrix($total->getPrix());
                $commande->setStock($total->getStock());
                $commande->setImage($total->getImage());
                $commande->setGenre($total->getGenre());
                $commande->setDescription($total->getDescription());
                $commande->setIdf($total->getIdf());
                $commande->setQuantite($total->getQuantite());
                $commande->setIdc($iduser);

                $prixtotal=$prixtotal+($total->getQuantite()*$total->getPrix());
                $total->setQuantite(0);


                $em->persist($commande);



            }
            $em->flush();
        }

        $session = $request->getSession();

        $lastUsernameKey = Security::LAST_USERNAME;
        $authErrorKey = Security::AUTHENTICATION_ERROR;

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
        $session->set("produits",null);
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'iduser'=>$iduser,
            'prixtotal' =>$prixtotal,


        );
        return $this->render('@Shop/Default/livraison.html.twig', $data);


    }
    function checkIfExist($array,$value){
        foreach ($array  as $key => $v){
            if ($v[0]->getIdc()==$value->getIdc()){
                return $key;
            }
        }
        return -1;
    }
    public function singleProductAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $rating = new Rating();
        $produit=$em->getRepository('ShopBundle:Produit')->find($id);
        $user=$this->getUser();
        $idc=$user->getId();
        $form=$this->createFormBuilder($rating)
            ->add('rating', RatingType::class, [
                'label' => 'Rating'
            ])
            ->add('valider',SubmitType::class, array(
                'attr' => array(

                    'class'=>'btn btn-xs btn-primary'
                )))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $rating->setIdc($idc);
            $rating->setNomProduit($produit->getNom());
            $rating->setIdproduit($produit->getId());
            $rating->setIdf($produit->getIdf());
            $em->persist($rating);
            $em->flush();
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
            'f'=>$form->createView(),
            'produit'=>$produit

        );
        return $this->render('ShopBundle:Default:single_product.html.twig',$data);

    }
}

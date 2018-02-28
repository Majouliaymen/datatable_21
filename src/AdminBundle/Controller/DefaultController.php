<?php

namespace AdminBundle\Controller;

use DonateBundle\Entity\ProduitDonation;
use EventsBundle\Entity\Evenement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Default:index.html.twig');
    }
public function AdminAction()
    {
        return $this->render('AdminBundle::Admin.html.twig');
    }



    public function ListDoAction()
    {

        $produit = $this->getDoctrine()
            ->getRepository(ProduitDonation::class)
            ->findAll();
        return $this->render('AdminBundle::Donation.html.twig',array('all'=>$produit));
    }

    public function ListEvAction()
    {

        $produit = $this->getDoctrine()
            ->getRepository(Evenement::class)
            ->findAll();
        return $this->render('AdminBundle::Events.html.twig',array('all'=>$produit));
    }

}


<?php

namespace DonateBundle\Repository;

/**
 * ProduitDonationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProduitDonationRepository extends \Doctrine\ORM\EntityRepository
{

/*    function findSerieDQL($nom){
        $query=$this->getEntityManager()
            ->createQuery("select v from DonateBundle v WHERE v.nom 
LIKE :nom  ")
            ->setParameter('nom','%'.$nom.'%');
        return $query->getResult();



    }*/

}

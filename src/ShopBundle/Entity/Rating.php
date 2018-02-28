<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;



/**
 * Rating
 *
 * @ORM\Table(name="rating")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\RatingRepository")
 */
class Rating
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer" ,nullable=true)
     */
    private $rating;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_produit", type="string", length=255)
     */
    private $nom_produit;

    /**
     * @var int
     *
     * @ORM\Column(name="idproduit", type="integer")
     */
    private $idproduit;

    /**
     * @var int
     *
     * @ORM\Column(name="idf", type="integer")
     */
    private $idf;

    /**
     * @var int
     *
     * @ORM\Column(name="idc", type="integer")
     */
    private $idc;






    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     *
     * @return Rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set idf
     *
     * @param integer $idf
     *
     * @return Rating
     */
    public function setIdf($idf)
    {
        $this->idf = $idf;

        return $this;
    }

    /**
     * Get idf
     *
     * @return integer
     */
    public function getIdf()
    {
        return $this->idf;
    }

    /**
     * Set idc
     *
     * @param integer $idc
     *
     * @return Rating
     */
    public function setIdc($idc)
    {
        $this->idc = $idc;

        return $this;
    }

    /**
     * Get idc
     *
     * @return integer
     */
    public function getIdc()
    {
        return $this->idc;
    }

    /**
     * Set nomProduit
     *
     * @param integer $nomProduit
     *
     * @return Rating
     */
    public function setNomProduit($nomProduit)
    {
        $this->nom_produit = $nomProduit;

        return $this;
    }

    /**
     * Get nomProduit
     *
     * @return integer
     */
    public function getNomProduit()
    {
        return $this->nom_produit;
    }

    /**
     * Set idproduit
     *
     * @param integer $idproduit
     *
     * @return Rating
     */
    public function setIdproduit($idproduit)
    {
        $this->idproduit = $idproduit;

        return $this;
    }

    /**
     * Get idproduit
     *
     * @return integer
     */
    public function getIdproduit()
    {
        return $this->idproduit;
    }
}

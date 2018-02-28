<?php

namespace BabyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Baby
 *
 * @ORM\Table(name="baby")
 * @ORM\Entity(repositoryClass="BabyBundle\Repository\BabyRepository")
 */
class Baby
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="adrese", type="string", length=255)
     */
    private $adrese;

    /**
     * @var string
     *
     * @ORM\Column(name="disponibilite", type="string")
     */
    private $disponibilite;


    /**
     * @var string
     *
     * @ORM\Column(name="datefin", type="string")
     */
    private $datefin;





    /**
     * @var int
     *
     * @ORM\Column(name="idb", type="integer")
     */
    private $idb;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;
    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer")
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return Baby
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Baby
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set adrese
     *
     * @param string $adrese
     *
     * @return Baby
     */
    public function setAdrese($adrese)
    {
        $this->adrese = $adrese;

        return $this;
    }

    /**
     * Get adrese
     *
     * @return string
     */
    public function getAdrese()
    {
        return $this->adrese;
    }


    /**
     * Set email
     *
     * @param string $email
     *
     * @return Baby
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Baby
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Baby
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set disponibilite
     *
     * @param DateTime $disponibilite
     *
     * @return Baby
     */
    public function setDisponibilite($disponibilite)
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    /**
     * Get disponibilite
     *
     * @return DateTime
     */
    public function getDisponibilite()
    {
        return $this->disponibilite;
    }

    /**
     * Set prix
     *
     * @param integer $prix
     *
     * @return Baby
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return integer
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set idb
     *
     * @param integer $idb
     *
     * @return Baby
     */
    public function setIdb($idb)
    {
        $this->idb = $idb;

        return $this;
    }

    /**
     * Get idb
     *
     * @return integer
     */
    public function getIdb()
    {
        return $this->idb;
    }

    /**
     * Set datefin.
     *
     * @param string $datefin
     *
     * @return Baby
     */
    public function setDatefin($datefin)
    {
        $this->datefin = $datefin;

        return $this;
    }

    /**
     * Get datefin.
     *
     * @return string
     */
    public function getDatefin()
    {
        return $this->datefin;
    }
}

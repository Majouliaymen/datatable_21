<?php

namespace EventsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert ;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement")
 * @ORM\Entity(repositoryClass="EventsBundle\Repository\EvenementRepository")
 */
class Evenement
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
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="string", length=255)
     */
    private $prix='0';
    /**
     * @var integer
     *
     * @ORM\Column(name="idu", type="integer", length=255)
     */
    private $idu;





    /**
     * @var integer
     *
     * @ORM\Column(name="particiate", type="integer", length=255 ,nullable=true)
     */
    private $particiate='0';

    /**
     * @var string
     *
     * @ORM\Column(name="datedeb", type="string")
     */
    private $datedeb;



    /**
     * @var string
     *
     * @ORM\Column(name="datefin", type="string")
     */
    private $datefin;


    /**
     * @var string
     *
     * @ORM\Column(name="nomorg", type="string", length=255)
     */
    private $nomorg;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload the product brochure as a PDF file.")
     * @Assert\Image()
     */
    private $image;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom.
     *
     * @param string $nom
     *
     * @return Evenement
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom.
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Evenement
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Evenement
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set prix.
     *
     * @param string $prix
     *
     * @return Evenement
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix.
     *
     * @return string
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set datedeb.
     *
     * @param string $datedeb
     *
     * @return Evenement
     */
    public function setDatedeb($datedeb)
    {
        $this->datedeb = $datedeb;

        return $this;
    }

    /**
     * Get datedeb.
     *
     * @return string
     */
    public function getDatedeb()
    {
        return $this->datedeb;
    }

    /**
     * Set datefin.
     *
     * @param string $datefin
     *
     * @return Evenement
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







    /**
     * Set nomorg.
     *
     * @param string $nomorg
     *
     * @return Evenement
     */
    public function setNomorg($nomorg)
    {
        $this->nomorg = $nomorg;

        return $this;
    }

    /**
     * Get nomorg.
     *
     * @return string
     */
    public function getNomorg()
    {
        return $this->nomorg;
    }

    /**
     * Set adresse.
     *
     * @param string $adresse
     *
     * @return Evenement
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse.
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set image.
     *
     * @param string $image
     *
     * @return Evenement
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set idu.
     *
     * @param int $idu
     *
     * @return Evenement
     */
    public function setIdu($idu)
    {
        $this->idu = $idu;

        return $this;
    }

    /**
     * Get idu.
     *
     * @return int
     */
    public function getIdu()
    {
        return $this->idu;
    }

    /**
     * Set particiate.
     *
     * @param int $particiate
     *
     * @return Evenement
     */
    public function setParticiate($particiate)
    {
        $this->particiate = $particiate;

        return $this;
    }

    /**
     * Get particiate.
     *
     * @return int
     */
    public function getParticiate()
    {
        return $this->particiate;
    }
}

<?php

namespace JardinBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jarenfant
 *
 * @ORM\Table(name="jarenfant")
 * @ORM\Entity(repositoryClass="JardinBundle\Repository\JarenfantRepository")
 */
class Jarenfant
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
     * @ORM\Column(name="description", type="string", length=5000)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=5000)
     */
    private $logo;

    /**
     * @var int
     *
     * @ORM\Column(name="proprietaire", type="integer")
     *  @ORM\OneToOne(targetEntity="UserBundle\Entity\User")
     */
    private $proprietaire;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrnote", type="integer")
     */
    private $nbrnote;

    /**
     * @var int
     *
     * @ORM\Column(name="note", type="integer")
     */
    private $note;

    /**
     * @var int
     *
     * @ORM\Column(name="numtel", type="integer")
     */
    private $numtel;

    /**
     * @var string
     *
     * @ORM\Column(name="adressemail", type="string", length=255)
     */
    private $adressemail;


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
     * @return Jarenfant
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
     * Set description
     *
     * @param string $description
     *
     * @return Jarenfant
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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Jarenfant
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Jarenfant
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set proprietaire
     *
     * @param integer $proprietaire
     *
     * @return Jarenfant
     */
    public function setProprietaire($proprietaire)
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    /**
     * Get proprietaire
     *
     * @return int
     */
    public function getProprietaire()
    {
        return $this->proprietaire;
    }

    /**
     * Set nbrnote
     *
     * @param integer $nbrnote
     *
     * @return Jarenfant
     */
    public function setNbrnote($nbrnote)
    {
        $this->nbrnote = $nbrnote;

        return $this;
    }

    /**
     * Get nbrnote
     *
     * @return int
     */
    public function getNbrnote()
    {
        return $this->nbrnote;
    }

    /**
     * Set note
     *
     * @param integer $note
     *
     * @return Jarenfant
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return int
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set numtel
     *
     * @param integer $numtel
     *
     * @return Jarenfant
     */
    public function setNumtel($numtel)
    {
        $this->numtel = $numtel;

        return $this;
    }

    /**
     * Get numtel
     *
     * @return int
     */
    public function getNumtel()
    {
        return $this->numtel;
    }

    /**
     * Set adressemail
     *
     * @param string $adressemail
     *
     * @return Jarenfant
     */
    public function setAdressemail($adressemail)
    {
        $this->adressemail = $adressemail;

        return $this;
    }

    /**
     * Get adressemail
     *
     * @return string
     */
    public function getAdressemail()
    {
        return $this->adressemail;
    }

   
}


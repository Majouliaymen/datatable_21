<?php
/**
 * Created by PhpStorm.
 * User: ikbel
 * Date: 11/02/2018
 * Time: 19:34
 */

namespace ForumBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * categorie
 *
 * @ORM\Table(name="Categorie")
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\categorieRepository")
 */

class Categorie
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
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $nom;

    /**
     * @var int
     *
     * @ORM\Column(name="nbr_publications", type="integer")
     */
    private $nbrPublications;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string")
     */
    private $type;

    /**
     *@ORM\ManyToOne(targetEntity="ForumBundle\Entity\Categorie")
     *@ORM\JoinColumn(name="id_cat", referencedColumnName="id", onDelete="CASCADE")
     */
    private $id_cat;


    /**
     * Get id
     *
     * @return integer
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
     * @return Categorie
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
     * Set nbrPublications
     *
     * @param integer $nbrPublications
     *
     * @return Categorie
     */
    public function setNbrPublications($nbrPublications)
    {
        $this->nbrPublications = $nbrPublications;

        return $this;
    }

    /**
     * Get nbrPublications
     *
     * @return integer
     */
    public function getNbrPublications()
    {
        return $this->nbrPublications;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Categorie
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set idCat
     *
     * @param \ForumBundle\Entity\Categorie $idCat
     *
     * @return Categorie
     */
    public function setIdCat(\ForumBundle\Entity\Categorie $idCat = null)
    {
        $this->id_cat = $idCat;

        return $this;
    }

    /**
     * Get idCat
     *
     * @return \ForumBundle\Entity\Categorie
     */
    public function getIdCat()
    {
        return $this->id_cat;
    }
}

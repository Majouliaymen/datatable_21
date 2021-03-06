<?php
/**
 * Created by PhpStorm.
 * User: ikbel
 * Date: 11/02/2018
 * Time: 20:18
 */

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Class Vues
 * @package ForumBundle\Entity
 */
/**
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\vuepublicationcategorieRepository")
 */
class Vues
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
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="iduser", referencedColumnName="id")
     */
    private $iduser;

    /**
     * @ORM\ManyToOne(targetEntity="ForumBundle\Entity\Publication")
     * @ORM\JoinColumn(name="idPublication", referencedColumnName="id")
     */
    private $idPublication;
    /**
     * @ORM\ManyToOne(targetEntity="ForumBundle\Entity\Categorie")
     * @ORM\JoinColumn(name="idcategorie", referencedColumnName="id")
     */
    private $idcategorie;

    /**
     * @var int
     *
     * @ORM\Column(name="vue", type="integer")
     */
    private $vue;

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
     * Set vue
     *
     * @param integer $vue
     *
     * @return Vues
     */
    public function setVue($vue)
    {
        $this->vue = $vue;

        return $this;
    }

    /**
     * Get vue
     *
     * @return integer
     */
    public function getVue()
    {
        return $this->vue;
    }

    /**
     * Set iduser
     *
     * @param \UserBundle\Entity\User $iduser
     *
     * @return Vues
     */
    public function setIduser(\UserBundle\Entity\User $iduser )
    {
        $this->iduser = $iduser;

        return $this;
    }

    /**
     * Get iduser
     *
     * @return \UserBundle\Entity\User
     */
    public function getIduser()
    {
        return $this->iduser;
    }

    /**
     * Set idPublication
     *
     * @param \ForumBundle\Entity\Publication $idPublication
     *
     * @return Vues
     */
    public function setIdPublication(\ForumBundle\Entity\Publication $idPublication = null)
    {
        $this->idPublication = $idPublication;

        return $this;
    }

    /**
     * Get idPublication
     *
     * @return \ForumBundle\Entity\Publication
     */
    public function getIdPublication()
    {
        return $this->idPublication;
    }

    /**
     * Set idcategorie
     *
     * @param \ForumBundle\Entity\Categorie $idcategorie
     *
     * @return Vues
     */
    public function setIdcategorie(\ForumBundle\Entity\Categorie $idcategorie = null)
    {
        $this->idcategorie = $idcategorie;

        return $this;
    }

    /**
     * Get idcategorie
     *
     * @return \ForumBundle\Entity\Categorie
     */
    public function getIdcategorie()
    {
        return $this->idcategorie;
    }
}

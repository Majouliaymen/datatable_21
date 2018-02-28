<?php
/**
 * Created by PhpStorm.
 * User: ikbel
 * Date: 11/02/2018
 * Time: 19:58
 */

namespace ForumBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * Class Reponse
 * @package ForumBundle\Entity
 */
/**
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\reponseRepository")
 */

class Reponse
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
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="ForumBundle\Entity\Publication",inversedBy="id_reponse")
     * @ORM\JoinColumn(name="id_publication", referencedColumnName="id")
     */
    private $id_publication;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User" , inversedBy="id_reponse")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $id_user;

    /**
     * One Product has Many Features.
     * @ORM\OneToMany(targetEntity="ForumBundle\Entity\Avis", mappedBy="reponse", cascade={"remove"})
     * @ORM\JoinColumn(name="avis", referencedColumnName="id")
     */
    private $avis;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->avis = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Reponse
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Reponse
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set idPublication
     *
     * @param \ForumBundle\Entity\Publication $idPublication
     *
     * @return Reponse
     */
    public function setIdPublication(\ForumBundle\Entity\Publication $idPublication )
    {
        $this->id_publication = $idPublication;

        return $this;
    }

    /**
     * Get idPublication
     *
     * @return \ForumBundle\Entity\Publication
     */
    public function getIdPublication()
    {
        return $this->id_publication;
    }

    /**
     * Set idUser
     *
     * @param \UserBundle\Entity\User $idUser
     *
     * @return Reponse
     */
    public function setIdUser(\UserBundle\Entity\User $idUser = null)
    {
        $this->id_user = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return \UserBundle\Entity\User
     */
    public function getIdUser()
    {
        return $this->id_user;
    }

    /**
     * Add avi
     *
     * @param \ForumBundle\Entity\Avis $avi
     *
     * @return Reponse
     */
    public function addAvi(\ForumBundle\Entity\Avis $avi)
    {
        $this->avis[] = $avi;

        return $this;
    }

    /**
     * Remove avi
     *
     * @param \ForumBundle\Entity\Avis $avi
     */
    public function removeAvi(\ForumBundle\Entity\Avis $avi)
    {
        $this->avis->removeElement($avi);
    }

    /**
     * Get avis
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAvis()
    {
        return $this->avis;
    }
}

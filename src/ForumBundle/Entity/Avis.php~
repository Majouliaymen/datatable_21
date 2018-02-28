<?php
/**
 * Created by PhpStorm.
 * User: ikbel
 * Date: 11/02/2018
 * Time: 20:07
 */

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Class Avis
 * @package ForumBundle\Entity
 */
/**
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\avisRepository")
 */
class Avis
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
     * @ORM\Column(name="avis", type="float")
     */
    private $avis;

    /**
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;


    /**
     * Many Features have One Product.
     * @ORM\ManyToOne(targetEntity="ForumBundle\Entity\Reponse", inversedBy="avis")
     * @ORM\JoinColumn(name="reponse", referencedColumnName="id")
     */
    private $reponse;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $id_user;

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
     * Set avis
     *
     * @param float $avis
     *
     * @return Avis
     */
    public function setAvis($avis)
    {
        $this->avis = $avis;

        return $this;
    }

    /**
     * Get avis
     *
     * @return float
     */
    public function getAvis()
    {
        return $this->avis;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Avis
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
     * Set reponse
     *
     * @param \ForumBundle\Entity\Reponse $reponse
     *
     * @return Avis
     */
    public function setReponse(\ForumBundle\Entity\Reponse $reponse = null)
    {
        $this->reponse = $reponse;

        return $this;
    }

    /**
     * Get reponse
     *
     * @return \ForumBundle\Entity\Reponse
     */
    public function getReponse()
    {
        return $this->reponse;
    }

    /**
     * Set idUser
     *
     * @param \UserBundle\Entity\User $idUser
     *
     * @return Avis
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
}

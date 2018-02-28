<?php
/**
 * Created by PhpStorm.
 * User: ikbel
 * Date: 19/02/2018
 * Time: 01:01
 */

namespace ForumBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * Class Publicationresolu
 * @package ForumBundle\Entity
 */
/**
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\PublicationresoluRepository")
 */

class Publicationresolu
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_pub", type="integer")
     */
    private $id_pub;


    /**
     * @ORM\OneToOne(targetEntity="ForumBundle\Entity\Publication")
     * @ORM\JoinColumn(name="id_publication", referencedColumnName="id")
     */
    private $idpublication;

    /**
     * @ORM\OneToOne(targetEntity="ForumBundle\Entity\Reponse")
     * @ORM\JoinColumn(name="id_reponse", referencedColumnName="id")
     */
    private $id_reponse;


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
     * Set idPub
     *
     * @param integer $idPub
     *
     * @return Publicationresolu
     */
    public function setIdPub($idPub)
    {
        $this->id_pub = $idPub;

        return $this;
    }

    /**
     * Get idPub
     *
     * @return integer
     */
    public function getIdPub()
    {
        return $this->id_pub;
    }

    /**
     * Set idpublication
     *
     * @param \ForumBundle\Entity\Publication $idpublication
     *
     * @return Publicationresolu
     */
    public function setIdpublication( $idpublication )
    {
        $this->idpublication = $idpublication;

        return $this;
    }

    /**
     * Get idpublication
     *
     * @return \ForumBundle\Entity\Publication
     */
    public function getIdpublication()
    {
        return $this->idpublication;
    }

    /**
     * Set idReponse
     *
     * @param \ForumBundle\Entity\Reponse $idReponse
     *
     * @return Publicationresolu
     */
    public function setIdReponse(\ForumBundle\Entity\Reponse $idReponse = null)
    {
        $this->id_reponse = $idReponse;

        return $this;
    }

    /**
     * Get idReponse
     *
     * @return \ForumBundle\Entity\Reponse
     */
    public function getIdReponse()
    {
        return $this->id_reponse;
    }
}

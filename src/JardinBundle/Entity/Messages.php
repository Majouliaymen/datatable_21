<?php

namespace JardinBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Messages
 *
 * @ORM\Table(name="messages")
 * @ORM\Entity(repositoryClass="JardinBundle\Repository\MessagesRepository")
 */
class Messages
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
     * @ORM\Column(name="id_recepteur", type="integer")
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\User")
     **/
    private $idrecepteur;

    /**
     * @var int
     *
     * @ORM\Column(name="idEmetteur", type="integer")
     */
    private $idEmetteur;

    /**
     * @var string
     *
     * @ORM\Column(name="objet", type="string", length=255)
     */
    private $objet;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu_message", type="string", length=3000)
     */
    private $contenuMessage;


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
     * @return int
     */
    public function getIdrecepteur()
    {
        return $this->idrecepteur;
    }

    /**
     * @param int $idrecepteur
     */
    public function setIdrecepteur($idrecepteur)
    {
        $this->idrecepteur = $idrecepteur;
    }

    /**
     * @return int
     */
    public function getIdEmetteur()
    {
        return $this->idEmetteur;
    }

    /**
     * @param int $idEmetteur
     */
    public function setIdEmetteur($idEmetteur)
    {
        $this->idEmetteur = $idEmetteur;
    }


    /**
     * Set objet.
     *
     * @param string $objet
     *
     * @return Messages
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Get objet.
     *
     * @return string
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * Set contenuMessage.
     *
     * @param string $contenuMessage
     *
     * @return Messages
     */
    public function setContenuMessage($contenuMessage)
    {
        $this->contenuMessage = $contenuMessage;

        return $this;
    }

    /**
     * Get contenuMessage.
     *
     * @return string
     */
    public function getContenuMessage()
    {
        return $this->contenuMessage;
    }
}

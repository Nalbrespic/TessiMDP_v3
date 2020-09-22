<?php

namespace TMD\AppliBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * EcommTransport
 *
 * @ORM\Table(name="ecomm_transport", indexes={@ORM\Index(name="idTransporteur", columns={"idTransporteur"})})
 * @ORM\Entity(repositoryClass="TMD\AppliBundle\Repository\EcommTransportRepository", readOnly=false)
 */
class EcommTransport
{
    /**
     * @var string
     *
     * @ORM\Column(name="codeTransport", type="string", length=255, nullable=false)
     */
    private $codetransport;

    /**
     * @var string
     *
     * @ORM\Column(name="libelleTransport", type="string", length=100, nullable=false)
     */
    private $libelletransport;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaires", type="string", length=80, nullable=false)
     */
    private $commentaires;

    /**
     * @var integer
     *
     * @ORM\Column(name="idTransport", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idtransport;

    /**
     * @var \TMD\AppliBundle\Entity\EcommTransporteurs
     *
     * @ORM\ManyToOne(targetEntity="TMD\AppliBundle\Entity\EcommTransporteurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTransporteur", referencedColumnName="idTransporteur", nullable=false)
     * })
     */
    private $idtransporteur;



    /**
     * @return string
     */
    public function getCodetransport()
    {
        return $this->codetransport;
    }

    /**
     * @param string $codetransport
     */
    public function setCodetransport($codetransport)
    {
        $this->codetransport = $codetransport;
    }

    /**
     * @return string
     */
        public function getLibelletransport()
    {
        return $this->libelletransport;
    }

    /**
     * @param string $libelletransport
     */
    public function setLibelletransport($libelletransport)
    {
        $this->libelletransport = $libelletransport;
    }

    /**
     * @return string
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * @param string $commentaires
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;
    }

    /**
     * @return EcommTransporteurs
     */
    public function getIdtransporteur()
    {
        return $this->idtransporteur;
    }

    /**
     * @param EcommTransporteurs $idtransporteur
     */
    public function setIdtransporteur($idtransporteur)
    {
        $this->idtransporteur = $idtransporteur;
    }

    /**
     * @return int
     */
    public function getIdtransport()
    {
        return $this->idtransport;
    }



//    public function addCompteTransport(EcommCompteTransport $transport)
//    {
//        $this->CompteTransports[] = $transport;
//        $transport->setIdtransport($this);
//        return $this;
//    }
//
//    public function removeCompteTransport(EcommCompteTransport $transport)
//    {
//        $this->CompteTransports->removeElement($transport);
//    }
//
//    public function getCompteTransports()
//    {
//        return $this->CompteTransports;
//    }


}


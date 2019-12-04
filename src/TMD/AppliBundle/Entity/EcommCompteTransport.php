<?php

namespace TMD\AppliBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

///**
// * EcommTransport
// *
// * @ORM\Table(name="ecomm_compte_transport", indexes={@ORM\Index(name="idtransport", columns={"idTransport"})} , indexes={@ORM\Index(name="idcompte", columns={"idcompte"})})
// * @ORM\Entity(repositoryClass="TMD\AppliBundle\Repository\EcommCompteTransportRepository", readOnly=false)
// */
class EcommCompteTransport
{


    /**
     * @var integer
     *
     * @ORM\Column(name="idcomptetransport", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcomptetransport;

    /**
     * @var \TMD\AppliBundle\Entity\EcommTransport
     *
     * @ORM\ManyToOne(targetEntity="TMD\AppliBundle\Entity\EcommTransport")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idtransport", referencedColumnName="idTransport", nullable=false)
     * })
     */
    private $idtransport;

    /**
     * @var \TMD\AppliBundle\Entity\EcommCompte
     *
     * @ORM\ManyToOne(targetEntity="TMD\AppliBundle\Entity\EcommCompte")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idcompte", referencedColumnName="idcompte", nullable=false)
     * })
     */
    private $idcompte;

    /**
     * @return EcommTransport
     */
    public function getIdtransport()
    {
        return $this->idtransport;
    }

    /**
     * @param EcommTransport $idtransport
     */
    public function setIdtransport($idtransport)
    {
        $this->idtransport = $idtransport;
    }

    /**
     * @return EcommCompte
     */
    public function getIdcompte()
    {
        return $this->idcompte;
    }

    /**
     * @param EcommCompte $idcompte
     */
    public function setIdcompte($idcompte)
    {
        $this->idcompte = $idcompte;
    }

    /**
     * @return int
     */
    public function getIdcomptetransport()
    {
        return $this->idcomptetransport;
    }





}


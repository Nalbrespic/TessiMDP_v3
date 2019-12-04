<?php

namespace TMD\AppliBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

///**
// * EcommTransport
// *
// * @ORM\Table(name="ecomm_AppliConfig")
// * @ORM\Entity
// */
class EcommAppliConfig
{


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAppliConfig;


    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length=100, nullable=true)
     */
    private $commentaire;

//    /**
//     * @var \TMD\AppliBundle\Entity\EcommCompteTransport
//     *
//     * @ORM\ManyToOne(targetEntity="TMD\AppliBundle\Entity\EcommCompteTransport")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="idcomptetransport", referencedColumnName="idcomptetransport", nullable=false)
//     * })
//     */
//    private $idtransportCompte;

//    /**
//     * @var \TMD\AppliBundle\Entity\EcommCompte
//     *
//     * @ORM\ManyToOne(targetEntity="TMD\AppliBundle\Entity\EcommCompte")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="idcompte", referencedColumnName="idcompte", nullable=false)
//     * })
//     */
//    private $idCompte;

    /**
     * @var \TMD\AppliBundle\Entity\EcommCompteTransport
     *
     * @ORM\ManyToOne(targetEntity="TMD\AppliBundle\Entity\EcommCompteTransport")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idcomptetransport", referencedColumnName="idcomptetransport", nullable=false)
     * })
     */
    private $idCompteTransport;

    /**
     * @return int
     */
    public function getIdAppliConfig()
    {
        return $this->idAppliConfig;
    }

    /**
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * @param string $commentaire
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    }

    /**
     * @return EcommCompteTransport
     */
    public function getIdCompteTransport()
    {
        return $this->idCompteTransport;
    }

    /**
     * @param EcommCompteTransport $idCompteTransport
     */
    public function setIdCompteTransport($idCompteTransport)
    {
        $this->idCompteTransport = $idCompteTransport;
    }




//    /**
//     * @return EcommCompteTransport
//     */
//    public function getIdtransportCompte()
//    {
//        return $this->idtransportCompte;
//    }
//
//    /**
//     * @param EcommCompteTransport $idtransportCompte
//     */
//    public function setIdtransportCompte($idtransportCompte)
//    {
//        $this->idtransportCompte = $idtransportCompte;
//    }









}


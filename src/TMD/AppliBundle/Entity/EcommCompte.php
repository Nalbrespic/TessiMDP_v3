<?php

namespace TMD\AppliBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

///**
// * EcommCompte
// *
// * @ORM\Table(name="ecomm_compte", indexes={@ORM\Index(name="idclient", columns={"idClient"})})
// * @ORM\Entity(repositoryClass="TMD\AppliBundle\Repository\EcommCompteRepository", readOnly=false)
// */
class EcommCompte
{

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=80, nullable=false)
     */
    private $libelle;

    /**
     * @var integer
     *
     * @ORM\Column(name="idcompte", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcompte;

    /**
     * @var \TMD\ProdBundle\Entity\Client
     *
     * @ORM\ManyToOne(targetEntity="TMD\ProdBundle\Entity\Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idclient", referencedColumnName="idClient", nullable=false)
     * })
     */
    private $idclient;

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    /**
     * @return \TMD\ProdBundle\Entity\Client
     */
    public function getIdclient()
    {
        return $this->idclient;
    }

    /**
     * @param \TMD\ProdBundle\Entity\Client $idclient
     */
    public function setIdclient($idclient)
    {
        $this->idclient = $idclient;
    }

    /**
     * @return int
     */
    public function getIdcompte()
    {
        return $this->idcompte;
    }

}

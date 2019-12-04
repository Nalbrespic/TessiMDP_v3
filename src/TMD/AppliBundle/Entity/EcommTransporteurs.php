<?php

namespace TMD\AppliBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EcommTransporteurs
 *
 * @ORM\Table(name="ecomm_transporteurs")
 * @ORM\Entity(repositoryClass="TMD\AppliBundle\Repository\EcommTransporteurRepository" , readOnly=false)
 */
class EcommTransporteurs
{
    /**
     * @var string
     *
     * @ORM\Column(name="codeTransporteur", type="string", length=15, nullable=false)
     */
    private $codetransport;

    /**
     * @var string
     *
     * @ORM\Column(name="libelleTransporteur", type="string", length=50, nullable=false)
     */
    private $libelletransporteur;

    /**
     * @var integer
     *
     * @ORM\Column(name="idTransporteur", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
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
     * @return int
     */
    public function getIdtransporteur()
    {
        return $this->idtransporteur;
    }


}


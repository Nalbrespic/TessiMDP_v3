<?php

namespace TMD\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use TMD\AppliBundle\Entity\EcommTransport;
use TMD\AppliBundle\Entity\EcommTransporteurs;
use Tms\Bundle\LogisticBundle\Entity\EcommTransporteur;

/**
 * TransporteursTarif
 *
 * @ORM\Table(name="transporteurs_tarif")
 * @ORM\Entity(repositoryClass="TMD\CoreBundle\Repository\TransporteursTarifRepository", readOnly=false)
 */
class TransporteursTarif
{

    /**
     * @var integer
     *
     * @ORM\Column(name="idTransportTarif", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTransportTarif;

    /**
     * @var \TMD\AppliBundle\Entity\EcommTransporteurs
     *
     * @ORM\ManyToOne(targetEntity="TMD\AppliBundle\Entity\EcommTransporteurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTransporteur", referencedColumnName="idTransporteur", nullable=false)
     * })
     */
    private $transporteur;

    /**
     * @var \TMD\AppliBundle\Entity\EcommTransport
     *
     * @ORM\ManyToOne(targetEntity="TMD\AppliBundle\Entity\EcommTransport")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idtransport", referencedColumnName="idTransport", nullable=false)
     * })
     */
    private $typeTransport;
    /**
     * @var TransporteursTranche
     * @ORM\ManyToOne(targetEntity="TMD\CoreBundle\Entity\TransporteursTranche")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTransporteursTranches", referencedColumnName="idTransporteursTranches", nullable=false)
     * })
     */
    private $idTransportTranches;
    /**
     * @var TransporteursZones
     *
     * @ORM\ManyToOne(targetEntity="TMD\CoreBundle\Entity\TransporteursZones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTransporteursZones", referencedColumnName="idTransporteursZones", nullable=false)
     * })
     */
    private $zone;
    /**
     * @var float
     *
     * @ORM\Column(name="tarif", type="float", length=10, precision=2)
     */
    private $tarif;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut_valide", type="date")
     */
    private $dateDebut;

    /**
     * @return \DateTime
     */
    public function getDateDebut(): \DateTime
    {
        return $this->dateDebut;
    }

    /**
     * @param \DateTime $dateDebut
     */
    public function setDateDebut(\DateTime $dateDebut)
    {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @param integer
     *
     * @ORM\Column(name="isValid", type="integer", length=1, nullable=true)
     */
    private $isValid;

    /**
     * @return mixed
     */
    public function getIsValid()
    {
        return $this->isValid;
    }

    /**
     * @param mixed $isValid
     */
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="commentaires", type="string", length=80, nullable=true)
     */
    private $commentaires;

    /**
     * @return int
     */
    public function getIdTransportTarif(): int
    {
        return $this->idTransportTarif;
    }



    /**
     * @return EcommTransporteurs
     */
    public function getTransporteur()
    {
        return $this->transporteur;
    }

    /**
     * @param EcommTransporteurs $transporteur
     */
    public function setTransporteur($transporteur)
    {
        $this->transporteur = $transporteur;
    }

    /**
     * @return EcommTransport
     */
    public function getTypeTransport()
    {
        return $this->typeTransport;
    }

    /**
     * @param EcommTransport $idTransport
     */
    public function setTypeTransport($idTransport)
    {
        $this->typeTransport = $idTransport;
    }

    /**
     * @return TransporteursTranche
     */
    public function getIdTransportTranches()
    {
        return $this->idTransportTranches;
    }

    /**
     * @param TransporteursTranche $idTransportTranches
     */
    public function setIdTransportTranches($idTransportTranches)
    {
        $this->idTransportTranches = $idTransportTranches;
    }

    /**
     * @return TransporteursZones
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * @param TransporteursZones $zone
     */
    public function setidZone($zone)
    {
        $this->zone = $zone;
    }

    /**
     * @return float
     */
    public function getTarif(): float
    {
        return $this->tarif;
    }

    /**
     * @param float $tarif
     */
    public function setTarif(float $tarif)
    {
        $this->tarif = $tarif;
    }


    /**
     * @return string
     */
    public function getCommentaires(): string
    {
        return $this->commentaires;
    }

    /**
     * @param string $commentaires
     */
    public function setCommentaires(string $commentaires)
    {
        $this->commentaires = $commentaires;
    }


}

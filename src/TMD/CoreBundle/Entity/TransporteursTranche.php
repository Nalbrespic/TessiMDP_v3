<?php

namespace TMD\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TransporteursTranche
 *
 * @ORM\Table(name="transporteurs_tranches")
 * @ORM\Entity(repositoryClass="TMD\CoreBundle\Repository\TransporteursTrancheRepository", readOnly=false)
 */
class TransporteursTranche
{

    /**
     * @var integer
     *
     * @ORM\Column(name="idTransporteursTranches", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTransportTranches;

    /**
     * @var integer
     *
     * @ORM\Column(name="idTransporteur", type="integer", length=6, nullable=false)
     */
    private $transporteur;

    /**
     * @var float
     *
     * @ORM\Column(name="poidsMax", type="float", length=10, precision=2, nullable=false)
     */
    private $poidsMax;

    /**
     * @var string
     *
     * @ORM\Column(name="libellé", type="string", length=25, nullable=false)
     */
    private $libelle;

    /**
     * @return int
     */
    public function getIdTransportTranches(): int
    {
        return $this->idTransportTranches;
    }

    /**
     * @param int $idTransportTranches
     */
    public function setIdTransportTranches(int $idTransportTranches)
    {
        $this->idTransportTranches = $idTransportTranches;
    }

    /**
     * @return int
     */
    public function getTransporteur(): int
    {
        return $this->transporteur;
    }

    /**
     * @param int $transporteur
     */
    public function setTransporteur(int $transporteur)
    {
        $this->transporteur = $transporteur;
    }

    /**
     * @return float
     */
    public function getPoidsMax(): float
    {
        return $this->poidsMax;
    }

    /**
     * @param float $poidsMax
     */
    public function setPoidsMax(float $poidsMax)
    {
        $this->poidsMax = $poidsMax;
    }

    /**
     * @return string
     */
    public function getLibelle(): string
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     */
    public function setLibelle(string $libelle)
    {
        $this->libelle = $libelle;
    }


}

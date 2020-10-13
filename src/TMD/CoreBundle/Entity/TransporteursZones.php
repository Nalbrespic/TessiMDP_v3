<?php

namespace TMD\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use http\Encoding\Stream\Inflate;

/**
 * TransporteursZones
 *
 * @ORM\Table(name="transporteurs_zones")
 * @ORM\Entity(repositoryClass="TMD\CoreBundle\Repository\TransporteursZonesRepository", readOnly=false)
 */
class TransporteursZones
{

    /**
     * @var integer
     *
     * @ORM\Column(name="idTransporteursZones", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTransporteursZones;

    /**
     * @var integer
     *
     * @ORM\Column(name="idTransport", type="integer", length=6)
     */
    private $transport;

    /**
     * @var string
     *
     * @ORM\Column(name="ISO", type="string", length=2)
     */
    private $pays;

    /**
     * @var string
     *
     * @ORM\Column(name="zone", type="string", length=50, nullable=false)
     */
    private $zone;

    /**
     * @return int
     */
    public function getIdTransporteursZones(): int
    {
        return $this->idTransporteursZones;
    }

    /**
     * @param int $idTransporteursZones
     */
    public function setIdTransporteursZones(int $idTransporteursZones)
    {
        $this->idTransporteursZones = $idTransporteursZones;
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
     * @return string
     */
    public function getPays(): string
    {
        return $this->pays;
    }

    /**
     * @param string $pays
     */
    public function setPays(string $pays)
    {
        $this->pays = $pays;
    }

    /**
     * @return string
     */
    public function getZone(): string
    {
        return $this->zone;
    }

    /**
     * @param string $zone
     */
    public function setZone(string $zone)
    {
        $this->zone = $zone;
    }
}
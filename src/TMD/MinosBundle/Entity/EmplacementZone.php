<?php

namespace TMD\MinosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmplacementZone
 *
 * @ORM\Table(name="EMPLACEMENT_ZONE")
 * @ORM\Entity
 */
class EmplacementZone
{
    /**
     * @var string
     *
     * @ORM\Column(name="ZONE_EMPL", type="string", length=15, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="EMPLACEMENT_ZONE_ZONE_EMPL_seq", allocationSize=1, initialValue=1)
     */
    private $zoneEmpl;

    /**
     * @var string
     *
     * @ORM\Column(name="LIB_ZONEMPL", type="string", length=80, nullable=true)
     */
    private $libZonempl;



    /**
     * Get zoneEmpl
     *
     * @return string
     */
    public function getZoneEmpl()
    {
        return $this->zoneEmpl;
    }

    /**
     * Set libZonempl
     *
     * @param string $libZonempl
     *
     * @return EmplacementZone
     */
    public function setLibZonempl($libZonempl)
    {
        $this->libZonempl = $libZonempl;

        return $this;
    }

    /**
     * Get libZonempl
     *
     * @return string
     */
    public function getLibZonempl()
    {
        return $this->libZonempl;
    }
}

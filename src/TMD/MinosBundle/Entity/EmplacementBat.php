<?php

namespace TMD\MinosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmplacementBat
 *
 * @ORM\Table(name="EMPLACEMENT_BAT")
 * @ORM\Entity
 */
class EmplacementBat
{
    /**
     * @var string
     *
     * @ORM\Column(name="BATIMENT_EMPL", type="string", length=15, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="EMPLACEMENT_BAT_BATIMENT_EMPL_", allocationSize=1, initialValue=1)
     */
    private $batimentEmpl;

    /**
     * @var string
     *
     * @ORM\Column(name="LIB_BATEMPL", type="string", length=80, nullable=true)
     */
    private $libBatempl;

    /**
     * @var float
     *
     * @ORM\Column(name="HYGRO_MIN_BATEMPL", type="float", precision=126, scale=0, nullable=true)
     */
    private $hygroMinBatempl;

    /**
     * @var float
     *
     * @ORM\Column(name="HYGRO_MAX_BATEMPL", type="float", precision=126, scale=0, nullable=true)
     */
    private $hygroMaxBatempl;

    /**
     * @var float
     *
     * @ORM\Column(name="TEMP_MIN_BATEMPL", type="float", precision=126, scale=0, nullable=true)
     */
    private $tempMinBatempl;

    /**
     * @var float
     *
     * @ORM\Column(name="TEMP_MAX_BATEMPL", type="float", precision=126, scale=0, nullable=true)
     */
    private $tempMaxBatempl;



    /**
     * Get batimentEmpl
     *
     * @return string
     */
    public function getBatimentEmpl()
    {
        return $this->batimentEmpl;
    }

    /**
     * Set libBatempl
     *
     * @param string $libBatempl
     *
     * @return EmplacementBat
     */
    public function setLibBatempl($libBatempl)
    {
        $this->libBatempl = $libBatempl;

        return $this;
    }

    /**
     * Get libBatempl
     *
     * @return string
     */
    public function getLibBatempl()
    {
        return $this->libBatempl;
    }

    /**
     * Set hygroMinBatempl
     *
     * @param float $hygroMinBatempl
     *
     * @return EmplacementBat
     */
    public function setHygroMinBatempl($hygroMinBatempl)
    {
        $this->hygroMinBatempl = $hygroMinBatempl;

        return $this;
    }

    /**
     * Get hygroMinBatempl
     *
     * @return float
     */
    public function getHygroMinBatempl()
    {
        return $this->hygroMinBatempl;
    }

    /**
     * Set hygroMaxBatempl
     *
     * @param float $hygroMaxBatempl
     *
     * @return EmplacementBat
     */
    public function setHygroMaxBatempl($hygroMaxBatempl)
    {
        $this->hygroMaxBatempl = $hygroMaxBatempl;

        return $this;
    }

    /**
     * Get hygroMaxBatempl
     *
     * @return float
     */
    public function getHygroMaxBatempl()
    {
        return $this->hygroMaxBatempl;
    }

    /**
     * Set tempMinBatempl
     *
     * @param float $tempMinBatempl
     *
     * @return EmplacementBat
     */
    public function setTempMinBatempl($tempMinBatempl)
    {
        $this->tempMinBatempl = $tempMinBatempl;

        return $this;
    }

    /**
     * Get tempMinBatempl
     *
     * @return float
     */
    public function getTempMinBatempl()
    {
        return $this->tempMinBatempl;
    }

    /**
     * Set tempMaxBatempl
     *
     * @param float $tempMaxBatempl
     *
     * @return EmplacementBat
     */
    public function setTempMaxBatempl($tempMaxBatempl)
    {
        $this->tempMaxBatempl = $tempMaxBatempl;

        return $this;
    }

    /**
     * Get tempMaxBatempl
     *
     * @return float
     */
    public function getTempMaxBatempl()
    {
        return $this->tempMaxBatempl;
    }
}

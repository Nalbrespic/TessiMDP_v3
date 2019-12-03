<?php

namespace TMD\MinosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UniteMesure
 *
 * @ORM\Table(name="UNITE_MESURE")
 * @ORM\Entity
 */
class UniteMesure
{
    /**
     * @var string
     *
     * @ORM\Column(name="UM_CODE", type="string", length=15, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="UNITE_MESURE_UM_CODE_seq", allocationSize=1, initialValue=1)
     */
    private $umCode;

    /**
     * @var string
     *
     * @ORM\Column(name="UM_DESIGN", type="string", length=50, nullable=true)
     */
    private $umDesign;

    /**
     * @var float
     *
     * @ORM\Column(name="UM_COEF_MULT", type="float", precision=126, scale=0, nullable=false)
     */
    private $umCoefMult;

    /**
     * @var string
     *
     * @ORM\Column(name="UM_TYPE", type="string", length=10, nullable=false)
     */
    private $umType;

    /**
     * @return string
     */
    public function getUmCode()
    {
        return $this->umCode;
    }

    /**
     * @param string $umCode
     */
    public function setUmCode($umCode)
    {
        $this->umCode = $umCode;
    }

    /**
     * @return string
     */
    public function getUmDesign()
    {
        return $this->umDesign;
    }

    /**
     * @param string $umDesign
     */
    public function setUmDesign($umDesign)
    {
        $this->umDesign = $umDesign;
    }

    /**
     * @return float
     */
    public function getUmCoefMult()
    {
        return $this->umCoefMult;
    }

    /**
     * @param float $umCoefMult
     */
    public function setUmCoefMult($umCoefMult)
    {
        $this->umCoefMult = $umCoefMult;
    }

    /**
     * @return string
     */
    public function getUmType()
    {
        return $this->umType;
    }

    /**
     * @param string $umType
     */
    public function setUmType($umType)
    {
        $this->umType = $umType;
    }


}


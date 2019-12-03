<?php

namespace TMD\MinosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmplacementType
 *
 * @ORM\Table(name="EMPLACEMENT_TYPE")
 * @ORM\Entity
 */
class EmplacementType
{
    /**
     * @var string
     *
     * @ORM\Column(name="EMT_ID", type="string", length=1, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="EMPLACEMENT_TYPE_EMT_ID_seq", allocationSize=1, initialValue=1)
     */
    private $emtId;

    /**
     * @var string
     *
     * @ORM\Column(name="EMT_CODE", type="string", length=25, nullable=false)
     */
    private $emtCode;

    /**
     * @var string
     *
     * @ORM\Column(name="EMT_LIBELLE", type="string", length=80, nullable=true)
     */
    private $emtLibelle;

    /**
     * @var integer
     *
     * @ORM\Column(name="EMT_PRIORITE", type="integer", nullable=false)
     */
    private $emtPriorite = '0';



    /**
     * Get emtId
     *
     * @return string
     */
    public function getEmtId()
    {
        return $this->emtId;
    }

    /**
     * Set emtCode
     *
     * @param string $emtCode
     *
     * @return EmplacementType
     */
    public function setEmtCode($emtCode)
    {
        $this->emtCode = $emtCode;

        return $this;
    }

    /**
     * Get emtCode
     *
     * @return string
     */
    public function getEmtCode()
    {
        return $this->emtCode;
    }

    /**
     * Set emtLibelle
     *
     * @param string $emtLibelle
     *
     * @return EmplacementType
     */
    public function setEmtLibelle($emtLibelle)
    {
        $this->emtLibelle = $emtLibelle;

        return $this;
    }

    /**
     * Get emtLibelle
     *
     * @return string
     */
    public function getEmtLibelle()
    {
        return $this->emtLibelle;
    }

    /**
     * Set emtPriorite
     *
     * @param integer $emtPriorite
     *
     * @return EmplacementType
     */
    public function setEmtPriorite($emtPriorite)
    {
        $this->emtPriorite = $emtPriorite;

        return $this;
    }

    /**
     * Get emtPriorite
     *
     * @return integer
     */
    public function getEmtPriorite()
    {
        return $this->emtPriorite;
    }
}

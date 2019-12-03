<?php

namespace TMD\MinosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MatStatut
 *
 * @ORM\Table(name="MAT_STATUT")
 * @ORM\Entity
 */
class MatStatut
{
    /**
     * @var integer
     *
     * @ORM\Column(name="MST_ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="MAT_STATUT_MST_ID_seq", allocationSize=1, initialValue=1)
     */
    private $mstId;

    /**
     * @var string
     *
     * @ORM\Column(name="MST_CODE", type="string", length=10, nullable=false)
     */
    private $mstCode;

    /**
     * @var string
     *
     * @ORM\Column(name="MST_LIB", type="string", length=50, nullable=true)
     */
    private $mstLib;

    /**
     * @var string
     *
     * @ORM\Column(name="MST_BLOQUE", type="string", length=1, nullable=false)
     */
    private $mstBloque = 'N';

    /**
     * @var string
     *
     * @ORM\Column(name="MST_MORT", type="string", length=1, nullable=true)
     */
    private $mstMort = 'N';



    /**
     * Get mstId
     *
     * @return integer
     */
    public function getMstId()
    {
        return $this->mstId;
    }

    /**
     * Set mstCode
     *
     * @param string $mstCode
     *
     * @return MatStatut
     */
    public function setMstCode($mstCode)
    {
        $this->mstCode = $mstCode;

        return $this;
    }

    /**
     * Get mstCode
     *
     * @return string
     */
    public function getMstCode()
    {
        return $this->mstCode;
    }

    /**
     * Set mstLib
     *
     * @param string $mstLib
     *
     * @return MatStatut
     */
    public function setMstLib($mstLib)
    {
        $this->mstLib = $mstLib;

        return $this;
    }

    /**
     * Get mstLib
     *
     * @return string
     */
    public function getMstLib()
    {
        return $this->mstLib;
    }

    /**
     * Set mstBloque
     *
     * @param string $mstBloque
     *
     * @return MatStatut
     */
    public function setMstBloque($mstBloque)
    {
        $this->mstBloque = $mstBloque;

        return $this;
    }

    /**
     * Get mstBloque
     *
     * @return string
     */
    public function getMstBloque()
    {
        return $this->mstBloque;
    }

    /**
     * Set mstMort
     *
     * @param string $mstMort
     *
     * @return MatStatut
     */
    public function setMstMort($mstMort)
    {
        $this->mstMort = $mstMort;

        return $this;
    }

    /**
     * Get mstMort
     *
     * @return string
     */
    public function getMstMort()
    {
        return $this->mstMort;
    }
}

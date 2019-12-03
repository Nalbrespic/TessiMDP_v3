<?php

namespace TMD\MinosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

//* @ORM\Table(name="LIEU_S", indexes={@ORM\Index(name="position2_fk", columns={"CODE_EXP"})})

/**
 * LieuS
 *
 * @ORM\Table(name="LIEU_S")
 * @ORM\Entity
 */
class LieuS
{
    /**
     * @var string
     *
     * @ORM\Column(name="CODE_LIEU_S", type="string", length=15, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="LIEU_S_CODE_LIEU_S_seq", allocationSize=1, initialValue=1)
     */
    private $codeLieuS;

    /**
     * @var string
     *
     * @ORM\Column(name="LIB_LIEU_S", type="string", length=40, nullable=true)
     */
    private $libLieuS;

    /**
     * @var integer
     *
     * @ORM\Column(name="PRIO_LIEU_S", type="integer", nullable=true)
     */
    private $prioLieuS = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="AVEC_STOCK_LIEU_S", type="string", length=1, nullable=true)
     */
    private $avecStockLieuS = 'O';

    /**
     * @var string
     *
     * @ORM\Column(name="AVEC_MACH_LIEU_S", type="string", length=1, nullable=true)
     */
    private $avecMachLieuS = 'O';

    /**
     * @return string
     */
    public function getCodeLieuS()
    {
        return $this->codeLieuS;
    }

    /**
     * @param string $codeLieuS
     */
    public function setCodeLieuS($codeLieuS)
    {
        $this->codeLieuS = $codeLieuS;
    }

    /**
     * @return string
     */
    public function getLibLieuS()
    {
        return $this->libLieuS;
    }

    /**
     * @param string $libLieuS
     */
    public function setLibLieuS($libLieuS)
    {
        $this->libLieuS = $libLieuS;
    }

    /**
     * @return int
     */
    public function getPrioLieuS()
    {
        return $this->prioLieuS;
    }

    /**
     * @param int $prioLieuS
     */
    public function setPrioLieuS($prioLieuS)
    {
        $this->prioLieuS = $prioLieuS;
    }

    /**
     * @return string
     */
    public function getAvecStockLieuS()
    {
        return $this->avecStockLieuS;
    }

    /**
     * @param string $avecStockLieuS
     */
    public function setAvecStockLieuS($avecStockLieuS)
    {
        $this->avecStockLieuS = $avecStockLieuS;
    }

    /**
     * @return string
     */
    public function getAvecMachLieuS()
    {
        return $this->avecMachLieuS;
    }

    /**
     * @param string $avecMachLieuS
     */
    public function setAvecMachLieuS($avecMachLieuS)
    {
        $this->avecMachLieuS = $avecMachLieuS;
    }

//    /**
//     * @var \Adrexp
//     *
//     * @ORM\ManyToOne(targetEntity="Adrexp")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="CODE_EXP", referencedColumnName="CODE_EXP")
//     * })
//     */
//    private $codeExp;



}


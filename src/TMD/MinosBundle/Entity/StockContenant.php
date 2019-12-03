<?php

namespace TMD\MinosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StockContenant
 *
 * @ORM\Table(name="STOCK_CONTENANT", uniqueConstraints={@ORM\UniqueConstraint(name="stock_contenant_unik2", columns={"SC_IDEMPL", "SC_ARB_IDNOEUD"})}, indexes={@ORM\Index(name="i_sc_noeud_r", columns={"SC_NOEUD_R"}), @ORM\Index(name="i_arb_idnoeud", columns={"SC_ARB_IDNOEUD"}), @ORM\Index(name="IDX_1A13E83A4B247613", columns={"SC_IDEMPL"})})
 * @ORM\Entity
 */
class StockContenant
{
    /**
     * @var integer
     *
     * @ORM\Column(name="SC_IDSTOCK", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="STOCK_CONTENANT_SC_IDSTOCK_seq", allocationSize=1, initialValue=1)
     */
    private $scIdstock;

    /**
     * @var string
     *
     * @ORM\Column(name="SC_NOEUD_R", type="string", nullable=true)
     */
    private $scNoeudR;

    /**
     * @var integer
     *
     * @ORM\Column(name="SC_ARB_IDNOEUD", type="integer", nullable=false)
     */
    private $scArbIdnoeud;

    /**
     * @var \TMD\MinosBundle\Entity\Emplacement
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\Emplacement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SC_IDEMPL", referencedColumnName="IDEMPL")
     * })
     */
    private $scempl;



    /**
     * Get scIdstock
     *
     * @return integer
     */
    public function getScIdstock()
    {
        return $this->scIdstock;
    }

    /**
     * Set scNoeudR
     *
     * @param string $scNoeudR
     *
     * @return StockContenant
     */
    public function setScNoeudR($scNoeudR)
    {
        $this->scNoeudR = $scNoeudR;

        return $this;
    }

    /**
     * Get scNoeudR
     *
     * @return string
     */
    public function getScNoeudR()
    {
        return $this->scNoeudR;
    }

    /**
     * Set scArbIdnoeud
     *
     * @param integer $scArbIdnoeud
     *
     * @return StockContenant
     */
    public function setScArbIdnoeud($scArbIdnoeud)
    {
        $this->scArbIdnoeud = $scArbIdnoeud;

        return $this;
    }

    /**
     * Get scArbIdnoeud
     *
     * @return integer
     */
    public function getScArbIdnoeud()
    {
        return $this->scArbIdnoeud;
    }

    /**
     * Set scempl
     *
     * @param \TMD\MinosBundle\Entity\Emplacement $scempl
     *
     * @return StockContenant
     */
    public function setScempl(Emplacement $scempl = null)
    {
        $this->scempl = $scempl;

        return $this;
    }

    /**
     * Get scempl
     *
     * @return \TMD\MinosBundle\Entity\Emplacement
     */
    public function getScempl()
    {
        return $this->scempl;
    }
}

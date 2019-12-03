<?php

namespace TMD\MinosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stock
 *
 * @ORM\Table(name="STOCK", indexes={@ORM\Index(name="lotissement2_fk", columns={"LOT_ID"}), @ORM\Index(name="i_stc_noeud_r", columns={"STC_NOEUD_R"}), @ORM\Index(name="est_contenu_dans_fk", columns={"IDMAT"}), @ORM\Index(name="appartient_a_fk", columns={"IDEMPL"})})
 * @ORM\Entity
 */
class Stock
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IDSTOCK", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="STOCK_IDSTOCK_seq", allocationSize=1, initialValue=1)
     */
    private $idstock;

    /**
     * @var float
     *
     * @ORM\Column(name="QTE_STOCK", type="float", precision=126, scale=0, nullable=false)
     */
    private $qteStock;

    /**
     * @var string
     *
     * @ORM\Column(name="LIB_STOCK", type="string", length=40, nullable=true)
     */
    private $libStock;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE_MVT_STOCK", type="date", nullable=false)
     */
    private $dateMvtStock;

    /**
     * @var string
     *
     * @ORM\Column(name="STC_NOEUD_R", type="string", nullable=true)
     */
    private $stcNoeudR;

    /**
     * @var integer
     *
     * @ORM\Column(name="LOT_ID", type="integer", nullable=true)
     */
    private $lotId;

    /**
     * @var \TMD\MinosBundle\Entity\Emplacement
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\Emplacement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IDEMPL", referencedColumnName="IDEMPL")
     * })
     */
    private $idempl;

    /**
     * @var \TMD\MinosBundle\Entity\Materiel
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\Materiel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IDMAT", referencedColumnName="IDMAT")
     * })
     */
    private $idmat;



    /**
     * Get idstock
     *
     * @return integer
     */
    public function getIdstock()
    {
        return $this->idstock;
    }

    /**
     * Set qteStock
     *
     * @param float $qteStock
     *
     * @return Stock
     */
    public function setQteStock($qteStock)
    {
        $this->qteStock = $qteStock;

        return $this;
    }

    /**
     * Get qteStock
     *
     * @return float
     */
    public function getQteStock()
    {
        return $this->qteStock;
    }

    /**
     * Set libStock
     *
     * @param string $libStock
     *
     * @return Stock
     */
    public function setLibStock($libStock)
    {
        $this->libStock = $libStock;

        return $this;
    }

    /**
     * Get libStock
     *
     * @return string
     */
    public function getLibStock()
    {
        return $this->libStock;
    }

    /**
     * Set dateMvtStock
     *
     * @param \DateTime $dateMvtStock
     *
     * @return Stock
     */
    public function setDateMvtStock($dateMvtStock)
    {
        $this->dateMvtStock = $dateMvtStock;

        return $this;
    }

    /**
     * Get dateMvtStock
     *
     * @return \DateTime
     */
    public function getDateMvtStock()
    {
        return $this->dateMvtStock;
    }

    /**
     * Set stcNoeudR
     *
     * @param string $stcNoeudR
     *
     * @return Stock
     */
    public function setStcNoeudR($stcNoeudR)
    {
        $this->stcNoeudR = $stcNoeudR;

        return $this;
    }

    /**
     * Get stcNoeudR
     *
     * @return string
     */
    public function getStcNoeudR()
    {
        return $this->stcNoeudR;
    }

    /**
     * Set lotId
     *
     * @param integer $lotId
     *
     * @return Stock
     */
    public function setLotId($lotId)
    {
        $this->lotId = $lotId;

        return $this;
    }

    /**
     * Get lotId
     *
     * @return integer
     */
    public function getLotId()
    {
        return $this->lotId;
    }

    /**
     * Set idempl
     *
     * @param \TMD\MinosBundle\Entity\Emplacement $idempl
     *
     * @return Stock
     */
    public function setIdempl(Emplacement $idempl = null)
    {
        $this->idempl = $idempl;

        return $this;
    }

    /**
     * Get idempl
     *
     * @return \TMD\MinosBundle\Entity\Emplacement
     */
    public function getIdempl()
    {
        return $this->idempl;
    }

    /**
     * Set idmat
     *
     * @param \TMD\MinosBundle\Entity\Materiel $idmat
     *
     * @return Stock
     */
    public function setIdmat(Materiel $idmat = null)
    {
        $this->idmat = $idmat;

        return $this;
    }

    /**
     * Get idmat
     *
     * @return \TMD\MinosBundle\Entity\Materiel
     */
    public function getIdmat()
    {
        return $this->idmat;
    }
}

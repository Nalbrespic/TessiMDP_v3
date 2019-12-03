<?php

namespace TMD\MinosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StockFacturation
 *
 * @ORM\Table(name="STOCK_FACTURATION", indexes={@ORM\Index(name="stockage_fk", columns={"IDCLI"})})
 * @ORM\Entity
 */
class StockFacturation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="STF_ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="STOCK_FACTURATION_STF_ID_seq", allocationSize=1, initialValue=1)
     */
    private $stfId;

    /**
     * @var integer
     *
     * @ORM\Column(name="STF_NBRE_CONTENANT", type="integer", nullable=false)
     */
    private $stfNbreContenant;

    /**
     * @var integer
     *
     * @ORM\Column(name="STF_NBRE_EMPLACEMENT", type="integer", nullable=false)
     */
    private $stfNbreEmplacement;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="STF_DATE_STOCK", type="date", nullable=false)
     */
    private $stfDateStock;

    /**
     * @var \TMD\MinosBundle\Entity\Client
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IDCLI", referencedColumnName="IDCLI")
     * })
     */
    private $idcli;



    /**
     * Get stfId
     *
     * @return integer
     */
    public function getStfId()
    {
        return $this->stfId;
    }

    /**
     * Set stfNbreContenant
     *
     * @param integer $stfNbreContenant
     *
     * @return StockFacturation
     */
    public function setStfNbreContenant($stfNbreContenant)
    {
        $this->stfNbreContenant = $stfNbreContenant;

        return $this;
    }

    /**
     * Get stfNbreContenant
     *
     * @return integer
     */
    public function getStfNbreContenant()
    {
        return $this->stfNbreContenant;
    }

    /**
     * Set stfNbreEmplacement
     *
     * @param integer $stfNbreEmplacement
     *
     * @return StockFacturation
     */
    public function setStfNbreEmplacement($stfNbreEmplacement)
    {
        $this->stfNbreEmplacement = $stfNbreEmplacement;

        return $this;
    }

    /**
     * Get stfNbreEmplacement
     *
     * @return integer
     */
    public function getStfNbreEmplacement()
    {
        return $this->stfNbreEmplacement;
    }

    /**
     * Set stfDateStock
     *
     * @param \DateTime $stfDateStock
     *
     * @return StockFacturation
     */
    public function setStfDateStock($stfDateStock)
    {
        $this->stfDateStock = $stfDateStock;

        return $this;
    }

    /**
     * Get stfDateStock
     *
     * @return \DateTime
     */
    public function getStfDateStock()
    {
        return $this->stfDateStock;
    }

    /**
     * Set idcli
     *
     * @param \TMD\MinosBundle\Entity\Client $idcli
     *
     * @return StockFacturation
     */
    public function setIdcli(Client $idcli = null)
    {
        $this->idcli = $idcli;

        return $this;
    }

    /**
     * Get idcli
     *
     * @return \TMD\MinosBundle\Entity\Client
     */
    public function getIdcli()
    {
        return $this->idcli;
    }
}

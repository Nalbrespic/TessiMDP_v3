<?php

namespace TMD\MinosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MaterielEan
 *
 * @ORM\Table(name="MATERIEL_EAN", indexes={@ORM\Index(name="IDX_7F1A7EAE7A91F775", columns={"IDMAT"})})
 * @ORM\Entity
 */
class MaterielEan
{
    /**
     * @var string
     *
     * @ORM\Column(name="MAT_EAN_CODE", type="string", length=30, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $matEanCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="MAT_EAN_NUMLIGN", type="integer", nullable=false)
     */
    private $matEanNumlign;

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_EAN_SYMBOL", type="string", length=6, nullable=false)
     */
    private $matEanSymbol;

    /**
     * @var \TMD\MinosBundle\Entity\Materiel
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Materiel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IDMAT", referencedColumnName="IDMAT")
     * })
     */
    private $idmat;



    /**
     * Set matEanCode
     *
     * @param string $matEanCode
     *
     * @return MaterielEan
     */
    public function setMatEanCode($matEanCode)
    {
        $this->matEanCode = $matEanCode;

        return $this;
    }

    /**
     * Get matEanCode
     *
     * @return string
     */
    public function getMatEanCode()
    {
        return $this->matEanCode;
    }

    /**
     * Set matEanNumlign
     *
     * @param integer $matEanNumlign
     *
     * @return MaterielEan
     */
    public function setMatEanNumlign($matEanNumlign)
    {
        $this->matEanNumlign = $matEanNumlign;

        return $this;
    }

    /**
     * Get matEanNumlign
     *
     * @return integer
     */
    public function getMatEanNumlign()
    {
        return $this->matEanNumlign;
    }

    /**
     * Set matEanSymbol
     *
     * @param string $matEanSymbol
     *
     * @return MaterielEan
     */
    public function setMatEanSymbol($matEanSymbol)
    {
        $this->matEanSymbol = $matEanSymbol;

        return $this;
    }

    /**
     * Get matEanSymbol
     *
     * @return string
     */
    public function getMatEanSymbol()
    {
        return $this->matEanSymbol;
    }

    /**
     * Set idmat
     *
     * @param \TMD\MinosBundle\Entity\Materiel $idmat
     *
     * @return MaterielEan
     */
    public function setIdmat(\TMD\MinosBundle\Entity\Materiel $idmat)
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

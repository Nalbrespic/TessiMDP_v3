<?php

namespace TMD\MinosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Emplacement
 *
 * @ORM\Table(name="EMPLACEMENT", uniqueConstraints={@ORM\UniqueConstraint(name="pk_code_empl", columns={"CODE_EMPL"})}, indexes={@ORM\Index(name="situe_dans1_fk", columns={"BATIMENT_EMPL"}), @ORM\Index(name="regroupe_fk", columns={"ZONE_EMPL"}), @ORM\Index(name="est_entrepose_sur_fk", columns={"CODE_LIEU_S"}), @ORM\Index(name="position_fk", columns={"ALLEE_EMPL"}), @ORM\Index(name="IDX_4DCCF71E3DB12639", columns={"EMT_ID"})})
 * @ORM\Entity
 */
class Emplacement
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IDEMPL", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="EMPLACEMENT_IDEMPL_seq", allocationSize=1, initialValue=1)
     */
    private $idempl;

    /**
     * @var string
     *
     * @ORM\Column(name="TRAVEE_EMPL", type="string", length=5, nullable=true)
     */
    private $traveeEmpl;

    /**
     * @var string
     *
     * @ORM\Column(name="HAUTEUR_EMPL", type="string", length=5, nullable=false)
     */
    private $hauteurEmpl = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="PROFOND_EMPL", type="string", length=5, nullable=true)
     */
    private $profondEmpl;

    /**
     * @var string
     *
     * @ORM\Column(name="LIB_EMPL", type="string", length=40, nullable=true)
     */
    private $libEmpl;

    /**
     * @var string
     *
     * @ORM\Column(name="CODE_EMPL", type="string", length=15, nullable=false)
     */
    private $codeEmpl;

    /**
     * @var string
     *
     * @ORM\Column(name="STATUT_EMPL", type="string", length=1, nullable=true)
     */
    private $statutEmpl = 'L';

    /**
     * @var string
     *
     * @ORM\Column(name="MODE_EMPL", type="string", length=1, nullable=true)
     */
    private $modeEmpl;

    /**
     * @var string
     *
     * @ORM\Column(name="TMP_BACK_CODE_EMPL", type="string", length=30, nullable=true)
     */
    private $tmpBackCodeEmpl;

    /**
     * @var string
     *
     * @ORM\Column(name="CASIER_EMPL", type="string", length=5, nullable=false)
     */
    private $casierEmpl = '1';

    /**
     * @var float
     *
     * @ORM\Column(name="DIM_LONG_EMPL", type="float", precision=126, scale=0, nullable=true)
     */
    private $dimLongEmpl;

    /**
     * @var float
     *
     * @ORM\Column(name="DIM_LARG_EMPL", type="float", precision=126, scale=0, nullable=true)
     */
    private $dimLargEmpl;

    /**
     * @var float
     *
     * @ORM\Column(name="DIM_HAUT_EMPL", type="float", precision=126, scale=0, nullable=true)
     */
    private $dimHautEmpl;

    /**
     * @var \TMD\MinosBundle\Entity\EmplacementType
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\EmplacementType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="EMT_ID", referencedColumnName="EMT_ID")
     * })
     */
    private $emt;

    /**
     * @var \TMD\MinosBundle\Entity\LieuS
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\LieuS")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CODE_LIEU_S", referencedColumnName="CODE_LIEU_S")
     * })
     */
    private $codeLieuS;

    /**
     * @var \TMD\MinosBundle\Entity\EmplacementZone
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\EmplacementZone")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ZONE_EMPL", referencedColumnName="ZONE_EMPL")
     * })
     */
    private $zoneEmpl;

    /**
     * @var \TMD\MinosBundle\Entity\EmplacementBat
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\EmplacementBat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="BATIMENT_EMPL", referencedColumnName="BATIMENT_EMPL")
     * })
     */
    private $batimentEmpl;

    /**
     * @var \TMD\MinosBundle\Entity\EmplAllee
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\EmplAllee")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ALLEE_EMPL", referencedColumnName="ALLEE_EMPL")
     * })
     */
    private $alleeEmpl;



    /**
     * Get idempl
     *
     * @return integer
     */
    public function getIdempl()
    {
        return $this->idempl;
    }

    /**
     * Set traveeEmpl
     *
     * @param string $traveeEmpl
     *
     * @return Emplacement
     */
    public function setTraveeEmpl($traveeEmpl)
    {
        $this->traveeEmpl = $traveeEmpl;

        return $this;
    }

    /**
     * Get traveeEmpl
     *
     * @return string
     */
    public function getTraveeEmpl()
    {
        return $this->traveeEmpl;
    }

    /**
     * Set hauteurEmpl
     *
     * @param string $hauteurEmpl
     *
     * @return Emplacement
     */
    public function setHauteurEmpl($hauteurEmpl)
    {
        $this->hauteurEmpl = $hauteurEmpl;

        return $this;
    }

    /**
     * Get hauteurEmpl
     *
     * @return string
     */
    public function getHauteurEmpl()
    {
        return $this->hauteurEmpl;
    }

    /**
     * Set profondEmpl
     *
     * @param string $profondEmpl
     *
     * @return Emplacement
     */
    public function setProfondEmpl($profondEmpl)
    {
        $this->profondEmpl = $profondEmpl;

        return $this;
    }

    /**
     * Get profondEmpl
     *
     * @return string
     */
    public function getProfondEmpl()
    {
        return $this->profondEmpl;
    }

    /**
     * Set libEmpl
     *
     * @param string $libEmpl
     *
     * @return Emplacement
     */
    public function setLibEmpl($libEmpl)
    {
        $this->libEmpl = $libEmpl;

        return $this;
    }

    /**
     * Get libEmpl
     *
     * @return string
     */
    public function getLibEmpl()
    {
        return $this->libEmpl;
    }

    /**
     * Set codeEmpl
     *
     * @param string $codeEmpl
     *
     * @return Emplacement
     */
    public function setCodeEmpl($codeEmpl)
    {
        $this->codeEmpl = $codeEmpl;

        return $this;
    }

    /**
     * Get codeEmpl
     *
     * @return string
     */
    public function getCodeEmpl()
    {
        return $this->codeEmpl;
    }

    /**
     * Set statutEmpl
     *
     * @param string $statutEmpl
     *
     * @return Emplacement
     */
    public function setStatutEmpl($statutEmpl)
    {
        $this->statutEmpl = $statutEmpl;

        return $this;
    }

    /**
     * Get statutEmpl
     *
     * @return string
     */
    public function getStatutEmpl()
    {
        return $this->statutEmpl;
    }

    /**
     * Set modeEmpl
     *
     * @param string $modeEmpl
     *
     * @return Emplacement
     */
    public function setModeEmpl($modeEmpl)
    {
        $this->modeEmpl = $modeEmpl;

        return $this;
    }

    /**
     * Get modeEmpl
     *
     * @return string
     */
    public function getModeEmpl()
    {
        return $this->modeEmpl;
    }

    /**
     * Set tmpBackCodeEmpl
     *
     * @param string $tmpBackCodeEmpl
     *
     * @return Emplacement
     */
    public function setTmpBackCodeEmpl($tmpBackCodeEmpl)
    {
        $this->tmpBackCodeEmpl = $tmpBackCodeEmpl;

        return $this;
    }

    /**
     * Get tmpBackCodeEmpl
     *
     * @return string
     */
    public function getTmpBackCodeEmpl()
    {
        return $this->tmpBackCodeEmpl;
    }

    /**
     * Set casierEmpl
     *
     * @param string $casierEmpl
     *
     * @return Emplacement
     */
    public function setCasierEmpl($casierEmpl)
    {
        $this->casierEmpl = $casierEmpl;

        return $this;
    }

    /**
     * Get casierEmpl
     *
     * @return string
     */
    public function getCasierEmpl()
    {
        return $this->casierEmpl;
    }

    /**
     * Set dimLongEmpl
     *
     * @param float $dimLongEmpl
     *
     * @return Emplacement
     */
    public function setDimLongEmpl($dimLongEmpl)
    {
        $this->dimLongEmpl = $dimLongEmpl;

        return $this;
    }

    /**
     * Get dimLongEmpl
     *
     * @return float
     */
    public function getDimLongEmpl()
    {
        return $this->dimLongEmpl;
    }

    /**
     * Set dimLargEmpl
     *
     * @param float $dimLargEmpl
     *
     * @return Emplacement
     */
    public function setDimLargEmpl($dimLargEmpl)
    {
        $this->dimLargEmpl = $dimLargEmpl;

        return $this;
    }

    /**
     * Get dimLargEmpl
     *
     * @return float
     */
    public function getDimLargEmpl()
    {
        return $this->dimLargEmpl;
    }

    /**
     * Set dimHautEmpl
     *
     * @param float $dimHautEmpl
     *
     * @return Emplacement
     */
    public function setDimHautEmpl($dimHautEmpl)
    {
        $this->dimHautEmpl = $dimHautEmpl;

        return $this;
    }

    /**
     * Get dimHautEmpl
     *
     * @return float
     */
    public function getDimHautEmpl()
    {
        return $this->dimHautEmpl;
    }

    /**
     * Set emt
     *
     * @param \TMD\MinosBundle\Entity\EmplacementType $emt
     *
     * @return Emplacement
     */
    public function setEmt(\TMD\MinosBundle\Entity\EmplacementType $emt = null)
    {
        $this->emt = $emt;

        return $this;
    }

    /**
     * Get emt
     *
     * @return \TMD\MinosBundle\Entity\EmplacementType
     */
    public function getEmt()
    {
        return $this->emt;
    }

    /**
     * Set codeLieuS
     *
     * @param \TMD\MinosBundle\Entity\LieuS $codeLieuS
     *
     * @return Emplacement
     */
    public function setCodeLieuS(\TMD\MinosBundle\Entity\LieuS $codeLieuS = null)
    {
        $this->codeLieuS = $codeLieuS;

        return $this;
    }

    /**
     * Get codeLieuS
     *
     * @return \TMD\MinosBundle\Entity\LieuS
     */
    public function getCodeLieuS()
    {
        return $this->codeLieuS;
    }

    /**
     * Set zoneEmpl
     *
     * @param \TMD\MinosBundle\Entity\EmplacementZone $zoneEmpl
     *
     * @return Emplacement
     */
    public function setZoneEmpl(\TMD\MinosBundle\Entity\EmplacementZone $zoneEmpl = null)
    {
        $this->zoneEmpl = $zoneEmpl;

        return $this;
    }

    /**
     * Get zoneEmpl
     *
     * @return \TMD\MinosBundle\Entity\EmplacementZone
     */
    public function getZoneEmpl()
    {
        return $this->zoneEmpl;
    }

    /**
     * Set batimentEmpl
     *
     * @param \TMD\MinosBundle\Entity\EmplacementBat $batimentEmpl
     *
     * @return Emplacement
     */
    public function setBatimentEmpl(\TMD\MinosBundle\Entity\EmplacementBat $batimentEmpl = null)
    {
        $this->batimentEmpl = $batimentEmpl;

        return $this;
    }

    /**
     * Get batimentEmpl
     *
     * @return \TMD\MinosBundle\Entity\EmplacementBat
     */
    public function getBatimentEmpl()
    {
        return $this->batimentEmpl;
    }

    /**
     * Set alleeEmpl
     *
     * @param \TMD\MinosBundle\Entity\EmplAllee $alleeEmpl
     *
     * @return Emplacement
     */
    public function setAlleeEmpl(\TMD\MinosBundle\Entity\EmplAllee $alleeEmpl = null)
    {
        $this->alleeEmpl = $alleeEmpl;

        return $this;
    }

    /**
     * Get alleeEmpl
     *
     * @return \TMD\MinosBundle\Entity\EmplAllee
     */
    public function getAlleeEmpl()
    {
        return $this->alleeEmpl;
    }
}

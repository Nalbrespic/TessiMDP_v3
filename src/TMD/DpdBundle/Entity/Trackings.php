<?php

namespace TMD\DpdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TMD\ProdBundle\Entity\EcommLignes;

/**
 * Trackings
 *
 * @ORM\Table(name="trackings", uniqueConstraints={@ORM\UniqueConstraint(name="Exapass", columns={"numCompteExp", "numTracking"}), @ORM\UniqueConstraint(name="numBL", columns={"numBL"})}, indexes={@ORM\Index(name="numLigne", columns={"numLigne"}), @ORM\Index(name="numTrackingBar", columns={"numTrackingBar"}), @ORM\Index(name="numCompteExp", columns={"numCompteExp"})})
 * @ORM\Entity(repositoryClass="TMD\DpdBundle\Repository\TrackingsRepository", readOnly=true)
 */
class Trackings
{
    /**
     * @var integer
     *
     * @ORM\Column(name="numLigne", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $numligne;

    /**
     * @var integer
     *
     * @ORM\Column(name="idClient", type="smallint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $idclient;

    /**
     * @var integer
     *
     * @ORM\Column(name="numCompteExp", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $numcompteexp;

    /**
     * @var integer
     *
     * @ORM\Column(name="numPlage", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $numplage;

    /**
     * @var integer
     *
     * @ORM\Column(name="numTracking", type="bigint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $numtracking;

    /**
     * @var integer
     *
     * @ORM\Column(name="numTrackingBar", type="bigint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $numtrackingbar;

    /**
     * @var integer
     *
     * @ORM\Column(name="numConsolidation", type="bigint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $numconsolidation;

    /**
     * @var string
     *
     * @ORM\Column(name="AC", type="string", length=3, precision=0, scale=0, nullable=false, unique=false)
     */
    private $ac;

    /**
     * @var string
     *
     * @ORM\Column(name="TOUR", type="string", length=3, precision=0, scale=0, nullable=false, unique=false)
     */
    private $tour;

    /**
     * @var string
     *
     * @ORM\Column(name="LPFX", type="string", length=3, precision=0, scale=0, nullable=false, unique=false)
     */
    private $lpfx;

    /**
     * @var string
     *
     * @ORM\Column(name="SCONSOLID", type="string", length=3, precision=0, scale=0, nullable=false, unique=false)
     */
    private $sconsolid;

    /**
     * @var boolean
     *
     * @ORM\Column(name="codeService", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $codeservice;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAttrib", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $dateattrib;

    /**
     * @var string
     *
     * @ORM\Column(name="numBL", type="string", length=30, nullable=false)
     */
    private $numbl;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Xfer", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $xfer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_Xfer", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $dateXfer;

    /**
     * @var integer
     *
     * @ORM\Column(name="statut", type="smallint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $statut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_statut", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $dateStatut;

    /**
     * @var string
     *
     * @ORM\Column(name="URLtrace", type="string", length=80, precision=0, scale=0, nullable=false, unique=false)
     */
    private $urltrace;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numero;

    /**
     * @return int
     */
    public function getNumligne()
    {
        return $this->numligne;
    }

    /**
     * @param int $numligne
     */
    public function setNumligne($numligne)
    {
        $this->numligne = $numligne;
    }

    /**
     * @return int
     */
    public function getIdclient()
    {
        return $this->idclient;
    }

    /**
     * @param int $idclient
     */
    public function setIdclient($idclient)
    {
        $this->idclient = $idclient;
    }

    /**
     * @return int
     */
    public function getNumcompteexp()
    {
        return $this->numcompteexp;
    }

    /**
     * @param int $numcompteexp
     */
    public function setNumcompteexp($numcompteexp)
    {
        $this->numcompteexp = $numcompteexp;
    }

    /**
     * @return int
     */
    public function getNumplage()
    {
        return $this->numplage;
    }

    /**
     * @param int $numplage
     */
    public function setNumplage($numplage)
    {
        $this->numplage = $numplage;
    }

    /**
     * @return int
     */
    public function getNumtracking()
    {
        return $this->numtracking;
    }

    /**
     * @param int $numtracking
     */
    public function setNumtracking($numtracking)
    {
        $this->numtracking = $numtracking;
    }

    /**
     * @return int
     */
    public function getNumtrackingbar()
    {
        return $this->numtrackingbar;
    }

    /**
     * @param int $numtrackingbar
     */
    public function setNumtrackingbar($numtrackingbar)
    {
        $this->numtrackingbar = $numtrackingbar;
    }

    /**
     * @return int
     */
    public function getNumconsolidation()
    {
        return $this->numconsolidation;
    }

    /**
     * @param int $numconsolidation
     */
    public function setNumconsolidation($numconsolidation)
    {
        $this->numconsolidation = $numconsolidation;
    }

    /**
     * @return string
     */
    public function getAc()
    {
        return $this->ac;
    }

    /**
     * @param string $ac
     */
    public function setAc($ac)
    {
        $this->ac = $ac;
    }

    /**
     * @return string
     */
    public function getTour()
    {
        return $this->tour;
    }

    /**
     * @param string $tour
     */
    public function setTour($tour)
    {
        $this->tour = $tour;
    }

    /**
     * @return string
     */
    public function getLpfx()
    {
        return $this->lpfx;
    }

    /**
     * @param string $lpfx
     */
    public function setLpfx($lpfx)
    {
        $this->lpfx = $lpfx;
    }

    /**
     * @return string
     */
    public function getSconsolid()
    {
        return $this->sconsolid;
    }

    /**
     * @param string $sconsolid
     */
    public function setSconsolid($sconsolid)
    {
        $this->sconsolid = $sconsolid;
    }

    /**
     * @return bool
     */
    public function isCodeservice()
    {
        return $this->codeservice;
    }

    /**
     * @param bool $codeservice
     */
    public function setCodeservice($codeservice)
    {
        $this->codeservice = $codeservice;
    }

    /**
     * @return \DateTime
     */
    public function getDateattrib()
    {
        return $this->dateattrib;
    }

    /**
     * @param \DateTime $dateattrib
     */
    public function setDateattrib($dateattrib)
    {
        $this->dateattrib = $dateattrib;
    }

    /**
     * @return EcommLignes
     */
    public function getNumbl()
    {
        return $this->numbl;
    }

    /**
     * @param EcommLignes $numbl
     */
    public function setNumbl(EcommLignes $numbl)
    {
        $this->numbl = $numbl;
    }

    /**
     * @return bool
     */
    public function isXfer()
    {
        return $this->xfer;
    }

    /**
     * @param bool $xfer
     */
    public function setXfer($xfer)
    {
        $this->xfer = $xfer;
    }

    /**
     * @return \DateTime
     */
    public function getDateXfer()
    {
        return $this->dateXfer;
    }

    /**
     * @param \DateTime $dateXfer
     */
    public function setDateXfer($dateXfer)
    {
        $this->dateXfer = $dateXfer;
    }

    /**
     * @return int
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param int $statut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

    /**
     * @return \DateTime
     */
    public function getDateStatut()
    {
        return $this->dateStatut;
    }

    /**
     * @param \DateTime $dateStatut
     */
    public function setDateStatut($dateStatut)
    {
        $this->dateStatut = $dateStatut;
    }

    /**
     * @return string
     */
    public function getUrltrace()
    {
        return $this->urltrace;
    }

    /**
     * @param string $urltrace
     */
    public function setUrltrace($urltrace)
    {
        $this->urltrace = $urltrace;
    }

    /**
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param int $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }




}


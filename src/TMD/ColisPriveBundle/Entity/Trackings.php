<?php

namespace TMD\ColisPriveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trackings
 *
 * @ORM\Table(name="trackings", uniqueConstraints={@ORM\UniqueConstraint(name="Exapass", columns={"numCompteExp", "numColis"})}, indexes={@ORM\Index(name="numLigne", columns={"numLigne"}), @ORM\Index(name="numColis", columns={"numColis"}), @ORM\Index(name="ncolis", columns={"ncolis"})})
 * @ORM\Entity(repositoryClass="TMD\ColisPriveBundle\Repository\TrackingsRepository", readOnly=true)
 */
class Trackings
{
    /**
     * @var integer
     *
     * @ORM\Column(name="numLigne", type="integer", nullable=false)
     */
    private $numligne;

    /**
     * @var integer
     *
     * @ORM\Column(name="idClient", type="smallint", nullable=false)
     */
    private $idclient = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="numCompteExp", type="string", length=6, nullable=false)
     */
    private $numcompteexp;

    /**
     * @var integer
     *
     * @ORM\Column(name="numPlage", type="integer", nullable=false)
     */
    private $numplage;

    /**
     * @var string
     *
     * @ORM\Column(name="numColis", type="string", length=30, nullable=false)
     */
    private $numcolis;

    /**
     * @var string
     *
     * @ORM\Column(name="ncolis", type="string", length=10, nullable=false)
     */
    private $ncolis;

    /**
     * @var string
     *
     * @ORM\Column(name="numTracking", type="string", length=30, nullable=false)
     */
    private $numtracking;

    /**
     * @var string
     *
     * @ORM\Column(name="numTrackingPCH", type="string", length=30, nullable=false)
     */
    private $numtrackingpch;

    /**
     * @var string
     *
     * @ORM\Column(name="TypeProduit", type="string", length=1, nullable=false)
     */
    private $typeproduit = '';

    /**
     * @var string
     *
     * @ORM\Column(name="CENTRE", type="string", length=2, nullable=false)
     */
    private $centre = '';

    /**
     * @var string
     *
     * @ORM\Column(name="TOURNEE", type="string", length=4, nullable=false)
     */
    private $tournee = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAttrib", type="datetime", nullable=false)
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
     * @ORM\Column(name="Xfer", type="boolean", nullable=false)
     */
    private $xfer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_Xfer", type="datetime", nullable=false)
     */
    private $dateXfer;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=6, nullable=false)
     */
    private $statut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_statut", type="date", nullable=false)
     */
    private $dateStatut;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer")
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
     * @return string
     */
    public function getNumcompteexp()
    {
        return $this->numcompteexp;
    }

    /**
     * @param string $numcompteexp
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
     * @return string
     */
    public function getNumcolis()
    {
        return $this->numcolis;
    }

    /**
     * @param string $numcolis
     */
    public function setNumcolis($numcolis)
    {
        $this->numcolis = $numcolis;
    }

    /**
     * @return string
     */
    public function getNcolis()
    {
        return $this->ncolis;
    }

    /**
     * @param string $ncolis
     */
    public function setNcolis($ncolis)
    {
        $this->ncolis = $ncolis;
    }

    /**
     * @return string
     */
    public function getNumtracking()
    {
        return $this->numtracking;
    }

    /**
     * @param string $numtracking
     */
    public function setNumtracking($numtracking)
    {
        $this->numtracking = $numtracking;
    }

    /**
     * @return string
     */
    public function getNumtrackingpch()
    {
        return $this->numtrackingpch;
    }

    /**
     * @param string $numtrackingpch
     */
    public function setNumtrackingpch($numtrackingpch)
    {
        $this->numtrackingpch = $numtrackingpch;
    }

    /**
     * @return string
     */
    public function getTypeproduit()
    {
        return $this->typeproduit;
    }

    /**
     * @param string $typeproduit
     */
    public function setTypeproduit($typeproduit)
    {
        $this->typeproduit = $typeproduit;
    }

    /**
     * @return string
     */
    public function getCentre()
    {
        return $this->centre;
    }

    /**
     * @param string $centre
     */
    public function setCentre($centre)
    {
        $this->centre = $centre;
    }

    /**
     * @return string
     */
    public function getTournee()
    {
        return $this->tournee;
    }

    /**
     * @param string $tournee
     */
    public function setTournee($tournee)
    {
        $this->tournee = $tournee;
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
     * @return string
     */
    public function getNumbl()
    {
        return $this->numbl;
    }

    /**
     * @param string $numbl
     */
    public function setNumbl($numbl)
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
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param string $statut
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


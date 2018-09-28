<?php

namespace TMD\AppliBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TMD\ProdBundle\Entity\Client;

/**
 * NTracking
 *
 * @ORM\Table(name="n_tracking", uniqueConstraints={@ORM\UniqueConstraint(name="plageDebut", columns={"numCompteExp", "plageDebut"}), @ORM\UniqueConstraint(name="plageFin", columns={"numCompteExp", "plageFin"})})
 * @ORM\Entity(repositoryClass="TMD\AppliBundle\Repository\NTrackingRepository", readOnly=false)
 */
class NTracking
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="numTransport", type="boolean", nullable=true)
     */
    private $numtransport;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="TMD\ProdBundle\Entity\Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idClient", referencedColumnName="idClient", nullable=false)
     * })
     */
    private $idclient;

    /**
     * @var string
     *
     * @ORM\Column(name="numCompteExp", type="string", length=12, nullable=true)
     */
    private $numcompteexp;

    /**
     * @var string
     *
     * @ORM\Column(name="nomChargeur", type="string", length=50, nullable=true)
     */
    private $nomchargeur;

    /**
     * @var string
     *
     * @ORM\Column(name="idAgence", type="string", length=3, nullable=true)
     */
    private $idagence;

    /**
     * @var string
     *
     * @ORM\Column(name="codeAgence", type="string", length=3, nullable=false)
     */
    private $codeagence = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="retour", type="boolean", nullable=false)
     */
    private $retour = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="codeService", type="boolean", nullable=true)
     */
    private $codeservice;

    /**
     * @var string
     *
     * @ORM\Column(name="ServiceConsolidation", type="string", length=3, nullable=true)
     */
    private $serviceconsolidation;

    /**
     * @var integer
     *
     * @ORM\Column(name="pays_agence", type="integer", nullable=true)
     */
    private $paysAgence;

    /**
     * @var string
     *
     * @ORM\Column(name="box", type="string",length=20, nullable=false)
     */
    private $box;

    /**
     * @var integer
     *
     * @ORM\Column(name="plageDebut", type="bigint", nullable=false)
     */
    private $plagedebut;

    /**
     * @var integer
     *
     * @ORM\Column(name="plageFin", type="bigint", nullable=false)
     */
    private $plagefin;

    /**
     * @var integer
     *
     * @ORM\Column(name="current", type="bigint", nullable=true)
     */
    private $current;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAttrib", type="date", nullable=false)
     */
    private $dateattrib;

    /**
     * @var integer
     *
     * @ORM\Column(name="valid", type="smallint", nullable=false)
     */
    private $valid = 1;

    /**
     * @var string
     *
     * @ORM\Column(name="Commentaires", type="string", length=32, nullable=false)
     */
    private $commentaires = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="numPlage", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numplage;

    /**
     * NTracking constructor.
     * @param \DateTime $dateattrib
     */
    public function __construct()
    {
        $this->dateattrib = new \DateTime('now');
    }

    /**
     * @return bool
     */
    public function isNumtransport()
    {
        return $this->numtransport;
    }

    /**
     * @param bool $numtransport
     */
    public function setNumtransport($numtransport)
    {
        $this->numtransport = $numtransport;
    }

    /**
     * @return Client
     */
    public function getIdclient()
    {
        return $this->idclient;
    }

    /**
     * @param int $idclient
     */
    public function setIdclient(Client $idclient)
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
     * @return string
     */
    public function getNomchargeur()
    {
        return $this->nomchargeur;
    }

    /**
     * @param string $nomchargeur
     */
    public function setNomchargeur($nomchargeur)
    {
        $this->nomchargeur = $nomchargeur;
    }

    /**
     * @return string
     */
    public function getIdagence()
    {
        return $this->idagence;
    }

    /**
     * @param string $idagence
     */
    public function setIdagence($idagence)
    {
        $this->idagence = $idagence;
    }

    /**
     * @return string
     */
    public function getCodeagence()
    {
        return $this->codeagence;
    }

    /**
     * @param string $codeagence
     */
    public function setCodeagence($codeagence)
    {
        $this->codeagence = $codeagence;
    }

    /**
     * @return bool
     */
    public function isRetour()
    {
        return $this->retour;
    }

    /**
     * @param bool $retour
     */
    public function setRetour($retour)
    {
        $this->retour = $retour;
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
     * @return string
     */
    public function getServiceconsolidation()
    {
        return $this->serviceconsolidation;
    }

    /**
     * @param string $serviceconsolidation
     */
    public function setServiceconsolidation($serviceconsolidation)
    {
        $this->serviceconsolidation = $serviceconsolidation;
    }

    /**
     * @return int
     */
    public function getPaysAgence()
    {
        return $this->paysAgence;
    }

    /**
     * @param int $paysAgence
     */
    public function setPaysAgence($paysAgence)
    {
        $this->paysAgence = $paysAgence;
    }

    /**
     * @return int
     */
    public function getBox()
    {
        return $this->box;
    }

    /**
     * @param int $box
     */
    public function setBox($box)
    {
        $this->box = $box;
    }

    /**
     * @return int
     */
    public function getPlagedebut()
    {
        return $this->plagedebut;
    }

    /**
     * @param int $plagedebut
     */
    public function setPlagedebut($plagedebut)
    {
        $this->plagedebut = $plagedebut;
    }

    /**
     * @return int
     */
    public function getPlagefin()
    {
        return $this->plagefin;
    }

    /**
     * @param int $plagefin
     */
    public function setPlagefin($plagefin)
    {
        $this->plagefin = $plagefin;
    }

    /**
     * @return int
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * @param int $current
     */
    public function setCurrent($current)
    {
        $this->current = $current;
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
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * @param string $commentaires
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;
    }

    /**
     * @return int
     */
    public function getNumplage()
    {
        return $this->numplage;
    }

    /**
     * @return int
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * @param int $valid
     */
    public function setValid($valid)
    {
        $this->valid = $valid;
    }


}


<?php

namespace TMD\ProdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EcommBl
 *
 * @ORM\Table(name="ecomm_bl", uniqueConstraints={@ORM\UniqueConstraint(name="BL", columns={"BL"})}, indexes={@ORM\Index(name="numLigne", columns={"numLigne"}), @ORM\Index(name="ModExp", columns={"ModExp"}) , @ORM\Index(name="date_production", columns={"date_production"}), @ORM\Index(name="site_production", columns={"site_production"})})
 * @ORM\Entity(repositoryClass="TMD\ProdBundle\Repository\EcommBLRepository", readOnly=false)
 */
class EcommBl
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idFile", type="integer", nullable=false)
     */
    private $idfile;

    /**
     * @var string
     *
     * @ORM\Column(name="N_COLIS", type="string", length=30, nullable=false)
     */
    private $nColis;

    /**
     * @var string
     *
     * @ORM\Column(name="Poids", type="string", length=6, nullable=false)
     */
    private $poids;

    /**
     * @var string
     *
     * @ORM\Column(name="Poids_reel", type="string", length=6, nullable=false)
     */
    private $poidsReel;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_production", type="datetime", nullable=false)
     */
    private $dateProduction;

    /**
     * @var string
     *
     * @ORM\Column(name="ModExp", type="string", length=50, nullable=false)
     */
    private $modexp;

    /**
     * @var integer
     *
     * @ORM\Column(name="idClient", type="smallint", nullable=false)
     */
    private $idclient;

    /**
     * @var string
     *
     * @ORM\Column(name="num_cmde_client", type="string", length=30, nullable=false)
     */
    private $numCmdeClient;

    /**
     * @var string
     *
     * @ORM\Column(name="n_consolidation", type="string", length=30, nullable=false)
     */
    private $nConsolidation;

    /**
     * @var integer
     *
     * @ORM\Column(name="numLigne", type="integer", nullable=false)
     */
    private $numligne;

    /**
     * @var string
     *
     * @ORM\Column(name="valeur", type="decimal", precision=7, scale=3, nullable=false)
     */
    private $valeur;

    /**
     * @var integer
     *
     * @ORM\Column(name="idBL", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idbl;

    /**
     * @var \TMD\ProdBundle\Entity\Siteprod
     *
     * @ORM\ManyToOne(targetEntity="TMD\ProdBundle\Entity\Siteprod")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="site_production", referencedColumnName="idsitePROD", nullable=false)
     * })
     */
    private $siteProduction;

    /**
     * @var \TMD\ProdBundle\Entity\EcommLignes
     *
     * @ORM\ManyToOne(targetEntity="TMD\ProdBundle\Entity\EcommLignes", inversedBy="bls")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="BL", referencedColumnName="numBL")
     * })
     */
    private $bl;


    /**
     * @return int
     */
    public function getIdfile()
    {
        return $this->idfile;
    }

    /**
     * @param int $idfile
     */
    public function setIdfile($idfile)
    {
        $this->idfile = $idfile;
    }

    /**
     * @return string
     */
    public function getNColis()
    {
        return $this->nColis;
    }

    /**
     * @param string $nColis
     */
    public function setNColis($nColis)
    {
        $this->nColis = $nColis;
    }

    /**
     * @return string
     */
    public function getPoids()
    {
        return $this->poids;
    }

    /**
     * @param string $poids
     */
    public function setPoids($poids)
    {
        $this->poids = $poids;
    }

    /**
     * @return string
     */
    public function getPoidsReel()
    {
        return $this->poidsReel;
    }

    /**
     * @param string $poidsReel
     */
    public function setPoidsReel($poidsReel)
    {
        $this->poidsReel = $poidsReel;
    }

    /**
     * @return \DateTime
     */
    public function getDateProduction()
    {
        return $this->dateProduction;
    }

    /**
     * @param \DateTime $dateProduction
     */
    public function setDateProduction($dateProduction)
    {
        $this->dateProduction = $dateProduction;
    }

    /**
     * @return string
     */
    public function getModexp()
    {
        return $this->modexp;
    }

    /**
     * @param string $modexp
     */
    public function setModexp($modexp)
    {
        $this->modexp = $modexp;
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
    public function getNumCmdeClient()
    {
        return $this->numCmdeClient;
    }

    /**
     * @param string $numCmdeClient
     */
    public function setNumCmdeClient($numCmdeClient)
    {
        $this->numCmdeClient = $numCmdeClient;
    }

    /**
     * @return string
     */
    public function getNConsolidation()
    {
        return $this->nConsolidation;
    }

    /**
     * @param string $nConsolidation
     */
    public function setNConsolidation($nConsolidation)
    {
        $this->nConsolidation = $nConsolidation;
    }

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
     * @return string
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * @param string $valeur
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;
    }

    /**
     * @return int
     */
    public function getIdbl()
    {
        return $this->idbl;
    }

    /**
     * @param int $idbl
     */
    public function setIdbl($idbl)
    {
        $this->idbl = $idbl;
    }

    /**
     * @return Siteprod
     */
    public function getSiteProduction()
    {
        return $this->siteProduction;
    }

    /**
     * @param Siteprod $siteProduction
     */
    public function setSiteProduction($siteProduction)
    {
        $this->siteProduction = $siteProduction;
    }

    /**
     * @return EcommLignes
     */
    public function getBl()
    {
        return $this->bl;
    }

    /**
     * @param EcommLignes $bl
     */
    public function setBl($bl)
    {
        $this->bl = $bl;
    }

}

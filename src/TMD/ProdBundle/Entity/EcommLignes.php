<?php

namespace TMD\ProdBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * EcommLignes
 *
 * @ORM\Table(name="ecomm_lignes", uniqueConstraints={@ORM\UniqueConstraint(name="BL", columns={"numBL"})}, indexes={@ORM\Index(name="numLigne", columns={"numLigne"})})
 * @ORM\Entity(repositoryClass="TMD\ProdBundle\Repository\EcommLigneRepository", readOnly=false)
 */
class EcommLignes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idClient", type="smallint", nullable=false)
     */
    private $idclient = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="idFile", type="integer", nullable=false)
     */
    private $idfile = '0';

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="numBL", type="bigint", nullable=false)
     */
    private $numbl;

    /**
     * @var string
     *
     * @ORM\Column(name="poids", type="decimal", precision=8, scale=0, nullable=false)
     */
    private $poids = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=6, scale=2, nullable=false)
     */
    private $montant;

    /**
     * @var string
     *
     * @ORM\Column(name="epaisseur", type="decimal", precision=6, scale=2, nullable=false)
     */
    private $epaisseur;



    /**
     * @var \TMD\ProdBundle\Entity\EcommTracking
     *
     * @ORM\ManyToOne(targetEntity="TMD\ProdBundle\Entity\EcommTracking")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="numLigne", referencedColumnName="numLigne")
     * })
     */
    private $numligne;

    /**
     * @var integer
     *
     * @ORM\Column(name="numOrder", type="smallint", nullable=true)
     */
    private $numOrder ;


    /**
     * @ORM\OneToMany(targetEntity="TMD\ProdBundle\Entity\EcommCmdep", mappedBy="numbl")
     */
    private $ecommCmdeps;

    /**
     * @ORM\OneToMany(targetEntity="TMD\ProdBundle\Entity\EcommBl", mappedBy="bl")
     */
    private $bls;

    public function __construct()
    {
        $this->ecommCmdeps = new ArrayCollection();
        $this->bls = new ArrayCollection();
    }


    public function addEcommCmdep(EcommCmdep $ecommCmdep)
    {
        $this->ecommCmdeps[] = $ecommCmdep;
        $ecommCmdep->setNumbl($this);
    }


    public function removeEcommCmdep(EcommCmdep $ecommCmdep)
    {
        $this->ecommCmdeps->removeElement($ecommCmdep);
    }


    public function getEcommCmdeps()
    {
        return $this->ecommCmdeps;
    }


    public function addBl(EcommBl $bl)
    {
        $this->bls[] = $bl;
        $bl->setBl($this);
    }

    public function removebl(EcommBl $bl)
    {
        $this->bls->removeElement($bl);
    }

    public function getbls()
    {
        return $this->bls;
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
     * @return int
     */
        public function getNumbl()
    {
        return $this->numbl;
    }

    /**
     * @param int $numbl
     */
    public function setNumbl($numbl)
    {
        $this->numbl = $numbl;
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
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * @param string $montant
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;
    }

    /**
     * @return string
     */
    public function getEpaisseur()
    {
        return $this->epaisseur;
    }

    /**
     * @param string $epaisseur
     */
    public function setEpaisseur($epaisseur)
    {
        $this->epaisseur = $epaisseur;
    }



    /**
     * @return EcommTracking
     */
    public function getNumligne()
    {
        return $this->numligne;
    }

    /**
     * @param EcommTracking $numligne
     */
    public function setNumligne($numligne)
    {
        $this->numligne = $numligne;
    }

    /**
     * @return int
     */
    public function getNumOrder()
    {
        return $this->numOrder;
    }

    /**
     * @param int $numOrder
     */
    public function setNumOrder($numOrder)
    {
        $this->numOrder = $numOrder;
    }




}


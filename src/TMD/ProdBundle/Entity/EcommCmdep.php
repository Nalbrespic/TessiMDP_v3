<?php

namespace TMD\ProdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EcommCmdep
 *
 * @ORM\Table(name="ecomm_cmdep", indexes={@ORM\Index(name="numBL", columns={"numBL"}), @ORM\Index(name="numRef", columns={"numRef"}), @ORM\Index(name="numcmde", columns={"numcmde"}), @ORM\Index(name="idFile", columns={"idFile"}), @ORM\Index(name="codeArticle", columns={"codeArticle"}), @ORM\Index(name="record", columns={"record"})})
 * @ORM\Entity(repositoryClass="TMD\ProdBundle\Repository\EcommCmdepRepository")
 */
class EcommCmdep
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idClient", type="smallint", nullable=false)
     */
    private $idclient;

    /**
     * @var integer
     *
     * @ORM\Column(name="idFile", type="integer", nullable=false)
     */
    private $idfile;

    /**
     * @var string
     *
     * @ORM\Column(name="numCmde", type="string", length=35, nullable=false)
     */
    private $numcmde;

    /**
     * @var \TMD\ProdBundle\Entity\EcommLignes
     *
     * @ORM\ManyToOne(targetEntity="TMD\ProdBundle\Entity\EcommLignes", inversedBy="ecommCmdeps")
* @ORM\JoinColumns({
*   @ORM\JoinColumn(name="numBL", referencedColumnName="numBL")
* })
*/
private $numbl;

    /**
     * @var string
     *
     * @ORM\Column(name="codeArticle", type="string", length=20, nullable=false)
     */
    private $codearticle;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=30, nullable=false)
     */
    private $type = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="numseq", type="integer", nullable=false)
     */
    private $numseq;

    /**
     * @var string
     *
     * @ORM\Column(name="perso1", type="string", length=150, nullable=false)
     */
    private $perso1;

    /**
     * @var string
     *
     * @ORM\Column(name="perso2", type="string", length=150, nullable=false)
     */
    private $perso2;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=150, nullable=false)
     */
    private $libelle;

    /**
     * @var integer
     *
     * @ORM\Column(name="poids", type="smallint", nullable=false)
     */
    private $poids = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="epaisseur", type="smallint", nullable=false)
     */
    private $epaisseur = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="nomImg", type="string", length=60, nullable=false)
     */
    private $nomimg;

    /**
     * @var boolean
     *
     * @ORM\Column(name="flagArt", type="boolean", nullable=false)
     */
    private $flagart;

    /**
     * @var string
     *
     * @ORM\Column(name="record", type="blob", nullable=true)
     */
    private $record;

    /**
     * @var integer
     *
     * @ORM\Column(name="numOrder", type="integer", nullable=true)
     */
    private $numOrder ;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbPages", type="integer", nullable=true)
     */
    private $nbPages ;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="numRef", type="string", length=20, nullable=true)
     */
    private $numRef;

    /**
     * @var string
     *
     * @ORM\Column(name="numTrack", type="string", length=20, nullable=true)
     */
    private $numTrack;

    /**
     * @var boolean
     *
     * @ORM\Column(name="flagProd", type="boolean", nullable=false)
     */
    private $flagProd = 0;

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
     * @return string
     */
    public function getNumcmde()
    {
        return $this->numcmde;
    }

    /**
     * @param string $numcmde
     */
    public function setNumcmde($numcmde)
    {
        $this->numcmde = $numcmde;
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
    public function setNumbl($numbl)
    {
        $this->numbl = $numbl;
    }

    /**
     * @return string
     */
    public function getCodearticle()
    {
        return $this->codearticle;
    }

    /**
     * @param string $codearticle
     */
    public function setCodearticle($codearticle)
    {
        $this->codearticle = $codearticle;
    }

    /**
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * @param int $quantite
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getNumseq()
    {
        return $this->numseq;
    }

    /**
     * @param int $numseq
     */
    public function setNumseq($numseq)
    {
        $this->numseq = $numseq;
    }

    /**
     * @return string
     */
    public function getPerso1()
    {
        return $this->perso1;
    }

    /**
     * @param string $perso1
     */
    public function setPerso1($perso1)
    {
        $this->perso1 = $perso1;
    }

    /**
     * @return string
     */
    public function getPerso2()
    {
        return $this->perso2;
    }

    /**
     * @param string $perso2
     */
    public function setPerso2($perso2)
    {
        $this->perso2 = $perso2;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    /**
     * @return int
     */
    public function getPoids()
    {
        return $this->poids;
    }

    /**
     * @param int $poids
     */
    public function setPoids($poids)
    {
        $this->poids = $poids;
    }

    /**
     * @return int
     */
    public function getEpaisseur()
    {
        return $this->epaisseur;
    }

    /**
     * @param int $epaisseur
     */
    public function setEpaisseur($epaisseur)
    {
        $this->epaisseur = $epaisseur;
    }

    /**
     * @return string
     */
    public function getNomimg()
    {
        return $this->nomimg;
    }

    /**
     * @param string $nomimg
     */
    public function setNomimg($nomimg)
    {
        $this->nomimg = $nomimg;
    }

    /**
     * @return bool
     */
    public function isFlagart()
    {
        return $this->flagart;
    }

    /**
     * @param bool $flagart
     */
    public function setFlagart($flagart)
    {
        $this->flagart = $flagart;
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

    /**
     * @return String
     */
    public function getRecord()
    {
        return $this->record;
    }

    /**
     * @param String $record
     */
    public function setRecord($record)
    {
        $this->record = $record;
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

    /**
     * @return int
     */
    public function getNbPages()
    {
        return $this->nbPages;
    }

    /**
     * @param int $nbPages
     */
    public function setNbPages($nbPages)
    {
        $this->nbPages = $nbPages;
    }

    /**
     * @return string
     */
    public function getNumRef()
    {
        return $this->numRef;
    }

    /**
     * @param string $numRef
     */
    public function setNumRef($numRef)
    {
        $this->numRef = $numRef;
    }

    /**
     * @return string
     */
    public function getNumTrack()
    {
        return $this->numTrack;
    }

    /**
     * @param string $numTrack
     */
    public function setNumTrack($numTrack)
    {
        $this->numTrack = $numTrack;
    }

    /**
     * @return bool
     */
    public function isFlagProd()
    {
        return $this->flagProd;
    }

    /**
     * @param bool $flagProd
     */
    public function setFlagProd($flagProd)
    {
        $this->flagProd = $flagProd;
    }




}


<?php

namespace TMD\ProdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EcommTracking
 *
 * @ORM\Table(name="ecomm_tracking", uniqueConstraints={@ORM\UniqueConstraint(name="idCliNumCmde", columns={"idClient", "num_cmde_client", "idFile", "refClient"})}, indexes={@ORM\Index(name="num_cmde_client", columns={"num_cmde_client"}),@ORM\Index(name="refClient", columns={"refClient"}), @ORM\Index(name="exp_ref", columns={"exp_ref"})})
 * @ORM\Entity(repositoryClass="TMD\ProdBundle\Repository\EcommTrackingRepository", readOnly=false)
 */
class EcommTracking
{
    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idClient", referencedColumnName="idClient")
     * })
     */
    private $idclient;

    /**
     * @var EcommFiles
     *
     * @ORM\ManyToOne(targetEntity="EcommFiles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idFile", referencedColumnName="idFile")
     * })
     */
    private $idfile;

    /**
     * @var string
     *
     * @ORM\Column(name="refClient", type="string", length=30, nullable=false)
     */
    private $refclient;

    /**
     * @var string
     *
     * @ORM\Column(name="num_cmde_client", type="string", length=35, nullable=false)
     */
    private $numCmdeClient;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_cmde", type="date", nullable=false)
     */
    private $dateCmde = '0000-00-00';

    /**
     * @var string
     *
     * @ORM\Column(name="dest_civ", type="string", length=20, nullable=false)
     */
    private $dest_civ;
    /**
     * @var string
     *
     * @ORM\Column(name="dest_prenom", type="string", length=30, nullable=false)
     */
    private $dest_prenom;
    /**
     * @var string
     *
     * @ORM\Column(name="dest_nom", type="string", length=50, nullable=false)
     */
    private $dest_nom;
    /**
     * @var string
     *
     * @ORM\Column(name="destinataire", type="string", length=65, nullable=false)
     */
    private $destinataire;

    /**
     * @var string
     *
     * @ORM\Column(name="dest_rue", type="string", length=65, nullable=false)
     */
    private $destRue;

    /**
     * @var string
     *
     * @ORM\Column(name="dest_ad2", type="string", length=65, nullable=false)
     */
    private $destAd2;

    /**
     * @var string
     *
     * @ORM\Column(name="dest_ad3", type="string", length=65, nullable=false)
     */
    private $destAd3;

    /**
     * @var string
     *
     * @ORM\Column(name="dest_ad4", type="string", length=65, nullable=false)
     */
    private $destAd4;

    /**
     * @var string
     *
     * @ORM\Column(name="dest_ad5", type="string", length=40, nullable=false)
     */
    private $destAd5;

    /**
     * @var string
     *
     * @ORM\Column(name="dest_ad6", type="string", length=40, nullable=false)
     */
    private $destAd6;

    /**
     * @var string
     *
     * @ORM\Column(name="dest_cp", type="string", length=10, nullable=false)
     */
    private $destCp;

    /**
     * @var string
     *
     * @ORM\Column(name="dest_ville", type="string", length=40, nullable=false)
     */
    private $destVille;

    /**
     * @var string
     *
     * @ORM\Column(name="dest_PAYS", type="string", length=25, nullable=false)
     */
    private $destPays;

    /**
     * @var string
     *
     * @ORM\Column(name="dest_tel", type="string", length=14, nullable=false)
     */
    private $destTel;

    /**
     * @var string
     *
     * @ORM\Column(name="dest_mail", type="string", length=100, nullable=false)
     */
    private $destMail;

    /**
     * @var string
     *
     * @ORM\Column(name="exp_compte", type="string", length=9, nullable=false)
     */
    private $expCompte = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="exp_ref", type="bigint", nullable=false)
     */
    private $expRef = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="code_agence_transp", type="integer", nullable=false)
     */
    private $codeAgenceTransp = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="Instr_livrais1", type="string", length=70, nullable=false)
     */
    private $instrLivrais1;

    /**
     * @var string
     *
     * @ORM\Column(name="Instr_livrais2", type="string", length=70, nullable=false)
     */
    private $instrLivrais2;


    /**
     * @var EcommStatut
     *
     * @ORM\ManyToOne(targetEntity="TMD\ProdBundle\Entity\EcommStatut")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idStatut", referencedColumnName="idStatut")
     * })
     */
    private $idStatut;

    /**
     * @var string
     *
     * @ORM\Column(name="type_transport", type="string", length=25, nullable=false)
     */
    private $typeTransport;

    /**
     * @var string
     *
     * @ORM\Column(name="tab_numBL_poids", type="string", length=1700, nullable=false)
     */
    private $tabNumblPoids;

    /**
     * @var string
     *
     * @ORM\Column(name="poids", type="decimal", precision=8, scale=0, nullable=false)
     */
    private $poids = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="exp_tel", type="string", length=14, nullable=false)
     */
    private $expTel;

    /**
     * @var string
     *
     * @ORM\Column(name="exp_mail", type="string", length=100, nullable=false)
     */
    private $expMail;

    /**
     * @var string
     *
     * @ORM\Column(name="relais_code", type="string", length=8, nullable=false)
     */
    private $relaisCode;

    /**
     * @var string
     *
     * @ORM\Column(name="relais_pournom", type="string", length=80, nullable=false)
     */
    private $relaisPournom;

    /**
     * @var string
     *
     * @ORM\Column(name="relais_pourprenom", type="string", length=50, nullable=false)
     */
    private $relaisPourprenom;

    /**
     * @var string
     *
     * @ORM\Column(name="relais_nom", type="string", length=40, nullable=false)
     */
    private $relaisNom;

    /**
     * @var string
     *
     * @ORM\Column(name="relais_ad1", type="string", length=40, nullable=false)
     */
    private $relaisAd1;

    /**
     * @var string
     *
     * @ORM\Column(name="relais_ad2", type="string", length=40, nullable=false)
     */
    private $relaisAd2;

    /**
     * @var string
     *
     * @ORM\Column(name="relais_cp", type="string", length=10, nullable=false)
     */
    private $relaisCp;

    /**
     * @var string
     *
     * @ORM\Column(name="relais_ville", type="string", length=40, nullable=false)
     */
    private $relaisVille;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_insert", type="datetime", nullable=false)
     */
    private $dateInsert;

    /**
     * @var string
     *
     * @ORM\Column(name="trans_notification", type="string", length=5, nullable=false)
     */
    private $transNotification = '';

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exp_id", referencedColumnName="idClient", nullable=false)
     * })
     */
    private $expId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="exp_ref1", type="string", length=35, nullable=false)
     */
    private $expRef1;

    /**
     * @var string
     *
     * @ORM\Column(name="exp_ref2", type="string", length=30, nullable=false)
     */
    private $expRef2;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=6, scale=2, nullable=false)
     */
    private $montant;

    /**
     * @var string
     *
     * @ORM\Column(name="code_douanier", type="string", length=15, nullable=false)
     */
    private $codeDouanier;

    /**
     * @var integer
     *
     * @ORM\Column(name="type_production", type="integer", nullable=true)
     */
    private $typeProduction;

    /**
     * @var boolean
     *
     * @ORM\Column(name="flag_etikt", type="boolean", nullable=false)
     */
    private $flagEtikt = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="flag_exp", type="boolean", nullable=false)
     */
    private $flagExp = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_depot", type="date", nullable=true)
     */
    private $dateDepot ='0000-00-00';

    /**
     * @var boolean
     *
     * @ORM\Column(name="flag_xport", type="boolean", nullable=false)
     */
    private $flagXport = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20, nullable=false)
     */
    private $type;

    /**
     * @var boolean
     *
     * @ORM\Column(name="CRJ", type="boolean", nullable=false)
     */
    private $crj = '0';

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="numLigne", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numligne;

    /**
     * @var string
     *
     * @ORM\Column(name="json", type="string", length=1024, nullable=false)
     */
    private $json;

    /**
     * @return Client
     */
    public function getIdclient()
    {
        return $this->idclient;
    }

    /**
     * @param Client $idclient
     */
    public function setIdclient($idclient)
    {
        $this->idclient = $idclient;
    }

    /**
     * @return EcommFiles
     */
        public function getIdfile()
    {
        return $this->idfile;
    }

    /**
     * @param EcommFiles $idfile
     */
    public function setIdfile($idfile)
    {
        $this->idfile = $idfile;
    }

    /**
     * @return string
     */
    public function getRefclient()
    {
        return $this->refclient;
    }

    /**
     * @param string $refclient
     */
    public function setRefclient($refclient)
    {
        $this->refclient = $refclient;
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
     * @return \DateTime
     */
    public function getDateCmde()
    {
        return $this->dateCmde;
    }

    /**
     * @param \DateTime $dateCmde
     */
    public function setDateCmde($dateCmde)
    {
        $this->dateCmde = $dateCmde;
    }

    /**
     * @return string
     */
    public function getDestinataire()
    {
        return $this->destinataire;
    }

    /**
     * @param string $destinataire
     */
    public function setDestinataire($destinataire)
    {
        $this->destinataire = $destinataire;
    }

    /**
     * @return string
     */
    public function getDestRue()
    {
        return $this->destRue;
    }

    /**
     * @param string $destRue
     */
    public function setDestRue($destRue)
    {
        $this->destRue = $destRue;
    }

    /**
     * @return string
     */
    public function getDestAd2()
    {
        return $this->destAd2;
    }

    /**
     * @param string $destAd2
     */
    public function setDestAd2($destAd2)
    {
        $this->destAd2 = $destAd2;
    }

    /**
     * @return string
     */
    public function getDestAd3()
    {
        return $this->destAd3;
    }

    /**
     * @param string $destAd3
     */
    public function setDestAd3($destAd3)
    {
        $this->destAd3 = $destAd3;
    }

    /**
     * @return string
     */
    public function getDestAd4()
    {
        return $this->destAd4;
    }

    /**
     * @param string $destAd4
     */
    public function setDestAd4($destAd4)
    {
        $this->destAd4 = $destAd4;
    }

    /**
     * @return string
     */
    public function getDestAd5()
    {
        return $this->destAd5;
    }

    /**
     * @param string $destAd5
     */
    public function setDestAd5($destAd5)
    {
        $this->destAd5 = $destAd5;
    }

    /**
     * @return string
     */
    public function getDestAd6()
    {
        return $this->destAd6;
    }

    /**
     * @param string $destAd6
     */
    public function setDestAd6($destAd6)
    {
        $this->destAd6 = $destAd6;
    }

    /**
     * @return string
     */
    public function getDestCp()
    {
        return $this->destCp;
    }

    /**
     * @param string $destCp
     */
    public function setDestCp($destCp)
    {
        $this->destCp = $destCp;
    }

    /**
     * @return string
     */
    public function getDestVille()
    {
        return $this->destVille;
    }

    /**
     * @param string $destVille
     */
    public function setDestVille($destVille)
    {
        $this->destVille = $destVille;
    }

    /**
     * @return string
     */
    public function getDestPays()
    {
        return $this->destPays;
    }

    /**
     * @param string $destPays
     */
    public function setDestPays($destPays)
    {
        $this->destPays = $destPays;
    }

    /**
     * @return string
     */
    public function getDestTel()
    {
        return $this->destTel;
    }

    /**
     * @param string $destTel
     */
    public function setDestTel($destTel)
    {
        $this->destTel = $destTel;
    }

    /**
     * @return string
     */
    public function getDestMail()
    {
        return $this->destMail;
    }

    /**
     * @param string $destMail
     */
    public function setDestMail($destMail)
    {
        $this->destMail = $destMail;
    }

    /**
     * @return string
     */
    public function getExpCompte()
    {
        return $this->expCompte;
    }

    /**
     * @param string $expCompte
     */
    public function setExpCompte($expCompte)
    {
        $this->expCompte = $expCompte;
    }

    /**
     * @return int
     */
    public function getExpRef()
    {
        return $this->expRef;
    }

    /**
     * @param int $expRef
     */
    public function setExpRef($expRef)
    {
        $this->expRef = $expRef;
    }

    /**
     * @return int
     */
    public function getCodeAgenceTransp()
    {
        return $this->codeAgenceTransp;
    }

    /**
     * @param int $codeAgenceTransp
     */
    public function setCodeAgenceTransp($codeAgenceTransp)
    {
        $this->codeAgenceTransp = $codeAgenceTransp;
    }

    /**
     * @return string
     */
    public function getInstrLivrais1()
    {
        return $this->instrLivrais1;
    }

    /**
     * @param string $instrLivrais1
     */
    public function setInstrLivrais1($instrLivrais1)
    {
        $this->instrLivrais1 = $instrLivrais1;
    }

    /**
     * @return string
     */
    public function getInstrLivrais2()
    {
        return $this->instrLivrais2;
    }

    /**
     * @param string $instrLivrais2
     */
    public function setInstrLivrais2($instrLivrais2)
    {
        $this->instrLivrais2 = $instrLivrais2;
    }

    /**
     * @return bool
     */
    public function isNbEtiquettes()
    {
        return $this->nbEtiquettes;
    }

    /**
     * @param bool $nbEtiquettes
     */
    public function setNbEtiquettes($nbEtiquettes)
    {
        $this->nbEtiquettes = $nbEtiquettes;
    }

    /**
     * @return string
     */
    public function getTypeTransport()
    {
        return $this->typeTransport;
    }

    /**
     * @param string $typeTransport
     */
    public function setTypeTransport($typeTransport)
    {
        $this->typeTransport = $typeTransport;
    }

    /**
     * @return string
     */
    public function getTabNumblPoids()
    {
        return $this->tabNumblPoids;
    }

    /**
     * @param string $tabNumblPoids
     */
    public function setTabNumblPoids($tabNumblPoids)
    {
        $this->tabNumblPoids = $tabNumblPoids;
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
    public function getExpTel()
    {
        return $this->expTel;
    }

    /**
     * @param string $expTel
     */
    public function setExpTel($expTel)
    {
        $this->expTel = $expTel;
    }

    /**
     * @return string
     */
    public function getExpMail()
    {
        return $this->expMail;
    }

    /**
     * @param string $expMail
     */
    public function setExpMail($expMail)
    {
        $this->expMail = $expMail;
    }

    /**
     * @return string
     */
    public function getRelaisCode()
    {
        return $this->relaisCode;
    }

    /**
     * @param string $relaisCode
     */
    public function setRelaisCode($relaisCode)
    {
        $this->relaisCode = $relaisCode;
    }

    /**
     * @return string
     */
    public function getRelaisPournom()
    {
        return $this->relaisPournom;
    }

    /**
     * @param string $relaisPournom
     */
    public function setRelaisPournom($relaisPournom)
    {
        $this->relaisPournom = $relaisPournom;
    }

    /**
     * @return string
     */
    public function getRelaisPourprenom()
    {
        return $this->relaisPourprenom;
    }

    /**
     * @param string $relaisPourprenom
     */
    public function setRelaisPourprenom($relaisPourprenom)
    {
        $this->relaisPourprenom = $relaisPourprenom;
    }

    /**
     * @return string
     */
    public function getRelaisNom()
    {
        return $this->relaisNom;
    }

    /**
     * @param string $relaisNom
     */
    public function setRelaisNom($relaisNom)
    {
        $this->relaisNom = $relaisNom;
    }

    /**
     * @return string
     */
    public function getRelaisAd1()
    {
        return $this->relaisAd1;
    }

    /**
     * @param string $relaisAd1
     */
    public function setRelaisAd1($relaisAd1)
    {
        $this->relaisAd1 = $relaisAd1;
    }

    /**
     * @return string
     */
    public function getRelaisAd2()
    {
        return $this->relaisAd2;
    }

    /**
     * @param string $relaisAd2
     */
    public function setRelaisAd2($relaisAd2)
    {
        $this->relaisAd2 = $relaisAd2;
    }

    /**
     * @return string
     */
    public function getRelaisCp()
    {
        return $this->relaisCp;
    }

    /**
     * @param string $relaisCp
     */
    public function setRelaisCp($relaisCp)
    {
        $this->relaisCp = $relaisCp;
    }

    /**
     * @return string
     */
    public function getRelaisVille()
    {
        return $this->relaisVille;
    }

    /**
     * @param string $relaisVille
     */
    public function setRelaisVille($relaisVille)
    {
        $this->relaisVille = $relaisVille;
    }

    /**
     * @return \DateTime
     */
    public function getDateInsert()
    {
        return $this->dateInsert;
    }

    /**
     * @param \DateTime $dateInsert
     */
    public function setDateInsert($dateInsert)
    {
        $this->dateInsert = $dateInsert;
    }

    /**
     * @return string
     */
    public function getTransNotification()
    {
        return $this->transNotification;
    }

    /**
     * @param string $transNotification
     */
    public function setTransNotification($transNotification)
    {
        $this->transNotification = $transNotification;
    }

    /**
     * @return int
     */
    public function getExpId()
    {
        return $this->expId;
    }

    /**
     * @param int $expId
     */
    public function setExpId($expId)
    {
        $this->expId = $expId;
    }

    /**
     * @return string
     */
    public function getExpRef1()
    {
        return $this->expRef1;
    }

    /**
     * @param string $expRef1
     */
    public function setExpRef1($expRef1)
    {
        $this->expRef1 = $expRef1;
    }

    /**
     * @return string
     */
    public function getExpRef2()
    {
        return $this->expRef2;
    }

    /**
     * @param string $expRef2
     */
    public function setExpRef2($expRef2)
    {
        $this->expRef2 = $expRef2;
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
    public function getCodeDouanier()
    {
        return $this->codeDouanier;
    }

    /**
     * @param string $codeDouanier
     */
    public function setCodeDouanier($codeDouanier)
    {
        $this->codeDouanier = $codeDouanier;
    }

    /**
     * @return \Siteprod
     */
    public function getTypeProduction()
    {
        return $this->typeProduction;
    }

    /**
     * @param \Siteprod $typeProduction
     */
    public function setTypeProduction($typeProduction)
    {
        $this->typeProduction = $typeProduction;
    }

    /**
     * @return bool
     */
    public function isFlagEtikt()
    {
        return $this->flagEtikt;
    }

    /**
     * @param bool $flagEtikt
     */
    public function setFlagEtikt($flagEtikt)
    {
        $this->flagEtikt = $flagEtikt;
    }

    /**
     * @return bool
     */
    public function isFlagExp()
    {
        return $this->flagExp;
    }

    /**
     * @param bool $flagExp
     */
    public function setFlagExp($flagExp)
    {
        $this->flagExp = $flagExp;
    }

    /**
     * @return \DateTime
     */
    public function getDateDepot()
    {
        return $this->dateDepot;
    }

    /**
     * @param \DateTime $dateDepot
     */
    public function setDateDepot($dateDepot)
    {
        $this->dateDepot = $dateDepot;
        $this->dateDepot = $dateDepot;
    }

    /**
     * @return bool
     */
    public function isFlagXport()
    {
        return $this->flagXport;
    }

    /**
     * @param bool $flagXport
     */
    public function setFlagXport($flagXport)
    {
        $this->flagXport = $flagXport;
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
     * @return bool
     */
    public function isCrj()
    {
        return $this->crj;
    }

    /**
     * @param bool $crj
     */
    public function setCrj($crj)
    {
        $this->crj = $crj;
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
    public function getDestCiv()
    {
        return $this->dest_civ;
    }

    /**
     * @param string $dest_civ
     */
    public function setDestCiv($dest_civ)
    {
        $this->dest_civ = $dest_civ;
    }

    /**
     * @return string
     */
    public function getDestPrenom()
    {
        return $this->dest_prenom;
    }

    /**
     * @param string $dest_prenom
     */
    public function setDestPrenom($dest_prenom)
    {
        $this->dest_prenom = $dest_prenom;
    }

    /**
     * @return string
     */
    public function getDestNom()
    {
        return $this->dest_nom;
    }

    /**
     * @param string $dest_nom
     */
    public function setDestNom($dest_nom)
    {
        $this->dest_nom = $dest_nom;
    }

    /**
     * @return EcommStatut
     */
    public function getIdStatut()
    {
        return $this->idStatut;
    }

    /**
     * @param EcommStatut $idStatut
     */
    public function setIdStatut($idStatut)
    {
        $this->idStatut = $idStatut;
    }

}


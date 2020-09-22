<?php

namespace TMD\ProdBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Proxies\__CG__\TMD\AppliBundle\Entity\EcommCompteTransport;
//use TMD\AppliBundle\Entity\EcommAppliConfig;

/**
 * EcommAppli
 *
 * @ORM\Table(name="ecomm_appli", uniqueConstraints={@ORM\UniqueConstraint(name="FileName", columns={"AppliName"})},indexes={@ORM\Index(name="idClient", columns={"idClient"}), @ORM\Index(name="codeAppli", columns={"codeAppli"}), @ORM\Index(name="idTypeProd", columns={"idTypeProd"}) })
 * @ORM\Entity(repositoryClass="TMD\ProdBundle\Repository\EcommAppliRepository", readOnly=false)
 */
class EcommAppli
{
    /**
     * @var string
     *
     * @ORM\Column(name="AppliName", type="string", length=255, nullable=false)
     */
    private $appliname;

    /**
     * @var string
     *
     * @ORM\Column(name="AppliNameRef", type="text", length=255, nullable=true)
     */
    private $applinameref;

    /**
     * @var string
     *
     * @ORM\Column(name="cmde", type="string", length=25, nullable=true)
     */
    private $cmde;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAppli", type="datetime", nullable=false)
     */
    private $dateappli;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbRecords", type="integer", nullable=true)
     */
    private $nbrecords;

    /**
     * @var string
     *
     * @ORM\Column(name="dossierImg", type="string", length=128, nullable=true)
     */
    private $dossierimg;

    /**
     * @var boolean
     *
     * @ORM\Column(name="gpmnttrans", type="boolean", nullable=true, options={"default" = 0})
     */
    private $gpmnttrans;

    /**
     * @var string
     *
     * @ORM\Column(name="Appliimg", type="blob", nullable=true)
     */
    private $appliImage;

    /**
     * @var string
     *
     * @ORM\Column(name="codeAppli", type="string", length=255, nullable=true)
     */
    private $codeappli;

    /**
     * @var boolean
     *
     * @ORM\Column(name="trtAppli", type="boolean", nullable=true)
     */
    private $trtappli;

    /**
     * @var integer
     *
     * @ORM\Column(name="idAppli", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idappli;


    /**
     * @var boolean
     *
     * @ORM\Column(name="flag_mailing", type="boolean", nullable=false, options={"default" = 0})
     */
    private $mailing;



    /**
     * @var \TMD\ProdBundle\Entity\Client
     *
     * @ORM\ManyToOne(targetEntity="TMD\ProdBundle\Entity\Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idClient", referencedColumnName="idClient", nullable=false)
     * })
     */
    private $idclient;

    /**
     * @var \TMD\ProdBundle\Entity\EcommTypeProduction
     *
     * @ORM\ManyToOne(targetEntity="TMD\ProdBundle\Entity\EcommTypeProduction", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTypeProd", referencedColumnName="idTypeProd", nullable=false)
     * })
     */
    private $idtypeprod = 1 ;

//    /**
//     * @ORM\ManyToMany(targetEntity="TMD\AppliBundle\Entity\EcommCompteTransport", cascade={"persist"})
//     *  @ORM\JoinTable(name="ecomm_Appli_Transport",
//     *      joinColumns={@ORM\JoinColumn(name="idApp", referencedColumnName="idAppli")},
//     *      inverseJoinColumns={@ORM\JoinColumn(name="idTrans", referencedColumnName="idcomptetransport")}
//     *      )
//     */
//    private $transports;

//    /**
//     * @ORM\ManyToMany(targetEntity="TMD\AppliBundle\Entity\EcommAppliConfig", cascade={"persist"})
//     *  @ORM\JoinTable(name="ecomm_Appli_AppliConfig" ,
//     *                     joinColumns={@ORM\JoinColumn(name="idApp", referencedColumnName="idAppli",  nullable=true )}
//     *
//     * )
//     *
//     */
//    private $configs;




    /**
     * EcommAppli constructor.
     * @param \DateTime $dateappli
     */
    public function __construct()
    {
        $this->dateappli = new \DateTime('now');
//        $this->configs = new ArrayCollection();
    }


    /**
     * @return string
     */
    public function getAppliname()
    {
        return $this->appliname;
    }

    /**
     * @param string $appliname
     */
    public function setAppliname($appliname)
    {
        $this->appliname = $appliname;
    }

    /**
     * @return string
     */
    public function getApplinameref()
    {
        return $this->applinameref;
    }

    /**
     * @param string $applinameref
     */
    public function setApplinameref($applinameref)
    {
        $this->applinameref = $applinameref;
    }

    /**
     * @return string
     */
    public function getCmde()
    {
        return $this->cmde;
    }

    /**
     * @param string $cmde
     */
    public function setCmde($cmde)
    {
        $this->cmde = $cmde;
    }

    /**
     * @return \DateTime
     */
    public function getDateappli()
    {
        return $this->dateappli;
    }

    /**
     * @param \DateTime $dateappli
     */
    public function setDateappli($dateappli)
    {
        $this->dateappli = $dateappli;
    }

    /**
     * @return int
     */
    public function getNbrecords()
    {
        return $this->nbrecords;
    }

    /**
     * @param int $nbrecords
     */
    public function setNbrecords($nbrecords)
    {
        $this->nbrecords = $nbrecords;
    }

    /**
     * @return string
     */
    public function getDossierimg()
    {
        return $this->dossierimg;
    }

    /**
     * @param string $dossierimg
     */
    public function setDossierimg($dossierimg)
    {
        $this->dossierimg = $dossierimg;
    }

    /**
     * @return bool
     */
    public function isGpmnttrans()
    {
        return $this->gpmnttrans;
    }

    /**
     * @param bool $gpmnttrans
     */
    public function setGpmnttrans($gpmnttrans)
    {
        $this->gpmnttrans = $gpmnttrans;
    }

    /**
     * @return string
     */
    public function getappliImage()
    {
        return $this->appliImage;
    }

    /**
     * @param string $appliimg
     */
    public function setappliImage($appliimg)
    {
        $this->appliImage = $appliimg;
    }

    /**
     * @return string
     */
    public function getCodeappli()
    {
        return $this->codeappli;
    }

    /**
     * @param string $codeappli
     */
    public function setCodeappli($codeappli)
    {
        $this->codeappli = $codeappli;
    }

    /**
     * @return bool
     */
    public function isTrtappli()
    {
        return $this->trtappli;
    }

    /**
     * @param bool $trtappli
     */
    public function setTrtappli($trtappli)
    {
        $this->trtappli = $trtappli;
    }

    /**
     * @return int
     */
    public function getIdAppli()
    {
        return $this->idappli;
    }

    /**
     * @param int $idAppli
     */
    public function setIdAppli($idAppli)
    {
        $this->idappli = $idAppli;
    }

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
     * @return EcommTypeProduction
     */
    public function getidTypeprod()
    {
        return $this->idtypeprod;
    }

    /**
     * @param EcommTypeProduction $typeprod
     */
    public function setidTypeprod($idtypeprod)
    {
        $this->idtypeprod = $idtypeprod;
    }

    /**
     * @return bool
     */
    public function isMailing()
    {
        return $this->mailing;
    }

    /**
     * @param bool $mailing
     */
    public function setMailing($mailing)
    {
        $this->mailing = $mailing;
    }




//    public function addConfig(EcommAppliConfig $config)
//    {
//        $this->configs[] = $config;
//    }
//
//    public function removeConfig(EcommAppliConfig $config)
//    {
//        $this->configs->removeElement($config);
//    }
//
//    public function getConfigs()
//    {
//        return $this->configs;
//    }





}


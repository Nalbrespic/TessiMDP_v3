<?php

namespace TMD\ProdBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="r_client")
 * @ORM\Entity(repositoryClass="TMD\ProdBundle\Repository\ClientRepository", readOnly=false)
 */
class Client
{
    /**
     * @var string
     *
     * @ORM\Column(name="nomClient", type="text", length=65535, nullable=true)
     */
    private $nomclient;

    /**
     * @var string
     *
     * @ORM\Column(name="rue", type="string", length=35, nullable=false)
     */
    private $rue;

    /**
     * @var string
     *
     * @ORM\Column(name="ad1", type="string", length=35, nullable=false)
     */
    private $ad1;

    /**
     * @var string
     *
     * @ORM\Column(name="ad2", type="string", length=35, nullable=false)
     */
    private $ad2;

    /**
     * @var string
     *
     * @ORM\Column(name="ad3", type="string", length=35, nullable=false)
     */
    private $ad3;

    /**
     * @var string
     *
     * @ORM\Column(name="cp", type="string", length=12, nullable=false)
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=35, nullable=false)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="codePays", type="string", length=3, nullable=false)
     */
    private $codepays;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=30, nullable=false)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=30, nullable=false)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=80, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="gsm", type="string", length=20, nullable=false)
     */
    private $gsm;

    /**
     * @var integer
     *
     * @ORM\Column(name="idClient", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idclient;





    /**
     * @return string
     */
    public function getNomclient()
    {
        return $this->nomclient;
    }

    /**
     * @param string $nomclient
     */
    public function setNomclient($nomclient)
    {
        $this->nomclient = $nomclient;
    }

    /**
     * @return string
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * @param string $rue
     */
    public function setRue($rue)
    {
        $this->rue = $rue;
    }

    /**
     * @return string
     */
    public function getAd1()
    {
        return $this->ad1;
    }

    /**
     * @param string $ad1
     */
    public function setAd1($ad1)
    {
        $this->ad1 = $ad1;
    }

    /**
     * @return string
     */
    public function getAd2()
    {
        return $this->ad2;
    }

    /**
     * @param string $ad2
     */
    public function setAd2($ad2)
    {
        $this->ad2 = $ad2;
    }

    /**
     * @return string
     */
    public function getAd3()
    {
        return $this->ad3;
    }

    /**
     * @param string $ad3
     */
    public function setAd3($ad3)
    {
        $this->ad3 = $ad3;
    }

    /**
     * @return string
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * @param string $cp
     */
    public function setCp($cp)
    {
        $this->cp = $cp;
    }

    /**
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param string $ville
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    /**
     * @return string
     */
    public function getCodepays()
    {
        return $this->codepays;
    }

    /**
     * @param string $codepays
     */
    public function setCodepays($codepays)
    {
        $this->codepays = $codepays;
    }

    /**
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param string $tel
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getGsm()
    {
        return $this->gsm;
    }

    /**
     * @param string $gsm
     */
    public function setGsm($gsm)
    {
        $this->gsm = $gsm;
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





}


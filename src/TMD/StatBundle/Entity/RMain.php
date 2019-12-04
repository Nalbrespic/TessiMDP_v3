<?php

namespace TMD\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

///**
// * RMain
// *
// * @ORM\Table(name="r_main")
// * @ORM\Entity
// */
class RMain
{
    /**
     * @var string
     *
     * @ORM\Column(name="Horodatage", type="text", length=65535, nullable=true)
     */
    private $horodatage;

    /**
     * @var string
     *
     * @ORM\Column(name="Operation", type="text", length=65535, nullable=true)
     */
    private $operation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_reception", type="datetime", nullable=false , options={"default" = "0000-00-00 00:00:00"})
     */
    private $dateReception;

    /**
     * @var integer
     *
     * @ORM\Column(name="idClientM", type="smallint", nullable=true)
     */
    private $idclientm;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ATC1", type="boolean", nullable=true)
     */
    private $atc1;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_dossier", type="text", length=65535, nullable=false)
     */
    private $numeroDossier;

    /**
     * @var boolean
     *
     * @ORM\Column(name="idStatus", type="boolean", nullable=false , options={"default" = "0"})
     */
    private $idstatus;

    /**
     * @var integer
     *
     * @ORM\Column(name="Tech1", type="smallint", nullable=true)
     */
    private $tech1;

    /**
     * @var integer
     *
     * @ORM\Column(name="IdMain", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idmain;



    /**
     * Set horodatage
     *
     * @param string $horodatage
     *
     * @return RMain
     */
    public function setHorodatage($horodatage)
    {
        $this->horodatage = $horodatage;

        return $this;
    }

    /**
     * Get horodatage
     *
     * @return string
     */
    public function getHorodatage()
    {
        return $this->horodatage;
    }

    /**
     * Set operation
     *
     * @param string $operation
     *
     * @return RMain
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Get operation
     *
     * @return string
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Set dateReception
     *
     * @param \DateTime $dateReception
     *
     * @return RMain
     */
    public function setDateReception($dateReception)
    {
        $this->dateReception = $dateReception;

        return $this;
    }

    /**
     * Get dateReception
     *
     * @return \DateTime
     */
    public function getDateReception()
    {
        return $this->dateReception;
    }

    /**
     * Set idclientm
     *
     * @param integer $idclientm
     *
     * @return RMain
     */
    public function setIdclientm($idclientm)
    {
        $this->idclientm = $idclientm;

        return $this;
    }

    /**
     * Get idclientm
     *
     * @return integer
     */
    public function getIdclientm()
    {
        return $this->idclientm;
    }

    /**
     * Set atc1
     *
     * @param boolean $atc1
     *
     * @return RMain
     */
    public function setAtc1($atc1)
    {
        $this->atc1 = $atc1;

        return $this;
    }

    /**
     * Get atc1
     *
     * @return boolean
     */
    public function getAtc1()
    {
        return $this->atc1;
    }

    /**
     * Set numeroDossier
     *
     * @param string $numeroDossier
     *
     * @return RMain
     */
    public function setNumeroDossier($numeroDossier)
    {
        $this->numeroDossier = $numeroDossier;

        return $this;
    }

    /**
     * Get numeroDossier
     *
     * @return string
     */
    public function getNumeroDossier()
    {
        return $this->numeroDossier;
    }

    /**
     * Set idstatus
     *
     * @param boolean $idstatus
     *
     * @return RMain
     */
    public function setIdstatus($idstatus)
    {
        $this->idstatus = $idstatus;

        return $this;
    }

    /**
     * Get idstatus
     *
     * @return boolean
     */
    public function getIdstatus()
    {
        return $this->idstatus;
    }

    /**
     * Set tech1
     *
     * @param integer $tech1
     *
     * @return RMain
     */
    public function setTech1($tech1)
    {
        $this->tech1 = $tech1;

        return $this;
    }

    /**
     * Get tech1
     *
     * @return integer
     */
    public function getTech1()
    {
        return $this->tech1;
    }

    /**
     * Get idmain
     *
     * @return integer
     */
    public function getIdmain()
    {
        return $this->idmain;
    }
}

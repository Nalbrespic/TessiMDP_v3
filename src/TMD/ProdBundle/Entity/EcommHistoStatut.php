<?php

namespace TMD\ProdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EcommHistoStatut
 *
 * @ORM\Table(name="ecomm_histo_statut", indexes={@ORM\Index(name="numBL", columns={"numBL"}), @ORM\Index(name="idStatut", columns={"idStatut"})})
 * @ORM\Entity(repositoryClass="TMD\ProdBundle\Repository\EcommHistoStatutRepository", readOnly=false)
 */
class EcommHistoStatut
{
    /**
     * @var string
     *
     * @ORM\Column(name="numBL", type="string", length=13, nullable=false)
     */
    private $numbl;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="EcommStatut")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idStatut", referencedColumnName="idStatut")
     * })
     */
    private $idstatut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateStatut", type="datetime", nullable=false)
     */
    private $datestatut;

    /**
     * @var integer
     *
     * @ORM\Column(name="idUser", type="integer", nullable=false)
     */
    private $iduser;

    /**
     * @var string
     *
     * @ORM\Column(name="observation", type="text", length=65535, nullable=true)
     */
    private $observation;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

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
     * @return int
     */
    public function getIdstatut()
    {
        return $this->idstatut;
    }

    /**
     * @param int $idstatut
     */
    public function setIdstatut($idstatut)
    {
        $this->idstatut = $idstatut;
    }

    /**
     * @return \DateTime
     */
    public function getDatestatut()
    {
        return $this->datestatut;
    }

    /**
     * @param \DateTime $datestatut
     */
    public function setDatestatut($datestatut)
    {
        $this->datestatut = $datestatut;
    }

    /**
     * @return int
     */
    public function getIduser()
    {
        return $this->iduser;
    }

    /**
     * @param int $iduser
     */
    public function setIduser($iduser)
    {
        $this->iduser = $iduser;
    }

    /**
     * @return string
     */
    public function getObservation()
    {
        return $this->observation;
    }

    /**
     * @param string $observation
     */
    public function setObservation($observation)
    {
        $this->observation = $observation;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



}


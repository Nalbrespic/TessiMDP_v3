<?php

namespace TMD\ProdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EcommFiles
 *
 * @ORM\Table(name="ecomm_files", uniqueConstraints={@ORM\UniqueConstraint(name="FileName", columns={"FileName"})}, indexes={@ORM\Index(name="idAppli", columns={"idAppli"}), @ORM\Index(name="idClient", columns={"idClient"}), @ORM\Index(name="idAppli_2", columns={"idAppli"})})
 * @ORM\Entity(repositoryClass="TMD\ProdBundle\Repository\EcommFilesRepository", readOnly=false)
 */
class EcommFiles
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idClient", type="integer", nullable=false)
     */
    private $idclient;

    /**
     * @var EcommAppli
     *
     * @ORM\ManyToOne(targetEntity="EcommAppli")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idAppli", referencedColumnName="idAppli")
     * })
     */
    private $idappli = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="FileName", type="string", length=255, nullable=false)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="cmde", type="string", length=25, nullable=false)
     */
    private $cmde = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_file", type="datetime", nullable=false)
     */
    private $dateFile;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbRecords", type="integer", nullable=false)
     */
    private $nbrecords = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="nbPages", type="integer", nullable=false)
     */
    private $nbpages;

    /**
     * @var integer
     *
     * @ORM\Column(name="idFile", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idfile;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_offer", type="integer", nullable=true)
     */
    private $idOffer;


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
     * @return EcommAppli
     */
    public function getIdappli()
    {
        return $this->idappli;
    }

    /**
     * @param EcommAppli $idappli
     */
    public function setIdappli($idappli)
    {
        $this->idappli = $idappli;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
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
    public function getDateFile()
    {
        return $this->dateFile;
    }

    /**
     * @param \DateTime $dateFile
     */
    public function setDateFile($dateFile)
    {
        $this->dateFile = $dateFile;
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
     * @return int
     */
    public function getNbpages()
    {
        return $this->nbpages;
    }

    /**
     * @param int $nbpages
     */
    public function setNbpages($nbpages)
    {
        $this->nbpages = $nbpages;
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


}


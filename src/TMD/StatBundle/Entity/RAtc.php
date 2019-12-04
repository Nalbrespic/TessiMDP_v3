<?php

namespace TMD\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

///**
// * RMain
// *
// * @ORM\Table(name="r_atc")
// * @ORM\Entity
// */
class RAtc
{
    /**
     * @var string
     *
     * @ORM\Column(name="NomATC", type="text", length=65535, nullable=true)
     */
    private $nomatc;

    /**
     * @var string
     *
     * @ORM\Column(name="EmailATC", type="text", length=65535, nullable=true)
     */
    private $emailatc;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Actif", type="boolean", nullable=true)
     */
    private $actif;

    /**
     * @var integer
     *
     * @ORM\Column(name="idAtc", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAtc;

    /**
     * @return string
     */
    public function getNomatc()
    {
        return $this->nomatc;
    }

    /**
     * @param string $nomatc
     */
    public function setNomatc($nomatc)
    {
        $this->nomatc = $nomatc;
    }

    /**
     * @return string
     */
    public function getEmailatc()
    {
        return $this->emailatc;
    }

    /**
     * @param string $emailatc
     */
    public function setEmailatc($emailatc)
    {
        $this->emailatc = $emailatc;
    }

    /**
     * @return bool
     */
    public function isActif()
    {
        return $this->actif;
    }

    /**
     * @param bool $actif
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
    }

    /**
     * @return int
     */
    public function getIdAtc()
    {
        return $this->idAtc;
    }


}


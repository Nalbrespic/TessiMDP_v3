<?php

namespace TMD\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

///**
// * RMain
// *
// * @ORM\Table(name="r_developpeur")
// * @ORM\Entity
// */
class RDeveloppeur
{
        /**
         * @var string
         *
         * @ORM\Column(name="Nom", type="text", length=65535, nullable=true)
         */
    private $nom;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Actif", type="boolean", nullable=true)
     */
    private $actif;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_developpeur", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDeveloppeur;

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
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
    public function getIdDeveloppeur()
    {
        return $this->idDeveloppeur;
    }



}


<?php

namespace TMD\DpdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Routage
 *
 * @ORM\Table(name="routage")
 * @ORM\Entity
 */
class Routage
{
    /**
     * @var string
     *
     * @ORM\Column(name="rs1", type="string", length=100, precision=0, scale=0, nullable=false, unique=false)
     */
    private $rs1;

    /**
     * @var string
     *
     * @ORM\Column(name="rs2", type="string", length=100, precision=0, scale=0, nullable=false, unique=false)
     */
    private $rs2;

    /**
     * @var string
     *
     * @ORM\Column(name="civ", type="string", length=50, precision=0, scale=0, nullable=false, unique=false)
     */
    private $civ;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=100, precision=0, scale=0, nullable=false, unique=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=100, precision=0, scale=0, nullable=false, unique=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="adr1", type="string", length=100, precision=0, scale=0, nullable=false, unique=false)
     */
    private $adr1;

    /**
     * @var string
     *
     * @ORM\Column(name="adr2", type="string", length=100, precision=0, scale=0, nullable=false, unique=false)
     */
    private $adr2;

    /**
     * @var string
     *
     * @ORM\Column(name="adr3", type="string", length=100, precision=0, scale=0, nullable=false, unique=false)
     */
    private $adr3;

    /**
     * @var string
     *
     * @ORM\Column(name="adr4", type="string", length=100, precision=0, scale=0, nullable=false, unique=false)
     */
    private $adr4;

    /**
     * @var string
     *
     * @ORM\Column(name="cp", type="string", length=10, precision=0, scale=0, nullable=false, unique=false)
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=100, precision=0, scale=0, nullable=false, unique=false)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=30, precision=0, scale=0, nullable=false, unique=false)
     */
    private $pays;

    /**
     * @var string
     *
     * @ORM\Column(name="instruction", type="string", length=100, precision=0, scale=0, nullable=false, unique=false)
     */
    private $instruction;

    /**
     * @var string
     *
     * @ORM\Column(name="numtracking", type="string", length=30, precision=0, scale=0, nullable=false, unique=false)
     */
    private $numtracking;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="GDHconditionnement", type="datetime", precision=0, scale=0, nullable=true, unique=false)
     */
    private $gdhconditionnement;

    /**
     * @var integer
     *
     * @ORM\Column(name="liasse", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $liasse;

    /**
     * @var integer
     *
     * @ORM\Column(name="cpt", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $cpt;

    /**
     * @var string
     *
     * @ORM\Column(name="idDestinataire", type="string", length=50, precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $iddestinataire;

    /**
     * @var integer
     *
     * @ORM\Column(name="dossierPere", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $dossierpere;


}


<?php

namespace TMD\IcomedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commandes
 *
 * @ORM\Table(name="commandes", indexes={@ORM\Index(name="refCdeClient", columns={"refCdeClient"}), @ORM\Index(name="numBP", columns={"numBP"})})
 * @ORM\Entity
 */
class Commandes
{
    /**
     * @var string
     *
     * @ORM\Column(name="refCdeClient", type="string", length=30, nullable=false)
     */
    private $refcdeclient;

    /**
     * @var integer
     *
     * @ORM\Column(name="numCdeTessi", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numcdetessi;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCde", type="date", nullable=false)
     */
    private $datecde;

    /**
     * @var string
     *
     * @ORM\Column(name="CIV", type="string", length=50, nullable=false)
     */
    private $civ;

    /**
     * @var string
     *
     * @ORM\Column(name="PRENOM", type="string", length=50, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="ADR1", type="string", length=50, nullable=false)
     */
    private $adr1;

    /**
     * @var string
     *
     * @ORM\Column(name="ADR2", type="string", length=50, nullable=false)
     */
    private $adr2;

    /**
     * @var string
     *
     * @ORM\Column(name="ADR3", type="string", length=50, nullable=false)
     */
    private $adr3;

    /**
     * @var string
     *
     * @ORM\Column(name="ADR4", type="string", length=50, nullable=false)
     */
    private $adr4;

    /**
     * @var string
     *
     * @ORM\Column(name="CP", type="string", length=10, nullable=false)
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="VILLE", type="string", length=50, nullable=false)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="codePays", type="string", length=5, nullable=false)
     */
    private $codepays;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=70, nullable=false)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20, nullable=false)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="modeExpe", type="string", length=30, nullable=false)
     */
    private $modeexpe;

    /**
     * @var string
     *
     * @ORM\Column(name="numTracking", type="string", length=50, nullable=false)
     */
    private $numtracking;

    /**
     * @var string
     *
     * @ORM\Column(name="complemTracking", type="string", length=50, nullable=false)
     */
    private $complemtracking;

    /**
     * @var string
     *
     * @ORM\Column(name="numCpteTransporteur", type="string", length=20, nullable=false)
     */
    private $numcptetransporteur;

    /**
     * @var integer
     *
     * @ORM\Column(name="numBP", type="integer", nullable=false)
     */
    private $numbp;

    /**
     * @var boolean
     *
     * @ORM\Column(name="currentStatus", type="boolean", nullable=false)
     */
    private $currentstatus = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="GDHcurrentStatus", type="datetime", nullable=false)
     */
    private $gdhcurrentstatus = '0000-00-00 00:00:00';

    /**
     * @var boolean
     *
     * @ORM\Column(name="idSitePROD", type="boolean", nullable=false)
     */
    private $idsiteprod = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="poidsColis", type="integer", nullable=false)
     */
    private $poidscolis = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="CRJtransmit", type="boolean", nullable=false)
     */
    private $crjtransmit = '0';


}


<?php

namespace TMD\ColisPriveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plages
 *
 * @ORM\Table(name="plages", uniqueConstraints={@ORM\UniqueConstraint(name="plageDebut", columns={"numCompteExp", "plageDebut"}), @ORM\UniqueConstraint(name="plageFin", columns={"numCompteExp", "plageFin"})})
 * @ORM\Entity
 */
class Plages
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="numTransport", type="boolean", nullable=false)
     */
    private $numtransport;

    /**
     * @var string
     *
     * @ORM\Column(name="numCompteExp", type="string", length=35, nullable=false)
     */
    private $numcompteexp;

    /**
     * @var string
     *
     * @ORM\Column(name="nomChargeur", type="string", length=32, nullable=false)
     */
    private $nomchargeur;

    /**
     * @var string
     *
     * @ORM\Column(name="numComptePoste", type="string", length=10, nullable=false)
     */
    private $numcompteposte;

    /**
     * @var boolean
     *
     * @ORM\Column(name="codeProduit", type="boolean", nullable=false)
     */
    private $codeproduit;

    /**
     * @var string
     *
     * @ORM\Column(name="plageDebut", type="string", length=30, nullable=false)
     */
    private $plagedebut;

    /**
     * @var string
     *
     * @ORM\Column(name="plageFin", type="string", length=30, nullable=false)
     */
    private $plagefin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAttrib", type="date", nullable=false)
     */
    private $dateattrib;

    /**
     * @var boolean
     *
     * @ORM\Column(name="valid", type="boolean", nullable=false)
     */
    private $valid = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="numPlage", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numplage;


}


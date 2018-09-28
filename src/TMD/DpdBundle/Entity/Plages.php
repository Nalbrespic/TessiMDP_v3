<?php

namespace TMD\DpdBundle\Entity;

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
     * @ORM\Column(name="numTransport", type="boolean", precision=0, scale=0, nullable=true, unique=false)
     */
    private $numtransport;

    /**
     * @var integer
     *
     * @ORM\Column(name="idClient", type="smallint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $idclient;

    /**
     * @var string
     *
     * @ORM\Column(name="numCompteExp", type="string", length=12, precision=0, scale=0, nullable=false, unique=false)
     */
    private $numcompteexp;

    /**
     * @var string
     *
     * @ORM\Column(name="nomChargeur", type="string", length=50, precision=0, scale=0, nullable=false, unique=false)
     */
    private $nomchargeur;

    /**
     * @var string
     *
     * @ORM\Column(name="idAgence", type="string", length=3, precision=0, scale=0, nullable=false, unique=false)
     */
    private $idagence;

    /**
     * @var string
     *
     * @ORM\Column(name="codeAgence", type="string", length=3, precision=0, scale=0, nullable=false, unique=false)
     */
    private $codeagence;

    /**
     * @var boolean
     *
     * @ORM\Column(name="retour", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $retour;

    /**
     * @var boolean
     *
     * @ORM\Column(name="codeService", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $codeservice;

    /**
     * @var string
     *
     * @ORM\Column(name="ServiceConsolidation", type="string", length=3, precision=0, scale=0, nullable=false, unique=false)
     */
    private $serviceconsolidation;

    /**
     * @var integer
     *
     * @ORM\Column(name="pays_agence", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $paysAgence;

    /**
     * @var integer
     *
     * @ORM\Column(name="pays_retour", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $paysRetour;

    /**
     * @var integer
     *
     * @ORM\Column(name="plageDebut", type="bigint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $plagedebut;

    /**
     * @var integer
     *
     * @ORM\Column(name="plageFin", type="bigint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $plagefin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAttrib", type="date", precision=0, scale=0, nullable=false, unique=false)
     */
    private $dateattrib;

    /**
     * @var boolean
     *
     * @ORM\Column(name="valid", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $valid;

    /**
     * @var string
     *
     * @ORM\Column(name="Commentaires", type="string", length=32, precision=0, scale=0, nullable=false, unique=false)
     */
    private $commentaires;

    /**
     * @var integer
     *
     * @ORM\Column(name="numPlage", type="smallint", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numplage;


}


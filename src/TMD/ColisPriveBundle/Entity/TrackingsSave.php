<?php

namespace TMD\ColisPriveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrackingsSave
 *
 * @ORM\Table(name="trackings_save", uniqueConstraints={@ORM\UniqueConstraint(name="Exapass", columns={"numCompteExp", "numColis"})}, indexes={@ORM\Index(name="numLigne", columns={"numLigne"})})
 * @ORM\Entity
 */
class TrackingsSave
{
    /**
     * @var integer
     *
     * @ORM\Column(name="numLigne", type="integer", nullable=false)
     */
    private $numligne;

    /**
     * @var integer
     *
     * @ORM\Column(name="idClient", type="smallint", nullable=false)
     */
    private $idclient = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="numCompteExp", type="string", length=6, nullable=false)
     */
    private $numcompteexp;

    /**
     * @var integer
     *
     * @ORM\Column(name="numPlage", type="integer", nullable=false)
     */
    private $numplage;

    /**
     * @var string
     *
     * @ORM\Column(name="numColis", type="string", length=30, nullable=false)
     */
    private $numcolis;

    /**
     * @var string
     *
     * @ORM\Column(name="numTracking", type="string", length=30, nullable=false)
     */
    private $numtracking;

    /**
     * @var string
     *
     * @ORM\Column(name="numTrackingPCH", type="string", length=30, nullable=false)
     */
    private $numtrackingpch;

    /**
     * @var string
     *
     * @ORM\Column(name="TypeProduit", type="string", length=1, nullable=false)
     */
    private $typeproduit = '';

    /**
     * @var string
     *
     * @ORM\Column(name="CENTRE", type="string", length=2, nullable=false)
     */
    private $centre = '';

    /**
     * @var string
     *
     * @ORM\Column(name="TOURNEE", type="string", length=4, nullable=false)
     */
    private $tournee = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAttrib", type="datetime", nullable=false)
     */
    private $dateattrib;

    /**
     * @var string
     *
     * @ORM\Column(name="numBL", type="string", length=30, nullable=false)
     */
    private $numbl;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Xfer", type="boolean", nullable=false)
     */
    private $xfer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_Xfer", type="datetime", nullable=false)
     */
    private $dateXfer;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numero;


}


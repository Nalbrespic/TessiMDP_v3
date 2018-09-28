<?php

namespace TMD\DpdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrackingsSave20160624
 *
 * @ORM\Table(name="trackings_save20160624", uniqueConstraints={@ORM\UniqueConstraint(name="Exapass", columns={"numCompteExp", "numTracking"}), @ORM\UniqueConstraint(name="numBL", columns={"numBL"})}, indexes={@ORM\Index(name="numLigne", columns={"numLigne"})})
 * @ORM\Entity
 */
class TrackingsSave20160624
{
    /**
     * @var integer
     *
     * @ORM\Column(name="numLigne", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $numligne;

    /**
     * @var integer
     *
     * @ORM\Column(name="idClient", type="smallint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $idclient;

    /**
     * @var integer
     *
     * @ORM\Column(name="numCompteExp", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $numcompteexp;

    /**
     * @var integer
     *
     * @ORM\Column(name="numPlage", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $numplage;

    /**
     * @var integer
     *
     * @ORM\Column(name="numTracking", type="bigint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $numtracking;

    /**
     * @var integer
     *
     * @ORM\Column(name="numTrackingBar", type="bigint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $numtrackingbar;

    /**
     * @var integer
     *
     * @ORM\Column(name="numConsolidation", type="bigint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $numconsolidation;

    /**
     * @var string
     *
     * @ORM\Column(name="AC", type="string", length=3, precision=0, scale=0, nullable=false, unique=false)
     */
    private $ac;

    /**
     * @var string
     *
     * @ORM\Column(name="TOUR", type="string", length=3, precision=0, scale=0, nullable=false, unique=false)
     */
    private $tour;

    /**
     * @var string
     *
     * @ORM\Column(name="LPFX", type="string", length=3, precision=0, scale=0, nullable=false, unique=false)
     */
    private $lpfx;

    /**
     * @var string
     *
     * @ORM\Column(name="SCONSOLID", type="string", length=3, precision=0, scale=0, nullable=false, unique=false)
     */
    private $sconsolid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAttrib", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $dateattrib;

    /**
     * @var string
     *
     * @ORM\Column(name="numBL", type="string", length=30, precision=0, scale=0, nullable=false, unique=false)
     */
    private $numbl;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Xfer", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $xfer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_Xfer", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $dateXfer;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numero;


}


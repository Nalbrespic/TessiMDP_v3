<?php

namespace TMD\DpdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpost
 *
 * @ORM\Table(name="cpost")
 * @ORM\Entity
 */
class Cpost
{
    /**
     * @var string
     *
     * @ORM\Column(name="liRec", type="string", length=80, precision=0, scale=0, nullable=false, unique=false)
     */
    private $lirec;

    /**
     * @var string
     *
     * @ORM\Column(name="ISO", type="string", length=2, precision=0, scale=0, nullable=false, unique=false)
     */
    private $iso;

    /**
     * @var string
     *
     * @ORM\Column(name="LPFX", type="string", length=3, precision=0, scale=0, nullable=false, unique=false)
     */
    private $lpfx;

    /**
     * @var string
     *
     * @ORM\Column(name="CPDEB", type="string", length=10, precision=0, scale=0, nullable=false, unique=false)
     */
    private $cpdeb;

    /**
     * @var string
     *
     * @ORM\Column(name="CPFIN", type="string", length=10, precision=0, scale=0, nullable=false, unique=false)
     */
    private $cpfin;

    /**
     * @var string
     *
     * @ORM\Column(name="TRI1", type="string", length=1, precision=0, scale=0, nullable=false, unique=false)
     */
    private $tri1;

    /**
     * @var string
     *
     * @ORM\Column(name="LIGNE1", type="string", length=2, precision=0, scale=0, nullable=false, unique=false)
     */
    private $ligne1;

    /**
     * @var string
     *
     * @ORM\Column(name="TRI2", type="string", length=1, precision=0, scale=0, nullable=false, unique=false)
     */
    private $tri2;

    /**
     * @var string
     *
     * @ORM\Column(name="LIGNE2", type="string", length=2, precision=0, scale=0, nullable=false, unique=false)
     */
    private $ligne2;

    /**
     * @var string
     *
     * @ORM\Column(name="AC", type="string", length=3, precision=0, scale=0, nullable=false, unique=false)
     */
    private $ac;

    /**
     * @var string
     *
     * @ORM\Column(name="ACPRINT", type="string", length=3, precision=0, scale=0, nullable=false, unique=false)
     */
    private $acprint;

    /**
     * @var string
     *
     * @ORM\Column(name="TOUR", type="string", length=3, precision=0, scale=0, nullable=false, unique=false)
     */
    private $tour;

    /**
     * @var string
     *
     * @ORM\Column(name="TOURPRINT", type="string", length=3, precision=0, scale=0, nullable=false, unique=false)
     */
    private $tourprint;

    /**
     * @var string
     *
     * @ORM\Column(name="PRETRI", type="string", length=3, precision=0, scale=0, nullable=false, unique=false)
     */
    private $pretri;

    /**
     * @var string
     *
     * @ORM\Column(name="CBTRI", type="string", length=4, precision=0, scale=0, nullable=false, unique=false)
     */
    private $cbtri;

    /**
     * @var boolean
     *
     * @ORM\Column(name="MSG", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $msg;

    /**
     * @var integer
     *
     * @ORM\Column(name="primaire", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $primaire;


}


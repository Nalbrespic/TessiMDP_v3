<?php

namespace TMD\ColisPriveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CpostSav
 *
 * @ORM\Table(name="cpost_sav")
 * @ORM\Entity
 */
class CpostSav
{
    /**
     * @var string
     *
     * @ORM\Column(name="CP", type="string", length=5, nullable=false)
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="SocDistrib", type="string", length=2, nullable=false)
     */
    private $socdistrib;

    /**
     * @var string
     *
     * @ORM\Column(name="CENTRE", type="string", length=2, nullable=false)
     */
    private $centre;

    /**
     * @var string
     *
     * @ORM\Column(name="TOUR", type="string", length=4, nullable=false)
     */
    private $tour;

    /**
     * @var string
     *
     * @ORM\Column(name="CPDES", type="string", length=1, nullable=false)
     */
    private $cpdes;

    /**
     * @var string
     *
     * @ORM\Column(name="CRT", type="string", length=1, nullable=false)
     */
    private $crt;

    /**
     * @var string
     *
     * @ORM\Column(name="Z1", type="string", length=1, nullable=false)
     */
    private $z1;

    /**
     * @var string
     *
     * @ORM\Column(name="liRec", type="string", length=18)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $lirec;


}


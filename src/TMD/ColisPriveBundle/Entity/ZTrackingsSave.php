<?php

namespace TMD\ColisPriveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ZTrackingsSave
 *
 * @ORM\Table(name="z_trackings_save")
 * @ORM\Entity
 */
class ZTrackingsSave
{
    /**
     * @var integer
     *
     * @ORM\Column(name="numPlage", type="integer", nullable=false)
     */
    private $numplage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAttrib", type="datetime", nullable=false)
     */
    private $dateattrib;

    /**
     * @var string
     *
     * @ORM\Column(name="numColis", type="string", length=30, nullable=false)
     */
    private $numcolis;

    /**
     * @var string
     *
     * @ORM\Column(name="numTracking", type="string", length=30)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numtracking;


}


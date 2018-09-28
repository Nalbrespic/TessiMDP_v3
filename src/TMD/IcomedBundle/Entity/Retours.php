<?php

namespace TMD\IcomedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Retours
 *
 * @ORM\Table(name="retours")
 * @ORM\Entity
 */
class Retours
{
    /**
     * @var integer
     *
     * @ORM\Column(name="numCdeTessi", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $numcdetessi;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="GDHretour", type="datetime", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $gdhretour;

    /**
     * @var boolean
     *
     * @ORM\Column(name="idMotif", type="boolean", nullable=false)
     */
    private $idmotif;

    /**
     * @var boolean
     *
     * @ORM\Column(name="idStatut", type="boolean", nullable=false)
     */
    private $idstatut = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="observation", type="string", length=255, nullable=false)
     */
    private $observation;

    /**
     * @var boolean
     *
     * @ORM\Column(name="transmit", type="boolean", nullable=false)
     */
    private $transmit = '0';


}


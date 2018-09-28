<?php

namespace TMD\IcomedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rebuts
 *
 * @ORM\Table(name="rebuts")
 * @ORM\Entity
 */
class Rebuts
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
     * @var integer
     *
     * @ORM\Column(name="numCasier", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $numcasier;


}


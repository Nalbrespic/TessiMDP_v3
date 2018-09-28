<?php

namespace TMD\ColisPriveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Colis
 *
 * @ORM\Table(name="colis")
 * @ORM\Entity
 */
class Colis
{
    /**
     * @var string
     *
     * @ORM\Column(name="observation", type="string", length=50, nullable=false)
     */
    private $observation;

    /**
     * @var string
     *
     * @ORM\Column(name="numColis", type="string", length=30)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numcolis;


}


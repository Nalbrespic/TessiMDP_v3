<?php

namespace TMD\ColisPriveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ZColisSave
 *
 * @ORM\Table(name="z_colis_save")
 * @ORM\Entity
 */
class ZColisSave
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


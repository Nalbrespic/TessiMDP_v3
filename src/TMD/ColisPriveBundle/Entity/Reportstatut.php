<?php

namespace TMD\ColisPriveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reportstatut
 *
 * @ORM\Table(name="reportstatut")
 * @ORM\Entity
 */
class Reportstatut
{
    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=4, nullable=false)
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(name="libStatut", type="string", length=50, nullable=false)
     */
    private $libstatut;

    /**
     * @var integer
     *
     * @ORM\Column(name="num", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $num;


}


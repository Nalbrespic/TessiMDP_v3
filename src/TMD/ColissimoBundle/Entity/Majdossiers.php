<?php

namespace TMD\ColissimoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Majdossiers
 *
 * @ORM\Table(name="majdossiers")
 * @ORM\Entity
 */
class Majdossiers
{
    /**
     * @var string
     *
     * @ORM\Column(name="dossierNEW", type="string", length=10, nullable=false)
     */
    private $dossiernew;

    /**
     * @var string
     *
     * @ORM\Column(name="dossierOLD", type="string", length=10)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $dossierold;


}


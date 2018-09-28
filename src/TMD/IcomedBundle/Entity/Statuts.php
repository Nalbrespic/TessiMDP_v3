<?php

namespace TMD\IcomedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Statuts
 *
 * @ORM\Table(name="statuts")
 * @ORM\Entity
 */
class Statuts
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="idStatut", type="boolean", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idstatut;

    /**
     * @var string
     *
     * @ORM\Column(name="abregeStatut", type="string", length=10, nullable=false)
     */
    private $abregestatut;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=20, nullable=false)
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(name="coulStatut", type="string", length=6, nullable=false)
     */
    private $coulstatut;


}


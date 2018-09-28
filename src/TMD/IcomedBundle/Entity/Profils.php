<?php

namespace TMD\IcomedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Profils
 *
 * @ORM\Table(name="profils")
 * @ORM\Entity
 */
class Profils
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="idProfil", type="boolean", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idprofil;

    /**
     * @var string
     *
     * @ORM\Column(name="profil", type="string", length=30, nullable=false)
     */
    private $profil;


}


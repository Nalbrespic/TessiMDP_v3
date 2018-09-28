<?php

namespace TMD\IcomedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Typesmvt
 *
 * @ORM\Table(name="typesmvt")
 * @ORM\Entity
 */
class Typesmvt
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="typeMvt", type="boolean", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $typemvt;

    /**
     * @var string
     *
     * @ORM\Column(name="LibelleMvt", type="string", length=20, nullable=false)
     */
    private $libellemvt;


}


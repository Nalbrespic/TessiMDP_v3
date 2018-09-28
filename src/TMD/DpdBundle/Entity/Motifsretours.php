<?php

namespace TMD\DpdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Motifsretours
 *
 * @ORM\Table(name="motifsretours")
 * @ORM\Entity
 */
class Motifsretours
{
    /**
     * @var string
     *
     * @ORM\Column(name="motifretour", type="string", length=30, precision=0, scale=0, nullable=false, unique=false)
     */
    private $motifretour;

    /**
     * @var boolean
     *
     * @ORM\Column(name="coderetour", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $coderetour;


}


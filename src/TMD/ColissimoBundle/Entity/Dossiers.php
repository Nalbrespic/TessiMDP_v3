<?php

namespace TMD\ColissimoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dossiers
 *
 * @ORM\Table(name="dossiers")
 * @ORM\Entity
 */
class Dossiers
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOuverture", type="datetime", nullable=false)
     */
    private $dateouverture;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCloture", type="datetime", nullable=false)
     */
    private $datecloture;

    /**
     * @var string
     *
     * @ORM\Column(name="dossier", type="string", length=8)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $dossier;


}


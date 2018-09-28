<?php

namespace TMD\IcomedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MotifsRetour
 *
 * @ORM\Table(name="motifs_retour")
 * @ORM\Entity
 */
class MotifsRetour
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="idMotif", type="boolean", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idmotif;

    /**
     * @var string
     *
     * @ORM\Column(name="motif", type="string", length=30, nullable=false)
     */
    private $motif;


}


<?php

namespace TMD\DpdBundle\Entity;

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
     * @var string
     *
     * @ORM\Column(name="intitDossier", type="string", length=100, precision=0, scale=0, nullable=false, unique=false)
     */
    private $intitdossier;

    /**
     * @var string
     *
     * @ORM\Column(name="idOPE", type="string", length=10, precision=0, scale=0, nullable=false, unique=false)
     */
    private $idope;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDepot", type="date", precision=0, scale=0, nullable=false, unique=false)
     */
    private $datedepot;

    /**
     * @var integer
     *
     * @ORM\Column(name="idDossier", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iddossier;


}


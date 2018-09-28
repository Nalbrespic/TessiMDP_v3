<?php

namespace TMD\DpdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Colisstatut
 *
 * @ORM\Table(name="colisstatut", uniqueConstraints={@ORM\UniqueConstraint(name="statut", columns={"statut"})})
 * @ORM\Entity
 */
class Colisstatut
{
    /**
     * @var string
     *
     * @ORM\Column(name="statut_DPDFR", type="string", length=5, precision=0, scale=0, nullable=false, unique=false)
     */
    private $statutDpdfr;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=256, precision=0, scale=0, nullable=false, unique=false)
     */
    private $libelle;

    /**
     * @var integer
     *
     * @ORM\Column(name="EventIFTSTA", type="smallint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $eventiftsta;

    /**
     * @var integer
     *
     * @ORM\Column(name="raison", type="smallint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $raison;

    /**
     * @var string
     *
     * @ORM\Column(name="EventINOVERT", type="string", length=3, precision=0, scale=0, nullable=false, unique=false)
     */
    private $eventinovert;

    /**
     * @var string
     *
     * @ORM\Column(name="raisonA", type="string", length=3, precision=0, scale=0, nullable=false, unique=false)
     */
    private $raisona;

    /**
     * @var boolean
     *
     * @ORM\Column(name="livre", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $livre;

    /**
     * @var integer
     *
     * @ORM\Column(name="statut", type="smallint", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $statut;



}


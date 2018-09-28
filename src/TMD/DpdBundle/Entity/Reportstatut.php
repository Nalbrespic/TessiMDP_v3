<?php

namespace TMD\DpdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reportstatut
 *
 * @ORM\Table(name="reportstatut", uniqueConstraints={@ORM\UniqueConstraint(name="nStatut", columns={"statut"})})
 * @ORM\Entity
 */
class Reportstatut
{
    /**
     * @var integer
     *
     * @ORM\Column(name="statut", type="smallint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(name="statutCourt", type="string", length=16, precision=0, scale=0, nullable=false, unique=false)
     */
    private $statutcourt;

    /**
     * @var string
     *
     * @ORM\Column(name="libStatut", type="string", length=80, precision=0, scale=0, nullable=false, unique=false)
     */
    private $libstatut;

    /**
     * @var integer
     *
     * @ORM\Column(name="Evnmnt", type="smallint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $evnmnt;

    /**
     * @var integer
     *
     * @ORM\Column(name="Raison", type="smallint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $raison;

    /**
     * @var string
     *
     * @ORM\Column(name="libEvnmnt", type="string", length=16, precision=0, scale=0, nullable=false, unique=false)
     */
    private $libevnmnt;

    /**
     * @var string
     *
     * @ORM\Column(name="libRaison", type="string", length=16, precision=0, scale=0, nullable=false, unique=false)
     */
    private $libraison;

    /**
     * @var integer
     *
     * @ORM\Column(name="num", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $num;


}


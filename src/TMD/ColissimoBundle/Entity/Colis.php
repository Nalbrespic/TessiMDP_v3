<?php

namespace TMD\ColissimoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Colis
 *
 * @ORM\Table(name="colis", uniqueConstraints={@ORM\UniqueConstraint(name="codescann", columns={"codescann"})})
 * @ORM\Entity
 */
class Colis
{
    /**
     * @var string
     *
     * @ORM\Column(name="codescann", type="string", length=30, nullable=false)
     */
    private $codescann;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="GDHscann", type="datetime", nullable=false)
     */
    private $gdhscann;

    /**
     * @var string
     *
     * @ORM\Column(name="dossier", type="string", length=10, nullable=false)
     */
    private $dossier;

    /**
     * @var boolean
     *
     * @ORM\Column(name="idsitePROD", type="boolean", nullable=false)
     */
    private $idsiteprod;

    /**
     * @var integer
     *
     * @ORM\Column(name="idcolis", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcolis;


}


<?php

namespace TMD\DpdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Operations
 *
 * @ORM\Table(name="operations")
 * @ORM\Entity
 */
class Operations
{
    /**
     * @var string
     *
     * @ORM\Column(name="codeOPE", type="string", length=10, precision=0, scale=0, nullable=false, unique=false)
     */
    private $codeope;

    /**
     * @var string
     *
     * @ORM\Column(name="ope", type="string", length=50, precision=0, scale=0, nullable=false, unique=false)
     */
    private $ope;

    /**
     * @var integer
     *
     * @ORM\Column(name="idClient", type="smallint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $idclient;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $actif;

    /**
     * @var integer
     *
     * @ORM\Column(name="idOpe", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idope;


}


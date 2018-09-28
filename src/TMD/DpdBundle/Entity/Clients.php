<?php

namespace TMD\DpdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clients
 *
 * @ORM\Table(name="clients")
 * @ORM\Entity
 */
class Clients
{
    /**
     * @var string
     *
     * @ORM\Column(name="client", type="string", length=50, precision=0, scale=0, nullable=false, unique=false)
     */
    private $client;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $actif;

    /**
     * @var integer
     *
     * @ORM\Column(name="idClient", type="smallint", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idclient;


}


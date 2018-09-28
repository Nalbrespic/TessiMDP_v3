<?php

namespace TMD\ColissimoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity
 */
class Client
{
    /**
     * @var string
     *
     * @ORM\Column(name="abregeclient", type="string", length=10, nullable=false)
     */
    private $abregeclient;

    /**
     * @var string
     *
     * @ORM\Column(name="client", type="string", length=30, nullable=false)
     */
    private $client;

    /**
     * @var string
     *
     * @ORM\Column(name="idclient", type="string", length=2)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idclient;


}


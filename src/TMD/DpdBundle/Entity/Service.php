<?php

namespace TMD\DpdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Service
 *
 * @ORM\Table(name="service")
 * @ORM\Entity
 */
class Service
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idTransport", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $idtransport;

    /**
     * @var string
     *
     * @ORM\Column(name="type_transport", type="string", length=15, precision=0, scale=0, nullable=false, unique=false)
     */
    private $typeTransport;

    /**
     * @var string
     *
     * @ORM\Column(name="libelleTransport", type="string", length=70, precision=0, scale=0, nullable=false, unique=false)
     */
    private $libelletransport;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaires", type="string", length=80, precision=0, scale=0, nullable=false, unique=false)
     */
    private $commentaires;

    /**
     * @var boolean
     *
     * @ORM\Column(name="codeService", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $codeservice;

    /**
     * @var integer
     *
     * @ORM\Column(name="numTransport", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numtransport;


}


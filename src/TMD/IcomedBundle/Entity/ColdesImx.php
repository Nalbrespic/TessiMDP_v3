<?php

namespace TMD\IcomedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ColdesImx
 *
 * @ORM\Table(name="coldes_imx")
 * @ORM\Entity
 */
class ColdesImx
{
    /**
     * @var integer
     *
     * @ORM\Column(name="numCdeTessi", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numcdetessi;

    /**
     * @var string
     *
     * @ORM\Column(name="liDes", type="blob", length=65535, nullable=false)
     */
    private $lides;

    /**
     * @var boolean
     *
     * @ORM\Column(name="transmit", type="boolean", nullable=false)
     */
    private $transmit = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="GDHtransmit", type="datetime", nullable=false)
     */
    private $gdhtransmit = '0000-00-00 00:00:00';


}


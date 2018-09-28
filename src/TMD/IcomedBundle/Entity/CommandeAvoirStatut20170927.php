<?php

namespace TMD\IcomedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommandeAvoirStatut20170927
 *
 * @ORM\Table(name="commande_avoir_statut_20170927")
 * @ORM\Entity
 */
class CommandeAvoirStatut20170927
{
    /**
     * @var integer
     *
     * @ORM\Column(name="numCdeTessi", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $numcdetessi;

    /**
     * @var boolean
     *
     * @ORM\Column(name="idStatut", type="boolean", nullable=false)
     */
    private $idstatut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="GDHstatut", type="datetime", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $gdhstatut;

    /**
     * @var integer
     *
     * @ORM\Column(name="idUser", type="smallint", nullable=false)
     */
    private $iduser;

    /**
     * @var string
     *
     * @ORM\Column(name="observation", type="string", length=128, nullable=false)
     */
    private $observation;


}


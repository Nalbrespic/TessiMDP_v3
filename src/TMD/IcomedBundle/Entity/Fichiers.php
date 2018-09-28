<?php

namespace TMD\IcomedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fichiers
 *
 * @ORM\Table(name="fichiers")
 * @ORM\Entity
 */
class Fichiers
{
    /**
     * @var integer
     *
     * @ORM\Column(name="numBP", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $numbp;

    /**
     * @var string
     *
     * @ORM\Column(name="fichier", type="string", length=50, nullable=false)
     */
    private $fichier;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="GDHfichier", type="datetime", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $gdhfichier;

    /**
     * @var integer
     *
     * @ORM\Column(name="nRecords", type="integer", nullable=false)
     */
    private $nrecords;

    /**
     * @var string
     *
     * @ORM\Column(name="typeCde", type="string", length=4, nullable=false)
     */
    private $typecde = 'BTOC';

    /**
     * @var boolean
     *
     * @ORM\Column(name="cloture", type="boolean", nullable=false)
     */
    private $cloture = '0';


}


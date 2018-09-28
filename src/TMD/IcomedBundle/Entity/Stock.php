<?php

namespace TMD\IcomedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stock
 *
 * @ORM\Table(name="stock", indexes={@ORM\Index(name="Observation", columns={"Observation"})})
 * @ORM\Entity
 */
class Stock
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idMvt", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idmvt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="typeMvt", type="boolean", nullable=false)
     */
    private $typemvt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="GDHmvt", type="datetime", nullable=false)
     */
    private $gdhmvt;

    /**
     * @var string
     *
     * @ORM\Column(name="refArticle", type="string", length=20, nullable=false)
     */
    private $refarticle;

    /**
     * @var integer
     *
     * @ORM\Column(name="Qte", type="integer", nullable=false)
     */
    private $qte;

    /**
     * @var string
     *
     * @ORM\Column(name="Observation", type="string", length=50, nullable=false)
     */
    private $observation;


}


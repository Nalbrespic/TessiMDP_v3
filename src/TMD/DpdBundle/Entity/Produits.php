<?php

namespace TMD\DpdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produits
 *
 * @ORM\Table(name="produits")
 * @ORM\Entity
 */
class Produits
{
    /**
     * @var string
     *
     * @ORM\Column(name="produit", type="string", length=30, precision=0, scale=0, nullable=false, unique=false)
     */
    private $produit;

    /**
     * @var boolean
     *
     * @ORM\Column(name="codeProduit", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codeproduit;


}


<?php

namespace TMD\ColisPriveBundle\Entity;

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
     * @ORM\Column(name="produit", type="string", length=30, nullable=false)
     */
    private $produit;

    /**
     * @var boolean
     *
     * @ORM\Column(name="codeProduit", type="boolean")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codeproduit;


}


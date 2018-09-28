<?php

namespace TMD\ColissimoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Siteprod
 *
 * @ORM\Table(name="siteprod")
 * @ORM\Entity
 */
class Siteprod
{
    /**
     * @var string
     *
     * @ORM\Column(name="abregesitePROD", type="string", length=10, nullable=false)
     */
    private $abregesiteprod;

    /**
     * @var string
     *
     * @ORM\Column(name="sitePROD", type="string", length=30, nullable=false)
     */
    private $siteprod;

    /**
     * @var boolean
     *
     * @ORM\Column(name="idsitePROD", type="boolean")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idsiteprod;


}


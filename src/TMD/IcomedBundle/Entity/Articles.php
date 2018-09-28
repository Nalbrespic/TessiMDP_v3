<?php

namespace TMD\IcomedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Articles
 *
 * @ORM\Table(name="articles")
 * @ORM\Entity
 */
class Articles
{
    /**
     * @var string
     *
     * @ORM\Column(name="refArticle", type="string", length=20, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $refarticle;

    /**
     * @var string
     *
     * @ORM\Column(name="libArticle", type="string", length=100, nullable=false)
     */
    private $libarticle;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $prix = '0.00';

    /**
     * @var integer
     *
     * @ORM\Column(name="poidsUnitaire", type="integer", nullable=false)
     */
    private $poidsunitaire;

    /**
     * @var boolean
     *
     * @ORM\Column(name="meca", type="boolean", nullable=false)
     */
    private $meca = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $actif = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="seuilAlerte", type="smallint", nullable=false)
     */
    private $seuilalerte = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="emballage", type="string", length=20, nullable=false)
     */
    private $emballage;

    /**
     * @var float
     *
     * @ORM\Column(name="PA_emballage", type="float", precision=10, scale=0, nullable=false)
     */
    private $paEmballage = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="PV_emballage", type="float", precision=10, scale=0, nullable=false)
     */
    private $pvEmballage = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="poidsEmballage", type="integer", nullable=false)
     */
    private $poidsemballage = '0';


}


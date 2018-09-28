<?php

namespace TMD\IcomedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Emballages
 *
 * @ORM\Table(name="emballages")
 * @ORM\Entity
 */
class Emballages
{
    /**
     * @var string
     *
     * @ORM\Column(name="emballage", type="string", length=20, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $emballage;

    /**
     * @var integer
     *
     * @ORM\Column(name="idEmballage", type="smallint", nullable=false)
     */
    private $idemballage;

    /**
     * @var integer
     *
     * @ORM\Column(name="poidsEmballage", type="integer", nullable=false)
     */
    private $poidsemballage;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptif", type="string", length=50, nullable=false)
     */
    private $descriptif;

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


}


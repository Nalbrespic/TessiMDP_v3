<?php

namespace TMD\IcomedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserAvoirProfil
 *
 * @ORM\Table(name="user_avoir_profil")
 * @ORM\Entity
 */
class UserAvoirProfil
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idUser", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $iduser;

    /**
     * @var boolean
     *
     * @ORM\Column(name="idProfil", type="boolean", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idprofil;


}


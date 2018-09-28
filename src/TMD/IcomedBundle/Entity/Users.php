<?php

namespace TMD\IcomedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class Users
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idUser", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iduser;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=30, nullable=false)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=30, nullable=false)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="mdp", type="string", length=30, nullable=false)
     */
    private $mdp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetime", nullable=false)
     */
    private $datecreated;

    /**
     * @var boolean
     *
     * @ORM\Column(name="initPwd", type="boolean", nullable=false)
     */
    private $initpwd = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="idSession", type="string", length=30, nullable=false)
     */
    private $idsession;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbConnect", type="integer", nullable=false)
     */
    private $nbconnect = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $actif = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="superUser", type="boolean", nullable=false)
     */
    private $superuser = '0';


}


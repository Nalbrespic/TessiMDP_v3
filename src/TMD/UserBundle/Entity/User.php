<?php

namespace TMD\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="tmd_user")
 * @ORM\Entity(repositoryClass="TMD\UserBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="matricule", type="string", length=35, nullable=true)
     */
    private $matricule;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=35, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=35, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="service", type="string", length=20, nullable=false)
     */
    private $service;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=35, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;



    /**
     * @ORM\ManyToMany(targetEntity="TMD\UserBundle\Entity\Role")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\JoinTable(name="tmd_user_role")
     */
    private $roles ;

    /**
     * User constructor.
     * @param $roles
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->salt = md5("une_Question%Que#Je^Me9Pose");

    }

    public function addRole(Role $role)
    {
        $this->roles[] = $role;
    }

    public function removeRole(Role $role)
    {
        $this->roles->removeElement($role);
    }

    public function getRoles()
    {
        $roles = array();
        foreach ($this->roles as $role)
        {
            $roles[] = $role->getName();
        }
        return $roles;
    }

    public function getRole()
    {
        return $this->roles;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
//
    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return string
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @return string
     */
    public function getMatricule()
    {
        return $this->matricule;
    }

    /**
     * @param string $matricule
     */
    public function setMatricule($matricule)
    {
        $this->matricule = $matricule;
    }


    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param string $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }




    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}


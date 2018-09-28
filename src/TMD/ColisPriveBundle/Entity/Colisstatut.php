<?php

namespace TMD\ColisPriveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Colisstatut
 *
 * @ORM\Table(name="colisstatut")
 * @ORM\Entity
 */
class Colisstatut
{
    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=100, nullable=false)
     */
    private $libelle;

    /**
     * @var boolean
     *
     * @ORM\Column(name="arret_chrono", type="boolean", nullable=false)
     */
    private $arretChrono = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="livre", type="boolean", nullable=false)
     */
    private $livre = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="text", length=255)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $statut;

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    /**
     * @return bool
     */
    public function isArretChrono()
    {
        return $this->arretChrono;
    }

    /**
     * @param bool $arretChrono
     */
    public function setArretChrono($arretChrono)
    {
        $this->arretChrono = $arretChrono;
    }

    /**
     * @return bool
     */
    public function isLivre()
    {
        return $this->livre;
    }

    /**
     * @param bool $livre
     */
    public function setLivre($livre)
    {
        $this->livre = $livre;
    }

    /**
     * @return Trackings
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param Trackings $statut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }


}


<?php

namespace TMD\ProdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * EcommStatut
 *
 * @ORM\Table(name="ecomm_statut")
 * @ORM\Entity(repositoryClass="TMD\ProdBundle\Repository\EcommStatutRepository", readOnly=false)
 */
class EcommStatut
{

    /**
     * @var integer
     *
     * @ORM\Column(name="idStatut", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idStatut;

    /**
     * @var string
     *
     * @ORM\Column(name="abregeStatut", type="string", length=10, nullable=false)
     */
    private $abregestatut;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=255, nullable=false)
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(name="coulStatut", type="string", length=12, nullable=false)
     */
    private $coulstatut;


    /**
     * Get idstatut
     *
     * @return integer
     */
    public function getIdStatut()
    {
        return $this->idStatut;
    }

    /**
     * Set abregestatut
     *
     * @param string $abregestatut
     *
     * @return EcommStatut
     */
    public function setAbregestatut($abregestatut)
    {
        $this->abregestatut = $abregestatut;

        return $this;
    }


    /**
     * Get abregestatut
     *
     * @return string
     */
    public function getAbregestatut()
    {
        return $this->abregestatut;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return EcommStatut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }


    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set coulstatut
     *
     * @param string $coulstatut
     *
     * @return EcommStatut
     */
    public function setCoulstatut($coulstatut)
    {
        $this->coulstatut = $coulstatut;

        return $this;
    }


    /**
     * Get coulstatut
     *
     * @return string
     */
    public function getCoulstatut()
    {
        return $this->coulstatut;
    }

}

<?php

namespace TMD\ProdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TMD\ProdBundle\Repository\EcommTrtAppliRepository;

/**
 * EcommTracking
 *
 * @ORM\Table(name="ecomm_trt_appli")
 * @ORM\Entity(repositoryClass="TMD\ProdBundle\Repository\EcommTrtAppliRepository", readOnly=false)
 */
class EcommTrtAppli
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idtrtAppli", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idtrtappli;

    /**
     * @var integer
     *
     * @ORM\Column(name="trtAppli", type="smallint", length=1, nullable=false)
     */
    private $trtAppli;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=50, nullable=false)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaires", type="string", length=50, nullable=false)
     */
    private $commentaires;

    /**
     * @return int
     */
    public function getIdtrtappli(): int
    {
        return $this->idtrtappli;
    }

    /**
     * @param int $idtrtappli
     */
    public function setIdtrtappli(int $idtrtappli)
    {
        $this->idtrtappli = $idtrtappli;
    }

    /**
     * @return int
     */
    public function getTrtAppli(): int
    {
        return $this->trtAppli;
    }

    /**
     * @param int $trtAppli
     */
    public function setTrtAppli(int $trtAppli)
    {
        $this->trtAppli = $trtAppli;
    }

    /**
     * @return string
     */
    public function getLibelle(): string
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     */
    public function setLibelle(string $libelle)
    {
        $this->libelle = $libelle;
    }

    /**
     * @return string
     */
    public function getCommentaires(): string
    {
        return $this->commentaires;
    }

    /**
     * @param string $commentaires
     */
    public function setCommentaires(string $commentaires)
    {
        $this->commentaires = $commentaires;
    }
}
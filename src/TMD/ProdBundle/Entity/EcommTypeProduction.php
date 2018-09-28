<?php

namespace TMD\ProdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EcommTypeProduction
 *
 * @ORM\Table(name="ecomm_type_production", uniqueConstraints={@ORM\UniqueConstraint(name="type_production", columns={"idTypeProd"})})
 * @ORM\Entity(repositoryClass="TMD\ProdBundle\Repository\EcommTypeProductionRepository", readOnly=false)
 */
class EcommTypeProduction
{
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
     * @var integer
     *
     * @ORM\Column(name="TypeProd", type="smallint")
    */
    private $typeprod;

    /**
     * @var integer
     *
     * @ORM\Column(name="idTypeProd", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTypeProd;


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
     * @return string
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * @param string $commentaires
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;
    }

    /**
     * @return bool
     */
    public function isTypeprod()
    {
        return $this->typeprod;
    }

    /**
     * @param bool $typeprod
     */
    public function setTypeprod($typeprod)
    {
        $this->typeprod = $typeprod;
    }

    /**
     * @return int
     */
    public function getIdTypeProd()
    {
        return $this->idTypeProd;
    }


}


<?php

namespace TMD\ProdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EcommArticles
 *
 * @ORM\Table(name="ecomm_articles", uniqueConstraints={@ORM\UniqueConstraint(name="article", columns={"idAppli", "codeArticle"})})
 * @ORM\Entity
 */
class EcommArticles
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idAppli", type="smallint", nullable=false)
     */
    private $idappli;

    /**
     * @var string
     *
     * @ORM\Column(name="codeArticle", type="string", length=40, nullable=false)
     */
    private $codearticle;

    /**
     * @var string
     *
     * @ORM\Column(name="abregeArticle", type="string", length=20, nullable=false, options={"default" = ""})
     */
    private $abregearticle;

    /**
     * @var string
     *
     * @ORM\Column(name="libArticle", type="string", length=100, nullable=false)
     */
    private $libarticle;

    /**
     * @var integer
     *
     * @ORM\Column(name="poidsUnitaire", type="integer", nullable=false, options={"default" = 0})
     */
    private $poidsunitaire ;

    /**
     * @var string
     *
     * @ORM\Column(name="prixHT", type="decimal", precision=10, scale=3, nullable=false, options={"default" = 0})
     */
    private $prixht;

    /**
     * @var string
     *
     * @ORM\Column(name="imgArticle", type="string", length=30, nullable=false, options={"default" = ""})
     */
    private $imgarticle;

    /**
     * @var integer
     *
     * @ORM\Column(name="codeTri", type="integer", nullable=false, options={"default" = 0})
     */
    private $codetri = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="epaisseur", type="smallint", nullable=false, options={"default" = 0})
     */
    private $epaisseur = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="idArticle", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idarticle;


}


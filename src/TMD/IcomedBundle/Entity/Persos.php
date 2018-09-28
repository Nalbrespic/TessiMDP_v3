<?php

namespace TMD\IcomedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Persos
 *
 * @ORM\Table(name="persos", indexes={@ORM\Index(name="refCdeClient", columns={"refCdeClient"}), @ORM\Index(name="numBP", columns={"numBP"})})
 * @ORM\Entity
 */
class Persos
{
    /**
     * @var string
     *
     * @ORM\Column(name="refCdeClient", type="string", length=30, nullable=false)
     */
    private $refcdeclient;

    /**
     * @var integer
     *
     * @ORM\Column(name="numCdeTessi", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $numcdetessi;

    /**
     * @var string
     *
     * @ORM\Column(name="refArticleTessi", type="string", length=20, nullable=false)
     */
    private $refarticletessi;

    /**
     * @var integer
     *
     * @ORM\Column(name="numSeq", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $numseq;

    /**
     * @var string
     *
     * @ORM\Column(name="perso1", type="string", length=50, nullable=false)
     */
    private $perso1;

    /**
     * @var string
     *
     * @ORM\Column(name="perso2", type="string", length=50, nullable=false)
     */
    private $perso2;

    /**
     * @var integer
     *
     * @ORM\Column(name="Qte", type="smallint", nullable=false)
     */
    private $qte;

    /**
     * @var integer
     *
     * @ORM\Column(name="poidsArticle", type="integer", nullable=false)
     */
    private $poidsarticle = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="refArticleClient", type="string", length=20, nullable=false)
     */
    private $refarticleclient;

    /**
     * @var string
     *
     * @ORM\Column(name="libArticle", type="string", length=50, nullable=false)
     */
    private $libarticle;

    /**
     * @var integer
     *
     * @ORM\Column(name="numBP", type="integer", nullable=false)
     */
    private $numbp;


}


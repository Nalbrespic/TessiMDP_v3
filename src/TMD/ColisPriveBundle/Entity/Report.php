<?php

namespace TMD\ColisPriveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Report
 *
 * @ORM\Table(name="report")
 * @ORM\Entity
 */
class Report
{
    /**
     * @var string
     *
     * @ORM\Column(name="codeCentre", type="string", length=2, nullable=false)
     */
    private $codecentre;

    /**
     * @var string
     *
     * @ORM\Column(name="dateEnr", type="string", length=6, nullable=false)
     */
    private $dateenr;

    /**
     * @var string
     *
     * @ORM\Column(name="heureEvmnt", type="string", length=4, nullable=false)
     */
    private $heureevmnt;

    /**
     * @var string
     *
     * @ORM\Column(name="codeClient", type="string", length=2, nullable=false)
     */
    private $codeclient;

    /**
     * @var string
     *
     * @ORM\Column(name="numTracking", type="string", length=10, nullable=false)
     */
    private $numtracking;

    /**
     * @var string
     *
     * @ORM\Column(name="refClient", type="string", length=10, nullable=false)
     */
    private $refclient;

    /**
     * @var string
     *
     * @ORM\Column(name="numOrdre", type="string", length=10, nullable=false)
     */
    private $numordre;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=2, nullable=false)
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(name="modeLivraison", type="string", length=2, nullable=false)
     */
    private $modelivraison;

    /**
     * @var string
     *
     * @ORM\Column(name="dateEvmnt", type="string", length=6, nullable=false)
     */
    private $dateevmnt;

    /**
     * @var string
     *
     * @ORM\Column(name="modeReglement", type="string", length=2, nullable=false)
     */
    private $modereglement;

    /**
     * @var string
     *
     * @ORM\Column(name="motifLivraison", type="string", length=2, nullable=false)
     */
    private $motiflivraison;

    /**
     * @var string
     *
     * @ORM\Column(name="dateCRT", type="string", length=6, nullable=false)
     */
    private $datecrt;

    /**
     * @var integer
     *
     * @ORM\Column(name="mntCRT", type="integer", nullable=false)
     */
    private $mntcrt;

    /**
     * @var string
     *
     * @ORM\Column(name="devise", type="string", length=2, nullable=false)
     */
    private $devise;

    /**
     * @var integer
     *
     * @ORM\Column(name="num", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $num;


}


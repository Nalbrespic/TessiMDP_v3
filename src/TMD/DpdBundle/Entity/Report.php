<?php

namespace TMD\DpdBundle\Entity;

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
     * @var integer
     *
     * @ORM\Column(name="agence", type="smallint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $agence;

    /**
     * @var integer
     *
     * @ORM\Column(name="numCompteExpe", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $numcompteexpe;

    /**
     * @var integer
     *
     * @ORM\Column(name="numTrackingBar", type="bigint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $numtrackingbar;

    /**
     * @var integer
     *
     * @ORM\Column(name="numConsolidation", type="bigint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $numconsolidation;

    /**
     * @var string
     *
     * @ORM\Column(name="exp_ref1", type="string", length=35, precision=0, scale=0, nullable=true, unique=false)
     */
    private $expRef1;

    /**
     * @var string
     *
     * @ORM\Column(name="exp_ref2", type="string", length=35, precision=0, scale=0, nullable=true, unique=false)
     */
    private $expRef2;

    /**
     * @var string
     *
     * @ORM\Column(name="exp_ref3", type="string", length=35, precision=0, scale=0, nullable=true, unique=false)
     */
    private $expRef3;

    /**
     * @var string
     *
     * @ORM\Column(name="BIC3", type="string", length=14, precision=0, scale=0, nullable=true, unique=false)
     */
    private $bic3;

    /**
     * @var string
     *
     * @ORM\Column(name="dateExpedition", type="string", length=10, precision=0, scale=0, nullable=true, unique=false)
     */
    private $dateexpedition;

    /**
     * @var string
     *
     * @ORM\Column(name="poids", type="string", length=9, precision=0, scale=0, nullable=false, unique=false)
     */
    private $poids;

    /**
     * @var string
     *
     * @ORM\Column(name="destinataire", type="string", length=35, precision=0, scale=0, nullable=true, unique=false)
     */
    private $destinataire;

    /**
     * @var string
     *
     * @ORM\Column(name="dest_rue", type="string", length=70, precision=0, scale=0, nullable=true, unique=false)
     */
    private $destRue;

    /**
     * @var string
     *
     * @ORM\Column(name="dest_ville", type="string", length=35, precision=0, scale=0, nullable=true, unique=false)
     */
    private $destVille;

    /**
     * @var string
     *
     * @ORM\Column(name="dest_cp", type="string", length=10, precision=0, scale=0, nullable=true, unique=false)
     */
    private $destCp;

    /**
     * @var string
     *
     * @ORM\Column(name="dest_pays", type="string", length=2, precision=0, scale=0, nullable=true, unique=false)
     */
    private $destPays;

    /**
     * @var integer
     *
     * @ORM\Column(name="statut", type="smallint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(name="extensionStatut", type="string", length=256, precision=0, scale=0, nullable=true, unique=false)
     */
    private $extensionstatut;

    /**
     * @var string
     *
     * @ORM\Column(name="anomaliePredict", type="string", length=255, precision=0, scale=0, nullable=true, unique=false)
     */
    private $anomaliepredict;

    /**
     * @var string
     *
     * @ORM\Column(name="statutDate", type="string", length=10, precision=0, scale=0, nullable=true, unique=false)
     */
    private $statutdate;

    /**
     * @var string
     *
     * @ORM\Column(name="statutHeure", type="string", length=8, precision=0, scale=0, nullable=true, unique=false)
     */
    private $statutheure;

    /**
     * @var string
     *
     * @ORM\Column(name="dateRDV", type="string", length=10, precision=0, scale=0, nullable=true, unique=false)
     */
    private $daterdv;

    /**
     * @var string
     *
     * @ORM\Column(name="newDateLivr", type="string", length=10, precision=0, scale=0, nullable=true, unique=false)
     */
    private $newdatelivr;

    /**
     * @var string
     *
     * @ORM\Column(name="receptionNom", type="string", length=35, precision=0, scale=0, nullable=true, unique=false)
     */
    private $receptionnom;

    /**
     * @var string
     *
     * @ORM\Column(name="urlTrace", type="string", length=256, precision=0, scale=0, nullable=true, unique=false)
     */
    private $urltrace;

    /**
     * @var string
     *
     * @ORM\Column(name="urlPod", type="string", length=256, precision=0, scale=0, nullable=true, unique=false)
     */
    private $urlpod;

    /**
     * @var integer
     *
     * @ORM\Column(name="num", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $num;


}


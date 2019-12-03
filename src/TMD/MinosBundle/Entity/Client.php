<?php

namespace TMD\MinosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

//@ORM\Table(name="CLIENT", uniqueConstraints={@ORM\UniqueConstraint(name="unik_code_cli", columns={"CODE_CLI"})}, indexes={@ORM\Index(name="regroupe2_fk", columns={"GPT_CODE"}), @ORM\Index(name="civilite_contact_fk", columns={"CODE_CIVILITE"}), @ORM\Index(name="est_un3_fk", columns={"TT_ID"}), @ORM\Index(name="adr_prest_fact_fk", columns={"CODE_EXP_FACT_PREST"}), @ORM\Index(name="regl_prest_fk", columns={"CODE_MR_PREST"}), @ORM\Index(name="contact_com_fk", columns={"MATRICULE_SUIVI"}), @ORM\Index(name="regl_affr_fk", columns={"CODE_MR_AFFR"}), @ORM\Index(name="regl_achat_fk", columns={"CODE_MR_ACHAT"}), @ORM\Index(name="adr_fact_four_fk", columns={"CODE_EXP_FACT_ACHAT"}), @ORM\Index(name="contact_technico_fk", columns={"MATRICULE_TECH"}), @ORM\Index(name="adr_affr_fact_fk", columns={"CODE_EXP_FACT_AFFR"})})
/**
 * Client
 *
 * @ORM\Table(name="CLIENT", uniqueConstraints={@ORM\UniqueConstraint(name="unik_code_cli", columns={"CODE_CLI"})}, indexes={  }) * @ORM\Entity
 */
class Client
{
    /**
     * @var string
     *
     * @ORM\Column(name="IDCLI", type="string", length=5, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="CLIENT_IDCLI_seq", allocationSize=1, initialValue=1)
     */
    private $idcli;

    /**
     * @var string
     *
     * @ORM\Column(name="CODE_CLI", type="string", length=20, nullable=true)
     */
    private $codeCli;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMCLI", type="string", length=40, nullable=true)
     */
    private $nomcli;

    /**
     * @var string
     *
     * @ORM\Column(name="ADR1_CLI", type="string", length=40, nullable=true)
     */
    private $adr1Cli;

    /**
     * @var string
     *
     * @ORM\Column(name="ADR2_CLI", type="string", length=40, nullable=true)
     */
    private $adr2Cli;

    /**
     * @var string
     *
     * @ORM\Column(name="ADR3_CLI", type="string", length=40, nullable=true)
     */
    private $adr3Cli;

    /**
     * @var string
     *
     * @ORM\Column(name="CODPOS_CLI", type="string", length=10, nullable=true)
     */
    private $codposCli;

    /**
     * @var string
     *
     * @ORM\Column(name="VILLE_CLI", type="string", length=40, nullable=true)
     */
    private $villeCli;

    /**
     * @var string
     *
     * @ORM\Column(name="TEL_CLI", type="string", length=20, nullable=true)
     */
    private $telCli;

    /**
     * @var string
     *
     * @ORM\Column(name="FAX_CLI", type="string", length=20, nullable=true)
     */
    private $faxCli;

    /**
     * @var string
     *
     * @ORM\Column(name="MSG_CLI", type="string", length=60, nullable=true)
     */
    private $msgCli;

    /**
     * @var string
     *
     * @ORM\Column(name="OBSERVATION_CLI", type="string", length=1000, nullable=true)
     */
    private $observationCli;

    /**
     * @var string
     *
     * @ORM\Column(name="SUIVI_CLI", type="string", length=1, nullable=true)
     */
    private $suiviCli = 'N';

    /**
     * @var string
     *
     * @ORM\Column(name="FOURN_CLI", type="string", length=1, nullable=true)
     */
    private $fournCli;

    /**
     * @var string
     *
     * @ORM\Column(name="CONTACT_CLI", type="string", length=20, nullable=true)
     */
    private $contactCli;

    /**
     * @var string
     *
     * @ORM\Column(name="CODFACT", type="string", length=20, nullable=true)
     */
    private $codfact;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMCLI2", type="string", length=40, nullable=true)
     */
    private $nomcli2;

    /**
     * @var string
     *
     * @ORM\Column(name="PAYS_CLI", type="string", length=40, nullable=true)
     */
    private $paysCli;

    /**
     * @var string
     *
     * @ORM\Column(name="SIRET_CLI", type="string", length=40, nullable=true)
     */
    private $siretCli;

    /**
     * @var string
     *
     * @ORM\Column(name="TVA_CEE_CLI", type="string", length=20, nullable=true)
     */
    private $tvaCeeCli;

    /**
     * @var string
     *
     * @ORM\Column(name="FORMAT_CODE_COLIS_CLI", type="string", length=30, nullable=true)
     */
    private $formatCodeColisCli;

    /**
     * @var string
     *
     * @ORM\Column(name="FORMULAIRE_COLIS_CLI", type="string", length=25, nullable=true)
     */
    private $formulaireColisCli;

    /**
     * @var string
     *
     * @ORM\Column(name="FORMULAIRE_RPT_COLIS_CLI", type="string", length=25, nullable=true)
     */
    private $formulaireRptColisCli;

    /**
     * @var string
     *
     * @ORM\Column(name="DEFAUT_CLI", type="string", length=1, nullable=false)
     */
    private $defautCli = 'N';

//    /**
//     * @var \Adrexp
//     *
//     * @ORM\ManyToOne(targetEntity="Adrexp")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="CODE_EXP_FACT_AFFR", referencedColumnName="CODE_EXP")
//     * })
//     */
//    private $codeExpFactAffr;

    /**
     * @return string
     */
    public function getIdcli()
    {
        return $this->idcli;
    }

    /**
     * @param string $idcli
     */
    public function setIdcli($idcli)
    {
        $this->idcli = $idcli;
    }

    /**
     * @return string
     */
    public function getCodeCli()
    {
        return $this->codeCli;
    }

    /**
     * @param string $codeCli
     */
    public function setCodeCli($codeCli)
    {
        $this->codeCli = $codeCli;
    }

    /**
     * @return string
     */
    public function getNomcli()
    {
        return $this->nomcli;
    }

    /**
     * @param string $nomcli
     */
    public function setNomcli($nomcli)
    {
        $this->nomcli = $nomcli;
    }

    /**
     * @return string
     */
    public function getAdr1Cli()
    {
        return $this->adr1Cli;
    }

    /**
     * @param string $adr1Cli
     */
    public function setAdr1Cli($adr1Cli)
    {
        $this->adr1Cli = $adr1Cli;
    }

    /**
     * @return string
     */
    public function getAdr2Cli()
    {
        return $this->adr2Cli;
    }

    /**
     * @param string $adr2Cli
     */
    public function setAdr2Cli($adr2Cli)
    {
        $this->adr2Cli = $adr2Cli;
    }

    /**
     * @return string
     */
    public function getAdr3Cli()
    {
        return $this->adr3Cli;
    }

    /**
     * @param string $adr3Cli
     */
    public function setAdr3Cli($adr3Cli)
    {
        $this->adr3Cli = $adr3Cli;
    }

    /**
     * @return string
     */
    public function getCodposCli()
    {
        return $this->codposCli;
    }

    /**
     * @param string $codposCli
     */
    public function setCodposCli($codposCli)
    {
        $this->codposCli = $codposCli;
    }

    /**
     * @return string
     */
    public function getVilleCli()
    {
        return $this->villeCli;
    }

    /**
     * @param string $villeCli
     */
    public function setVilleCli($villeCli)
    {
        $this->villeCli = $villeCli;
    }

    /**
     * @return string
     */
    public function getTelCli()
    {
        return $this->telCli;
    }

    /**
     * @param string $telCli
     */
    public function setTelCli($telCli)
    {
        $this->telCli = $telCli;
    }

    /**
     * @return string
     */
    public function getFaxCli()
    {
        return $this->faxCli;
    }

    /**
     * @param string $faxCli
     */
    public function setFaxCli($faxCli)
    {
        $this->faxCli = $faxCli;
    }

    /**
     * @return string
     */
    public function getMsgCli()
    {
        return $this->msgCli;
    }

    /**
     * @param string $msgCli
     */
    public function setMsgCli($msgCli)
    {
        $this->msgCli = $msgCli;
    }

    /**
     * @return string
     */
    public function getObservationCli()
    {
        return $this->observationCli;
    }

    /**
     * @param string $observationCli
     */
    public function setObservationCli($observationCli)
    {
        $this->observationCli = $observationCli;
    }

    /**
     * @return string
     */
    public function getSuiviCli()
    {
        return $this->suiviCli;
    }

    /**
     * @param string $suiviCli
     */
    public function setSuiviCli($suiviCli)
    {
        $this->suiviCli = $suiviCli;
    }

    /**
     * @return string
     */
    public function getFournCli()
    {
        return $this->fournCli;
    }

    /**
     * @param string $fournCli
     */
    public function setFournCli($fournCli)
    {
        $this->fournCli = $fournCli;
    }

    /**
     * @return string
     */
    public function getContactCli()
    {
        return $this->contactCli;
    }

    /**
     * @param string $contactCli
     */
    public function setContactCli($contactCli)
    {
        $this->contactCli = $contactCli;
    }

    /**
     * @return string
     */
    public function getCodfact()
    {
        return $this->codfact;
    }

    /**
     * @param string $codfact
     */
    public function setCodfact($codfact)
    {
        $this->codfact = $codfact;
    }

    /**
     * @return string
     */
    public function getNomcli2()
    {
        return $this->nomcli2;
    }

    /**
     * @param string $nomcli2
     */
    public function setNomcli2($nomcli2)
    {
        $this->nomcli2 = $nomcli2;
    }

    /**
     * @return string
     */
    public function getPaysCli()
    {
        return $this->paysCli;
    }

    /**
     * @param string $paysCli
     */
    public function setPaysCli($paysCli)
    {
        $this->paysCli = $paysCli;
    }

    /**
     * @return string
     */
    public function getSiretCli()
    {
        return $this->siretCli;
    }

    /**
     * @param string $siretCli
     */
    public function setSiretCli($siretCli)
    {
        $this->siretCli = $siretCli;
    }

    /**
     * @return string
     */
    public function getTvaCeeCli()
    {
        return $this->tvaCeeCli;
    }

    /**
     * @param string $tvaCeeCli
     */
    public function setTvaCeeCli($tvaCeeCli)
    {
        $this->tvaCeeCli = $tvaCeeCli;
    }

    /**
     * @return string
     */
    public function getFormatCodeColisCli()
    {
        return $this->formatCodeColisCli;
    }

    /**
     * @param string $formatCodeColisCli
     */
    public function setFormatCodeColisCli($formatCodeColisCli)
    {
        $this->formatCodeColisCli = $formatCodeColisCli;
    }

    /**
     * @return string
     */
    public function getFormulaireColisCli()
    {
        return $this->formulaireColisCli;
    }

    /**
     * @param string $formulaireColisCli
     */
    public function setFormulaireColisCli($formulaireColisCli)
    {
        $this->formulaireColisCli = $formulaireColisCli;
    }

    /**
     * @return string
     */
    public function getFormulaireRptColisCli()
    {
        return $this->formulaireRptColisCli;
    }

    /**
     * @param string $formulaireRptColisCli
     */
    public function setFormulaireRptColisCli($formulaireRptColisCli)
    {
        $this->formulaireRptColisCli = $formulaireRptColisCli;
    }

    /**
     * @return string
     */
    public function getDefautCli()
    {
        return $this->defautCli;
    }

    /**
     * @param string $defautCli
     */
    public function setDefautCli($defautCli)
    {
        $this->defautCli = $defautCli;
    }

//    /**
//     * @return \Adrexp
//     */
//    public function getCodeExpFactAffr()
//    {
//        return $this->codeExpFactAffr;
//    }
//
//    /**
//     * @param \Adrexp $codeExpFactAffr
//     */
//    public function setCodeExpFactAffr($codeExpFactAffr)
//    {
//        $this->codeExpFactAffr = $codeExpFactAffr;
//    }

//    /**
//     * @var \Adrexp
//     *
//     * @ORM\ManyToOne(targetEntity="Adrexp")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="CODE_EXP_FACT_ACHAT", referencedColumnName="CODE_EXP")
//     * })
//     */
//    private $codeExpFactAchat;
//
//    /**
//     * @var \Adrexp
//     *
//     * @ORM\ManyToOne(targetEntity="Adrexp")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="CODE_EXP_FACT_PREST", referencedColumnName="CODE_EXP")
//     * })
//     */
//    private $codeExpFactPrest;
//
//    /**
//     * @var \Civilite
//     *
//     * @ORM\ManyToOne(targetEntity="Civilite")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="CODE_CIVILITE", referencedColumnName="CODE_CIVILITE")
//     * })
//     */
//    private $codeCivilite;
//
//    /**
//     * @var \Employe
//     *
//     * @ORM\ManyToOne(targetEntity="Employe")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="MATRICULE_SUIVI", referencedColumnName="MATRICULE")
//     * })
//     */
//    private $matriculeSuivi;
//
//    /**
//     * @var \Employe
//     *
//     * @ORM\ManyToOne(targetEntity="Employe")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="MATRICULE_TECH", referencedColumnName="MATRICULE")
//     * })
//     */
//    private $matriculeTech;
//
//    /**
//     * @var \TypeTiers
//     *
//     * @ORM\ManyToOne(targetEntity="TypeTiers")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="TT_ID", referencedColumnName="TT_ID")
//     * })
//     */
//    private $tt;
//
//    /**
//     * @var \GroupeTiers
//     *
//     * @ORM\ManyToOne(targetEntity="GroupeTiers")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="GPT_CODE", referencedColumnName="GPT_CODE")
//     * })
//     */
//    private $gptCode;
//
//    /**
//     * @var \ModaliteReglement
//     *
//     * @ORM\ManyToOne(targetEntity="ModaliteReglement")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="CODE_MR_ACHAT", referencedColumnName="CODE_MR")
//     * })
//     */
//    private $codeMrAchat;
//
//    /**
//     * @var \ModaliteReglement
//     *
//     * @ORM\ManyToOne(targetEntity="ModaliteReglement")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="CODE_MR_AFFR", referencedColumnName="CODE_MR")
//     * })
//     */
//    private $codeMrAffr;
//
//    /**
//     * @var \ModaliteReglement
//     *
//     * @ORM\ManyToOne(targetEntity="ModaliteReglement")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="CODE_MR_PREST", referencedColumnName="CODE_MR")
//     * })
//     */
//    private $codeMrPrest;



}


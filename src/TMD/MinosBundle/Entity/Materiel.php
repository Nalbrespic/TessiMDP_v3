<?php

namespace TMD\MinosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

//@ORM\Table(name="MATERIEL", uniqueConstraints={@ORM\UniqueConstraint(name="pk_code_mat", columns={"CODE_MAT"})}, indexes={@ORM\Index(name="substitution_fk", columns={"MAT_IDMAT_SUBST"}), @ORM\Index(name="mesure_vol_fk", columns={"UM_CODE_VOLUME"}), @ORM\Index(name="mesure_stock_fk", columns={"UM_CODE_STOCK"}), @ORM\Index(name="possede_stdf_fk", columns={"MST_ID"}), @ORM\Index(name="lie_a_fk", columns={"TYPE_MAT"}), @ORM\Index(name="specif_proj_fk", columns={"PRJ_ID"}), @ORM\Index(name="appartient_a3_fk", columns={"SFM_ID"}), @ORM\Index(name="mes_achat_fk", columns={"UM_CODE_ACHAT"}), @ORM\Index(name="cli_do_fk", columns={"IDCLI_DO"}), @ORM\Index(name="appartient_fk", columns={"CODFAMMAT"}), @ORM\Index(name="est_approvisionne_par_fk", columns={"IDCLI"}), @ORM\Index(name="mat_plan_fk", columns={"IDPLAN"}), @ORM\Index(name="unite_stockage_fk", columns={"UM_CODE_LONGUEUR"}), @ORM\Index(name="mesure_larg_fk", columns={"UM_CODE_LARGEUR"}), @ORM\Index(name="mesure_haut_fk", columns={"UM_CODE_HAUTEUR"}), @ORM\Index(name="mesure_pds_fk", columns={"UM_CODE_POIDS"}), @ORM\Index(name="remplacement_fk", columns={"MAT_IDMAT_REMPL"}), @ORM\Index(name="IDX_DEE12CDD5F04905D", columns={"IDCLI_FAB_EDITEUR"})})
/**
 * Materiel
 *
 * @ORM\Table(name="MATERIEL", uniqueConstraints={@ORM\UniqueConstraint(name="pk_code_mat", columns={"CODE_MAT"})}, indexes={@ORM\Index(name="substitution_fk", columns={"MAT_IDMAT_SUBST"}), @ORM\Index(name="mesure_vol_fk", columns={"UM_CODE_VOLUME"}), @ORM\Index(name="mesure_stock_fk", columns={"UM_CODE_STOCK"}), @ORM\Index(name="possede_stdf_fk", columns={"MST_ID"}), @ORM\Index(name="lie_a_fk", columns={"TYPE_MAT"}), @ORM\Index(name="appartient_a3_fk", columns={"SFM_ID"}), @ORM\Index(name="mes_achat_fk", columns={"UM_CODE_ACHAT"}), @ORM\Index(name="cli_do_fk", columns={"IDCLI_DO"}), @ORM\Index(name="appartient_fk", columns={"CODFAMMAT"}), @ORM\Index(name="est_approvisionne_par_fk", columns={"IDCLI"}), @ORM\Index(name="unite_stockage_fk", columns={"UM_CODE_LONGUEUR"}), @ORM\Index(name="mesure_larg_fk", columns={"UM_CODE_LARGEUR"}), @ORM\Index(name="mesure_haut_fk", columns={"UM_CODE_HAUTEUR"}), @ORM\Index(name="mesure_pds_fk", columns={"UM_CODE_POIDS"}), @ORM\Index(name="remplacement_fk", columns={"MAT_IDMAT_REMPL"}), @ORM\Index(name="IDX_DEE12CDD5F04905D", columns={"IDCLI_FAB_EDITEUR"})})
 * @ORM\Entity
 */
class Materiel
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IDMAT", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="MATERIEL_IDMAT_seq", allocationSize=1, initialValue=1)
     */
    private $idmat;

    /**
     * @var string
     *
     * @ORM\Column(name="CODE_MAT", type="string", length=35, nullable=false)
     */
    private $codeMat;

    /**
     * @var string
     *
     * @ORM\Column(name="CODE_MAT_TIER", type="string", length=35, nullable=true)
     */
    private $codeMatTier;

    /**
     * @var string
     *
     * @ORM\Column(name="DESIG_MAT", type="string", length=80, nullable=true)
     */
    private $desigMat;

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_COMMENTAIRE", type="string", length=1000, nullable=true)
     */
    private $matCommentaire;

    /**
     * @var integer
     *
     * @ORM\Column(name="MAT_PORTEUR_DE", type="integer", nullable=true)
     */
    private $matPorteurDe;

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_REPETITIF", type="string", length=1, nullable=true)
     */
    private $matRepetitif = 'N';

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_BLOQUE", type="string", length=1, nullable=true)
     */
    private $matBloque = 'N';

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_AVEC_LOT", type="string", length=1, nullable=true)
     */
    private $matAvecLot = 'N';

    /**
     * @var float
     *
     * @ORM\Column(name="COM_MAT", type="float", precision=126, scale=0, nullable=true)
     */
    private $comMat;

    /**
     * @var float
     *
     * @ORM\Column(name="STOCK_MINI_MAT", type="float", precision=126, scale=0, nullable=true)
     */
    private $stockMiniMat;

    /**
     * @var float
     *
     * @ORM\Column(name="MAT_HAUTEUR", type="float", precision=126, scale=0, nullable=true)
     */
    private $matHauteur;

    /**
     * @var float
     *
     * @ORM\Column(name="MAT_LARGEUR", type="float", precision=126, scale=0, nullable=true)
     */
    private $matLargeur;

    /**
     * @var float
     *
     * @ORM\Column(name="MAT_LONGUEUR", type="float", precision=126, scale=0, nullable=true)
     */
    private $matLongueur;

    /**
     * @var float
     *
     * @ORM\Column(name="MAT_POIDS", type="float", precision=126, scale=0, nullable=true)
     */
    private $matPoids;

    /**
     * @var float
     *
     * @ORM\Column(name="MAT_VOLUME", type="float", precision=126, scale=0, nullable=true)
     */
    private $matVolume;

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_CONT_STD", type="string", length=1, nullable=true)
     */
    private $matContStd;

    /**
     * @var string
     *
     * @ORM\Column(name="UNITE_MAT", type="string", length=5, nullable=true)
     */
    private $uniteMat;

    /**
     * @var float
     *
     * @ORM\Column(name="STOCK_MINI_GPA", type="float", precision=126, scale=0, nullable=true)
     */
    private $stockMiniGpa;

    /**
     * @var float
     *
     * @ORM\Column(name="STOCK_MAXI_GPA", type="float", precision=126, scale=0, nullable=true)
     */
    private $stockMaxiGpa;

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_FAB_MECA", type="string", length=1, nullable=true)
     */
    private $matFabMeca = 'N';

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_LIBRE_1", type="string", length=40, nullable=true)
     */
    private $matLibre1;

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_LIBRE_2", type="string", length=40, nullable=true)
     */
    private $matLibre2;

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_LIBRE_3", type="string", length=40, nullable=true)
     */
    private $matLibre3;

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_LIBRE_4", type="string", length=40, nullable=true)
     */
    private $matLibre4;

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_LIBRE_5", type="string", length=40, nullable=true)
     */
    private $matLibre5;

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_LIBRE_6", type="string", length=40, nullable=true)
     */
    private $matLibre6;

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_AVEC_PMP", type="string", length=1, nullable=true)
     */
    private $matAvecPmp = 'N';

    /**
     * @var float
     *
     * @ORM\Column(name="MAT_HYGRO_MIN", type="float", precision=126, scale=0, nullable=true)
     */
    private $matHygroMin;

    /**
     * @var float
     *
     * @ORM\Column(name="MAT_HYGRO_MAX", type="float", precision=126, scale=0, nullable=true)
     */
    private $matHygroMax;

    /**
     * @var float
     *
     * @ORM\Column(name="MAT_TEMP_MIN", type="float", precision=126, scale=0, nullable=true)
     */
    private $matTempMin;

    /**
     * @var float
     *
     * @ORM\Column(name="MAT_TEMP_MAX", type="float", precision=126, scale=0, nullable=true)
     */
    private $matTempMax;

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_DOSAGE_CONSERV", type="string", length=40, nullable=true)
     */
    private $matDosageConserv;

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_FORME_CONSERV", type="string", length=40, nullable=true)
     */
    private $matFormeConserv;

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_INDIC_UTIL", type="string", length=60, nullable=true)
     */
    private $matIndicUtil;

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_DOSAGE_UTIL", type="string", length=40, nullable=true)
     */
    private $matDosageUtil;

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_MOD_UTIL", type="string", length=60, nullable=true)
     */
    private $matModUtil;

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_LIMIT_UTIL", type="string", length=40, nullable=true)
     */
    private $matLimitUtil;

    /**
     * @var float
     *
     * @ORM\Column(name="MAT_PMP", type="float", precision=126, scale=0, nullable=true)
     */
    private $matPmp;

    /**
     * @var float
     *
     * @ORM\Column(name="MAT_PMP_QTE", type="float", precision=126, scale=0, nullable=true)
     */
    private $matPmpQte;

    /**
     * @var string
     *
     * @ORM\Column(name="CODE_MAT3", type="string", length=35, nullable=true)
     */
    private $codeMat3;

    /**
     * @var string
     *
     * @ORM\Column(name="CODE_MAT4", type="string", length=35, nullable=true)
     */
    private $codeMat4;

    /**
     * @var string
     *
     * @ORM\Column(name="CODE_MAT5", type="string", length=35, nullable=true)
     */
    private $codeMat5;

    /**
     * @var float
     *
     * @ORM\Column(name="MAT_PMP_PU", type="float", precision=126, scale=0, nullable=true)
     */
    private $matPmpPu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="MAT_DT_DEPOT", type="date", nullable=true)
     */
    private $matDtDepot;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="MAT_DATECREAT", type="date", nullable=true)
     */
    private $matDatecreat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="MAT_DATEMODIF", type="date", nullable=true)
     */
    private $matDatemodif;

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_PICK_AVEC_LOT", type="string", length=1, nullable=true)
     */
    private $matPickAvecLot = 'N';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="MAT_DATE_REMPL", type="date", nullable=true)
     */
    private $matDateRempl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="MAT_PMP_DATE_MAJ", type="date", nullable=true)
     */
    private $matPmpDateMaj;

    /**
     * @var string
     *
     * @ORM\Column(name="MAT_IMAGE", type="blob", nullable=true)
     */
    private $matImage;

    /**
     * @var \TMD\MinosBundle\Entity\Fammat
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\Fammat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CODFAMMAT", referencedColumnName="CODFAMMAT")
     * })
     */
    private $codfammat;

    /**
     * @var \TMD\MinosBundle\Entity\Sfammat
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\Sfammat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SFM_ID", referencedColumnName="SFM_ID")
     * })
     */
    private $sfm;

    /**
     * @var \TMD\MinosBundle\Entity\Client
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IDCLI_DO", referencedColumnName="IDCLI")
     * })
     */
    private $idcliDo;

    /**
     * @var \TMD\MinosBundle\Entity\Client
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IDCLI_FAB_EDITEUR", referencedColumnName="IDCLI")
     * })
     */
    private $idcliFabEditeur;

    /**
     * @var \TMD\MinosBundle\Entity\TypeMateriel
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\TypeMateriel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TYPE_MAT", referencedColumnName="TYPE_MAT")
     * })
     */
    private $typeMat;

//    /**
//     * @var \Plans
//     *
//     * @ORM\ManyToOne(targetEntity="Plans")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="IDPLAN", referencedColumnName="IDPLAN")
//     * })
//     */
//    private $idplan;

    /**
     * @var \TMD\MinosBundle\Entity\UniteMesure
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\UniteMesure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="UM_CODE_ACHAT", referencedColumnName="UM_CODE")
     * })
     */
    private $umCodeAchat;

    /**
     * @var \TMD\MinosBundle\Entity\UniteMesure
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\UniteMesure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="UM_CODE_HAUTEUR", referencedColumnName="UM_CODE")
     * })
     */
    private $umCodeHauteur;

    /**
     * @var \TMD\MinosBundle\Entity\UniteMesure
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\UniteMesure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="UM_CODE_LARGEUR", referencedColumnName="UM_CODE")
     * })
     */
    private $umCodeLargeur;

    /**
     * @var \TMD\MinosBundle\Entity\UniteMesure
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\UniteMesure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="UM_CODE_LONGUEUR", referencedColumnName="UM_CODE")
     * })
     */
    private $umCodeLongueur;

    /**
     * @var \TMD\MinosBundle\Entity\UniteMesure
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\UniteMesure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="UM_CODE_POIDS", referencedColumnName="UM_CODE")
     * })
     */
    private $umCodePoids;

    /**
     * @var \TMD\MinosBundle\Entity\UniteMesure
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\UniteMesure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="UM_CODE_STOCK", referencedColumnName="UM_CODE")
     * })
     */
    private $umCodeStock;

    /**
     * @var \TMD\MinosBundle\Entity\UniteMesure
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\UniteMesure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="UM_CODE_VOLUME", referencedColumnName="UM_CODE")
     * })
     */
    private $umCodeVolume;

    /**
     * @var \TMD\MinosBundle\Entity\MatStatut
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\MatStatut")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MST_ID", referencedColumnName="MST_ID")
     * })
     */
    private $mst;

    /**
     * @var \TMD\MinosBundle\Entity\Client
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IDCLI", referencedColumnName="IDCLI")
     * })
     */
    private $idcli;

    /**
     * @var \TMD\MinosBundle\Entity\Materiel
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\Materiel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MAT_IDMAT_REMPL", referencedColumnName="IDMAT")
     * })
     */
    private $matmatRempl;

//    /**
//     * @var \Projet
//     *
//     * @ORM\ManyToOne(targetEntity="Projet")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="PRJ_ID", referencedColumnName="PRJ_ID")
//     * })
//     */
//    private $prj;

    /**
     * @var \TMD\MinosBundle\Entity\Materiel
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\Materiel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MAT_IDMAT_SUBST", referencedColumnName="IDMAT")
     * })
     */
    private $matmatSubst;



    /**
     * Get idmat
     *
     * @return integer
     */
    public function getIdmat()
    {
        return $this->idmat;
    }

    /**
     * Set codeMat
     *
     * @param string $codeMat
     *
     * @return Materiel
     */
    public function setCodeMat($codeMat)
    {
        $this->codeMat = $codeMat;

        return $this;
    }

    /**
     * Get codeMat
     *
     * @return string
     */
    public function getCodeMat()
    {
        return $this->codeMat;
    }

    /**
     * Set codeMatTier
     *
     * @param string $codeMatTier
     *
     * @return Materiel
     */
    public function setCodeMatTier($codeMatTier)
    {
        $this->codeMatTier = $codeMatTier;

        return $this;
    }

    /**
     * Get codeMatTier
     *
     * @return string
     */
    public function getCodeMatTier()
    {
        return $this->codeMatTier;
    }

    /**
     * Set desigMat
     *
     * @param string $desigMat
     *
     * @return Materiel
     */
    public function setDesigMat($desigMat)
    {
        $this->desigMat = $desigMat;

        return $this;
    }

    /**
     * Get desigMat
     *
     * @return string
     */
    public function getDesigMat()
    {
        return $this->desigMat;
    }

    /**
     * Set matCommentaire
     *
     * @param string $matCommentaire
     *
     * @return Materiel
     */
    public function setMatCommentaire($matCommentaire)
    {
        $this->matCommentaire = $matCommentaire;

        return $this;
    }

    /**
     * Get matCommentaire
     *
     * @return string
     */
    public function getMatCommentaire()
    {
        return $this->matCommentaire;
    }

    /**
     * Set matPorteurDe
     *
     * @param integer $matPorteurDe
     *
     * @return Materiel
     */
    public function setMatPorteurDe($matPorteurDe)
    {
        $this->matPorteurDe = $matPorteurDe;

        return $this;
    }

    /**
     * Get matPorteurDe
     *
     * @return integer
     */
    public function getMatPorteurDe()
    {
        return $this->matPorteurDe;
    }

    /**
     * Set matRepetitif
     *
     * @param string $matRepetitif
     *
     * @return Materiel
     */
    public function setMatRepetitif($matRepetitif)
    {
        $this->matRepetitif = $matRepetitif;

        return $this;
    }

    /**
     * Get matRepetitif
     *
     * @return string
     */
    public function getMatRepetitif()
    {
        return $this->matRepetitif;
    }

    /**
     * Set matBloque
     *
     * @param string $matBloque
     *
     * @return Materiel
     */
    public function setMatBloque($matBloque)
    {
        $this->matBloque = $matBloque;

        return $this;
    }

    /**
     * Get matBloque
     *
     * @return string
     */
    public function getMatBloque()
    {
        return $this->matBloque;
    }

    /**
     * Set matAvecLot
     *
     * @param string $matAvecLot
     *
     * @return Materiel
     */
    public function setMatAvecLot($matAvecLot)
    {
        $this->matAvecLot = $matAvecLot;

        return $this;
    }

    /**
     * Get matAvecLot
     *
     * @return string
     */
    public function getMatAvecLot()
    {
        return $this->matAvecLot;
    }

    /**
     * Set comMat
     *
     * @param float $comMat
     *
     * @return Materiel
     */
    public function setComMat($comMat)
    {
        $this->comMat = $comMat;

        return $this;
    }

    /**
     * Get comMat
     *
     * @return float
     */
    public function getComMat()
    {
        return $this->comMat;
    }

    /**
     * Set stockMiniMat
     *
     * @param float $stockMiniMat
     *
     * @return Materiel
     */
    public function setStockMiniMat($stockMiniMat)
    {
        $this->stockMiniMat = $stockMiniMat;

        return $this;
    }

    /**
     * Get stockMiniMat
     *
     * @return float
     */
    public function getStockMiniMat()
    {
        return $this->stockMiniMat;
    }

    /**
     * Set matHauteur
     *
     * @param float $matHauteur
     *
     * @return Materiel
     */
    public function setMatHauteur($matHauteur)
    {
        $this->matHauteur = $matHauteur;

        return $this;
    }

    /**
     * Get matHauteur
     *
     * @return float
     */
    public function getMatHauteur()
    {
        return $this->matHauteur;
    }

    /**
     * Set matLargeur
     *
     * @param float $matLargeur
     *
     * @return Materiel
     */
    public function setMatLargeur($matLargeur)
    {
        $this->matLargeur = $matLargeur;

        return $this;
    }

    /**
     * Get matLargeur
     *
     * @return float
     */
    public function getMatLargeur()
    {
        return $this->matLargeur;
    }

    /**
     * Set matLongueur
     *
     * @param float $matLongueur
     *
     * @return Materiel
     */
    public function setMatLongueur($matLongueur)
    {
        $this->matLongueur = $matLongueur;

        return $this;
    }

    /**
     * Get matLongueur
     *
     * @return float
     */
    public function getMatLongueur()
    {
        return $this->matLongueur;
    }

    /**
     * Set matPoids
     *
     * @param float $matPoids
     *
     * @return Materiel
     */
    public function setMatPoids($matPoids)
    {
        $this->matPoids = $matPoids;

        return $this;
    }

    /**
     * Get matPoids
     *
     * @return float
     */
    public function getMatPoids()
    {
        return $this->matPoids;
    }

    /**
     * Set matVolume
     *
     * @param float $matVolume
     *
     * @return Materiel
     */
    public function setMatVolume($matVolume)
    {
        $this->matVolume = $matVolume;

        return $this;
    }

    /**
     * Get matVolume
     *
     * @return float
     */
    public function getMatVolume()
    {
        return $this->matVolume;
    }

    /**
     * Set matContStd
     *
     * @param string $matContStd
     *
     * @return Materiel
     */
    public function setMatContStd($matContStd)
    {
        $this->matContStd = $matContStd;

        return $this;
    }

    /**
     * Get matContStd
     *
     * @return string
     */
    public function getMatContStd()
    {
        return $this->matContStd;
    }

    /**
     * Set uniteMat
     *
     * @param string $uniteMat
     *
     * @return Materiel
     */
    public function setUniteMat($uniteMat)
    {
        $this->uniteMat = $uniteMat;

        return $this;
    }

    /**
     * Get uniteMat
     *
     * @return string
     */
    public function getUniteMat()
    {
        return $this->uniteMat;
    }

    /**
     * Set stockMiniGpa
     *
     * @param float $stockMiniGpa
     *
     * @return Materiel
     */
    public function setStockMiniGpa($stockMiniGpa)
    {
        $this->stockMiniGpa = $stockMiniGpa;

        return $this;
    }

    /**
     * Get stockMiniGpa
     *
     * @return float
     */
    public function getStockMiniGpa()
    {
        return $this->stockMiniGpa;
    }

    /**
     * Set stockMaxiGpa
     *
     * @param float $stockMaxiGpa
     *
     * @return Materiel
     */
    public function setStockMaxiGpa($stockMaxiGpa)
    {
        $this->stockMaxiGpa = $stockMaxiGpa;

        return $this;
    }

    /**
     * Get stockMaxiGpa
     *
     * @return float
     */
    public function getStockMaxiGpa()
    {
        return $this->stockMaxiGpa;
    }

    /**
     * Set matFabMeca
     *
     * @param string $matFabMeca
     *
     * @return Materiel
     */
    public function setMatFabMeca($matFabMeca)
    {
        $this->matFabMeca = $matFabMeca;

        return $this;
    }

    /**
     * Get matFabMeca
     *
     * @return string
     */
    public function getMatFabMeca()
    {
        return $this->matFabMeca;
    }

    /**
     * Set matLibre1
     *
     * @param string $matLibre1
     *
     * @return Materiel
     */
    public function setMatLibre1($matLibre1)
    {
        $this->matLibre1 = $matLibre1;

        return $this;
    }

    /**
     * Get matLibre1
     *
     * @return string
     */
    public function getMatLibre1()
    {
        return $this->matLibre1;
    }

    /**
     * Set matLibre2
     *
     * @param string $matLibre2
     *
     * @return Materiel
     */
    public function setMatLibre2($matLibre2)
    {
        $this->matLibre2 = $matLibre2;

        return $this;
    }

    /**
     * Get matLibre2
     *
     * @return string
     */
    public function getMatLibre2()
    {
        return $this->matLibre2;
    }

    /**
     * Set matLibre3
     *
     * @param string $matLibre3
     *
     * @return Materiel
     */
    public function setMatLibre3($matLibre3)
    {
        $this->matLibre3 = $matLibre3;

        return $this;
    }

    /**
     * Get matLibre3
     *
     * @return string
     */
    public function getMatLibre3()
    {
        return $this->matLibre3;
    }

    /**
     * Set matLibre4
     *
     * @param string $matLibre4
     *
     * @return Materiel
     */
    public function setMatLibre4($matLibre4)
    {
        $this->matLibre4 = $matLibre4;

        return $this;
    }

    /**
     * Get matLibre4
     *
     * @return string
     */
    public function getMatLibre4()
    {
        return $this->matLibre4;
    }

    /**
     * Set matLibre5
     *
     * @param string $matLibre5
     *
     * @return Materiel
     */
    public function setMatLibre5($matLibre5)
    {
        $this->matLibre5 = $matLibre5;

        return $this;
    }

    /**
     * Get matLibre5
     *
     * @return string
     */
    public function getMatLibre5()
    {
        return $this->matLibre5;
    }

    /**
     * Set matLibre6
     *
     * @param string $matLibre6
     *
     * @return Materiel
     */
    public function setMatLibre6($matLibre6)
    {
        $this->matLibre6 = $matLibre6;

        return $this;
    }

    /**
     * Get matLibre6
     *
     * @return string
     */
    public function getMatLibre6()
    {
        return $this->matLibre6;
    }

    /**
     * Set matAvecPmp
     *
     * @param string $matAvecPmp
     *
     * @return Materiel
     */
    public function setMatAvecPmp($matAvecPmp)
    {
        $this->matAvecPmp = $matAvecPmp;

        return $this;
    }

    /**
     * Get matAvecPmp
     *
     * @return string
     */
    public function getMatAvecPmp()
    {
        return $this->matAvecPmp;
    }

    /**
     * Set matHygroMin
     *
     * @param float $matHygroMin
     *
     * @return Materiel
     */
    public function setMatHygroMin($matHygroMin)
    {
        $this->matHygroMin = $matHygroMin;

        return $this;
    }

    /**
     * Get matHygroMin
     *
     * @return float
     */
    public function getMatHygroMin()
    {
        return $this->matHygroMin;
    }

    /**
     * Set matHygroMax
     *
     * @param float $matHygroMax
     *
     * @return Materiel
     */
    public function setMatHygroMax($matHygroMax)
    {
        $this->matHygroMax = $matHygroMax;

        return $this;
    }

    /**
     * Get matHygroMax
     *
     * @return float
     */
    public function getMatHygroMax()
    {
        return $this->matHygroMax;
    }

    /**
     * Set matTempMin
     *
     * @param float $matTempMin
     *
     * @return Materiel
     */
    public function setMatTempMin($matTempMin)
    {
        $this->matTempMin = $matTempMin;

        return $this;
    }

    /**
     * Get matTempMin
     *
     * @return float
     */
    public function getMatTempMin()
    {
        return $this->matTempMin;
    }

    /**
     * Set matTempMax
     *
     * @param float $matTempMax
     *
     * @return Materiel
     */
    public function setMatTempMax($matTempMax)
    {
        $this->matTempMax = $matTempMax;

        return $this;
    }

    /**
     * Get matTempMax
     *
     * @return float
     */
    public function getMatTempMax()
    {
        return $this->matTempMax;
    }

    /**
     * Set matDosageConserv
     *
     * @param string $matDosageConserv
     *
     * @return Materiel
     */
    public function setMatDosageConserv($matDosageConserv)
    {
        $this->matDosageConserv = $matDosageConserv;

        return $this;
    }

    /**
     * Get matDosageConserv
     *
     * @return string
     */
    public function getMatDosageConserv()
    {
        return $this->matDosageConserv;
    }

    /**
     * Set matFormeConserv
     *
     * @param string $matFormeConserv
     *
     * @return Materiel
     */
    public function setMatFormeConserv($matFormeConserv)
    {
        $this->matFormeConserv = $matFormeConserv;

        return $this;
    }

    /**
     * Get matFormeConserv
     *
     * @return string
     */
    public function getMatFormeConserv()
    {
        return $this->matFormeConserv;
    }

    /**
     * Set matIndicUtil
     *
     * @param string $matIndicUtil
     *
     * @return Materiel
     */
    public function setMatIndicUtil($matIndicUtil)
    {
        $this->matIndicUtil = $matIndicUtil;

        return $this;
    }

    /**
     * Get matIndicUtil
     *
     * @return string
     */
    public function getMatIndicUtil()
    {
        return $this->matIndicUtil;
    }

    /**
     * Set matDosageUtil
     *
     * @param string $matDosageUtil
     *
     * @return Materiel
     */
    public function setMatDosageUtil($matDosageUtil)
    {
        $this->matDosageUtil = $matDosageUtil;

        return $this;
    }

    /**
     * Get matDosageUtil
     *
     * @return string
     */
    public function getMatDosageUtil()
    {
        return $this->matDosageUtil;
    }

    /**
     * Set matModUtil
     *
     * @param string $matModUtil
     *
     * @return Materiel
     */
    public function setMatModUtil($matModUtil)
    {
        $this->matModUtil = $matModUtil;

        return $this;
    }

    /**
     * Get matModUtil
     *
     * @return string
     */
    public function getMatModUtil()
    {
        return $this->matModUtil;
    }

    /**
     * Set matLimitUtil
     *
     * @param string $matLimitUtil
     *
     * @return Materiel
     */
    public function setMatLimitUtil($matLimitUtil)
    {
        $this->matLimitUtil = $matLimitUtil;

        return $this;
    }

    /**
     * Get matLimitUtil
     *
     * @return string
     */
    public function getMatLimitUtil()
    {
        return $this->matLimitUtil;
    }

    /**
     * Set matPmp
     *
     * @param float $matPmp
     *
     * @return Materiel
     */
    public function setMatPmp($matPmp)
    {
        $this->matPmp = $matPmp;

        return $this;
    }

    /**
     * Get matPmp
     *
     * @return float
     */
    public function getMatPmp()
    {
        return $this->matPmp;
    }

    /**
     * Set matPmpQte
     *
     * @param float $matPmpQte
     *
     * @return Materiel
     */
    public function setMatPmpQte($matPmpQte)
    {
        $this->matPmpQte = $matPmpQte;

        return $this;
    }

    /**
     * Get matPmpQte
     *
     * @return float
     */
    public function getMatPmpQte()
    {
        return $this->matPmpQte;
    }

    /**
     * Set codeMat3
     *
     * @param string $codeMat3
     *
     * @return Materiel
     */
    public function setCodeMat3($codeMat3)
    {
        $this->codeMat3 = $codeMat3;

        return $this;
    }

    /**
     * Get codeMat3
     *
     * @return string
     */
    public function getCodeMat3()
    {
        return $this->codeMat3;
    }

    /**
     * Set codeMat4
     *
     * @param string $codeMat4
     *
     * @return Materiel
     */
    public function setCodeMat4($codeMat4)
    {
        $this->codeMat4 = $codeMat4;

        return $this;
    }

    /**
     * Get codeMat4
     *
     * @return string
     */
    public function getCodeMat4()
    {
        return $this->codeMat4;
    }

    /**
     * Set codeMat5
     *
     * @param string $codeMat5
     *
     * @return Materiel
     */
    public function setCodeMat5($codeMat5)
    {
        $this->codeMat5 = $codeMat5;

        return $this;
    }

    /**
     * Get codeMat5
     *
     * @return string
     */
    public function getCodeMat5()
    {
        return $this->codeMat5;
    }

    /**
     * Set matPmpPu
     *
     * @param float $matPmpPu
     *
     * @return Materiel
     */
    public function setMatPmpPu($matPmpPu)
    {
        $this->matPmpPu = $matPmpPu;

        return $this;
    }

    /**
     * Get matPmpPu
     *
     * @return float
     */
    public function getMatPmpPu()
    {
        return $this->matPmpPu;
    }

    /**
     * Set matDtDepot
     *
     * @param \DateTime $matDtDepot
     *
     * @return Materiel
     */
    public function setMatDtDepot($matDtDepot)
    {
        $this->matDtDepot = $matDtDepot;

        return $this;
    }

    /**
     * Get matDtDepot
     *
     * @return \DateTime
     */
    public function getMatDtDepot()
    {
        return $this->matDtDepot;
    }

    /**
     * Set matDatecreat
     *
     * @param \DateTime $matDatecreat
     *
     * @return Materiel
     */
    public function setMatDatecreat($matDatecreat)
    {
        $this->matDatecreat = $matDatecreat;

        return $this;
    }

    /**
     * Get matDatecreat
     *
     * @return \DateTime
     */
    public function getMatDatecreat()
    {
        return $this->matDatecreat;
    }

    /**
     * Set matDatemodif
     *
     * @param \DateTime $matDatemodif
     *
     * @return Materiel
     */
    public function setMatDatemodif($matDatemodif)
    {
        $this->matDatemodif = $matDatemodif;

        return $this;
    }

    /**
     * Get matDatemodif
     *
     * @return \DateTime
     */
    public function getMatDatemodif()
    {
        return $this->matDatemodif;
    }

    /**
     * Set matPickAvecLot
     *
     * @param string $matPickAvecLot
     *
     * @return Materiel
     */
    public function setMatPickAvecLot($matPickAvecLot)
    {
        $this->matPickAvecLot = $matPickAvecLot;

        return $this;
    }

    /**
     * Get matPickAvecLot
     *
     * @return string
     */
    public function getMatPickAvecLot()
    {
        return $this->matPickAvecLot;
    }

    /**
     * Set matDateRempl
     *
     * @param \DateTime $matDateRempl
     *
     * @return Materiel
     */
    public function setMatDateRempl($matDateRempl)
    {
        $this->matDateRempl = $matDateRempl;

        return $this;
    }

    /**
     * Get matDateRempl
     *
     * @return \DateTime
     */
    public function getMatDateRempl()
    {
        return $this->matDateRempl;
    }

    /**
     * Set matPmpDateMaj
     *
     * @param \DateTime $matPmpDateMaj
     *
     * @return Materiel
     */
    public function setMatPmpDateMaj($matPmpDateMaj)
    {
        $this->matPmpDateMaj = $matPmpDateMaj;

        return $this;
    }

    /**
     * Get matPmpDateMaj
     *
     * @return \DateTime
     */
    public function getMatPmpDateMaj()
    {
        return $this->matPmpDateMaj;
    }

    /**
     * Set matImage
     *
     * @param string $matImage
     *
     * @return Materiel
     */
    public function setMatImage($matImage)
    {
        $this->matImage = $matImage;

        return $this;
    }

    /**
     * Get matImage
     *
     * @return string
     */
    public function getMatImage()
    {
        return $this->matImage;
    }

    /**
     * Set codfammat
     *
     * @param \TMD\MinosBundle\Entity\Fammat $codfammat
     *
     * @return Materiel
     */
    public function setCodfammat(Fammat $codfammat = null)
    {
        $this->codfammat = $codfammat;

        return $this;
    }

    /**
     * Get codfammat
     *
     * @return \TMD\MinosBundle\Entity\Fammat
     */
    public function getCodfammat()
    {
        return $this->codfammat;
    }

    /**
     * Set sfm
     *
     * @param \TMD\MinosBundle\Entity\Sfammat $sfm
     *
     * @return Materiel
     */
    public function setSfm(Sfammat $sfm = null)
    {
        $this->sfm = $sfm;

        return $this;
    }

    /**
     * Get sfm
     *
     * @return \TMD\MinosBundle\Entity\Sfammat
     */
    public function getSfm()
    {
        return $this->sfm;
    }

    /**
     * Set idcliDo
     *
     * @param \TMD\MinosBundle\Entity\Client $idcliDo
     *
     * @return Materiel
     */
    public function setIdcliDo(\TMD\MinosBundle\Entity\Client $idcliDo = null)
    {
        $this->idcliDo = $idcliDo;

        return $this;
    }

    /**
     * Get idcliDo
     *
     * @return \TMD\MinosBundle\Entity\Client
     */
    public function getIdcliDo()
    {
        return $this->idcliDo;
    }

    /**
     * Set idcliFabEditeur
     *
     * @param \TMD\MinosBundle\Entity\Client $idcliFabEditeur
     *
     * @return Materiel
     */
    public function setIdcliFabEditeur(\TMD\MinosBundle\Entity\Client $idcliFabEditeur = null)
    {
        $this->idcliFabEditeur = $idcliFabEditeur;

        return $this;
    }

    /**
     * Get idcliFabEditeur
     *
     * @return \TMD\MinosBundle\Entity\Client
     */
    public function getIdcliFabEditeur()
    {
        return $this->idcliFabEditeur;
    }

    /**
     * Set typeMat
     *
     * @param \TMD\MinosBundle\Entity\TypeMateriel $typeMat
     *
     * @return Materiel
     */
    public function setTypeMat(TypeMateriel $typeMat = null)
    {
        $this->typeMat = $typeMat;

        return $this;
    }

    /**
     * Get typeMat
     *
     * @return \TMD\MinosBundle\Entity\TypeMateriel
     */
    public function getTypeMat()
    {
        return $this->typeMat;
    }
//
//    /**
//     * Set idplan
//     *
//     * @param \TMD\MinosBundle\Entity\Plans $idplan
//     *
//     * @return Materiel
//     */
//    public function setIdplan(\TMD\MinosBundle\Entity\Plans $idplan = null)
//    {
//        $this->idplan = $idplan;
//
//        return $this;
//    }
//
//    /**
//     * Get idplan
//     *
//     * @return \TMD\MinosBundle\Entity\Plans
//     */
//    public function getIdplan()
//    {
//        return $this->idplan;
//    }

    /**
     * Set umCodeAchat
     *
     * @param \TMD\MinosBundle\Entity\UniteMesure $umCodeAchat
     *
     * @return Materiel
     */
    public function setUmCodeAchat(UniteMesure $umCodeAchat = null)
    {
        $this->umCodeAchat = $umCodeAchat;

        return $this;
    }

    /**
     * Get umCodeAchat
     *
     * @return \TMD\MinosBundle\Entity\UniteMesure
     */
    public function getUmCodeAchat()
    {
        return $this->umCodeAchat;
    }

    /**
     * Set umCodeHauteur
     *
     * @param \TMD\MinosBundle\Entity\UniteMesure $umCodeHauteur
     *
     * @return Materiel
     */
    public function setUmCodeHauteur(UniteMesure $umCodeHauteur = null)
    {
        $this->umCodeHauteur = $umCodeHauteur;

        return $this;
    }

    /**
     * Get umCodeHauteur
     *
     * @return \TMD\MinosBundle\Entity\UniteMesure
     */
    public function getUmCodeHauteur()
    {
        return $this->umCodeHauteur;
    }

    /**
     * Set umCodeLargeur
     *
     * @param \TMD\MinosBundle\Entity\UniteMesure $umCodeLargeur
     *
     * @return Materiel
     */
    public function setUmCodeLargeur(UniteMesure $umCodeLargeur = null)
    {
        $this->umCodeLargeur = $umCodeLargeur;

        return $this;
    }

    /**
     * Get umCodeLargeur
     *
     * @return \TMD\MinosBundle\Entity\UniteMesure
     */
    public function getUmCodeLargeur()
    {
        return $this->umCodeLargeur;
    }

    /**
     * Set umCodeLongueur
     *
     * @param \TMD\MinosBundle\Entity\UniteMesure $umCodeLongueur
     *
     * @return Materiel
     */
    public function setUmCodeLongueur(UniteMesure $umCodeLongueur = null)
    {
        $this->umCodeLongueur = $umCodeLongueur;

        return $this;
    }

    /**
     * Get umCodeLongueur
     *
     * @return \TMD\MinosBundle\Entity\UniteMesure
     */
    public function getUmCodeLongueur()
    {
        return $this->umCodeLongueur;
    }

    /**
     * Set umCodePoids
     *
     * @param \TMD\MinosBundle\Entity\UniteMesure $umCodePoids
     *
     * @return Materiel
     */
    public function setUmCodePoids(UniteMesure $umCodePoids = null)
    {
        $this->umCodePoids = $umCodePoids;

        return $this;
    }

    /**
     * Get umCodePoids
     *
     * @return \TMD\MinosBundle\Entity\UniteMesure
     */
    public function getUmCodePoids()
    {
        return $this->umCodePoids;
    }

    /**
     * Set umCodeStock
     *
     * @param \TMD\MinosBundle\Entity\UniteMesure $umCodeStock
     *
     * @return Materiel
     */
    public function setUmCodeStock(UniteMesure $umCodeStock = null)
    {
        $this->umCodeStock = $umCodeStock;

        return $this;
    }

    /**
     * Get umCodeStock
     *
     * @return \TMD\MinosBundle\Entity\UniteMesure
     */
    public function getUmCodeStock()
    {
        return $this->umCodeStock;
    }

    /**
     * Set umCodeVolume
     *
     * @param \TMD\MinosBundle\Entity\UniteMesure $umCodeVolume
     *
     * @return Materiel
     */
    public function setUmCodeVolume(UniteMesure $umCodeVolume = null)
    {
        $this->umCodeVolume = $umCodeVolume;

        return $this;
    }

    /**
     * Get umCodeVolume
     *
     * @return \TMD\MinosBundle\Entity\UniteMesure
     */
    public function getUmCodeVolume()
    {
        return $this->umCodeVolume;
    }

    /**
     * Set mst
     *
     * @param \TMD\MinosBundle\Entity\MatStatut $mst
     *
     * @return Materiel
     */
    public function setMst(MatStatut $mst = null)
    {
        $this->mst = $mst;

        return $this;
    }

    /**
     * Get mst
     *
     * @return \TMD\MinosBundle\Entity\MatStatut
     */
    public function getMst()
    {
        return $this->mst;
    }

    /**
     * Set idcli
     *
     * @param \TMD\MinosBundle\Entity\Client $idcli
     *
     * @return Materiel
     */
    public function setIdcli(Client $idcli = null)
    {
        $this->idcli = $idcli;

        return $this;
    }

    /**
     * Get idcli
     *
     * @return \TMD\MinosBundle\Entity\Client
     */
    public function getIdcli()
    {
        return $this->idcli;
    }

    /**
     * Set matmatRempl
     *
     * @param \TMD\MinosBundle\Entity\Materiel $matmatRempl
     *
     * @return Materiel
     */
    public function setMatmatRempl(Materiel $matmatRempl = null)
    {
        $this->matmatRempl = $matmatRempl;

        return $this;
    }

    /**
     * Get matmatRempl
     *
     * @return \TMD\MinosBundle\Entity\Materiel
     */
    public function getMatmatRempl()
    {
        return $this->matmatRempl;
    }

    /**
     * Set prj
     *
     * @param \TMD\MinosBundle\Entity\Projet $prj
     *
     * @return Materiel
     */
//    public function setPrj(\TMD\MinosBundle\Entity\Projet $prj = null)
//    {
//        $this->prj = $prj;
//
//        return $this;
//    }
//
//    /**
//     * Get prj
//     *
//     * @return \TMD\MinosBundle\Entity\Projet
//     */
//    public function getPrj()
//    {
//        return $this->prj;
//    }

    /**
     * Set matmatSubst
     *
     * @param \TMD\MinosBundle\Entity\Materiel $matmatSubst
     *
     * @return Materiel
     */
    public function setMatmatSubst(Materiel $matmatSubst = null)
    {
        $this->matmatSubst = $matmatSubst;

        return $this;
    }

    /**
     * Get matmatSubst
     *
     * @return \TMD\MinosBundle\Entity\Materiel
     */
    public function getMatmatSubst()
    {
        return $this->matmatSubst;
    }
}

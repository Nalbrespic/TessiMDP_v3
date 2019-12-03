<?php

namespace TMD\MinosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

//@ORM\Table(name="MATERIEL_HISTO", indexes={@ORM\Index(name="responsable1_fk", columns={"MATRICULE"}), @ORM\Index(name="modif_mat_fk", columns={"IDMAT"})})
/**
 * MaterielHisto
 *
 * @ORM\Table(name="MATERIEL_HISTO", indexes={ @ORM\Index(name="modif_mat_fk", columns={"IDMAT"})})
 * @ORM\Entity
 */
class MaterielHisto
{
    /**
     * @var integer
     *
     * @ORM\Column(name="MAH_NUM", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="MATERIEL_HISTO_MAH_NUM_seq", allocationSize=1, initialValue=1)
     */
    private $mahNum;

    /**
     * @var string
     *
     * @ORM\Column(name="MAH_ANCIEN_CODE", type="string", length=30, nullable=false)
     */
    private $mahAncienCode;

    /**
     * @var string
     *
     * @ORM\Column(name="MAH_NOUVEAU_CODE", type="string", length=30, nullable=false)
     */
    private $mahNouveauCode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="MAH_DATE", type="date", nullable=false)
     */
    private $mahDate;

    /**
     * @var \TMD\MinosBundle\Entity\Materiel
     *
     * @ORM\ManyToOne(targetEntity="TMD\MinosBundle\Entity\Materiel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IDMAT", referencedColumnName="IDMAT")
     * })
     */
    private $idmat;

//    /**
//     * @var \Employe
//     *
//     * @ORM\ManyToOne(targetEntity="Employe")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="MATRICULE", referencedColumnName="MATRICULE")
//     * })
//     */
//    private $matricule;



    /**
     * Get mahNum
     *
     * @return integer
     */
    public function getMahNum()
    {
        return $this->mahNum;
    }

    /**
     * Set mahAncienCode
     *
     * @param string $mahAncienCode
     *
     * @return MaterielHisto
     */
    public function setMahAncienCode($mahAncienCode)
    {
        $this->mahAncienCode = $mahAncienCode;

        return $this;
    }

    /**
     * Get mahAncienCode
     *
     * @return string
     */
    public function getMahAncienCode()
    {
        return $this->mahAncienCode;
    }

    /**
     * Set mahNouveauCode
     *
     * @param string $mahNouveauCode
     *
     * @return MaterielHisto
     */
    public function setMahNouveauCode($mahNouveauCode)
    {
        $this->mahNouveauCode = $mahNouveauCode;

        return $this;
    }

    /**
     * Get mahNouveauCode
     *
     * @return string
     */
    public function getMahNouveauCode()
    {
        return $this->mahNouveauCode;
    }

    /**
     * Set mahDate
     *
     * @param \DateTime $mahDate
     *
     * @return MaterielHisto
     */
    public function setMahDate($mahDate)
    {
        $this->mahDate = $mahDate;

        return $this;
    }

    /**
     * Get mahDate
     *
     * @return \DateTime
     */
    public function getMahDate()
    {
        return $this->mahDate;
    }

    /**
     * Set idmat
     *
     * @param \TMD\MinosBundle\Entity\Materiel $idmat
     *
     * @return MaterielHisto
     */
    public function setIdmat(\TMD\MinosBundle\Entity\Materiel $idmat = null)
    {
        $this->idmat = $idmat;

        return $this;
    }

    /**
     * Get idmat
     *
     * @return \TMD\MinosBundle\Entity\Materiel
     */
    public function getIdmat()
    {
        return $this->idmat;
    }

//    /**
//     * Set matricule
//     *
//     * @param \TMD\MinosBundle\Entity\Employe $matricule
//     *
//     * @return MaterielHisto
//     */
//    public function setMatricule(\TMD\MinosBundle\Entity\Employe $matricule = null)
//    {
//        $this->matricule = $matricule;
//
//        return $this;
//    }
//
//    /**
//     * Get matricule
//     *
//     * @return \TMD\MinosBundle\Entity\Employe
//     */
//    public function getMatricule()
//    {
//        return $this->matricule;
//    }
}

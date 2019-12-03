<?php

namespace TMD\MinosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sfammat
 *
 * @ORM\Table(name="SFAMMAT", uniqueConstraints={@ORM\UniqueConstraint(name="u_sfammat", columns={"SFM_CODE"})}, indexes={@ORM\Index(name="classe_par_fk", columns={"CODFAMMAT"})})
 * @ORM\Entity
 */
class Sfammat
{
    /**
     * @var integer
     *
     * @ORM\Column(name="SFM_ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="SFAMMAT_SFM_ID_seq", allocationSize=1, initialValue=1)
     */
    private $sfmId;

    /**
     * @var string
     *
     * @ORM\Column(name="SFM_CODE", type="string", length=20, nullable=false)
     */
    private $sfmCode;

    /**
     * @var string
     *
     * @ORM\Column(name="SFM_DESIGN", type="string", length=80, nullable=true)
     */
    private $sfmDesign;

    /**
     * @var string
     *
     * @ORM\Column(name="SFM_CARACT", type="string", length=1, nullable=true)
     */
    private $sfmCaract;

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
     * @return int
     */
    public function getSfmId()
    {
        return $this->sfmId;
    }

    /**
     * @param int $sfmId
     */
    public function setSfmId($sfmId)
    {
        $this->sfmId = $sfmId;
    }

    /**
     * @return string
     */
    public function getSfmCode()
    {
        return $this->sfmCode;
    }

    /**
     * @param string $sfmCode
     */
    public function setSfmCode($sfmCode)
    {
        $this->sfmCode = $sfmCode;
    }

    /**
     * @return string
     */
    public function getSfmDesign()
    {
        return $this->sfmDesign;
    }

    /**
     * @param string $sfmDesign
     */
    public function setSfmDesign($sfmDesign)
    {
        $this->sfmDesign = $sfmDesign;
    }

    /**
     * @return string
     */
    public function getSfmCaract()
    {
        return $this->sfmCaract;
    }

    /**
     * @param string $sfmCaract
     */
    public function setSfmCaract($sfmCaract)
    {
        $this->sfmCaract = $sfmCaract;
    }

    /**
     * @return Fammat
     */
    public function getCodfammat()
    {
        return $this->codfammat;
    }

    /**
     * @param Fammat $codfammat
     */
    public function setCodfammat($codfammat)
    {
        $this->codfammat = $codfammat;
    }


}


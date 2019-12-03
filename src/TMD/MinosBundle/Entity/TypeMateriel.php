<?php

namespace TMD\MinosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeMateriel
 *
 * @ORM\Table(name="TYPE_MATERIEL")
 * @ORM\Entity
 */
class TypeMateriel
{
    /**
     * @var string
     *
     * @ORM\Column(name="TYPE_MAT", type="string", length=1, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="TYPE_MATERIEL_TYPE_MAT_seq", allocationSize=1, initialValue=1)
     */
    private $typeMat;

    /**
     * @var string
     *
     * @ORM\Column(name="TYM_LIB", type="string", length=80, nullable=true)
     */
    private $tymLib;

    /**
     * @var string
     *
     * @ORM\Column(name="TYM_IMAGE", type="blob", nullable=true)
     */
    private $tymImage;



    /**
     * Get typeMat
     *
     * @return string
     */
    public function getTypeMat()
    {
        return $this->typeMat;
    }

    /**
     * Set tymLib
     *
     * @param string $tymLib
     *
     * @return TypeMateriel
     */
    public function setTymLib($tymLib)
    {
        $this->tymLib = $tymLib;

        return $this;
    }

    /**
     * Get tymLib
     *
     * @return string
     */
    public function getTymLib()
    {
        return $this->tymLib;
    }

    /**
     * Set tymImage
     *
     * @param string $tymImage
     *
     * @return TypeMateriel
     */
    public function setTymImage($tymImage)
    {
        $this->tymImage = $tymImage;

        return $this;
    }

    /**
     * Get tymImage
     *
     * @return string
     */
    public function getTymImage()
    {
        return $this->tymImage;
    }
}

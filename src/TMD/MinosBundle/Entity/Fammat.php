<?php

namespace TMD\MinosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fammat
 *
 * @ORM\Table(name="FAMMAT")
 * @ORM\Entity
 */
class Fammat
{
    /**
     * @var string
     *
     * @ORM\Column(name="CODFAMMAT", type="string", length=15, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="FAMMAT_CODFAMMAT_seq", allocationSize=1, initialValue=1)
     */
    private $codfammat;

    /**
     * @var string
     *
     * @ORM\Column(name="CARACT_FAMMAT", type="string", length=1, nullable=true)
     */
    private $caractFammat = 'A';

    /**
     * @var string
     *
     * @ORM\Column(name="DESIGN_FAMMAT", type="string", length=80, nullable=true)
     */
    private $designFammat;

    /**
     * @return string
     */
    public function getCodfammat()
    {
        return $this->codfammat;
    }

    /**
     * @param string $codfammat
     */
    public function setCodfammat($codfammat)
    {
        $this->codfammat = $codfammat;
    }

    /**
     * @return string
     */
    public function getCaractFammat()
    {
        return $this->caractFammat;
    }

    /**
     * @param string $caractFammat
     */
    public function setCaractFammat($caractFammat)
    {
        $this->caractFammat = $caractFammat;
    }

    /**
     * @return string
     */
    public function getDesignFammat()
    {
        return $this->designFammat;
    }

    /**
     * @param string $designFammat
     */
    public function setDesignFammat($designFammat)
    {
        $this->designFammat = $designFammat;
    }


}


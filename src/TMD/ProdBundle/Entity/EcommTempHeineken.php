<?php

namespace TMD\ProdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


///**
// * EcommAppli
// *
// * @ORM\Table(name="ecomm_TempHeineken" )
// * @ORM\Entity(repositoryClass="TMD\ProdBundle\Repository\EcommTempHeinekenRepository", readOnly=false)
// */
class EcommTempHeineken
{
    /**
     * @var string
     *
     * @ORM\Column(name="NumCode", type="string", length=30, nullable=false)
     */
    private $NumCode;


    /**
     * @var boolean
     *
     * @ORM\Column(name="selected", type="boolean", nullable=true, options={"default" = 0})
     */
    private $selected;

    /**
     * @var integer
     *
     * @ORM\Column(name="idNum", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idNum;

    /**
     * @return string
     */
    public function getNumCode()
    {
        return $this->NumCode;
    }

    /**
     * @param string $NumCode
     */
    public function setNumCode($NumCode)
    {
        $this->NumCode = $NumCode;
    }

    /**
     * @return bool
     */
    public function isSelected()
    {
        return $this->selected;
    }

    /**
     * @param bool $selected
     */
    public function setSelected($selected)
    {
        $this->selected = $selected;
    }

    /**
     * @return int
     */
    public function getIdNum()
    {
        return $this->idNum;
    }





}


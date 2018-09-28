<?php

namespace TMD\ProdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Siteprod
 *
 * @ORM\Table(name="siteprod")
 * @ORM\Entity
 */
class Siteprod
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idsitePROD", type="integer", nullable=false)
     * @ORM\Id
    */
    private $idsiteprod;



    /**
     * @var string
     *
     * @ORM\Column(name="abregesitePROD", type="string", length=10, nullable=false)
     */
    private $abregesiteprod;

    /**
     * @var string
     *
     * @ORM\Column(name="sitePROD", type="string", length=30, nullable=false)
     */
    private $siteprod;

    /**
     * @return bool
     */
    public function isIdsiteprod()
    {
        return $this->idsiteprod;
    }

    /**
     * @param bool $idsiteprod
     */


    /**
     * @return string
     */
    public function getAbregesiteprod()
    {
        return $this->abregesiteprod;
    }

    /**
     * @param string $abregesiteprod
     */
    public function setAbregesiteprod($abregesiteprod)
    {
        $this->abregesiteprod = $abregesiteprod;
    }

    /**
     * @return string
     */
    public function getSiteprod()
    {
        return $this->siteprod;
    }

    /**
     * @param string $siteprod
     */
    public function setSiteprod($siteprod)
    {
        $this->siteprod = $siteprod;
    }

    /**
     * @return int
     */
    public function getIdsiteprod()
    {
        return $this->idsiteprod;
    }

    /**
     * @param int $idsiteprod
     */
    public function setIdsiteprod($idsiteprod)
    {
        $this->idsiteprod = $idsiteprod;
    }





}


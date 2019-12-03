<?php

namespace TMD\MinosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmplAllee
 *
 * @ORM\Table(name="EMPL_ALLEE")
 * @ORM\Entity
 */
class EmplAllee
{
    /**
     * @var string
     *
     * @ORM\Column(name="ALLEE_EMPL", type="string", length=5, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="EMPL_ALLEE_ALLEE_EMPL_seq", allocationSize=1, initialValue=1)
     */
    private $alleeEmpl;

    /**
     * @var integer
     *
     * @ORM\Column(name="ALLEE_NUM", type="integer", nullable=true)
     */
    private $alleeNum;

    /**
     * @var integer
     *
     * @ORM\Column(name="ALLEE_DIST", type="integer", nullable=false)
     */
    private $alleeDist;

    /**
     * @var integer
     *
     * @ORM\Column(name="ALLEE_LONG", type="integer", nullable=false)
     */
    private $alleeLong = '0';

    /**
     * @return string
     */
    public function getAlleeEmpl()
    {
        return $this->alleeEmpl;
    }

    /**
     * @param string $alleeEmpl
     */
    public function setAlleeEmpl($alleeEmpl)
    {
        $this->alleeEmpl = $alleeEmpl;
    }

    /**
     * @return int
     */
    public function getAlleeNum()
    {
        return $this->alleeNum;
    }

    /**
     * @param int $alleeNum
     */
    public function setAlleeNum($alleeNum)
    {
        $this->alleeNum = $alleeNum;
    }

    /**
     * @return int
     */
    public function getAlleeDist()
    {
        return $this->alleeDist;
    }

    /**
     * @param int $alleeDist
     */
    public function setAlleeDist($alleeDist)
    {
        $this->alleeDist = $alleeDist;
    }

    /**
     * @return int
     */
    public function getAlleeLong()
    {
        return $this->alleeLong;
    }

    /**
     * @param int $alleeLong
     */
    public function setAlleeLong($alleeLong)
    {
        $this->alleeLong = $alleeLong;
    }



}


<?php

namespace TMD\ProdBundle\Entity;

/**
 * InputData
 */
class InputData
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date1;

    /**
     * @var \DateTime
     */
    private $date2;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date1
     *
     * @param \DateTime $date1
     *
     * @return InputData
     */
    public function setDate1($date1)
    {
        $this->date1 = $date1;

        return $this;
    }

    /**
     * Get date1
     *
     * @return \DateTime
     */
    public function getDate1()
    {
        return $this->date1;
    }

    /**
     * Set date2
     *
     * @param \DateTime $date2
     *
     * @return InputData
     */
    public function setDate2($date2)
    {
        $this->date2 = $date2;

        return $this;
    }

    /**
     * Get date2
     *
     * @return \DateTime
     */
    public function getDate2()
    {
        return $this->date2;
    }
}


<?php

namespace TMD\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use TMD\AppliBundle\Entity\EcommTransport;
use TMD\AppliBundle\Entity\EcommTransporteurs;
use TMD\ProdBundle\Entity\Client;
use Tms\Bundle\LogisticBundle\Entity\EcommTransporteur;

/**
 * TransporteursTarifClient
 *
 * @ORM\Table(name="transporteurs_tarif_client")
 * @ORM\Entity(repositoryClass="TMD\CoreBundle\Repository\TransporteursTarifClientRepository", readOnly=false)
 */
class TransporteursTarifClient
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var EcommTransport
     *
     * @ORM\ManyToOne(targetEntity="TMD\AppliBundle\Entity\EcommTransport")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTransport", referencedColumnName="idTransport", nullable=false)
     * })
     */
    private $idTransport;

    /**
     * @var float
     *
     * @ORM\Column(name="remise", type="float", length=10, precision=2, nullable=false)
     */
    private $remise;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="TMD\ProdBundle\Entity\Client")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="idClient", referencedColumnName="idClient", nullable=false)
     * })
     */
    private $idClient;


    /**
     * @var string
     *
     * @ORM\Column(name="commentaires",type="string", length=80, nullable=true)
     */
    private $commentaires;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_Uptade", type="date", nullable=false)
     */
    private $dateUpdate;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return EcommTransport
     */
    public function getIdTransport(): EcommTransport
    {
        return $this->idTransport;
    }

    /**
     * @param EcommTransport $idTransport
     */
    public function setIdTransport(EcommTransport $idTransport)
    {
        $this->idTransport = $idTransport;
    }

    /**
     * @return float
     */
    public function getRemise(): float
    {
        return $this->remise;
    }

    /**
     * @param float $remise
     */
    public function setRemise(float $remise)
    {
        $this->remise = $remise;
    }

    /**
     * @return Client
     */
    public function getIdClient(): Client
    {
        return $this->idClient;
    }

    /**
     * @param Client $idClient
     */
    public function setIdClient(Client $idClient)
    {
        $this->idClient = $idClient;
    }

    /**
     * @return string
     */
    public function getCommentaires(): string
    {
        return $this->commentaires;
    }

    /**
     * @param string $commentaires
     */
    public function setCommentaires(string $commentaires)
    {
        $this->commentaires = $commentaires;
    }

    /**
     * @return \DateTime
     */
    public function getDateUpdate(): \DateTime
    {
        return $this->dateUpdate;
    }

    /**
     * @param \DateTime $dateUpdate
     */
    public function setDateUpdate(\DateTime $dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;
    }



}
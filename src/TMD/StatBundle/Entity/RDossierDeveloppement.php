<?php

namespace TMD\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

///**
// * RMain
// *
// * @ORM\Table(name="r_dossier_developpement")
// * @ORM\Entity
// */
class RDossierDeveloppement
{
    /**
     * @var string
     *
     * @ORM\Column(name="id_dossier_suivi", type="string", length=15, nullable=false)
     */
    private $idDossierSuivi;

    /**
     * @var \TMD\StatBundle\Entity\RDeveloppeur
     *
     * @ORM\ManyToOne(targetEntity="TMD\StatBundle\Entity\RDeveloppeur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_developpeur", referencedColumnName="id_developpeur", nullable=false)
     * })
     */
    private $idDeveloppeur;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Temps_passe", type="time", nullable=false, options={"default" =  "00:00:00"})
     */
    private $tempsPasse;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_traitement", type="datetime", nullable=false, options={"default" = "0000-00-00 00:00:00"})
     */
    private $dateTraitement ;

    /**
     * @var string
     *
     * @ORM\Column(name="Commentaire", type="text", length=65535, nullable=false)
     */
    private $commentaire;


    /**
     * @var integer
     *
     * @ORM\Column(name="Quantite", type="integer", nullable=false , options={"default" = 0})
     */
    private $quantite;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_dossier_developpement", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDossierDeveloppement;

    /**
     * @return string
     */
    public function getIdDossierSuivi()
    {
        return $this->idDossierSuivi;
    }

    /**
     * @param string $idDossierSuivi
     */
    public function setIdDossierSuivi($idDossierSuivi)
    {
        $this->idDossierSuivi = $idDossierSuivi;
    }

    /**
     * @return RDossierDeveloppement
     */
    public function getIdDeveloppeur()
    {
        return $this->idDeveloppeur;
    }

    /**
     * @param RDossierDeveloppement $idDeveloppeur
     */
    public function setIdDeveloppeur($idDeveloppeur)
    {
        $this->idDeveloppeur = $idDeveloppeur;
    }

    /**
     * @return \DateTime
     */
    public function getTempsPasse()
    {
        return $this->tempsPasse;
    }

    /**
     * @param \DateTime $tempsPasse
     */
    public function setTempsPasse($tempsPasse)
    {
        $this->tempsPasse = $tempsPasse;
    }

    /**
     * @return \DateTime
     */
    public function getDateTraitement()
    {
        return $this->dateTraitement;
    }

    /**
     * @param \DateTime $dateTraitement
     */
    public function setDateTraitement($dateTraitement)
    {
        $this->dateTraitement = $dateTraitement;
    }

    /**
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * @param string $commentaire
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    }

    /**
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * @param int $quantite
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
    }

    /**
     * @return int
     */
    public function getIdDossierDeveloppement()
    {
        return $this->idDossierDeveloppement;
    }




}


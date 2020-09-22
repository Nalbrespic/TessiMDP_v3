<?php

namespace TMD\ProdBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\User\UserInterface;
use TMD\ProdBundle\Entity\EcommHistoStatut;
use TMD\ProdBundle\Entity\EcommTracking;
use TMD\ProdBundle\Entity\EcommStatut;

class StatutManager
{
    protected $em;

    /**
     * StatutService constructor.
     * @param $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    /**
     * change tracking status (with history)
     * @param EcommTracking $tracking
     * @param EcommStatut $statut
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changeStatut(EcommTracking $tracking, string $agregeStatut, string $statutObservation, UserInterface $user) {
        // get status
        $statut = $this->em->getRepository('TMDProdBundle:EcommStatut')->getByAbrege($agregeStatut);
        dump($statut->getIdStatut());
        // change status himself
        $tracking->setIdStatut($statut);

        // record status history
        $histoStatut = new EcommHistoStatut();
        $histoStatut->setDatestatut(new \DateTime());
        $histoStatut->setIdstatut($statut);
        $histoStatut->setObservation($statutObservation);
        $histoStatut->setNumbl($tracking->getExpRef());
        $histoStatut->setIduser($user->getId());
        $this->em->persist($histoStatut);

        $this->em->flush();
    }

}

<?php

namespace TMD\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;


class TransporteursTrancheRepository extends EntityRepository
{
    public function allTranches(){
        return $this
            ->createQueryBuilder('tr')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findByTransporteur($idTransporteur){
        return $this
            ->createQueryBuilder('tr')
            ->where('tr.transporteur IN (:id)')
            ->setParameter('id', $idTransporteur)
            ->orderBy('tr.poidsMax', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
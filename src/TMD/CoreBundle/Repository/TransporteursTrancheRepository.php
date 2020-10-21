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
            ->innerJoin('tr.transporteur', 'trans')
            ->where('trans.idtransporteur IN (:id)')
            ->setParameter('id', $idTransporteur)
            ->getQuery()
            ->getArrayResult();
    }



    public function findTrancheByPoids($poids){

        return $this
            ->createQueryBuilder('tr')
            ->where('tr.poidsMax >= (:poids)')
            ->setParameter('poids', $poids)
            ->select('Min(tr.poidsMax)')
            ->addSelect('tr.idTransportTranches')
            ->addSelect('tr.poidsMax')
            ->getQuery()
            ->getResult()
            ;
    }
}
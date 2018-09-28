<?php

namespace TMD\AppliBundle\Repository;

use Doctrine\ORM\EntityRepository;


class NTrackingRepository extends EntityRepository
{
    public function findCorrespondancePlage($plage, $idClient )
    {
        return $this
            ->createQueryBuilder('nTrack')
            ->where('nTrack.plagedebut  <= :id ')
            ->setParameter('id', $plage)
            ->andWhere('nTrack.plagefin >= :id2')
            ->setParameter('id2', $plage)
            ->andWhere('nTrack.idclient = :clt')
            ->setParameter('clt', $idClient)
            ->getQuery()
            ->getResult()

            ;
    }

    public function findListeCarteByClient($idclient )
    {
        return $this
            ->createQueryBuilder('nTrack')
            ->where('nTrack.idclient  = :id ')
            ->setParameter('id', $idclient)
            ->andWhere('nTrack.valid  = :idV ')
            ->setParameter('idV', 1)
            ->orderBy('nTrack.numplage', 'DESC')
            ->getQuery()
            ->getArrayResult()

            ;
    }

    public function findNbCarteUtilByClient($idclient )
    {
        return $this
            ->createQueryBuilder('nTrack')
            ->where('nTrack.idclient  = :id ')
            ->setParameter('id', $idclient)
            ->andWhere('nTrack.valid  = :idV ')
            ->setParameter('idV', 1)
            ->select('SUM(SUBSTRING(nTrack.plagefin,7, 12)- SUBSTRING(nTrack.current,7, 12) +1) ')
            ->getQuery()
            ->getArrayResult()

            ;
    }
}

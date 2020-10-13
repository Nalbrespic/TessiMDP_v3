<?php

namespace TMD\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;


class TransporteursZonesRepository extends EntityRepository
{
    public function allZones(){

        return $this
            ->createQueryBuilder('z')
            ->groupBy('z.zone')
            ->getQuery()
            ->getArrayResult();
    }

    public function zonesByTransporteurs($id)
    {
        return $this
            ->createQueryBuilder('z')
            ->where('z.transport IN (:id)')
            ->setParameter('id', $id)
            ->groupBy('z.zone')
            ->getQuery()
            ->getArrayResult();
    }

}
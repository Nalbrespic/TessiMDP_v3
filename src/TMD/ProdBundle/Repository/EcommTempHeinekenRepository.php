<?php

namespace TMD\ProdBundle\Repository;

use Doctrine\ORM\EntityRepository;


class EcommTempHeinekenRepository extends EntityRepository
{
    public function findallCodeUnused()
    {
        return $this
            ->createQueryBuilder('code')
            ->where('code.selected  = (:cd)')
            ->setParameter('cd', 0)
            ->getQuery()
            ->getArrayResult()
            ;
    }





}

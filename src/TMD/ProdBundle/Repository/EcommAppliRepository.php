<?php

namespace TMD\ProdBundle\Repository;

use Doctrine\ORM\EntityRepository;


class EcommAppliRepository extends EntityRepository
{
    public function findAppliByClient($idClient)
    {
        return $this
            ->createQueryBuilder('a')
            ->where('a.idclient = :id')
            ->setParameter('id', $idClient)
            ->orderBy('a.idappli', 'DESC')
            ->getQuery()
            ->getResult()

            ;
    }

    public function findClientWithOperation()
    {
        return $this
            ->createQueryBuilder('a')
            ->innerJoin('a.idclient' ,'cl')
            ->select('DISTINCT cl.nomclient ')
            ->orderBy('cl.nomclient' , 'ASC')
            ->addSelect('cl.idclient')

            ->getQuery()
            ->getResult()

            ;
    }


}

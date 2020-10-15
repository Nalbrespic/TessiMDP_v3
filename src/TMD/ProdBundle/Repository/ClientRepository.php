<?php

namespace TMD\ProdBundle\Repository;

use Doctrine\ORM\EntityRepository;


class ClientRepository extends EntityRepository
{
    public function myFindClientAsc()
    {
        return $this
            ->createQueryBuilder('c')
            ->orderBy('c.nomclient', 'ASC')
            ->getQuery()
            ->getArrayResult()
        ;
    }

    public function clientWithAppli()
    {
        return $this
            ->createQueryBuilder('c')
            ->where('c.idclient = 92')

            ;
    }

    public function findallCLientCompte($findallCLientCompte)
    {
        return $this
            ->createQueryBuilder('c')
            ->where('c.idclient  IN (:date)')
            ->setParameter('date', $findallCLientCompte)
            ->getQuery()
            ->getArrayResult()
            ;
    }



}

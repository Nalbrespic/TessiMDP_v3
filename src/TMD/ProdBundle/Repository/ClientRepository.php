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
        ;
    }

    public function clientWithAppli()
    {
        return $this
            ->createQueryBuilder('c')
            ->where('c.idclient = 92')

            ;
    }




}

<?php

namespace TMD\CoreBundle\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;


class TransporteursTarifClientRepository extends EntityRepository
{
public function findbyDate(){

    return $this
        ->createQueryBuilder('tr')
        ->innerJoin('tr.idClient', 'c')
        ->innerJoin('tr.idTransport', 'type')
        ->select('c.nomclient')
        ->addSelect('type.codetransport')
        ->addSelect('tr.remise')
        ->addSelect('tr.dateUpdate')
        ->orderBy('tr.dateUpdate', 'DESC')
        ->getQuery()
        ->getArrayResult()
        ;
}
}
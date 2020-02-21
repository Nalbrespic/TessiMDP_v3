<?php

namespace TMD\AppliBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EcommTransporteurRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class EcommCompteTransportRepository extends EntityRepository
{
    public function findallTransporteurCompte()
{
    return $this
        ->createQueryBuilder('n')
        ->innerJoin('n.idcompte', 'co')
        ->innerJoin('n.idtransport', 'tr')
        ->addSelect('co.idcompte')
        ->addSelect('tr.idtransport')
        ->getQuery()
        ->getArrayResult()

        ;
}
}
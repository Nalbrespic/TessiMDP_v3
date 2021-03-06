<?php

namespace TMD\AppliBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EcommTransporteurRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class EcommTransporteurRepository extends EntityRepository
{
    public function findallTransporteur()
{
    return $this
        ->createQueryBuilder('n')
        ->getQuery()
        ->getArrayResult()

        ;
}

public function findByLibelle($libelle){
        return $this
            ->createQueryBuilder('t')
            ->where('t.libelletransporteur IN (:libelle)')
            ->setParameter('libelle', $libelle)
            ->getQuery()
            ->getArrayResult()
            ;
}

}

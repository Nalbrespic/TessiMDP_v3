<?php

namespace TMD\ProdBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EcommPersosRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class EcommPersosRepository extends EntityRepository
{
    public function findNbArticlesByFile($files)
    {
        return $this
            ->createQueryBuilder('perso')
            ->innerJoin('perso.numbl', 'ligne')
            ->innerJoin('ligne.ecommCmdes', 'cmd' )
            ->where('perso.idfile IN (:id)')
            ->setParameter('id', $files)
            ->select('perso.codeproduit')
            ->addSelect('count(perso.qte) AS qte')
            ->addSelect('IF(cmd.codearticle = perso.codeproduit, ,null) AS inCde', false)
            ->addSelect('perso.idfile')
            ->addSelect('perso.libelle')
            ->groupBy('perso.codeproduit, perso.idfile')
            ->getQuery()
            ->getResult()

            ;
    }
}

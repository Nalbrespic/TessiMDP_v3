<?php

namespace TMD\ProdBundle\Repository;

use Doctrine\ORM\EntityRepository;


/**
 * EcommHistoStatutRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class EcommHistoStatutRepository extends EntityRepository
{
    public function donneHistoByBl($bl)
    {
        return $this
            ->createQueryBuilder('histo')
            ->innerJoin('histo.idstatut', 'st')
            ->where('histo.numbl = :id')
            ->setParameter('id', $bl)
            ->orderBy('histo.datestatut','DESC')
            ->select('st.idStatut')
            ->addSelect('st.statut')
            ->addselect('histo.numbl')
            ->addSelect('histo.datestatut')
            ->addSelect('histo.iduser')
            ->addSelect('histo.observation')

            ->getQuery()
            ->getArrayResult()

            ;
    }

    public function donneHistoByBlbuerrorWS($bl)
    {
        return $this
            ->createQueryBuilder('histo')
            ->where('histo.numbl = :id')
            ->setParameter('id', $bl)
            ->andWhere('histo.idstatut = (:st)')
            ->setParameter('st', 99)
            ->orderBy('histo.datestatut','DESC')
            ->getQuery()
            ->getArrayResult()

            ;
    }
    public function donneHistoByBlASC($bl)
    {
        return $this
            ->createQueryBuilder('histo')
            ->innerJoin('histo.idstatut', 'st')
            ->where('histo.numbl = :id')
            ->setParameter('id', $bl)
            ->orderBy('histo.datestatut','ASC')
            ->select('st.idStatut')
            ->addselect('st.statut')
            ->addselect('histo.datestatut')
            ->addSelect('histo.observation')
            ->getQuery()
            ->getArrayResult()

            ;
    }
}

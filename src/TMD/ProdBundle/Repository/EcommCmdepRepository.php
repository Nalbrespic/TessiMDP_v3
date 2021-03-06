<?php

namespace TMD\ProdBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EcommCmdepRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class EcommCmdepRepository extends EntityRepository
{
    public function findArticlesByFile($files)
    {
        return $this

            ->createQueryBuilder('cmd')
//            ->innerJoin('cmd.numbl', 'ligne')
//            ->where('ligne.idfile IN (:id)')
//            ->setParameter('id', $files)
            ->where('cmd.idfile IN (:id)')
            ->setParameter('id', $files)
            ->select('cmd.idfile')
            ->addSelect('sum(cmd.quantite) as quantite')
            ->addSelect('cmd.libelle')
            ->addSelect('cmd.flagart')
            ->groupBy('cmd.idfile, cmd.libelle')
            ->getQuery()
            ->getResult()

            ;
    }

    public function findArticlesByBlforSynthese($bl)
    {
        return $this
            ->createQueryBuilder('cmd')
            ->innerJoin('cmd.numbl', 'ligne')
            ->where('cmd.numbl IN (:id)')
            ->setParameter('id', $bl)
            ->select('ligne.idfile')
            ->addSelect('sum(cmd.quantite) as quantite')
            ->addSelect('cmd.libelle')
            ->addSelect('cmd.codearticle')
            ->addSelect('cmd.poids')
            ->addSelect('cmd.flagart')
            ->groupBy('cmd.libelle')
            ->getQuery()
            ->getResult()

            ;
    }

    public function findArticlesByFileArray($files)
    {
        return $this
            ->createQueryBuilder('cmd')
            ->innerJoin('cmd.numbl', 'ligne')
            ->innerJoin('cmd.idStatut', 'sat')
            ->where('ligne.idfile IN (:id)')
            ->setParameter('id', $files)
            ->select('ligne.idfile')
            ->addSelect('sum(cmd.quantite) as quantite')
            ->addSelect('cmd.libelle')
            ->addSelect('cmd.flagart')
            ->groupBy('ligne.idfile, cmd.codearticle, cmd.idStatut')
            ->addSelect('cmd.codearticle')
            ->addSelect('sat.statut as trStatut')
            ->orderBy('cmd.codearticle', 'ASC')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findArticlesRuptureByFileArray($bls)
    {
        return $this
            ->createQueryBuilder('cmd')
            ->innerJoin('cmd.idStatut', 'sat')
            ->where('cmd.numbl IN (:id)')
            ->setParameter('id', $bls)
            ->select('cmd.idfile')
            ->addSelect('sum(cmd.quantite) as quantite')
            ->addSelect('cmd.libelle')
            ->addSelect('cmd.codearticle')
            ->addSelect('cmd.flagart')
            ->addSelect('sat.statut as trStatut')
            ->groupBy('cmd.libelle')
            ->orderBy('cmd.codearticle', 'ASC')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findArticlesByBLsArray($bls)
    {
        return $this
            ->createQueryBuilder('cmd')
            ->innerJoin('cmd.numbl', 'ligne')
            ->where('ligne.numbl IN (:id)')
            ->setParameter('id', $bls)
            ->select('ligne.idfile')
            ->addSelect('sum(cmd.quantite) as quantite')
            ->addSelect('cmd.libelle')
            ->addSelect('cmd.codearticle')
            ->addSelect('cmd.flagart')
            ->groupBy('cmd.codearticle')
            ->orderBy('cmd.codearticle', 'ASC')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findArticlesByBLsArticleArray($bls)
    {
        return $this
            ->createQueryBuilder('cmd')
            ->innerJoin('cmd.numbl', 'ligne')
            ->where('ligne.numbl IN (:id)')
            ->setParameter('id', $bls)
            ->andWhere('cmd.flagart = false')
            ->select('ligne.idfile')
            ->addSelect('sum(cmd.quantite) as quantite')
            ->addSelect('cmd.libelle')
            ->addSelect('cmd.codearticle')
            ->addSelect('ligne.numbl')
            ->addSelect('cmd.poids')
            ->groupBy('cmd.numbl, cmd.codearticle')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findArticlesPersoByFileArray($files)
    {
        return $this
            ->createQueryBuilder('cmd')
            ->innerJoin('cmd.numbl', 'ligne')
            ->where('ligne.idfile IN (:id)')
            ->setParameter('id', $files)
            ->andWhere('cmd.flagart = 0')
            ->select('ligne.idfile')
            ->addSelect('cmd.libelle')
            ->addSelect('ligne.numbl')
            ->addSelect('count(cmd.perso1)')
            ->addSelect('cmd.flagart')
            ->addSelect('cmd.perso1')
            ->addSelect('cmd.perso2')
            ->orderBy('cmd.perso1', 'ASC')
            ->groupBy('cmd.perso1')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findArticlesPersoByBLsArray($files)
    {
        return $this
            ->createQueryBuilder('cmd')
            ->innerJoin('cmd.numbl', 'ligne')
            ->where('ligne.numbl IN (:id)')
            ->setParameter('id', $files)
            ->andWhere('cmd.flagart = 0')
            ->select('ligne.idfile')
            ->select('ligne.idfile')
            ->addSelect('cmd.libelle')
            ->addSelect('ligne.numbl')
            ->addSelect('count(cmd.perso1)')
            ->addSelect('cmd.flagart')
            ->addSelect('cmd.perso1')
            ->addSelect('cmd.perso2')
            ->orderBy('cmd.perso1', 'ASC')
            ->groupBy('cmd.perso1')
            ->getQuery()
            ->getArrayResult()
            ;
    }



    public function findArticlesReclaByFile($files)
    {
        return $this
            ->createQueryBuilder('cmd')
            ->innerJoin('cmd.numbl', 'ligne')
            ->where('ligne.idfile IN (:id)')
            ->setParameter('id', $files)
            ->andWhere('cmd.libelle LIKE :rec')
            ->setParameter('rec', '%cla%' )
            ->select('count(DISTINCT(ligne.numbl)) AS q' )
            ->addSelect('ligne.idfile')
            ->groupBy('ligne.idfile')
            ->getQuery()
            ->getResult()

            ;
    }


    public function findArticlesByOpeRupture($idop)
    {
        return $this
            ->createQueryBuilder('cmd')
            ->innerJoin('cmd.numbl', 'ligne')
            ->innerJoin('ligne.numligne', 'tr')
            ->innerJoin('tr.idfile', 'file')
            ->where('file.idappli = (:id)')
            ->setParameter('id', $idop)
            ->andWhere('tr.idStatut = (:rec)')
            ->setParameter('rec', 10 )
            ->andWhere('cmd.idStatut = (:rec3)')
            ->setParameter('rec3', 10 )
            ->andWhere('tr.idStatut != (:rec2)')
            ->setParameter('rec2', 9 )
            ->select('sum(cmd.quantite) as quantite')
            ->addSelect('cmd.libelle')
            ->addSelect('cmd.flagart')
            ->addSelect('cmd.codearticle')
            ->groupBy('cmd.codearticle ')
            ->orderBy('cmd.codearticle', 'ASC')
            ->getQuery()
            ->getArrayResult()

            ;
    }

    public function findArticlesByOpe($ipOp)
    {
        return $this
            ->createQueryBuilder('cmd')
            ->innerJoin('cmd.numbl', 'ligne')
            ->innerJoin('ligne.numligne', 'tr')
            ->innerJoin('tr.idfile','file')
//            ->innerJoin('tr.idStatut','st')
            ->where('file.idappli = (:id)')
            ->setParameter('id', $ipOp)
            ->andWhere('tr.idStatut != (:an)')
            ->setParameter('an', 9)
            ->select('sum(cmd.quantite) as quantite')
            ->addSelect('cmd.libelle')
            ->addSelect('cmd.flagart')
            ->addSelect('cmd.codearticle')
            ->groupBy('cmd.codearticle ')
            ->orderBy('cmd.codearticle', 'ASC')
            ->getQuery()
            ->getArrayResult()

            ;
    }



    public function findArticlesByBL($numBL)
    {
        return $this
            ->createQueryBuilder('cmd')
            ->innerJoin('cmd.numbl', 'ligne')
            ->where('cmd.numbl IN (:id)')
            ->setParameter('id', $numBL)
            ->andWhere('cmd.flagart = (:f)')
            ->setParameter('f', 0)
            ->select('cmd.perso1')
            ->addSelect('sum(cmd.quantite) as qte')
            ->addSelect('cmd.libelle')
            ->addSelect('ligne.numbl')
            ->groupBy('cmd.perso1, cmd.libelle, cmd.numbl')
            ->getQuery()
            ->getResult()

            ;
    }

    public function findArticlesByFileBl($numBL)
    {
        return $this
            ->createQueryBuilder('cmd')
            ->innerJoin('cmd.numbl', 'ligne')
            ->where('ligne.numbl IN (:id)')
            ->setParameter('id', $numBL)
            ->select('sum(cmd.quantite) as quantite')
            ->addSelect('cmd.libelle')
            ->addSelect('cmd.codearticle')
            ->addSelect('cmd.perso1')
            ->addSelect('cmd.flagart')
            ->addSelect('ligne.numbl')
            ->groupBy('cmd.codearticle, ligne.numbl')
            ->getQuery()
            ->getArrayResult()

            ;
    }

    public function findArticlesDetailsByBL($numBL)
    {
        return $this
            ->createQueryBuilder('cmd')
            ->innerJoin('cmd.idArticle', 'article')
            ->where('cmd.numbl = (:id)')
            ->setParameter('id', $numBL)
            ->andWhere('cmd.flagart = (:idv)')
            ->setParameter('idv', 0)
            ->select('sum(cmd.quantite) as quantite')
            ->addSelect('article.libarticle')
            ->addSelect('article.poidsunitaire')
            ->addSelect('article.prixht')
            ->groupBy('article.codearticle')
            ->getQuery()
            ->getArrayResult()

            ;
    }

    public function findAllBlByBLSandPresse($numBLs)
    {
        return $this
            ->createQueryBuilder('cmd')
            ->where('cmd.numbl IN (:id)')
            ->setParameter('id', $numBLs)
            ->andWhere('cmd.flagart = (:idv)')
            ->setParameter('idv', 0)
            ->select('sum(cmd.quantite) as quantite')
            ->addSelect('sum(cmd.poids) as poids')
            ->addSelect('cmd.codearticle')
            ->groupBy('cmd.numbl')
            ->orderBy('quantite', 'ASC')
            ->getQuery()
            ->getArrayResult()

            ;
    }

    public function findregroupArticleByBLSandPresse($numBLs)
    {
        return $this
            ->createQueryBuilder('cmd')
            ->where('cmd.numbl IN (:id)')
            ->setParameter('id', $numBLs)
            ->andWhere('cmd.flagart = (:idv)')
            ->setParameter('idv', 0)
            ->select('sum(cmd.quantite) as quantite')
            ->addSelect('cmd.libelle as libelle')
            ->addSelect('cmd.poids')
            ->groupBy('cmd.codearticle')
            ->orderBy('libelle', 'ASC')
            ->getQuery()
            ->getArrayResult()

            ;
    }

    public function findArticlesByOpRupture($op)
    {
        return $this
            ->createQueryBuilder('cmd')
            ->innerJoin('cmd.numbl', 'ligne')
            ->innerJoin('ligne.numligne', 'tr')
            ->innerJoin('tr.idfile','file')
            ->innerJoin('file.idappli', 'app')
            ->where('file.idappli = (:id)')
            ->setParameter('id', $op)
            ->andwhere('tr.idStatut = :st')
            ->setParameter('st', 10)
            ->andwhere('cmd.idStatut = :st')
            ->setParameter('st', 10)
            ->select('sum(cmd.quantite) as quantite')
            ->addSelect('cmd.libelle')
            ->addSelect('cmd.flagart')
            ->addSelect('cmd.codearticle')
            ->addSelect('app.appliname')
            ->groupBy('cmd.codearticle')
            ->getQuery()
            ->getArrayResult()

            ;
    }

    public function FindCountArticlesByOpeByDate($idOpe, $thisdate, $type)
    {
        $query = $this->_em->createQuery(' SELECT sum(cmd.quantite) FROM TMDProdBundle:EcommCmdep cmd
                                                INNER JOIN TMDProdBundle:EcommBL bl
                                                WITH cmd.numbl = bl.bl
                                                INNER JOIN TMDProdBundle:EcommLignes ligne
                                                WITH cmd.numbl = ligne.numbl
                                                INNER JOIN TMDProdBundle:EcommTracking tr
                                                WITH ligne.numligne = tr.numligne
                                                INNER JOIN TMDProdBundle:EcommStatut st
                                                WITH tr.idStatut = st.idStatut
                                                INNER JOIN TMDProdBundle:EcommFiles file
                                                WITH tr.idfile = file.idfile
                                                INNER JOIN TMDProdBundle:EcommAppli ap
                                                WITH file.idappli = ap.idappli
                                                WHERE ap.idappli ='.$idOpe.'
                                                    and st.idStatut != 9
                                                    and st.idStatut !=10
                                                    and tr.dateDepot LIKE '."'".$thisdate.'%'."'".'
                                                    and tr.json LIKE '."'".'%'.$type.'%'."'".'
                                                    and cmd.flagart !=1
        ');
        $result = $query->getSingleScalarResult();
        return $result;
    }
    public function FindArticlesByOpeByDateNoType($idOpe, $thisdate)
    {
        $query = $this->_em->createQuery(' SELECT sum(cmd.quantite) FROM TMDProdBundle:EcommCmdep cmd
                                                INNER JOIN TMDProdBundle:EcommBL bl
                                                WITH cmd.numbl = bl.bl
                                                INNER JOIN TMDProdBundle:EcommLignes ligne
                                                WITH cmd.numbl = ligne.numbl
                                                INNER JOIN TMDProdBundle:EcommTracking tr
                                                WITH ligne.numligne = tr.numligne
                                                INNER JOIN TMDProdBundle:EcommStatut st
                                                WITH tr.idStatut = st.idStatut
                                                INNER JOIN TMDProdBundle:EcommFiles file
                                                WITH tr.idfile = file.idfile
                                                INNER JOIN TMDProdBundle:EcommAppli ap
                                                WITH file.idappli = ap.idappli
                                                WHERE ap.idappli ='.$idOpe.'
                                                    and st.idStatut != 9
                                                    and st.idStatut !=10
                                                   and tr.dateDepot LIKE '."'".$thisdate.'%'."'".'
                                                    and cmd.flagart !=1
        ');
        $result = $query->getSingleScalarResult();
        return $result;
    }

    public function findArticlesByOpeByDate($ipOp, $date)
    {
        return $this
            ->createQueryBuilder('cmd')
            ->innerJoin('cmd.numbl', 'ligne')
            ->innerJoin('ligne.numligne', 'tr')
            ->innerJoin('tr.idfile','file')
//            ->innerJoin('tr.idStatut','st')
            ->where('file.idappli = (:id)')
            ->setParameter('id', $ipOp)
            ->andWhere('cmd.numseq != 99')
            ->andWhere('tr.idStatut != (:an)')
            ->andWhere('tr.dateDepot LIKE :date ')
            ->setParameter('date', $date.'%')
            ->setParameter('an', 9)
            ->select('sum(cmd.quantite) as quantite')
            ->addSelect('cmd.libelle')
            ->addSelect('cmd.flagart')
            ->addSelect('cmd.codearticle')
            ->groupBy('cmd.codearticle ')
            ->orderBy('quantite', 'DESC')
            ->getQuery()
            ->getArrayResult()

            ;
    }
}

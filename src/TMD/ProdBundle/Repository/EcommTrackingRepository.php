<?php

namespace TMD\ProdBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * EcommTrackingRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class EcommTrackingRepository extends EntityRepository
{
    public function clientWithFile(){

        return $this
            ->createQueryBuilder('e')
            ->innerJoin('e.idfile', 'file')
            ;

    }

    public function findNbRetentionByFile( $files)
    {
        return $this
            ->createQueryBuilder('tr')
            ->innerJoin('tr.idfile','fil')
            ->select('fil.idfile')
            ->addSelect('count(tr.idfile)')
            ->where('tr.idfile IN (:id)')
            ->setParameter('id', $files)
            ->andWhere('tr.flagXport = :nb')
            ->setParameter('nb', true)
            ->groupBy('tr.idfile')
            ->getQuery()
            ->getArrayResult()

            ;
    }



    public function findFileByOpe($idappli, $page, $nbParPage)
    {
        $query = $this
            ->createQueryBuilder('tr')
            ->innerJoin('tr.idfile', 'fl')
            ->innerJoin('fl.idappli', 'app')
            ->where('app.idappli = :id')
            ->setParameter('id', $idappli)
            ->addSelect('tr.flagXport')
            ->addselect('fl.idfile')
            ->addSelect('fl.filename')
            ->addSelect('fl.dateFile')
            ->groupBy('fl.idfile')
            ->orderBy('tr.dateInsert', 'DESC')
            ->getQuery()

            ;
        $query
            // On définit l'annonce à partir de laquelle commencer la liste
            ->setFirstResult(($page-1) * $nbParPage)
            // Ainsi que le nombre d'annonce à afficher sur une page
            ->setMaxResults($nbParPage)
        ;

        // Enfin, on retourne l'objet Paginator correspondant à la requête construite
        // (n'oubliez pas le use correspondant en début de fichier)
        return new Paginator($query, true);


    }

    public function findFileTotByOpe($idappli)
    {
        return $this
            ->createQueryBuilder('tr')
            ->innerJoin('tr.idfile', 'fl')
            ->innerJoin('fl.idappli', 'app')
            ->where('app.idappli = :id')
            ->setParameter('id', $idappli)
            ->select('fl.idfile')
            ->addSelect('fl.filename')
            ->addSelect('fl.dateFile')
            ->groupBy('fl.idfile')
            ->orderBy('tr.dateInsert', 'DESC')
            ->getQuery()
            ->getResult()

        ;



    }

    public function findNbTrackingByFile($idappli)
    {
        return $this
            ->createQueryBuilder('tr')
            ->innerJoin('tr.idfile', 'fl')
            ->innerJoin('fl.idappli', 'app')
            ->where('app.idappli = :id')
            ->setParameter('id', $idappli)
            ->select('fl.idfile')
            ->addselect('count(tr.numligne)')
            ->groupBy('fl.idfile')
            ->getQuery()
            ->getResult()

        ;

    }


    public function findAllBlByFileUpdateDateDepot($idfile)
    {
        return $this
            ->createQueryBuilder('tr')
            ->where('tr.idfile = :id')
            ->setParameter('id', $idfile)
            ->getQuery()
            ->getArrayResult()

            ;

    }

    public function findTrackingsPbWS()
    {
        $date = date("Y-m-d", strtotime('-60 day'));
        return $this
            ->createQueryBuilder('tr')
            ->innerJoin('tr.idfile','file')
            ->innerJoin('file.idappli', 'app')
            ->innerJoin('tr.idclient', 'cli')
            ->select('cli.nomclient')
            ->addSelect('count(tr.idfile)')
            ->addSelect('app.appliname')
            ->addSelect('app.idappli')
            ->where('tr.flagEtikt = (:flag)')
            ->setParameter('flag', -1)
            ->andWhere('file.dateFile > :nb')
            ->setParameter('nb', $date)
            ->groupBy('app.appliname')
            ->getQuery()
            ->getArrayResult()

            ;
    }

    public function findSyntheseStatut($idOpe)
    {
        return $this
            ->createQueryBuilder('tr')
            ->innerJoin('tr.idStatut', 'stat')
            ->innerJoin('tr.idfile', 'file')
            ->where('file.idappli IN (:id)')
            ->setParameter('id', $idOpe)
            ->select('file.idfile')
            ->addSelect('stat.idStatut')
            ->addselect('count(stat.idStatut)')
            ->groupBy('tr.idfile, stat.idStatut')
            ->getQuery()
            ->getResult()

            ;

    }



//



}

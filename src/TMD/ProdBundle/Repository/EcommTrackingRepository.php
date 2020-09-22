<?php

namespace TMD\ProdBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use TMD\ProdBundle\Entity\EcommTracking;

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

    public function trackingByNumligne($numligne){
        return $this
            ->createQueryBuilder('t')
            ->where('t.numligne IN (:numligne)')
            ->setParameter('numligne', $numligne)
            ->innerJoin('t.idStatut' ,'st')
            ->addSelect('st.idStatut')
            ->innerJoin('t.idclient', 'cl')
            ->addSelect('cl.idclient')

            ->getQuery()
            ->getResult();
    }

    public function trackingByIdclient($idClient)
    {

        return $this
            ->createQueryBuilder('t')
            ->innerJoin('t.idclient' ,'cl')
            ->where('cl.idclient IN (:id)')
            ->setParameter('id', $idClient)
            ->select('cl.nomclient')
            ->addSelect('cl.idclient')
            ->innerJoin('t.idStatut' ,'st')
            ->addSelect('st.idStatut')
            ->addSelect('st.statut')
            ->addSelect('t.numligne')
            ->addSelect('t.numCmdeClient')
            ->addSelect('t.refclient')
            ->orderBy('t.refclient', 'DESC')
            ->addSelect('t.dateCmde')
            ->getQuery()
            ->getArrayResult();
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
            ->andWhere('tr.idStatut != :stat1')
            ->setParameter('stat1', 9)
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

    public function findnbBlWithDepotNull($idOpe)
    {
        return $this
            ->createQueryBuilder('tr')
            ->innerJoin('tr.idfile', 'file')
            ->where('file.idappli IN (:id)')
            ->setParameter('id', $idOpe)
            ->andWhere('tr.dateDepot = (:ida)')
            ->setParameter('ida', "0000-00-00")
            ->andWhere('tr.idStatut != (:st)')
            ->setParameter('st', 9)
            ->select('file.idfile')
            ->addselect('count(tr.expRef)')
            ->groupBy('file.idfile')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findBlbyFileWithDepotNull($idFile)
    {
        return $this
            ->createQueryBuilder('tr')
            ->where('tr.idfile = (:id)')
            ->setParameter('id', $idFile)
            ->andWhere('tr.dateDepot = (:ida)')
            ->setParameter('ida', "0000-00-00")
            ->andWhere('tr.idStatut != (:st)')
            ->setParameter('st', 9)
            ->groupBy('tr.expRef')
            ->getQuery()
            ->getResult()
            ;
    }


    /**
     * find all ecommtracking's by their numligne
     * @param array $numLignes array of numligne
     * @return EcommTracking[]
     */
    public function findAllNumlignes (array $numLignes) {
        return $this->createQueryBuilder('tr')
            ->where('tr.numligne IN (:numlignes)')
            ->setParameter('numlignes', $numLignes)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findTrackingByBl($numbl){
        return $this
            ->createQueryBuilder('tr')
            ->where('tr.expRef = :numbl')
            ->setParameter('numbl', $numbl)
            ->getQuery()
            ->getArrayResult();
    }

    public function findStatutByBl($numbl){
        return $this
            ->createQueryBuilder('tr')
            ->innerJoin('tr.idStatut', 'st')
            ->where('tr.expRef = :numbl')
            ->setParameter('numbl', $numbl)
            ->select('st.idStatut')
            ->addSelect('st.statut')
            ->getQuery()
            ->getArrayResult();
    }
}

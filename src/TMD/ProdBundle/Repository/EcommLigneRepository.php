<?php

namespace TMD\ProdBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EcommLigneRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class EcommLigneRepository extends EntityRepository
{

    public function findTrackingsPbWSwithop($iop)
    {
        $date = date("Y-m-d", strtotime('-60 day'));
        return $this
            ->createQueryBuilder('ligne')
            ->innerJoin('ligne.numligne', 'tr')
            ->innerJoin('tr.idfile','file')
            ->innerJoin('file.idappli', 'app')
            ->innerJoin('tr.idclient', 'cli')
//            ->innerJoin('tr.idStatut','statut')
            ->where('tr.flagEtikt = (:flag)')
            ->setParameter('flag', -1)
            ->andWhere('file.dateFile > :nb')
            ->setParameter('nb', $date)
            ->andWhere('app.idappli = :id')
            ->setParameter('id', $iop)
            ->andWhere('tr.idStatut != :stat1')
            ->setParameter('stat1', 9)
            ->orderBy('tr.numligne','ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findTrackingsbyDateDepotandop($iop, $date)
    {
        return $this
            ->createQueryBuilder('ligne')
            ->innerJoin('ligne.numligne', 'tr')
            ->innerJoin('tr.idfile','file')
            ->innerJoin('file.idappli', 'app')
            ->where('tr.dateDepot = (:dt)')
            ->setParameter('dt', $date)
            ->andWhere('app.idappli = :id')
            ->setParameter('id', $iop)
            ->select('ligne.numbl')
            ->addSelect('app.appliname')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findTrackingsbyDateDepotandopAndPress($iop, $date)
    {
        return $this
            ->createQueryBuilder('ligne')
            ->innerJoin('ligne.numligne', 'tr')
            ->innerJoin('tr.idfile','file')
            ->innerJoin('file.idappli', 'app')
            ->where('tr.dateDepot = (:dt)')
            ->setParameter('dt', $date)
            ->andWhere('app.idappli = :id')
            ->setParameter('id', $iop)
            ->andWhere('tr.typeTransport = :tr')
            ->setParameter('tr', 'PRESS')
            ->select('ligne.numbl')
            ->addSelect('app.appliname')
            ->getQuery()
            ->getArrayResult()
        ;
    }

    public function findAllBlRuptureByFile($idfile)
    {
        return $this
            ->createQueryBuilder('ligne')
            ->leftJoin('ligne.ecommCmdeps', 'cmd')
            ->innerJoin('ligne.numligne', 'tr')
            ->innerJoin('tr.idfile', 'file')
            ->innerJoin('file.idappli', 'app')
            ->innerJoin('tr.idStatut','statut')
            ->leftJoin('ligne.bls','bl')
            ->where('tr.idfile IN (:id)')
            ->setParameter('id', $idfile)
            ->andWhere('tr.idStatut = :stat')
            ->setParameter('stat', 10)
            ->andWhere('tr.idStatut != :stat1')
            ->setParameter('stat1', 9)
            ->addSelect('app.appliname')
            ->addSelect('tr.destinataire')
            ->addSelect('tr.destCp')
            ->addSelect('tr.typeTransport')
            ->addSelect('ligne.numbl')
            ->addSelect('tr.destRue')
            ->addSelect('tr.destAd2')
            ->addSelect('tr.destAd3')
            ->addSelect('tr.destAd4')
            ->addSelect('tr.destAd5')
            ->addSelect('tr.destAd6')
            ->addSelect('tr.expRef')
            ->addSelect('tr.refclient')
            ->addSelect('tr.destVille')
            ->addSelect('tr.destTel')
            ->addSelect('tr.destMail')
            ->addSelect('file.dateFile')
            ->addSelect('tr.dateCmde')
            ->addSelect('tr.numCmdeClient')
            ->addSelect('tr.instrLivrais1')
            ->addSelect('tr.instrLivrais2')
            ->addSelect('tr.dateDepot')
            ->addSelect('tr.type')
            ->addSelect('tr.destPays')
            ->addSelect('tr.montant')
            ->addSelect('file.nbpages')
            ->addSelect('file.filename')
            ->addSelect('ligne.poids as poidsReel')
            ->addSelect('sum(cmd.quantite) as quantite')
            ->addSelect('count(cmd.numbl) as nbCmd')
//            ->addSelect('statut.idStatut')
            ->addSelect('statut.statut as trStatut')
            ->addSelect('tr.json')
            ->addSelect('bl.modexp')
            ->addSelect('bl.dateProduction')
            ->addSelect('bl.nColis')
            ->orderBy('tr.dateInsert', 'DESC')
            ->groupBy('ligne.numbl')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findAllBlDepotNullByFile($idfile)
    {
        return $this
            ->createQueryBuilder('ligne')
            ->leftJoin('ligne.ecommCmdeps', 'cmd')
            ->innerJoin('ligne.numligne', 'tr')
            ->innerJoin('tr.idfile', 'file')
            ->innerJoin('file.idappli', 'app')
            ->innerJoin('tr.idStatut','statut')
            ->leftJoin('ligne.bls','bl')
            ->where('tr.idfile IN (:id)')
            ->setParameter('id', $idfile)
            ->andWhere('tr.idStatut != :stat1')
            ->setParameter('stat1', 9)
            ->andWhere('tr.dateDepot = :date')
            ->setParameter('date', '0000-00-00')
            ->addSelect('app.appliname')
            ->addSelect('tr.destinataire')
            ->addSelect('tr.destCp')
            ->addSelect('tr.typeTransport')
            ->addSelect('ligne.numbl')
            ->addSelect('tr.destRue')
            ->addSelect('tr.destAd2')
            ->addSelect('tr.destAd3')
            ->addSelect('tr.destAd4')
            ->addSelect('tr.destAd5')
            ->addSelect('tr.destAd6')
            ->addSelect('tr.expRef')
            ->addSelect('tr.refclient')
            ->addSelect('tr.destVille')
            ->addSelect('tr.destTel')
            ->addSelect('tr.destMail')
            ->addSelect('file.dateFile')
            ->addSelect('tr.dateCmde')
            ->addSelect('tr.numCmdeClient')
            ->addSelect('tr.instrLivrais1')
            ->addSelect('tr.instrLivrais2')
            ->addSelect('tr.dateDepot')
            ->addSelect('tr.type')
            ->addSelect('tr.destPays')
            ->addSelect('tr.montant')
            ->addSelect('file.nbpages')
            ->addSelect('file.filename')
            ->addSelect('ligne.poids as poidsReel')
            ->addSelect('sum(cmd.quantite) as quantite')
            ->addSelect('count(cmd.numbl) as nbCmd')
//            ->addSelect('statut.idStatut')
            ->addSelect('statut.statut as trStatut')
            ->addSelect('tr.json')
            ->addSelect('bl.modexp')
            ->addSelect('bl.dateProduction')
            ->addSelect('bl.nColis')
            ->orderBy('tr.dateInsert', 'DESC')
            ->groupBy('ligne.numbl')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findAllBlRuptureByFileArticle($idfile)
    {
        return $this
            ->createQueryBuilder('ligne')
            ->leftJoin('ligne.ecommCmdeps', 'cmd')
            ->innerJoin('ligne.numligne', 'tr')
            ->innerJoin('tr.idfile', 'file')
            ->innerJoin('file.idappli', 'app')
            ->leftJoin('ligne.bls','bl')
            ->where('tr.idfile IN (:id)')
            ->setParameter('id', $idfile)
            ->andWhere('tr.idStatut = :stat')
            ->setParameter('stat', 10)
            ->andWhere('tr.idStatut != :stat1')
            ->setParameter('stat1', 9)
            ->select('app.appliname')
            ->addSelect('tr.destinataire')
            ->addSelect('tr.destCp')
            ->addSelect('tr.typeTransport')
            ->addSelect('ligne.numbl')
            ->addSelect('tr.destRue')
            ->addSelect('tr.destAd2')
            ->addSelect('tr.destAd3')
            ->addSelect('tr.destAd4')
            ->addSelect('tr.destAd5')
            ->addSelect('tr.destAd6')
            ->addSelect('tr.expRef')
            ->addSelect('tr.refclient')
            ->addSelect('tr.destVille')
            ->addSelect('tr.destTel')
            ->addSelect('tr.destMail')
            ->addSelect('file.dateFile')
            ->addSelect('tr.dateCmde')
            ->addSelect('tr.numCmdeClient')
            ->addSelect('tr.instrLivrais1')
            ->addSelect('tr.instrLivrais2')
            ->addSelect('tr.dateDepot')
            ->addSelect('tr.type')
            ->addSelect('tr.destPays')
            ->addSelect('tr.montant')
            ->addSelect('file.nbpages')
            ->addSelect('file.filename')
            ->addSelect('ligne.poids as poidsReel')
//            ->addSelect('sum(cmd.quantite) as quantite')
//            ->addSelect('count(cmd.numbl) as nbCmd')
            ->addSelect('IDENTITY(tr.idStatut) as trStatut')
            ->addSelect('tr.json')
            ->addSelect('bl.modexp')
            ->addSelect('bl.dateProduction')
            ->addSelect('bl.nColis')
            ->addSelect('cmd.codearticle')
            ->addSelect('cmd.libelle')
            ->addSelect('cmd.quantite')
            ->addSelect('IDENTITY(cmd.idStatut) as cmdStatut')
            ->orderBy('tr.expRef', 'DESC')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findAllBlByFile($idfile)
    {
        return $this
            ->createQueryBuilder('ligne')
            ->leftJoin('ligne.ecommCmdeps', 'cmd')
            ->innerJoin('ligne.numligne', 'tr')
            ->innerJoin('tr.idfile', 'file')
            ->innerJoin('file.idappli', 'app')
            ->innerJoin('tr.idStatut', 'stat')
            ->leftJoin('ligne.bls', 'bl')
            ->where('tr.idfile IN (:id)')
            ->setParameter('id', $idfile)
            ->Select('app.appliname')
            ->addSelect('tr.destinataire')
            ->addSelect('tr.destCp')
            ->addSelect('tr.typeTransport')
            ->addSelect('ligne.numbl')
            ->addSelect('tr.destRue')
            ->addSelect('tr.destAd2')
            ->addSelect('tr.destAd3')
            ->addSelect('tr.destAd4')
            ->addSelect('tr.destAd5')
            ->addSelect('tr.destAd6')
            ->addSelect('tr.expRef')
            ->addSelect('tr.refclient')
            ->addSelect('tr.destVille')
            ->addSelect('tr.destTel')
            ->addSelect('tr.destMail')
            ->addSelect('file.dateFile')
            ->addSelect('tr.dateCmde')
            ->addSelect('tr.numCmdeClient')
            ->addSelect('tr.instrLivrais1')
            ->addSelect('tr.instrLivrais2')
            ->addSelect('tr.dateDepot')
            ->addSelect('tr.type')
            ->addSelect('tr.destPays')
            ->addSelect('tr.montant')
            ->addSelect('file.nbpages')
            ->addSelect('file.filename')
            ->addSelect('ligne.poids as poidsReel')
            ->addSelect('sum(cmd.quantite) as quantite')
            ->addSelect('count(cmd.numbl) as nbCmd')
            ->addSelect('stat.idStatut')
            ->addSelect('stat.statut as trStatut')
            ->addSelect('tr.json')
            ->addSelect('bl.modexp')
            ->addSelect('bl.dateProduction')
            ->addSelect('bl.nColis')
            ->orderBy('tr.dateInsert', 'DESC')
            ->groupBy('tr.numCmdeClient')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findAllBlByFileArticle($idfile)
    {
        return $this
            ->createQueryBuilder('ligne')
            ->leftJoin('ligne.ecommCmdeps', 'cmd')
            ->innerJoin('ligne.numligne', 'tr')
            ->innerJoin('tr.idfile', 'file')
            ->innerJoin('file.idappli', 'app')
            ->leftJoin('ligne.bls', 'bl')
            ->where('tr.idfile IN (:id)')
            ->setParameter('id', $idfile)
            ->select('app.appliname')
            ->addSelect('tr.destinataire')
            ->addSelect('tr.destCp')
            ->addSelect('tr.typeTransport')
            ->addSelect('ligne.numbl')
            ->addSelect('tr.destRue')
            ->addSelect('tr.destAd2')
            ->addSelect('tr.destAd3')
            ->addSelect('tr.destAd4')
            ->addSelect('tr.destAd5')
            ->addSelect('tr.destAd6')
            ->addSelect('tr.expRef')
            ->addSelect('tr.refclient')
            ->addSelect('tr.destVille')
            ->addSelect('tr.destTel')
            ->addSelect('tr.destMail')
            ->addSelect('file.dateFile')
            ->addSelect('tr.dateCmde')
            ->addSelect('tr.numCmdeClient')
            ->addSelect('tr.instrLivrais1')
            ->addSelect('tr.instrLivrais2')
            ->addSelect('tr.dateDepot')
            ->addSelect('tr.type')
            ->addSelect('tr.destPays')
            ->addSelect('tr.montant')
            ->addSelect('file.nbpages')
            ->addSelect('file.filename')
            ->addSelect('ligne.poids as poidsReel')
//            ->addSelect('sum(cmd.quantite) as quantite')
//            ->addSelect('count(cmd.numbl) as nbCmd')
            ->addSelect('IDENTITY(tr.idStatut) as trStatut')
            ->addSelect('tr.json')
            ->addSelect('bl.modexp')
            ->addSelect('bl.dateProduction')
            ->addSelect('bl.nColis')
            ->addSelect('cmd.codearticle')
            ->addSelect('cmd.libelle')
            ->addSelect('cmd.quantite')
            ->addSelect('IDENTITY(cmd.idStatut) as cmdStatut')
            ->orderBy('tr.expRef', 'DESC')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findAllBlDeleteByFile($idfile)
    {
        return $this
            ->createQueryBuilder('ligne')
            ->leftJoin('ligne.ecommCmdeps', 'cmd')
            ->innerJoin('ligne.numligne', 'tr')
            ->innerJoin('tr.idfile', 'file')
            ->innerJoin('file.idappli', 'app')
            ->innerJoin('tr.idStatut','statut')
            ->leftJoin('ligne.bls','bl')
            ->where('tr.idfile IN (:id)')
            ->setParameter('id', $idfile)
            ->andWhere('tr.idStatut = :stat')
            ->setParameter('stat', 9)
            ->Select('app.appliname')
            ->addSelect('tr.destinataire')
            ->addSelect('tr.destCp')
            ->addSelect('tr.typeTransport')
            ->addSelect('ligne.numbl')
            ->addSelect('tr.destRue')
            ->addSelect('tr.destAd2')
            ->addSelect('tr.destAd3')
            ->addSelect('tr.destAd4')
            ->addSelect('tr.destAd5')
            ->addSelect('tr.destAd6')
            ->addSelect('tr.expRef')
            ->addSelect('tr.refclient')
            ->addSelect('tr.destVille')
            ->addSelect('tr.destTel')
            ->addSelect('tr.destMail')
            ->addSelect('file.dateFile')
            ->addSelect('tr.dateCmde')
            ->addSelect('tr.numCmdeClient')
            ->addSelect('tr.instrLivrais1')
            ->addSelect('tr.instrLivrais2')
            ->addSelect('tr.dateDepot')
            ->addSelect('tr.type')
            ->addSelect('tr.destPays')
            ->addSelect('tr.montant')
            ->addSelect('file.nbpages')
            ->addSelect('file.filename')
            ->addSelect('ligne.poids as poidsReel')
            ->addSelect('sum(cmd.quantite) as quantite')
            ->addSelect('count(cmd.numbl) as nbCmd')
            ->addSelect('statut.idStatut')
            ->addSelect('tr.json')
            ->addSelect('bl.modexp')
            ->addSelect('bl.dateProduction')
            ->addSelect('bl.nColis')
//            ->andWhere('cmd.flagart = (:qt)')
//            ->setParameter('qt', 0)
            ->orderBy('tr.dateInsert', 'DESC')
            ->groupBy('ligne.numbl')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findAllBlNonProdByFile($idfile)
    {
        return $this
            ->createQueryBuilder('ligne')
            ->leftJoin('ligne.bls', 'bl')
            ->leftJoin('ligne.ecommCmdeps', 'cmd')
            ->innerJoin('ligne.numligne', 'tr')
            ->innerJoin('tr.idfile', 'file')
            ->innerJoin('file.idappli', 'app')
            ->innerJoin('tr.idStatut','statut')
            ->where('tr.idfile IN (:id)')
            ->setParameter('id', $idfile)
            ->andwhere('bl.dateProduction = :dat or bl.dateProduction is null ')
            ->setParameter('dat', '0000-00-00 00:00:00')
            ->andWhere('tr.idStatut != :sat')
            ->setParameter('sat', 9)
            ->andWhere('tr.idStatut != :sat1')
            ->setParameter('sat1', 10)
            ->select('app.appliname')
            ->addSelect('tr.numCmdeClient')
            ->addSelect('tr.destinataire')
            ->addSelect('tr.destCp')
            ->addSelect('tr.typeTransport')
            ->addSelect('ligne.numbl')
            ->addSelect('tr.destRue')
            ->addSelect('tr.destAd2')
            ->addSelect('tr.destAd3')
            ->addSelect('tr.destAd4')
            ->addSelect('tr.destAd5')
            ->addSelect('tr.destAd6')
            ->addSelect('tr.expRef')
            ->addSelect('tr.destTel')
            ->addSelect('tr.destMail')
            ->addSelect('tr.refclient')
            ->addSelect('tr.destVille')
            ->addSelect('file.dateFile')
            ->addSelect('tr.dateCmde')
            ->addSelect('tr.instrLivrais1')
            ->addSelect('tr.instrLivrais2')
            ->addSelect('tr.dateDepot')
            ->addSelect('tr.type')
            ->addSelect('tr.destPays')
            ->addSelect('tr.montant')
            ->addSelect('file.nbpages')
            ->addSelect('file.filename')
            ->addSelect('statut.idStatut')
            ->addSelect('statut.statut as trStatut')
            ->addSelect('ligne.poids as poidsReel')
            ->addSelect('count(cmd.numbl) as nbCmd')
            ->addSelect('tr.json')
            ->addSelect('bl.modexp')
            ->addSelect('bl.dateProduction')
            ->addSelect('bl.nColis')
            ->addSelect('sum(cmd.quantite) as quantite')
            ->orderBy('tr.dateInsert', 'DESC')
            ->groupBy('ligne.numbl')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findAllBlNonProdByFileArticle($idfile)
    {
        return $this
            ->createQueryBuilder('ligne')
            ->leftJoin('ligne.bls', 'bl')
            ->leftJoin('ligne.ecommCmdeps', 'cmd')
            ->innerJoin('ligne.numligne', 'tr')
            ->innerJoin('tr.idfile', 'file')
            ->innerJoin('file.idappli', 'app')
            ->where('tr.idfile IN (:id)')
            ->setParameter('id', $idfile)
            ->andwhere('bl.dateProduction = :dat or bl.dateProduction is null ')
            ->setParameter('dat', '0000-00-00 00:00:00')
            ->andWhere('tr.idStatut != :sat')
            ->setParameter('sat', 9)
            ->andWhere('tr.idStatut != :sat1')
            ->setParameter('sat1', 10)
            ->select('app.appliname')
            ->addSelect('tr.destinataire')
            ->addSelect('tr.destCp')
            ->addSelect('tr.typeTransport')
            ->addSelect('ligne.numbl')
            ->addSelect('tr.destRue')
            ->addSelect('tr.destAd2')
            ->addSelect('tr.destAd3')
            ->addSelect('tr.destAd4')
            ->addSelect('tr.destAd5')
            ->addSelect('tr.destAd6')
            ->addSelect('tr.expRef')
            ->addSelect('tr.refclient')
            ->addSelect('tr.destVille')
            ->addSelect('tr.destTel')
            ->addSelect('tr.destMail')
            ->addSelect('file.dateFile')
            ->addSelect('tr.dateCmde')
            ->addSelect('tr.numCmdeClient')
            ->addSelect('tr.instrLivrais1')
            ->addSelect('tr.instrLivrais2')
            ->addSelect('tr.dateDepot')
            ->addSelect('tr.type')
            ->addSelect('tr.destPays')
            ->addSelect('tr.montant')
            ->addSelect('file.nbpages')
            ->addSelect('file.filename')
            ->addSelect('ligne.poids as poidsReel')
//            ->addSelect('sum(cmd.quantite) as quantite')
//            ->addSelect('count(cmd.numbl) as nbCmd')
            ->addSelect('IDENTITY(tr.idStatut) as trStatut')
            ->addSelect('tr.json')
            ->addSelect('bl.modexp')
            ->addSelect('bl.dateProduction')
            ->addSelect('bl.nColis')
            ->addSelect('cmd.codearticle')
            ->addSelect('cmd.libelle')
            ->addSelect('cmd.quantite')
            ->addSelect('IDENTITY(cmd.idStatut) as cmdStatut')
            ->orderBy('tr.expRef', 'DESC')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findArticlesByFileArrayNonProd($files)
    {
        return $this
            ->createQueryBuilder('ligne')
            ->leftJoin('ligne.bls','bl')
            ->innerJoin('ligne.numligne', 'tr')
            ->innerJoin('ligne.ecommCmdeps', 'cmd')
            ->innerJoin('cmd.idStatut', 'st')
            ->where('ligne.idfile IN (:id)')
            ->andWhere('bl.dateProduction = :dat or bl.dateProduction is null')
            ->setParameter('dat', '0000-00-00 00:00:00')
            ->setParameter('id', $files)
            ->andWhere('tr.idStatut != :sat')
            ->setParameter('sat', 9)
            ->andWhere('tr.idStatut != :sat1')
            ->setParameter('sat1', 10)
            ->select('ligne.idfile')
            ->addSelect('sum(cmd.quantite) as quantite')
            ->addSelect('cmd.libelle')
            ->addSelect('cmd.codearticle')
            ->addSelect('cmd.flagart')
            ->addSelect('st.statut as trStatut')
            ->groupBy('cmd.libelle')
            ->orderBy('cmd.codearticle', 'ASC')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findArticlesPersoByFileArrayNonProd($files)
    {
        return $this
            ->createQueryBuilder('ligne')
            ->leftJoin('ligne.bls', 'bl')
            ->innerJoin('ligne.ecommCmdeps', 'cmd')
            ->innerJoin('ligne.numligne', 'tr')
            ->where('ligne.idfile IN (:id)')
            ->setParameter('id', $files)
            ->andWhere('cmd.flagart = 0')
            ->andwhere('bl.dateProduction = :dat or bl.dateProduction is null')
            ->setParameter('dat', '0000-00-00 00:00:00 ')
            ->andWhere('tr.idStatut != :sat')
            ->setParameter('sat', 9)
            ->andWhere('tr.idStatut != :sat1')
            ->setParameter('sat1', 10)
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

    public function findArticlesByOpeByDateNonProd($idop)
    {
        return $this
            ->createQueryBuilder('ligne')
            ->leftJoin('ligne.bls', 'bl')
            ->innerJoin('ligne.numligne','tr')
            ->innerJoin('ligne.ecommCmdeps ', 'cmd')
            ->innerJoin('tr.idfile', 'file')
            ->where('file.idappli = (:id)')
            ->setParameter('id', $idop)
            ->andWhere('bl.dateProduction = :dat or bl.dateProduction is null')
            ->setParameter('dat', '0000-00-00 00:00:00')
            ->andWhere('tr.idStatut != :d1')
            ->setParameter('d1', 10)
            ->andWhere('tr.idStatut != :d2')
            ->setParameter('d2', 9)
            ->select('sum(cmd.quantite) as quantite')
            ->addSelect('cmd.libelle')
            ->addSelect('cmd.flagart')
            ->addSelect('cmd.codearticle')
            ->groupBy('cmd.codearticle')
            ->getQuery()
            ->getArrayResult()

            ;
    }

    public function findArticlesByOpeNonProd($op)
    {
        return $this
            ->createQueryBuilder('ligne')
            ->leftJoin('ligne.bls', 'bl')
            ->innerJoin('ligne.numligne', 'tr')
            ->innerJoin('tr.idfile','file')
            ->innerJoin('file.idappli', 'app')
            ->innerJoin('ligne.ecommCmdeps ', 'cmd')
            ->where('file.idappli = (:id)')
            ->setParameter('id', $op)
            ->andwhere('bl.dateProduction = :dat or bl.dateProduction is null')
            ->setParameter('dat', '0000-00-00 00:00:00')
            ->andWhere('tr.idStatut != :d1')
            ->setParameter('d1', 10)
            ->andWhere('tr.idStatut != :d2')
            ->setParameter('d2', 9)
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

    public function findFileTotByOpe($idappli)
    {
        return $this
            ->createQueryBuilder('ligne')
            ->innerJoin('ligne.numligne', 'tr')
            ->innerJoin('tr.idfile', 'fl')
            ->innerJoin('fl.idappli', 'app')
            ->where('app.idappli = :id')
            ->setParameter('id', $idappli)
            ->select('count(ligne.numbl)')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function donneNbAllBlnonProd()
    {
        $dateM6 = date("Y-m-d", strtotime('-120 day'));
        return $this
            ->createQueryBuilder('ligne')
            ->leftJoin('ligne.bls', 'bl')
            ->leftJoin('ligne.numligne', 'tr')
            ->innerJoin('tr.idfile', 'file')
            ->innerJoin('file.idappli', 'app')
            ->innerJoin('app.idclient', 'cl')
            ->where('file.dateFile > (:dat)')
            ->setParameter('dat', $dateM6)
            ->andWhere('bl.dateProduction = :date or bl.dateProduction is null')
            ->setParameter('date', '0000-00-00 00:00:00')
            ->andWhere('tr.idStatut != :d1')
            ->setParameter('d1', 10)
            ->andWhere('tr.idStatut != :d2')
            ->setParameter('d2', 9)
            ->select('cl.nomclient')
            ->addSelect('app.appliname')
            ->addSelect('cl.idclient')
            ->addSelect('count(ligne.numbl)')
            ->addSelect('app.idappli')
            ->groupBy('app.appliname')
            ->orderBy('tr.dateInsert', 'DESC')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function donneNbAllArticlenonProd()
    {
        $dateM6 = date("Y-m-d", strtotime('-120 day'));
        return $this
            ->createQueryBuilder('ligne')
            ->leftJoin('ligne.bls', 'bl')
            ->leftJoin('ligne.numligne', 'tr')
            ->innerJoin('tr.idfile', 'file')
            ->innerJoin('file.idappli', 'app')
            ->innerJoin('app.idclient', 'cl')
            ->leftJoin('ligne.ecommCmdeps', 'cmd')
            ->where('file.dateFile > (:dat)')
            ->setParameter('dat', $dateM6)
            ->andWhere('bl.dateProduction = :date or bl.dateProduction is null')
            ->setParameter('date', '0000-00-00 00:00:00')
            ->andWhere('tr.idStatut != :d1')
            ->setParameter('d1', 10)
            ->andWhere('tr.idStatut != :d2')
            ->setParameter('d2', 9)
            ->select('app.idappli')
            ->addSelect('count(ligne.numbl)')
            ->andWhere('cmd.flagart = (:qt)')
            ->setParameter('qt', 0)
//            ->orderBy('tr.dateInsert', 'DESC')
            ->groupBy('app.appliname')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findAllBlByCmd($cmd, $idClient, $idope)
    {
        return $this
            ->createQueryBuilder('ligne')
            ->leftJoin('ligne.bls', 'bl')
            ->innerJoin('ligne.numligne', 'tr')
            ->leftJoin('ligne.ecommCmdeps', 'cmd')
            ->innerJoin('tr.idfile', 'file')
            ->innerJoin('tr.idStatut', 'statut')
            ->where('cmd.numcmde LIKE :cmd')
            ->orWhere('ligne.numbl LIKE :cmd3')
            ->orWhere('tr.refclient LIKE :cmd2')
            ->orWhere('UPPER(tr.destinataire) LIKE UPPER(:cmd5)')
            ->setParameter('cmd', '%'.$cmd.'%')
            ->setParameter('cmd3', '%'.$cmd.'%')
            ->setParameter('cmd2', '%'.$cmd.'%')
            ->setParameter('cmd5', '%'.$cmd.'%' )
            ->andWhere('ligne.idclient = :client')
            ->setParameter('client', $idClient)
            ->andWhere('file.idappli = :ope')
            ->setParameter('ope', $idope)
            ->select('tr.destinataire')
            ->addSelect('tr.destCp')
            ->addSelect('tr.typeTransport')
            ->addSelect('tr.numCmdeClient')
            ->addSelect('ligne.numbl')
            ->addSelect('tr.destRue')
            ->addSelect('tr.destAd2')
            ->addSelect('tr.destAd3')
            ->addSelect('tr.destAd4')
            ->addSelect('tr.destAd5')
            ->addSelect('tr.destAd6')
            ->addSelect('tr.destTel')
            ->addSelect('tr.expRef')
            ->addSelect('tr.refclient')
            ->addSelect('tr.destVille')
            ->addSelect('tr.dateDepot')
            ->addSelect('file.dateFile')
            ->addSelect('tr.dateCmde')
            ->addSelect('tr.instrLivrais1')
            ->addSelect('tr.instrLivrais2')
            ->addSelect('tr.destPays')
            ->addSelect('tr.montant')
            ->addSelect('file.nbpages')
            ->addSelect('file.filename')
            ->addSelect('ligne.poids as poidsReel')
            ->addSelect('sum(cmd.quantite) as quantite')
            ->addSelect('cmd.numTrack')
            ->addSelect('bl.nColis')
            ->addSelect('bl.modexp')
            ->addSelect('statut.statut as trStatut')
            ->addSelect('bl.dateProduction')

//            ->andWhere('cmd.flagart = (:qt)')
//            ->setParameter('qt', 0)
            ->orderBy('tr.dateInsert', 'DESC')
            ->groupBy('ligne.numbl')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findBlNumligne($numbl){

        return $this
        ->createQueryBuilder('ligne')
        ->where('ligne.numbl = :numbl')
        ->setParameter('numbl', $numbl)
        ->getQuery()
        ->getResult();
    }

}

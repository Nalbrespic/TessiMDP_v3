<?php

namespace TMD\CoreBundle\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;


class TransporteursTarifRepository extends EntityRepository
{
    public function findSame ($idclient, $transporteur, $tranche,$zone, $typeTransport){

        return $this
            ->createQueryBuilder('tt')
            ->innerJoin('tt.idclient', 'cl')
            ->innerJoin('tt.transporteur', 'tr')
            ->innerJoin('tt.typeTransport', 'type')
            ->innerJoin('tt.idTransportTranches', 'tranche')
            ->innerJoin('tt.zone', 'z')
            ->where('cl.idclient = (:idclient)')
            ->setParameter('idclient', $idclient)
            ->andWhere('tr.idtransporteur = (:transporteur)')
            ->setParameter('transporteur', $transporteur)
            ->andWhere('type.idtransport = (:typetransport)')
            ->setParameter('typetransport', $typeTransport)
            ->andWhere('tranche.idTransportTranches = (:tranche)')
            ->setParameter('tranche', $tranche)
            ->andWhere('z.idTransporteursZones = (:zone)')
            ->setParameter('zone', $zone)
            ->andWhere('tt.isValid = true')
            ->getQuery()
            ->getResult();
    }

    public function findAllValide(){
        $date = new DateTime();
        return $this
            ->createQueryBuilder('tt')
            ->innerJoin('tt.transporteur', 'tr')
            ->innerJoin('tt.typeTransport', 'type')
            ->innerJoin('tt.idTransportTranches', 'tranche')
            ->innerJoin('tt.zone', 'z')
            ->innerJoin('tt.idclient', 'cl')
            ->select('tt.tarif')
            ->addSelect('cl.idclient')
            ->addSelect('cl.nomclient')
            ->addSelect('tr.libelletransporteur')
            ->addSelect('type.codetransport')
            ->addSelect('tranche.poidsMax')
            ->addSelect('z.zone')
            ->where('tt.dateDebut <= (:date)')
            ->setParameter('date', $date)
            ->andWhere('tt.isValid = true')
            ->orderBy('tt.dateDebut', 'DESC')
            ->getQuery()
            ->getArrayResult();

    }

    public function findTarif($idclient,$typeTransport, $date, $idTranche ){

        return $this
            ->createQueryBuilder('tarif')
            ->innerJoin('tarif.idclient', 'cl')
            ->innerJoin('tarif.typeTransport', 'type')
            ->where('cl.idclient = (:idclient)')
            ->setParameter('idclient', $idclient)
            ->andWhere('type.codetransport IN (:type)')
            ->setParameter('type', $typeTransport)
//            ->andWhere('tarif.dateDebut < (:date)')
//            ->setParameter('date', $date)
            ->andWhere('tarif.isValid = true')
            ->andWhere('tarif.idTransportTranches = (:idtranche)')
            ->setParameter('idtranche', $idTranche)
            ->select('tarif.tarif')
            ->getQuery()
            ->getArrayResult();

    }
}
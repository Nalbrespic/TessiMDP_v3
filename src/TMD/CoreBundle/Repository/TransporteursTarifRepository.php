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
            ->where('cl.idclient IN (:idclient)')
            ->setParameter('idclient', $idclient)
            ->andWhere('tr.idtransporteur IN (:transporteur)')
            ->setParameter('transporteur', $transporteur)
            ->andWhere('type.idtransport IN (:typetransport)')
            ->setParameter('typetransport', $typeTransport)
            ->andWhere('tranche.idTransportTranches IN (:tranche)')
            ->setParameter('tranche', $tranche)
            ->andWhere('z.idTransporteursZones IN (:zone)')
            ->setParameter('zone', $zone)
            ->andWhere('tt.isValid IN (:valide)')
            ->setParameter('valide', 1)
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
            ->orderBy('tt.dateDebut', 'DESC')
            ->getQuery()
            ->getArrayResult();

    }
}
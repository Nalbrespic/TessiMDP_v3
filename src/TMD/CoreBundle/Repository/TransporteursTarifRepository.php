<?php

namespace TMD\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;


class TransporteursTarifRepository extends EntityRepository
{
    public function findSame($transporteur, $tranche,$zone, $typeTransport){

        return $this
            ->createQueryBuilder('tt')
            ->innerJoin('tt.transporteur', 'tr')
            ->innerJoin('tt.typeTransport', 'type')
            ->innerJoin('tt.idTransportTranches', 'tranche')
            ->innerJoin('tt.zone', 'z')
            ->where('tr.idtransporteur IN (:transporteur)')
            ->setParameter('transporteur', $transporteur)
            ->andWhere('type.idtransport IN (:typetransport)')
            ->setParameter('typetransport', $typeTransport)
            ->andWhere('tranche.idTransportTranches IN (:tranche)')
            ->setParameter('tranche', $tranche)
            ->andWhere('z.idTransporteursZones IN (:zone)')
            ->setParameter('zone', $zone)
            ->andWhere('tt.valide IN (:valide)')
            ->setParameter('valide', 1)
            ->getQuery()
            ->getResult();
    }

    public function findAllValide(){
        return $this
            ->createQueryBuilder('tt')
            ->innerJoin('tt.transporteur', 'tr')
            ->innerJoin('tt.typeTransport', 'type')
            ->innerJoin('tt.idTransportTranches', 'tranche')
            ->innerJoin('tt.zone', 'z')
            ->select('tt.tarif')
            ->addSelect('tr.libelletransporteur')
            ->addSelect('type.codetransport')
            ->addSelect('tranche.poidsMax')
            ->addSelect('z.zone')
            ->where('tt.valide IN (:valide)')
            ->setParameter('valide', 1)
            ->orderBy('tr.libelletransporteur', 'ASC')
             ->getQuery()
            ->getArrayResult();

    }
}
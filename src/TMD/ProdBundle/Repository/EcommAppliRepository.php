<?php

namespace TMD\ProdBundle\Repository;

use Doctrine\ORM\EntityRepository;


class EcommAppliRepository extends EntityRepository
{
    public function findbyId($id){
        return $this
            ->createQueryBuilder('a')
            ->innerJoin('a.idclient', 'c')
            ->leftJoin('a.idclientEmmetteur', 'e')
            ->where('a.idappli = :id')
            ->setParameter('id', $id)
            ->select('a.appliname')
            ->addSelect('a.codeappli')
            ->addSelect('a.dateappli')
            ->addSelect('c.nomclient as client')
            ->addSelect('e.nomclient as emetteur' )
            ->addSelect('e.idclient as idEmetteur')
            ->getQuery()
            ->getArrayResult()

            ;
    }
    public function findAppliByClient($idClient)
    {
        return $this
            ->createQueryBuilder('a')
            ->where('a.idclient = :id')
            ->setParameter('id', $idClient)
            ->orderBy('a.idappli', 'DESC')
            ->getQuery()
            ->getResult()

            ;
    }

    public function findClientWithOperation()
    {
        return $this
            ->createQueryBuilder('a')
            ->innerJoin('a.idclient' ,'cl')
            ->select('DISTINCT cl.nomclient ')
            ->orderBy('cl.nomclient' , 'ASC')
            ->addSelect('cl.idclient')

            ->getQuery()
            ->getResult()

            ;
    }
    public function findAllOpe(){

        return $this
            ->createQueryBuilder('app')
            ->innerJoin('app.idclient', 'cl')
            ->leftJoin('app.idclientEmmetteur', 'E')
            ->select('app.dateappli')
            ->addSelect('app.idappli')
            ->addSelect('app.appliname')
            ->addSelect('cl.nomclient as client')
            ->addSelect('E.nomclient as emetteur')
            ->orderBy('app.dateappli', 'DESC')
            ->getQuery()
            ->getArrayResult()
            ;
    }




}

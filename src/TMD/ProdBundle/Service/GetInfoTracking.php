<?php


namespace TMD\ProdBundle\Service;



use Doctrine\ORM\EntityManager;

class GetInfoTracking
{
    protected $em;

    /**
     * GetInfoTracking constructor.
     * @param $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    public function getStatutWithId($id){

        $statut = $this->em->getRepository('TMDProdBundle:EcommStatut')->findOneBy(array('idStatut'  => $id));
        return $statut;
    }
}
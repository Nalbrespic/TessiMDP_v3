<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new TMD\CoreBundle\TMDCoreBundle(),
            new TMD\ZplBundle\TMDZplBundle(),
            new TMD\ProdBundle\TMDProdBundle(),
            new TMD\UserBundle\TMDUserBundle(),
            new TMD\ConfigBundle\TMDConfigBundle(),
            new TMD\ColissimoBundle\TMDColissimoBundle(),
            new TMD\ColisPriveBundle\TMDColisPriveBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new TMD\IcomedBundle\TMDIcomedBundle(),
            new TMD\UtilsBundle\TMDUtilsBundle(),
            new TMD\DpdBundle\TMDDpdBundle(),
            new TMD\AppliBundle\TMDAppliBundle(),
            new TMD\CoriolisBundle\TMDCoriolisBundle(),
//            new Tms\Bundle\LogisticBundle\TmsLogisticBundle(),
            new Tms\Bundle\RestBundle\TmsRestBundle(),
            new Tms\Bundle\RestClientBundle\TmsRestClientBundle(),
            new TMD\StatBundle\TMDStatBundle(),
            new CMEN\GoogleChartsBundle\CMENGoogleChartsBundle(),
            new TMD\MinosBundle\TMDMinosBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Symfony\Bundle\DebugBundle\DebugBundle(),
            new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle(),

        );

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {

            $bundles[] = new \Symfony\Bundle\MakerBundle\MakerBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}

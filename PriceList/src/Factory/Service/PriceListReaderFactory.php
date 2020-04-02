<?php


namespace PriceList\Factory\Service;


use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use PriceList\Entity\PriceListEntity;
use PriceList\Repository\PriceListRepository;
use PriceList\Service\PriceListReader;
use Zend\ServiceManager\Factory\FactoryInterface;

class PriceListReaderFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var $em EntityManager */
        $em = $container->get(EntityManager::class);

        /**
         * @var $repo PriceListRepository
         */
        $repo = $em->getRepository(PriceListEntity::class);

        return new PriceListReader($em, $repo);
    }
}
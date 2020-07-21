<?php


namespace PriceList\Factory\Service;


use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use PriceList\Entity\PriceListProductEntity;
use PriceList\Repository\PriceListProductRepository;
use PriceList\Service\PriceListProductsReader;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class PriceListProductsReaderFactory
 * @package PriceList\Factory\Service
 */
class PriceListProductsReaderFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var $em EntityManager */
        $em = $container->get(EntityManager::class);

        /**
         * @var $repo PriceListProductRepository
         */
        $repo = $em->getRepository(PriceListProductEntity::class);

        return new PriceListProductsReader($em, $repo);
    }
}
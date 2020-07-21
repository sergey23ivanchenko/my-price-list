<?php


namespace PriceList\Factory\Service;

use Doctrine\ORM\EntityManager;
use PriceList\Entity\PriceListProductEntity;
use PriceList\Repository\PriceListProductRepository;
use PriceList\Service\PriceListProductsService;
use PriceList\Service\PriceListTransfer;
use Product\Products\Entity\ProductEntity;
use Product\Products\Repository\ProductRepository;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

/**
 * Class PriceListProductsServiceFactory
 * @package PriceList\Factory\Service
 */
class PriceListProductsServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var $em EntityManager */
        $em = $container->get(EntityManager::class);

        /**
         * @var $repo PriceListProductRepository
         */
        $repo = $em->getRepository(PriceListProductEntity::class);

        /**
         * @var $repository ProductRepository
         */
        $repository = $em->getRepository(ProductEntity::class);

        /**
         * @var $transfer PriceListTransfer
         */
        $transfer = $container->get(PriceListTransfer::class);

        return new PriceListProductsService($em, $repo, $repository, $transfer);
    }
}

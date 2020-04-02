<?php

namespace PriceList\Factory\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use PriceList\Entity\PriceListEntity;
use PriceList\Repository\PriceListRepository;
use PriceList\Service\PriceListEventManager;
use PriceList\Service\PriceListGoodsService;
use PriceList\Service\PriceListService;
use Runple\Modules\File\Entity\ImageEntity;
use Runple\Modules\File\Repository\ImageRepository;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class PriceListServiceFactory
 * @package PriceList\Factory\Service
 */
class PriceListServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var $em EntityManager */
        $em = $container->get(EntityManager::class);

        /**
         * @var $repo PriceListRepository
         */
        $repo = $em->getRepository(PriceListEntity::class);

        /**
         * @var $imgRepo ImageRepository
         */
        $imgRepo = $em->getRepository(ImageEntity::class);

        /**
         * @var $service PriceListGoodsService
         */
        $service = $container->get(PriceListGoodsService::class);

        /**
         * @var $eventManager PriceListEventManager
         */
        $eventManager = $container->get(PriceListEventManager::class);

        return new PriceListService($em, $repo, $imgRepo, $service, $eventManager);
    }
}
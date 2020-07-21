<?php


namespace PriceList\Factory\Service;


use CatalogManagement\Entity\CatalogEntity;
use CatalogManagement\Repository\CatalogRepository;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use PriceList\Service\PriceListAssignment;
use PriceList\Service\PriceListTransfer;
use Zend\ServiceManager\Factory\FactoryInterface;

class PriceListAssignmentFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var $em EntityManager */
        $em = $container->get(EntityManager::class);

        /**
         * @var $cRepository CatalogRepository
         */
        $cRepository = $em->getRepository(CatalogEntity::class);

        /**
         * @var $transfer PriceListTransfer
         */
        $transfer = $container->get(PriceListTransfer::class);

        return new PriceListAssignment($em, $cRepository, $transfer);
    }
}
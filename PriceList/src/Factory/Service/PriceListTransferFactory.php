<?php


namespace PriceList\Factory\Service;


use CatalogManagement\Entity\CatalogEntity;
use CatalogManagement\Repository\CatalogRepository;
use Common\Tool\CurrencyConverter;
use Company\Service\LocalizationSettingsService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use PriceList\Service\PriceListTransfer;

class PriceListTransferFactory
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
         * @var $converter CurrencyConverter
         */
        $converter = $container->get(CurrencyConverter::class);

        /**
         * @var $localizations LocalizationSettingsService
         */
        $localizations = $container->get(LocalizationSettingsService::class);

        return new PriceListTransfer($em, $cRepository, $converter, $localizations);
    }
}
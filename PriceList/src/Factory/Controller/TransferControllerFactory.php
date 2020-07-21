<?php


namespace PriceList\Factory\Controller;


use CatalogManagement\Service\CatalogReader;
use Interop\Container\ContainerInterface;
use PriceList\Controller\Api\TransferController;
use PriceList\Service\PriceListReader;
use PriceList\Service\PriceListTransfer;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class TransferControllerFactory
 * @package PriceList\Factory\Controller
 */
class TransferControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /**
         * @var $service PriceListTransfer
         */
        $service = $container->get(PriceListTransfer::class);

        /**
         * @var $reader PriceListReader
         */
        $reader = $container->get(PriceListReader::class);

        /**
         * @var $catalogReader CatalogReader
         */
        $catalogReader = $container->get(CatalogReader::class);

        return new TransferController($service, $reader, $catalogReader);
    }
}
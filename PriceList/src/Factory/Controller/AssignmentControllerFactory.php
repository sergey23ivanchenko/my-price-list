<?php


namespace PriceList\Factory\Controller;


use CatalogManagement\Service\CatalogReader;
use PriceList\Controller\Api\AssignmentController;
use PriceList\Service\PriceListAssignment;
use PriceList\Service\PriceListReader;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class AssignmentControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /**
         * @var $service PriceListAssignment
         */
        $service = $container->get(PriceListAssignment::class);

        /**
         * @var $reader PriceListReader
         */
        $reader = $container->get(PriceListReader::class);

        /**
         * @var $catalogReader CatalogReader
         */
        $catalogReader = $container->get(CatalogReader::class);

        return new AssignmentController($service, $reader, $catalogReader);
    }
}
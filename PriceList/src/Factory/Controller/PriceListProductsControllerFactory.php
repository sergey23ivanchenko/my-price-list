<?php


namespace PriceList\Factory\Controller;


use Interop\Container\ContainerInterface;
use PriceList\Controller\Api\PriceListProductsController;
use PriceList\Service\PriceListProductsReader;
use PriceList\Service\PriceListProductsService;
use PriceList\Service\PriceListReader;
use PriceList\Service\PriceListViewTransformer;
use Runple\Modules\Product\Family\Service\FamilyReader;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class PriceListProductsControllerFactory
 * @package PriceList\Factory\Controller
 */
class PriceListProductsControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var PriceListReader $priceListReader */
        $priceListReader = $container->get(PriceListReader::class);

        /** @var PriceListProductsReader $reader */
        $reader = $container->get(PriceListProductsReader::class);

        /** @var FamilyReader $familyReader */
        $familyReader = $container->get(FamilyReader::class);

        /** @var PriceListProductsService $service */
        $service = $container->get(PriceListProductsService::class);

        /** @var PriceListViewTransformer $transformer */
        $transformer = $container->get(PriceListViewTransformer::class);

        return new  PriceListProductsController($priceListReader, $reader, $familyReader, $service, $transformer);
    }
}
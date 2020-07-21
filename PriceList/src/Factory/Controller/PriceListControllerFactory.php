<?php


namespace PriceList\Factory\Controller;


use Interop\Container\ContainerInterface;
use PriceList\Controller\Api\PriceListController;
use PriceList\Form\EditPriceListForm;
use PriceList\Service\PriceListReader;
use PriceList\Service\PriceListService;
use PriceList\Service\PriceListViewTransformer;
use Runple\Devtools\Pagination\Form\PaginationForm;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class PriceListControllerFactory
 * @package PriceList\Factory\Controller
 */
class PriceListControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|PriceListController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $elementManager = $container->get('FormElementManager');

        /**
         * @var $paginator PaginationForm
         */
        $paginator = $elementManager->get(PaginationForm::class);

        /**
         * @var $service PriceListService
         */
        $service = $container->get(PriceListService::class);

        /**
         * @var $reader PriceListReader
         */
        $reader = $container->get(PriceListReader::class);

        /**
         * @var $transformer PriceListViewTransformer
         */
        $transformer = $container->get(PriceListViewTransformer::class);

        return new PriceListController($paginator, $service, $reader, $transformer);
    }
}
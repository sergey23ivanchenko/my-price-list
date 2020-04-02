<?php


namespace PriceList\Factory\Controller;


use Interop\Container\ContainerInterface;
use PriceList\Controller\Api\PriceListController;
use PriceList\Form\PriceListForm;
use PriceList\Service\PriceListReader;
use PriceList\Service\PriceListService;
use Runple\Devtools\Pagination\Form\PaginationForm;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class PriceListControllerFactory
 * @package PriceList\Factory\Controller
 */
class PriceListControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $elementManager = $container->get('FormElementManager');

        /**
         * @var $paginator PaginationForm
         */
        $paginator = $elementManager->get(PaginationForm::class);

        /**
         * @var $form PriceListForm
         */
        $form = $elementManager->get(PriceListForm::class);

        /**
         * @var $service PriceListService
         */
        $service = $container->get(PriceListService::class);

        /**
         * @var $reader PriceListReader
         */
        $reader = $container->get(PriceListReader::class);
        return new PriceListController($paginator, $form, $service, $reader);
    }
}
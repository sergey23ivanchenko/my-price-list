<?php

namespace PriceList;

use PriceList\Controller\Api\AssignmentController;
use PriceList\Controller\Api\PriceListController;
use PriceList\Controller\Api\PriceListProductsController;
use PriceList\Controller\Api\TransferController;
use PriceList\Factory\Controller\AssignmentControllerFactory;
use PriceList\Factory\Controller\PriceListControllerFactory;
use PriceList\Factory\Controller\PriceListProductsControllerFactory;
use PriceList\Factory\Controller\TransferControllerFactory;
use PriceList\Factory\Service\PriceListAssignmentFactory;
use PriceList\Factory\Service\PriceListProductsReaderFactory;
use PriceList\Factory\Service\PriceListProductsServiceFactory;
use PriceList\Factory\Service\PriceListReaderFactory;
use PriceList\Factory\Service\PriceListServiceFactory;
use PriceList\Factory\Service\PriceListTransferFactory;
use PriceList\Service\PriceListAssignment;
use PriceList\Service\PriceListProductsReader;
use PriceList\Service\PriceListProductsService;
use PriceList\Service\PriceListReader;
use PriceList\Service\PriceListService;
use PriceList\Service\PriceListTransfer;
use PriceList\Service\PriceListViewTransformer;
use Zend\ServiceManager\Factory\InvokableFactory;

return [

    'controllers' => [
        'factories' => [
            PriceListController::class=>PriceListControllerFactory::class,
            TransferController::class=>TransferControllerFactory::class,
            AssignmentController::class=>AssignmentControllerFactory::class,
            PriceListProductsController::class=>PriceListProductsControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            PriceListService::class=>PriceListServiceFactory::class,
            PriceListProductsService::class=>PriceListProductsServiceFactory::class,
            PriceListAssignment::class=>PriceListAssignmentFactory::class,
            PriceListReader::class=>PriceListReaderFactory::class,
            PriceListTransfer::class=>PriceListTransferFactory::class,
            PriceListProductsReader::class=>PriceListProductsReaderFactory::class,
            PriceListViewTransformer::class=>InvokableFactory::class,
        ]
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy'
        ],
    ],
];
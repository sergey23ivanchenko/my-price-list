<?php

namespace PriceList;

use PriceList\Controller\Api\AssignmentController;
use PriceList\Controller\Api\PriceListController;
use PriceList\Controller\Api\TransferController;
use PriceList\Factory\Controller\AssignmentControllerFactory;
use PriceList\Factory\Controller\PriceListControllerFactory;
use PriceList\Factory\Controller\TransferControllerFactory;
use PriceList\Factory\Service\PriceListAssignmentFactory;
use PriceList\Factory\Service\PriceListGoodsServiceFactory;
use PriceList\Factory\Service\PriceListReaderFactory;
use PriceList\Factory\Service\PriceListServiceFactory;
use PriceList\Factory\Service\PriceListTransferFactory;
use PriceList\Service\PriceListAssignment;
use PriceList\Service\PriceListGoodsService;
use PriceList\Service\PriceListReader;
use PriceList\Service\PriceListService;
use PriceList\Service\PriceListTransfer;

return [

    'controllers' => [
        'factories' => [
            PriceListController::class=>PriceListControllerFactory::class,
            TransferController::class=>TransferControllerFactory::class,
            AssignmentController::class=>AssignmentControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            PriceListService::class=>PriceListServiceFactory::class,
            PriceListGoodsService::class=>PriceListGoodsServiceFactory::class,
            PriceListAssignment::class=>PriceListAssignmentFactory::class,
            PriceListReader::class=>PriceListReaderFactory::class,
            PriceListTransfer::class=>PriceListTransferFactory::class,
        ]
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy'
        ],
    ],
];
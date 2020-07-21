<?php

namespace PriceList;

use PriceList\Controller\Api\AssignmentController;
use PriceList\Controller\Api\PriceListController;
use PriceList\Controller\Api\PriceListProductsController;
use PriceList\Controller\Api\TransferController;
use Runple\Devtools\Zend\Router\RouteBuilder;
use Zend\Http\Request;
use Zend\Router\Http\Segment;

$routes = [
    Request::METHOD_POST => [
        'add_price_lists' => [
            'type' => Segment::class,
            'options' => [
                'route' => '/price-lists',
                'defaults' => [
                    'controller' => PriceListController::class,
                    'action' => 'createPriceList',
                ],
            ],
        ],
        'transfer_price_lists_to_catalog' => [
            'type' => Segment::class,
            'options' => [
                'route' => '/price-lists/:id/price-transfer/catalogs',
                'defaults' => [
                    'controller' => TransferController::class,
                    'action' => 'transferPriceToCatalogs',
                ],
                'constraints' => [
                    'id' => '\d+',
                ],
            ],
        ],
    ],
    Request::METHOD_PATCH => [
        'change_price_lists_status' => [
            'type' => Segment::class,
            'options' => [
                'route' => '/price-lists/status',
                'defaults' => [
                    'controller' => PriceListController::class,
                    'action' => 'changeStatus',
                ],
            ],
        ],
        'update_price_list_products' => [
            'type' => Segment::class,
            'options' => [
                'route' => '/price-lists/:price_list_id/products/:id',
                'defaults' => [
                    'controller' => PriceListProductsController::class,
                    'action' => 'updatePriceListProduct',
                ],
                'constraints' => [
                    'price_list_id' => '\d+',
                    'id' => '\d+',
                ],
            ],
        ],
    ],
    Request::METHOD_GET => [
        'get_price_list' => [
            'type' => Segment::class,
            'options' => [
                'route' => '/price-lists/:id',
                'defaults' => [
                    'controller' => PriceListController::class,
                    'action' => 'getPriceList',
                ],
                'constraints' => [
                    'id' => '\d+',
                ],
            ],
        ],
        'get_all_price_lists' => [
            'type' => Segment::class,
            'options' => [
                'route' => '/price-lists',
                'defaults' => [
                    'controller' => PriceListController::class,
                    'action' => 'allPriceLists',
                ],
            ],
        ],
        'get_price_list_products_list' => [
            'type' => Segment::class,
            'options' => [
                'route' => '/price-lists/:id/products/family/:family_id',
                'defaults' => [
                    'controller' => PriceListProductsController::class,
                    'action' => 'getProductsList',
                ],
                'constraints' => [
                    'id' => '\d+',
                    'family_id' => '\d+',
                ],
            ],
        ],
        'get_counters' => [
            'type' => Segment::class,
            'options' => [
                'route' => '/price-lists/counters',
                'defaults' => [
                    'controller' => PriceListController::class,
                    'action' => 'getCounters',
                ],
            ],
        ],
    ],
    Request::METHOD_PUT => [
        'edit_price_list' => [
            'type' => Segment::class,
            'options' => [
                'route' => '/price-lists/:id',
                'defaults' => [
                    'controller' => PriceListController::class,
                    'action' => 'editPriceList',
                ],
                'constraints' => [
                    'id' => '\d+',
                ],
            ],
        ],
        'assign_catalogs' => [
            'type' => Segment::class,
            'options' => [
                'route' => '/price-lists/:id/catalogs',
                'defaults' => [
                    'controller' => AssignmentController::class,
                    'action' => 'assignCatalogs',
                ],
            ],
            'constraints' => [
                'id' => '\d+',
            ],
        ],
    ],
    Request::METHOD_DELETE => [
        'remove_price_lists' => [
            'type' => Segment::class,
            'options' => [
                'route' => '/price-lists',
                'defaults' => [
                    'controller' => PriceListController::class,
                    'action' => 'deletePriceLists',
                ],
            ],
        ],
    ]
];
return RouteBuilder::buildApi('price_list', $routes);



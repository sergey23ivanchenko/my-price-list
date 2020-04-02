<?php

use PriceList\Factory\Listener\PriceListListenerFactory;
use PriceList\Listener\PriceListListener;
use PriceList\Service\PriceListEventManager;
use Runple\Devtools\Zend\EventManager\Enum\ConfigKeys;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    ConfigKeys::EVENT_MANAGER => [
        'product' => [
            ConfigKeys::MANAGER => PriceListEventManager::class,
            ConfigKeys::LISTENERS => [
                PriceListListener::class,
            ]
        ]
    ],
    'service_manager' => [
        'factories' => [
            PriceListEventManager::class => InvokableFactory::class,
            PriceListListener::class => PriceListListenerFactory::class
        ]
    ]
];
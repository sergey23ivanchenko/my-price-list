<?php

namespace PriceList;

use PriceList\Form\PriceListAssignFieldset;
use PriceList\Form\EditPriceListForm;
use PriceList\Form\PriceListProductsFieldset;
use PriceList\Form\PriceListImageFieldset;
use PriceList\Form\PriceListProductForm;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'form_elements' => [
        'factories' => [
            EditPriceListForm::class => InvokableFactory::class,
            PriceListProductsFieldset::class => InvokableFactory::class,
            PriceListImageFieldset::class => InvokableFactory::class,
            PriceListAssignFieldset::class => InvokableFactory::class,
            PriceListProductForm::class => InvokableFactory::class,
        ],
    ]
];

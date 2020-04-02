<?php

namespace PriceList;

use PriceList\Factory\Form\PriceListFormFactory;
use PriceList\Form\PriceListAssignFieldset;
use PriceList\Form\PriceListForm;
use PriceList\Form\PriceListGoodsFieldset;
use PriceList\Form\PriceListImageFieldset;
use PriceList\Form\PriceListProductFieldset;
use PriceList\InputFilter\CreatePriceListInputFilter;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'form_elements' => [
        'factories' => [
            PriceListForm::class => PriceListFormFactory::class,
            PriceListGoodsFieldset::class => InvokableFactory::class,
            PriceListImageFieldset::class => InvokableFactory::class,
            PriceListAssignFieldset::class => InvokableFactory::class,
            PriceListProductFieldset::class => InvokableFactory::class,
        ],
    ],
    'input_filters' => [
        'factories' => [
            CreatePriceListInputFilter::class => InvokableFactory::class,
        ]
    ]
];

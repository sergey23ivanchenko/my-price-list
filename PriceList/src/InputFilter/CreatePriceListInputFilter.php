<?php


namespace PriceList\InputFilter;


use CatalogManagement\Enum\CatalogCurrencies;
use CatalogManagement\Model\CreateCatalogModel;
use Common\Validator\EnumValidator;
use PriceList\Model\PriceListModel;
use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;

/**
 * Class CreatePriceListInputFilter
 * @package PriceList\InputFilter
 */
class CreatePriceListInputFilter extends InputFilter
{
    public function init()
    {
        parent::init(); // called due to inheritance

        $this->add([
            'name' => PriceListModel::F_TITLE,
            'required' => true,
            'filters' => [
            ],
            'validators' => [
                new StringLength(['min' => 1, 'max' => 256])
            ],
        ]);
        $this->add([
            'name' => PriceListModel::F_DESCRIPTION,
            'required' => false,
            'filters' => [
            ],
            'validators' => [
                new StringLength(['max' => 3000])
            ],
        ]);
        $this->add([
            'name' => PriceListModel::F_IMAGE,
            'required' => false,
            'filters' => [
            ],
            'validators' => [
            ],
        ]);
    }
}
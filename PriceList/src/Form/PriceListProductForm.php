<?php


namespace PriceList\Form;

use PriceList\Model\PriceListProductModel;
use Zend\Form\Form;
use Zend\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilterAwareTrait;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Validator\Digits;

/**
 * Class PriceListProductForm
 * @package PriceList\Form
 */
class PriceListProductForm extends Form implements InputFilterProviderInterface
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setHydrator(new ClassMethods());
        $model = new PriceListProductModel();
        $this->setObject($model);
        $this->add(['name' => $model::F_NET_PRICE]);
        $this->add(['name' => $model::F_GROSS_PRICE]);
        $this->add(['name' => $model::F_VAT]);
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification(): array
    {
        return [
            PriceListProductModel::F_NET_PRICE => [
                'required' => false,
                'filters' => [
                ],
                'validators' => [
                    new Digits()
                ],
            ],
            PriceListProductModel::F_GROSS_PRICE => [
                'required' => false,
                'filters' => [
                ],
                'validators' => [
                    new Digits()
                ],
            ],
            PriceListProductModel::F_VAT => [
                'required' => false,
                'filters' => [
                ],
                'validators' => [
                    new Digits()
                ],
            ],
        ];
    }
}
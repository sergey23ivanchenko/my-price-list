<?php


namespace PriceList\Form;


use PriceList\Model\PriceListProductModel;
use PriceList\Model\PriceListProductsModel;
use Zend\Form\Fieldset;
use Zend\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilterAwareTrait;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Validator\Digits;

/**
 * Class PriceListProductsFieldset
 * @package PriceList\Form
 */
class PriceListProductsFieldset extends Fieldset implements InputFilterProviderInterface
{
    use InputFilterAwareTrait;
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setHydrator(new ClassMethods());
        $model = new PriceListProductsModel();
        $this->setObject($model);
        $this->add(['name' =>  $model::F_PRODUCT, 'type' => PriceListProductForm::class]);
        $this->add(['name' => $model::F_PRICE]);
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            PriceListProductsModel::F_PRODUCT => [
                'required' => true,
                'filters' => [
                ],
                'validators' => [
                ],
            ],
            PriceListProductsModel::F_PRICE => [
                'required' => true,
                'filters' => [
                ],
                'validators' => [
                    new Digits()
                ],
            ],
        ];
    }
}
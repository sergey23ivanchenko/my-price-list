<?php


namespace PriceList\Form;


use Common\Validator\EnumValidator;
use PriceList\Model\PriceListModel;
use Product\Enum\ProductTypes;
use Zend\Form\Form;
use Zend\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * Class CreatePriceListForm
 * @package PriceList\Form
 */
class CreatePriceListForm extends Form implements InputFilterProviderInterface
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setHydrator(new ClassMethods());
        $model = new PriceListModel();
        $this->setObject($model);
        $this->add(['name' => $model::F_TITLE]);
        $this->add(['name' => $model::F_DESCRIPTION]);
        $this->add(['name' => $model::F_PRODUCT_TYPE]);
        $this->add(['name' =>  $model::F_IMAGE, 'type' => PriceListImageFieldset::class]);
    }

    /**
     * @inheritdoc
     */
    public function getInputFilterSpecification(): array
    {

        return [
            PriceListModel::F_TITLE => [
                'required' => true,
                'filters' => [
                ],
                'validators' => [
                ],
            ],
            PriceListModel::F_DESCRIPTION => [
                'required' => true,
                'filters' => [
                ],
                'validators' => [
                ],
            ],
            PriceListModel::F_PRODUCT_TYPE => [
                'required' => true,
                'filters' => [
                ],
                'validators' => [
                    new EnumValidator(['enum' => new ProductTypes()])
                ],
            ],
        ];
    }
}
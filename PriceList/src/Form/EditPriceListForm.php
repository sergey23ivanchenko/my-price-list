<?php


namespace PriceList\Form;


use PriceList\Model\PriceListModel;
use Zend\Form\Form;
use Zend\Hydrator\ClassMethods;
use Zend\Form\Element\Collection;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * Class PriceListForm
 * @package PriceList\Form
 */
class EditPriceListForm extends Form implements InputFilterProviderInterface
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
        ];
    }
}
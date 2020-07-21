<?php


namespace PriceList\Form;


use File\Model\ImageModel;
use Zend\Form\Fieldset;
use Zend\Validator\Digits;
use Zend\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilterAwareTrait;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * Class PriceListImageFieldset
 * @package PriceList\Form
 */
class PriceListImageFieldset extends Fieldset implements InputFilterProviderInterface
{
    use InputFilterAwareTrait;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setHydrator(new ClassMethods());
        $model = new ImageModel();
        $this->setObject($model);
        $this->add(['name' => $model::F_ID]);
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            ImageModel::F_ID => [
                'required' => false,
                'filters' => [
                ],
                'validators' => [
                    new Digits()
                ],
            ]
        ];
    }
}
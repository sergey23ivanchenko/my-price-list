<?php


namespace PriceList\Form;

use PriceList\Model\PriceListAssignModel;
use Zend\Form\Fieldset;
use Zend\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilterAwareTrait;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Validator\Digits;

/**
 * Class PriceListCatalogsFieldset
 * @package PriceList\Form
 */
class PriceListAssignFieldset extends Fieldset implements InputFilterProviderInterface
{
    use InputFilterAwareTrait;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setHydrator(new ClassMethods());
        $model = new PriceListAssignModel();
        $this->setObject($model);
        $this->add(['name' => $model::F_ID]);
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            PriceListAssignModel::F_ID => [
                'required' => true,
                'filters' => [
                ],
                'validators' => [
                    new Digits()
                ],
            ]
        ];
    }
}
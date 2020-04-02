<?php


namespace PriceList\Form;


use PriceList\Model\PriceListModel;
use Zend\Form\Form;
use Zend\Hydrator\ClassMethods;
use Zend\Form\Element\Collection;

class PriceListForm extends Form
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
        $this->add([
            'name' => $model::F_GOODS,
            'type' => Collection::class,
            'options' => [
                'count' => 0,
                'allow_add' => true,
                'allow_remove' => true,
                'target_element' => [
                    'type' => PriceListGoodsFieldset::class,
                ],
            ],
        ]);
    }
}
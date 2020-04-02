<?php


namespace PriceList\Factory\Form;

use Interop\Container\ContainerInterface;
use PriceList\Form\PriceListForm;
use PriceList\InputFilter\CreatePriceListInputFilter;
use PriceList\Model\PriceListModel;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class PriceListFormFactory
 * @package PriceList\Factory\Form
 */
class PriceListFormFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return PriceListForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) : PriceListForm
    {
        $form = new PriceListForm();
        $form->setInputFilter($container->get('InputFilterManager')->get(CreatePriceListInputFilter::class));
        $form->setObject(new PriceListModel());
        return $form;
    }
}
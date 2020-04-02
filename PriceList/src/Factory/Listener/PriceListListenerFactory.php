<?php


namespace PriceList\Factory\Listener;


use Common\RIDGenerator\RIDGenerator;
use Interop\Container\ContainerInterface;
use PriceList\Listener\PriceListListener;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class PriceListListenerFactory
 * @package PriceList\Factory\Listener
 */
class PriceListListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /**
         * $ruidGenerator RIDGenerator
         */
        $ruidGenerator = $container->get(RIDGenerator::class);

        return new PriceListListener($ruidGenerator);
    }
}
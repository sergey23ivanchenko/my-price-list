<?php


namespace PriceList\Listener;


use Common\RIDGenerator\RIDGenerator;
use PriceList\Enum\PriceListEvents;
use PriceList\Event\PriceListEvent;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;

/**
 * Class PriceListListener
 * @package PriceList\Listener
 */
class PriceListListener extends AbstractListenerAggregate
{
    /**
     * @var RIDGenerator
     */
    private $ruidGenerator;

    /**
     * PriceListListener constructor.
     * @param RIDGenerator $ruidGenerator
     */
    public function __construct(RIDGenerator $ruidGenerator)
    {
        $this->ruidGenerator = $ruidGenerator;
    }

    /**
     * @param EventManagerInterface $events
     * @param int $priority
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(PriceListEvents::BEFORE_CREATE_OPL_PRICE_LIST, [$this, 'onBeforeCreateOPLPriceList'], 0);
    }

    /**
     * @param PriceListEvent $event
     * @throws \Exception
     */
    public function onBeforeCreateOPLPriceList(PriceListEvent $event)
    {
        $entity = $event->getPriceList();
        $this->ruidGenerator->generate($entity, 'OPL');
    }
}
<?php


namespace PriceList\Event;


use PriceList\Entity\PriceListEntity;
use Zend\EventManager\Event;

/**
 * Class PriceListEvent
 * @package PriceList\Event
 */
class PriceListEvent extends Event
{
    /**
     * @var PriceListEntity
     */
    private $priceList;

    public function __construct($name = null, $target = null, ?PriceListEntity $params = null)
    {
        $this->priceList = $params;
        parent::__construct($name, $target, $params);
    }

    /**
     * @return PriceListEntity
     */
    public function getPriceList(): PriceListEntity
    {
        return $this->priceList;
    }
}
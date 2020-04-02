<?php


namespace PriceList\Enum;


use Runple\Devtools\Enum\EnumInterface;
use Runple\Devtools\Enum\EnumTrait;

/**
 * Class PriceListEvents
 * @package PriceList\Enum
 */
class PriceListEvents implements EnumInterface
{
    use EnumTrait;
    const BEFORE_CREATE_OPL_PRICE_LIST = 'before_create_opl_price_list';

    public static function getLabels(): array
    {
        return [
            self::BEFORE_CREATE_OPL_PRICE_LIST => 'Before create opl price list',
        ];
    }
}
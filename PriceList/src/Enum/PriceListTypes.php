<?php


namespace PriceList\Enum;


use Runple\Devtools\Enum\EnumInterface;
use Runple\Devtools\Enum\EnumTrait;

class PriceListTypes implements EnumInterface
{
    use EnumTrait;
    const OPL = 'OPL';

    /**
     * @return array
     */
    public static function getLabels(): array
    {
        return [
            self::OPL => "OPL",
        ];
    }
}
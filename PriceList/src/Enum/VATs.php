<?php


namespace PriceList\Enum;


use Runple\Devtools\Enum\EnumInterface;
use Runple\Devtools\Enum\EnumTrait;

/**
 * Class VATs
 * @package PriceList\Enum
 */
class VATs implements EnumInterface
{
    use EnumTrait;

    const ZERO = 0;
    const TEN = 10;
    const THIRTEEN = 13;
    const TWENTY = 20;

    /**
     * @return array
     */
    public static function getLabels(): array
    {
        return [
            self::ZERO => 0,
            self::TEN => 10,
            self::THIRTEEN => 13,
            self::TWENTY => 20,
        ];
    }
}
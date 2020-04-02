<?php


namespace PriceList\Enum;


use Runple\Devtools\Enum\EnumInterface;
use Runple\Devtools\Enum\EnumTrait;

class PriceListStatuses implements EnumInterface
{
    use EnumTrait;
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const DELETED = 'deleted';

    /**
     * @return array
     */
    public static function getLabels(): array
    {
        return [
            self::ACTIVE => "active",
            self::INACTIVE => "inactive",
            self::DELETED => "deleted",
        ];
    }
}
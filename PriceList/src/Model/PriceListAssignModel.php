<?php


namespace PriceList\Model;


use Runple\Devtools\Model\AbstractModel;

/**
 * Class PriceListAssignModel
 * @package PriceList\Model
 */
class PriceListAssignModel extends AbstractModel
{
    const F_ID = 'id';

    /**
     * @var string|null
     */
    private $id;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }
}
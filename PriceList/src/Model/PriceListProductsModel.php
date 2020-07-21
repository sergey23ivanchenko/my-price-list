<?php


namespace PriceList\Model;

use Runple\Devtools\Model\AbstractModel;

class PriceListProductsModel extends AbstractModel
{
    const F_PRODUCT = 'product';
    const F_PRICE = 'price';

    /**
     * @var PriceListProductModel|null
     */
    private $product;

    /**
     * @var int|null
     */
    private $price;

    /**
     * @return int|null
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @param int|null $price
     */
    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return PriceListProductModel|null
     */
    public function getProduct(): ? PriceListProductModel
    {
        return $this->product;
    }

    /**
     * @param PriceListProductModel|null $product
     */
    public function setProduct(?PriceListProductModel $product): void
    {
        $this->product = $product;
    }
}
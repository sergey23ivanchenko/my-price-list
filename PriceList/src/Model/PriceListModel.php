<?php


namespace PriceList\Model;


use Runple\Devtools\Model\AbstractModel;

/**
 * Class PriceListModel
 * @package PriceList\Model
 */
class PriceListModel extends AbstractModel
{
    const F_TITLE = 'title';
    const F_DESCRIPTION = 'description';
    const F_IMAGE = 'image';
    const F_GOODS = 'price_list_goods';

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var object|null
     */
    private $image;

    /**
     * @var array|null
     */
    private $priceListGoods = [];

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return object|null
     */
    public function getImage(): ?object
    {
        return $this->image;
    }

    /**
     * @param object|null $image
     */
    public function setImage(?object $image): void
    {
        $this->image = $image;
    }


    /**
     * @return array|null
     */
    public function getPriceListGoods(): ?array
    {
        return $this->priceListGoods;
    }

    /**
     * @param array|null $priceListGoods
     */
    public function setPriceListGoods(?array $priceListGoods): void
    {
        $this->priceListGoods = $priceListGoods;
    }
}
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
    const F_PRODUCT_TYPE = 'product_type';
    const F_IMAGE = 'image';

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string|null
     */
    private $productType;

    /**
     * @var object|null
     */
    private $image;

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
     * @return string|null
     */
    public function getProductType(): ?string
    {
        return $this->productType;
    }

    /**
     * @param string|null $productType
     */
    public function setProductType(?string $productType): void
    {
        $this->productType = $productType;
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
}
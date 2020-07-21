<?php


namespace PriceList\Model;


use Runple\Devtools\Model\AbstractModel;
use JMS\Serializer\Annotation\Groups;

/**
 * Class PriceListProductsViewModel
 * @package PriceList\Model
 */
class PriceListProductViewModel extends AbstractModel
{
    /**
     * @var int
     * @Groups({"price_list_product_details"})
     */
    private $id;

    /**
     * @var int
     * @Groups({"price_list_product_details"})
     */
    private $productId;

    /**
     * @var string
     * @Groups({"price_list_product_details"})
     */
    private $runpleId;

    /**
     * @var string|null
     * @Groups({"price_list_product_details"})
     */
    private $name;

    /**
     * @var int|null
     * @Groups({"price_list_product_details"})
     */
    private $netPrice;

    /**
     * @var int|null
     * @Groups({"price_list_product_details"})
     */
    private $vat;

    /**
     * @var int|null
     * @Groups({"price_list_product_details"})
     */
    private $grossPrice;

    /**
     * @var string|null
     * @Groups({"price_list_product_details"})
     */
    private $remark;

    /**
     * @var string|null
     * @Groups({"price_list_product_details"})
     */
    private $category;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getRunpleId(): string
    {
        return $this->runpleId;
    }

    /**
     * @param string $runpleId
     */
    public function setRunpleId(string $runpleId): void
    {
        $this->runpleId = $runpleId;
    }

    /**
     * @return int|null
     */
    public function getVat(): ?int
    {
        return $this->vat;
    }

    /**
     * @param int|null $vat
     */
    public function setVat(?int $vat): void
    {
        $this->vat = $vat;
    }

    /**
     * @return int|null
     */
    public function getGrossPrice(): ?int
    {
        return $this->grossPrice;
    }

    /**
     * @param int|null $grossPrice
     */
    public function setGrossPrice(?int $grossPrice): void
    {
        $this->grossPrice = $grossPrice;
    }

    /**
     * @return string|null
     */
    public function getRemark(): ?string
    {
        return $this->remark;
    }

    /**
     * @param string|null $remark
     */
    public function setRemark(?string $remark): void
    {
        $this->remark = $remark;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string|null $category
     */
    public function setCategory(?string $category): void
    {
        $this->category = $category;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return int|null
     */
    public function getNetPrice(): ?int
    {
        return $this->netPrice;
    }

    /**
     * @param int|null $netPrice
     */
    public function setNetPrice(?int $netPrice): void
    {
        $this->netPrice = $netPrice;
    }
}
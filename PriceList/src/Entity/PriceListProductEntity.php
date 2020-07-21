<?php


namespace PriceList\Entity;

use Common\Doctrine\Traits\TimestampableEntity;
use PriceList\Enum\VATs;
use Product\GeneralProducts\Entity\GeneralProductEntity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Product\Products\Entity\ProductEntity;

/**
 * @ORM\Table(name="price_list_products",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="product_unique_idx", columns={"product_id", "price_list_id"})}
 * )
 * @ORM\Entity(repositoryClass="PriceList\Repository\PriceListProductRepository")
 */
class PriceListProductEntity
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="bigint", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"default"})
     */
    private $id;

    /**
     * @var PriceListEntity
     * @ORM\ManyToOne(targetEntity="PriceListEntity", inversedBy="priceListProducts")
     * @ORM\JoinColumn(name="price_list_id", referencedColumnName="id")
     * @Groups({"default"})
     */
    private $priceList;

    /**
     * @var ProductEntity
     * @ORM\ManyToOne(targetEntity="Product\Products\Entity\ProductEntity", inversedBy="priceListProducts")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     * @Groups({"default"})
     */
    private $product;

    /**
     * @var int|null
     * @ORM\Column(name="net_price", type="integer", options={"unsigned":true}, nullable=true)
     * @Groups({"default"})
     */
    private $netPrice;

    /**
     * @var int|null
     * @ORM\Column(name="gross_price", type="integer", options={"unsigned":true}, nullable=true)
     * @Groups({"default"})
     */
    private $grossPrice;

    /**
     * @see VATs
     * @var int|null
     * @ORM\Column(name="vat", type="integer", options={"unsigned":true}, nullable=true)
     * @Groups({"default"})
     */
    private $vat;

    /**
     * @var string|null
     * @ORM\Column(name="remark", type="text", nullable=true)
     * @Groups({"default"})
     */
    private $remark;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return PriceListEntity
     */
    public function getPriceList(): PriceListEntity
    {
        return $this->priceList;
    }

    /**
     * @param PriceListEntity $priceList
     */
    public function setPriceList(PriceListEntity $priceList): void
    {
        $this->priceList = $priceList;
    }

    /**
     * @return ProductEntity
     */
    public function getProduct(): ProductEntity
    {
        return $this->product;
    }

    /**
     * @param ProductEntity $product
     */
    public function setProduct(ProductEntity $product): void
    {
        $this->product = $product;
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
}

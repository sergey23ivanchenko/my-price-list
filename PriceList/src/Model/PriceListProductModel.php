<?php


namespace PriceList\Model;


use Runple\Devtools\Model\AbstractModel;

class PriceListProductModel extends AbstractModel
{
    const F_ID = 'id';
    const F_NET_PRICE = 'net_price';
    const F_GROSS_PRICE = 'gross_price';
    const F_VAT = 'vat';

    /**
     * @var int|null
     */
    private $id;

    /**
     * @var int|null
     */
    protected $netPrice;

    /**
     * @var int|null
     */
    protected $grossPrice;

    /**
     * @var int|null
     */
    protected $vat;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
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
}
<?php


namespace PriceList\Model;


use DateTime;
use Runple\Devtools\Model\AbstractModel;
use Runple\Modules\File\Entity\ImageEntity;
use User\Entity\UserEntity;
use JMS\Serializer\Annotation\Groups;

/**
 * Class PriceListViewModel
 * @package PriceList\Model
 */
class PriceListViewModel extends AbstractModel
{
    /**
     * @var int
     * @Groups({"price_list_details"})
     */
    private $id;

    /**
     * @var string|null
     * @Groups({"price_list_details"})
     */
    private $title;

    /**
     * @var string|null
     * @Groups({"price_list_details"})
     */
    private $status;

    /**
     * @var string|null
     * @Groups({"price_list_details"})
     */
    private $description;

    /**
     * @var UserEntity|null
     * @Groups({"price_list_details"})
     */
    private $manager;

    /**
     * @var DateTime
     * @Groups({"price_list_details"})
     */
    private $createdAt;

    /**
     * @var DateTime
     * @Groups({"price_list_details"})
     */
    private $updateAt;

    /**
     * @var ImageEntity
     * @Groups({"price_list_details"})
     */
    private $image;

    /**
     * @var PriceListProductViewModel[]|null
     * @Groups({"price_list_details"})
     */
    private $priceListProducts;

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
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
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
     * @return UserEntity|null
     */
    public function getManager(): ?UserEntity
    {
        return $this->manager;
    }

    /**
     * @param UserEntity|null $manager
     */
    public function setManager(?UserEntity $manager): void
    {
        $this->manager = $manager;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdateAt(): DateTime
    {
        return $this->updateAt;
    }

    /**
     * @param DateTime $updateAt
     */
    public function setUpdateAt(DateTime $updateAt): void
    {
        $this->updateAt = $updateAt;
    }

    /**
     * @return ImageEntity
     */
    public function getImage(): ImageEntity
    {
        return $this->image;
    }

    /**
     * @param ImageEntity $image
     */
    public function setImage(ImageEntity $image): void
    {
        $this->image = $image;
    }

    /**
     * @return PriceListProductViewModel[]|null
     */
    public function getPriceListProducts(): ?array
    {
        return $this->priceListProducts;
    }

    /**
     * @param PriceListProductViewModel[]|null $priceListProducts
     */
    public function setPriceListProducts(?array $priceListProducts): void
    {
        $this->priceListProducts = $priceListProducts;
    }
}
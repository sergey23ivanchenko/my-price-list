<?php


namespace PriceList\Entity;


use ArrayAccess;
use Common\Doctrine\Traits\BlameableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Product\Enum\ProductTypes;
use Runple\Devtools\Doctrine\Traits\TimestampableEntity;
use Runple\Modules\File\Entity\ImageEntity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Runple\Modules\Tools\RIDGenerator\RunpleIdEntityInterface;
use Runple\Modules\Tools\RIDGenerator\RunpleIdEntityTrait;
use User\Entity\UserEntity;

/**
 * @ORM\Table(name="price_list_price_lists",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="title_price_list_unique_idx", columns={"title"}),
 *          @ORM\UniqueConstraint(name="runple_id_price_list_unique_idx", columns={"runple_id"})
 *      }
 * )
 * @ORM\Entity(repositoryClass="PriceList\Repository\PriceListRepository")
 */
class PriceListEntity implements RunpleIdEntityInterface
{
    use TimestampableEntity;
    use BlameableEntity;
    use RunpleIdEntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"default"})
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(name="title", type="text")
     * @Groups({"default"})
     */
    private $title;

    /**
     * @var string|null
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Groups({"default"})
     */
    private $description;

    /**
     * @see PriceListStatuses
     * @var string
     * @ORM\Column(name="status", type="string", length=255)
     * @Groups({ "default"})
     */
    private $status;

    /**
     * @see PriceListTypes
     * @var string
     * @ORM\Column(name="type", type="string", length=255)
     * @Groups({ "default"})
     */
    private $type;

    /**
     * @see ProductTypes
     * @var string|null
     * @ORM\Column(name="product_type", type="string", length=255, nullable=true)
     * @Groups({ "default"})
     */
    private $productType;

    /**
     * @var ImageEntity
     * @ORM\ManyToOne(targetEntity="Runple\Modules\File\Entity\ImageEntity", cascade={"persist"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="CASCADE")
     * @Groups({"default"})
     */
    private $image;

    /**
     * @var UserEntity
     * @ORM\ManyToOne(targetEntity="User\Entity\UserEntity")
     * @ORM\JoinColumn(name="manager_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @Groups({"default"})
     */
    private $manager;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="PriceListProductEntity", mappedBy="priceList", cascade={"remove"})
     * @Groups({"default"})
     */
    private $priceListProducts;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="CatalogManagement\Entity\CatalogEntity", mappedBy="priceList", cascade={"remove"})
     * @Groups({"default"})
     */
    private $catalogs;

    /**
     * PriceListEntity constructor.
     */
    public function __construct()
    {
        $this->priceListProducts = new ArrayCollection();
        $this->catalogs = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     * @return ImageEntity|null
     */
    public function getImage(): ?ImageEntity
    {
        return $this->image;
    }

    /**
     * @param ImageEntity|null $image
     */
    public function setImage(?ImageEntity $image): void
    {
        $this->image = $image;
    }

    /**
     * @return Collection|null
     */
    public function getPriceListProducts(): ?Collection
    {
        return $this->priceListProducts;
    }

    /**
     * @param Collection|null $priceListProducts
     */
    public function setPriceListProducts(?Collection $priceListProducts): void
    {
        $this->priceListProducts = $priceListProducts;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
    /**
     * @param ArrayAccess $items
     */
    public function addPriceListProducts(ArrayAccess $items)
    {
        foreach ($items as $item) {
            $this->addPriceListProduct($item);
        }
    }

    /**
     * @param ArrayAccess $items
     */
    public function removePriceListProducts(ArrayAccess $items)
    {
        foreach ($items as $item) {
            $this->removePriceListProduct($item);
        }
    }


    /**
     * @param PriceListProductEntity $item
     * @return bool
     */
    public function addPriceListProduct(PriceListProductEntity $item) : bool
    {
        if (! $this->priceListProducts->contains($item)) {
            $this->priceListProducts->add($item);
            $item->setPriceList($this);
            return true;
        }
        return false;
    }

    /**
     * @param PriceListProductEntity $item
     */
    public function removePriceListProduct(PriceListProductEntity $item)
    {
        if ($this->priceListProducts->contains($item)) {
            $this->priceListProducts->removeElement($item);
        }
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return Collection
     */
    public function getCatalogs(): Collection
    {
        return $this->catalogs;
    }

    /**
     * @param Collection $catalog
     */
    public function setCatalogs(Collection $catalog): void
    {
        $this->catalogs = $catalog;
    }

    /**
     * @return UserEntity
     */
    public function getManager(): UserEntity
    {
        return $this->manager;
    }

    /**
     * @param UserEntity $manager
     */
    public function setManager(UserEntity $manager): void
    {
        $this->manager = $manager;
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
}
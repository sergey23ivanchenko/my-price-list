<?php


namespace PriceList\Service;


use Common\Exceptions\CommonException;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use PriceList\Entity\PriceListEntity;
use PriceList\Entity\PriceListProductEntity;
use PriceList\Repository\PriceListProductRepository;
use ReflectionException;
use Runple\Devtools\Pagination\Model\PaginationModel;
use Runple\Modules\Product\Family\Entity\ProductFamilyEntity;

class PriceListProductsReader
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var PriceListProductRepository
     */
    private $repository;

    /**
     * PriceListReader constructor.
     * @param EntityManager $em
     * @param PriceListProductRepository $repository
     */
    public function __construct(EntityManager $em, PriceListProductRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * @param PriceListEntity $priceList
     * @param int $id
     * @return PriceListProductEntity|null
     * @throws NonUniqueResultException
     */
    public function getProductsByIdInPriceList(PriceListEntity $priceList, int $id): ?PriceListProductEntity
    {
        return $this->repository->getProductsByIdInPriceList($priceList, $id);
    }

    /**
     * @param PriceListEntity $priceList
     * @param ProductFamilyEntity $family
     * @param PaginationModel $pagination
     * @param array|null $sorting
     * @return PriceListProductEntity[]|null
     * @throws CommonException
     * @throws MappingException
     * @throws ReflectionException
     */
    public function findPriceListProducts(
        PriceListEntity $priceList,
        ProductFamilyEntity $family,
        PaginationModel $pagination,
        ?array $sorting
    ): ?array
    {
        return $this->repository->getProductsList($priceList, $family, $pagination, $sorting);
    }
}
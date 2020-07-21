<?php


namespace PriceList\Repository;


use CatalogManagement\Entity\CatalogEntity;
use Common\Doctrine\Traits\EntityFieldCheckableTrait;
use Common\Exceptions\CommonException;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use PriceList\Entity\PriceListEntity;
use PriceList\Entity\PriceListProductEntity;
use Product\Products\Enum\ProductStatuses;
use ReflectionException;
use Runple\Devtools\Pagination\Model\PaginationModel;
use Runple\Devtools\Pagination\Repository\PaginationApplierTrait;
use Runple\Devtools\Tool\StringTool;
use Runple\Modules\Product\Family\Entity\ProductFamilyEntity;

/**
 * Class PriceListProductRepository
 * @package PriceList\Repository
 */
class PriceListProductRepository extends EntityRepository
{
    use PaginationApplierTrait;
    use EntityFieldCheckableTrait;

    /**
     * @param int $id
     * @return PriceListProductEntity[]
     */
    public function getProductFromPriceList(int $id)
    {
        return $this->createQueryBuilder('plp')
            ->where("plp.priceList = :price_list_id")
            ->setParameter('price_list_id', $id)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param PriceListProductEntity $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function store(PriceListProductEntity $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
    }

    /**
     * @param PriceListEntity $priceList
     * @param int $id
     * @return PriceListProductEntity|null
     * @throws NonUniqueResultException
     */
    public function getProductsByIdInPriceList(PriceListEntity $priceList, int $id): ?PriceListProductEntity
    {
        return $this->createQueryBuilder('plp')
            ->where("plp.id = :id")
            ->andWhere('plp.priceList = :priceList')
            ->setParameter('id', $id)
            ->setParameter('priceList', $priceList)
            ->orderBy("plp.id", "DESC")
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param PriceListEntity $priceList
     * @param ProductFamilyEntity $family
     * @param PaginationModel $pagination
     * @param array|null $sort
     * @return PriceListProductEntity[]|null
     * @throws CommonException
     * @throws MappingException
     * @throws ReflectionException
     */
    public function getProductsList(PriceListEntity $priceList, ProductFamilyEntity $family,  PaginationModel $pagination, ?array $sort = []): ?array
    {
        $qb = $this->createQueryBuilder('plp')
            ->leftJoin('plp.product', 'p')
            ->leftJoin('p.generalProduct', 'gp')
            ->where('plp.priceList = :priceList')
            ->andWhere('gp.family = :family')
            ->andWhere('p.status IN (:status)')
            ->setParameter('family', $family)
            ->setParameter('priceList', $priceList)
            ->setParameter("status", [ProductStatuses::ACTIVE, ProductStatuses::CONFLICT]);
        if (!empty($sort)) {
            $this->checkFields(array_keys($sort), CatalogEntity::class, $this->getEntityManager());
            foreach ($sort as $key => $param) {
                $field = StringTool::snakeCaseToCamelCase($key, true);
                $qb->orderBy('plp.' . $field, $param);
            }
        }
        $this->applyPagination($qb, $pagination);
        return $qb->getQuery()->getResult();
    }
}
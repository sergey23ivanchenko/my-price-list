<?php


namespace PriceList\Repository;


use Common\Doctrine\Traits\EntityFieldCheckableTrait;
use Common\RIDGenerator\RunpleIdRepositoryInterface;
use Common\RIDGenerator\RunpleIdRepositoryTrait;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use PriceList\Entity\PriceListEntity;
use Runple\Devtools\Filter\FilterContainer;
use Runple\Devtools\Pagination\Model\PaginationModel;
use Runple\Devtools\Pagination\Repository\PaginationApplierTrait;
use Runple\Devtools\Tool\StringTool;
use Runple\Modules\Tools\RIDGenerator\RunpleIdEntityInterface;

/**
 * Class PriceListRepository
 * @package PriceList\Repository
 */
class PriceListRepository extends EntityRepository implements RunpleIdRepositoryInterface
{
    use PaginationApplierTrait;
    use EntityFieldCheckableTrait;
    use RunpleIdRepositoryTrait;

    /**
     * @return PriceListEntity[]|null
     */
    public function getAll()
    {
        return $this->createQueryBuilder('pl')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $id
     * @return PriceListEntity|null
     * @throws NonUniqueResultException
     */
    public function getPriceList(int $id) : ?PriceListEntity
    {
        return $this->createQueryBuilder('pl')
            ->leftJoin('pl.priceListGoods', 'plg')
            ->where("pl.id = :id")
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
    /**
     * @param int[] $ids
     * @return PriceListEntity[]|null
     */
    public function getPriceLists(array $ids)
    {
        return $this->createQueryBuilder('pl')
            ->where("pl.id IN(:id)")
            ->setParameter('id', $ids)
            ->orderBy("pl.id", "DESC")
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $title
     * @return PriceListEntity|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getPriceListByTitle(string $title) : ?PriceListEntity
    {
        return $this->createQueryBuilder('pl')
            ->where("pl.title = :title")
            ->setParameter('title', $title)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param PriceListEntity $entity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(PriceListEntity $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
    }

    /**
     * @param FilterContainer $filters
     * @param PaginationModel $pagination
     * @param array|null $sort
     * @return PriceListEntity[]|null
     * @throws \Common\Exceptions\CommonException
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \ReflectionException
     */
    public function findByFilters( FilterContainer $filters, PaginationModel $pagination, ?array $sort = [])
    {
        $qb = $this->createQueryBuilder('pl');
        $filters->apply($qb, 'pl');
        if (!empty($sort)) {
            $this->checkFields(array_keys($sort), PriceListEntity::class, $this->getEntityManager());
            foreach ($sort as $key => $param) {
                $field = StringTool::snakeCaseToCamelCase($key, true);
                $qb->orderBy('pl.' . $field, $param);
            }
        }
        $this->applyPagination($qb, $pagination);
        return $qb->getQuery()->getResult();
    }

    /**
     * @param FilterContainer $filters
     * @return int
     * @throws NonUniqueResultException
     */
    public function countByFilters(FilterContainer $filters) : int
    {
        $qb = $this->createQueryBuilder('pl');
        $qb->select('count(1)');
        $filters->apply($qb, 'pl');
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return mixed
     */
    public function countByStatuses()
    {
        $qb = $this->createQueryBuilder('pl')
            ->select('count(1) as count, pl.status')
            ->groupBy('pl.status')
            ->getQuery();
        return $qb->getResult();
    }
}
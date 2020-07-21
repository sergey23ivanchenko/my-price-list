<?php


namespace PriceList\Service;


use Common\Enum\ComparisonExpressions;
use Common\Exceptions\CommonException;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityManager;
use PriceList\Entity\PriceListEntity;
use PriceList\Enum\PriceListStatuses;
use PriceList\Repository\PriceListRepository;
use Runple\Devtools\Filter\FilterContainer;
use Runple\Devtools\Filter\Model\FieldConfig;
use Runple\Devtools\Pagination\Model\PaginationModel;
use Runple\Devtools\Tool\Arrays\ArrayHelper;

/**
 * Class PriceListReader
 * @package PriceList\Service
 */
class PriceListReader
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var PriceListRepository
     */
    private $repository;

    /**
     * PriceListReader constructor.
     * @param EntityManager $em
     * @param PriceListRepository $repository
     */
    public function __construct(EntityManager $em, PriceListRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }
    /**
     * @param int $id
     * @return PriceListEntity|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getPriceListById(int $id) :?PriceListEntity
    {
        return $this->repository->getPriceList($id);
    }
    /**
     * @param int[] $ids
     * @return PriceListEntity[]|null
     */
    public function getPriceListByIds(array $ids):?array
    {
        return $this->repository->getPriceLists($ids);
    }

    /**
     * @param FilterContainer $filters
     * @param PaginationModel $pagination
     * @param array|null $sorting
     * @return array|null
     * @throws CommonException
     * @throws MappingException
     * @throws \ReflectionException
     */
    public function findAll(FilterContainer $filters, PaginationModel $pagination, ?array $sorting): ?array
    {
        $this->configureFiltersContainer($filters);
        $this->sortingCheck($sorting);
        $result = $this->repository->findByFilters($filters, $pagination, $sorting);
        return $result;
    }

    /**
     * @param array|null $sorting
     * @throws CommonException
     */
    protected function sortingCheck(?array $sorting): void
    {
        if (!empty($sorting)) {
            if (count($sorting) > 1) {
                throw new CommonException('You\'re not allowed sort by multiple fields');
            }
            if(!isset($sorting['created_at'])) {
                throw new CommonException('Only created_at allowed for sorting');
            }
        }
    }

    /**
     * @param FilterContainer $filters
     */
    protected function configureFiltersContainer(FilterContainer $filters)
    {
        $field = new FieldConfig();
        $field->setKey('status');
        $filters->addField($field);
        $field->setCompOp(ComparisonExpressions::CONTAINS);
    }

    /**
     * @param FilterContainer $filters
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAll(FilterContainer $filters) : int
    {
        $this->configureFiltersContainer($filters);
        return $this->repository->countByFilters($filters);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function countByStatuses(): array
    {
        $counts = ArrayHelper::index($counts = $this->repository->countByStatuses(), 'status');
        $statuses = PriceListStatuses::getList();
        $result = [];
        foreach ($statuses as $status) {
            if (array_key_exists($status, $counts)) {
                $result[$status] = $counts[$status]["count"];
            } else {
                $result[$status] = 0;
            }
        }
        return $result;
    }
}
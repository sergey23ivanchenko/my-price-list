<?php


namespace PriceList\Service;


use CatalogManagement\Entity\CatalogEntity;
use CatalogManagement\Repository\CatalogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use PriceList\Entity\PriceListEntity;
use PriceList\Enum\PriceListStatuses;
use Runple\Devtools\Exception\CommonException;
use Runple\Devtools\Tool\Arrays\ArrayHelper;

/**
 * Class PriceListAssignment
 * @package PriceList\Service
 */
class PriceListAssignment
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var CatalogRepository
     */
    private $cRepository;

    /**
     * @var PriceListTransfer
     */
    private $transfer;

    /**
     * PriceListAssignment constructor.
     * @param EntityManager $em
     * @param CatalogRepository $cRepository
     * @param PriceListTransfer $transfer
     */
    public function __construct(
        EntityManager $em,
        CatalogRepository $cRepository,
        PriceListTransfer $transfer
    )
    {
        $this->em = $em;
        $this->cRepository = $cRepository;
        $this->transfer = $transfer;
    }

    /**
     * @param PriceListEntity $priceList
     * @param array $catalogs
     * @return PriceListEntity
     * @throws CommonException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function assignCatalogsToPriceList(PriceListEntity $priceList, array $catalogs)
    {
        if($priceList->getStatus() !== PriceListStatuses::ACTIVE) {
            throw new CommonException('The price list should have only ACTIVE status');
        }

        $currentCatalogs = $priceList->getCatalogs()->toArray();

        $currentCatalogsMap = ArrayHelper::index($currentCatalogs, 'id');
        $catalogsIds = array_map(function(CatalogEntity $model){
            return $model->getId();
        }, $catalogs);

        $newCatalogs = $this->cRepository->getCatalogs($catalogsIds);
        $newCatalogsMap = ArrayHelper::index($newCatalogs, 'id');

        /**
         * @var $catalog CatalogEntity
         */
        $arrCatalogs = [];
        $transferCatalogs = [];
        foreach ($catalogs as $catalog) {
            if(in_array($catalog->getId(), $arrCatalogs)) {
                continue;
            }
            $arrCatalogs[] = $catalog->getId();
            $id = $catalog->getId();
            if(!isset($newCatalogsMap[$id])) {
                throw new CommonException(sprintf("Such catalog id is not exists: %d", (int) $id));
            }
            if(isset($currentCatalogsMap[$id])) {
                $entity = $currentCatalogsMap[$id];
                unset($currentCatalogsMap[$id]);
            }
            else {
                $entity = $catalog;
                $transferCatalogs[] = $catalog;
            }
            $entity->setPriceList($priceList);
        }
        foreach($currentCatalogsMap as $removedAssign) {
            $this->em->remove($removedAssign);
        }

        $this->em->flush();
        $this->em->refresh($priceList);
        $this->transfer->transfer($priceList->getPriceListProduct()->toArray(), $transferCatalogs);

        return $priceList;
    }
}
<?php


namespace PriceList\Service;


use Common\Exceptions\CommonException;
use Doctrine\ORM\EntityManager;
use File\Model\ImageModel;
use PriceList\Entity\PriceListEntity;
use PriceList\Enum\PriceListEvents;
use PriceList\Enum\PriceListStatuses;
use PriceList\Enum\PriceListTypes;
use PriceList\Event\PriceListEvent;
use PriceList\Model\PriceListModel;
use PriceList\Repository\PriceListRepository;
use Runple\Modules\File\Entity\ImageEntity;
use Runple\Modules\File\Repository\ImageRepository;

/**
 * Class PriceListService
 * @package PriceList\Service
 */
class PriceListService
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
     * @var ImageRepository
     */
    protected $imageRepo;

    /**
     * @var PriceListGoodsService
     */
    private $service;

    /**
     * @var PriceListEventManager
     */
    private $eventManager;

    /**
     * PriceListService constructor.
     * @param EntityManager $em
     * @param PriceListRepository $repository
     * @param ImageRepository $imageRepo
     * @param PriceListGoodsService $service
     * @param PriceListEventManager $eventManager
     */
    public function __construct(
        EntityManager $em,
        PriceListRepository $repository,
        ImageRepository $imageRepo,
        PriceListGoodsService $service,
        PriceListEventManager $eventManager

    )
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->imageRepo = $imageRepo;
        $this->service = $service;
        $this->eventManager = $eventManager;
    }

    /**
     * @param PriceListModel $model
     * @return PriceListEntity
     * @throws CommonException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Runple\Devtools\Exception\CommonException
     */
    public function createOutgoingPriceList(PriceListModel $model)
    {
        $priceListGoods = $model->getPriceListGoods();
        if ($this->repository->getPriceListByTitle($model->getTitle())) {
            throw new CommonException("Duplicate unique keys Price List Title");
        }
        $priceList = $this->fillEntity($model, new PriceListEntity());
        $priceList->setStatus(PriceListStatuses::ACTIVE);
        $priceList->setType(PriceListTypes::OPL);
        $this->em->persist($priceList);
        $this->service->putPriceListGoods($priceList, $priceListGoods);
        $this->triggerBeforeCreateEvent($priceList);
        $this->em->flush();
        return $priceList;
    }

    /**
     * @param PriceListEntity $priceList
     */
    protected function triggerBeforeCreateEvent(PriceListEntity $priceList)
    {
        $event = new PriceListEvent(PriceListEvents::BEFORE_CREATE_OPL_PRICE_LIST, $this, $priceList);
        $this->eventManager->triggerEvent($event);
    }
    /**
     * @param PriceListEntity $priceList
     * @param PriceListModel $model
     * @return PriceListEntity
     * @throws CommonException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Runple\Devtools\Exception\CommonException
     */
    public function editPriceList(PriceListEntity $priceList, PriceListModel $model)
    {
        if($priceList->getStatus() !== PriceListStatuses::ACTIVE) {
            throw new \Runple\Devtools\Exception\CommonException('The price list should have only ACTIVE status');
        }
        $priceListGoods = $model->getPriceListGoods();
        if ($priceList->getTitle() != $model->getTitle() && $this->repository->getPriceListByTitle($model->getTitle())) {
            throw new CommonException("Duplicate unique Price List title");
        }
        $priceList = $this->fillEntity($model, $priceList);
        $this->service->putPriceListGoods($priceList, $priceListGoods);
        $this->em->flush();
        return $priceList;
    }

    /**
     * @param PriceListModel $model
     * @param PriceListEntity $entity
     * @return PriceListEntity
     * @throws CommonException
     */
    protected function fillEntity(PriceListModel $model, PriceListEntity $entity): PriceListEntity
    {
        $entity->setTitle($model->getTitle());
        $entity->setDescription($model->getDescription());

        $image = null;
        if($model->getImage()) {
            /**
             * @var $image ImageEntity
             */
            $image = $this->imageRepo->find($model->getImage()->getId())?? [];
            if(empty($image)) {
                throw new CommonException(sprintf("Such image id is not exists: %d", (int) $model->getImage()->getId()));
            }
        }
        $entity->setImage($image);
        return $entity;
    }


    /**
     * @param array $priceLists
     * @param string $status
     * @return array
     * @throws CommonException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changePriceListsStatus(array $priceLists, string $status): array
    {
        foreach ($priceLists as $priceList) {
            if(!$this->validateStatus($status, $priceList->getStatus())) {
                throw new CommonException("New status wasn't pass validation check by current status");
            }
            $priceList->setStatus($status);
        }
        $this->em->flush();
        return $priceLists;
    }
    /**
     * @param string $newStatus
     * @param string $currentStatus
     * @return bool
     */
    protected function validateStatus(string $newStatus, string $currentStatus): bool
    {
        switch ($newStatus) {
            case PriceListStatuses::INACTIVE : return in_array($currentStatus, [PriceListStatuses::DELETED, PriceListStatuses::ACTIVE]);
            case PriceListStatuses::ACTIVE : return in_array($currentStatus, [PriceListStatuses::INACTIVE, PriceListStatuses::DELETED]);
            case PriceListStatuses::DELETED : return in_array($currentStatus, [PriceListStatuses::INACTIVE]);
        }
        return false;
    }

    /**
     * @param array $priceLists
     * @return bool
     * @throws CommonException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deletePriceLists(array $priceLists):bool
    {
        foreach ($priceLists as $priceList) {
            if($priceList->getStatus() != PriceListStatuses::DELETED) {
                throw new CommonException('Only DELETED status is allowed to Price List removal');
            }
            $this->em->remove($priceList);
        }
        $this->em->flush();
        return true;
    }
}
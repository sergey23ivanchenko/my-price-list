<?php


namespace PriceList\Service;


use Common\Exceptions\CommonException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use File\Model\ImageModel;
use PriceList\Entity\PriceListEntity;
use PriceList\Enum\PriceListEvents;
use PriceList\Enum\PriceListStatuses;
use PriceList\Enum\PriceListTypes;
use PriceList\Event\PriceListEvent;
use PriceList\Model\CreatePriceListModel;
use PriceList\Model\PriceListModel;
use PriceList\Repository\PriceListRepository;
use Product\Enum\ProductTypes;
use Runple\Modules\File\Entity\ImageEntity;
use Runple\Modules\File\Repository\ImageRepository;
use User\Entity\UserEntity;

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
     * @var PriceListProductsService
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
     * @param PriceListProductsService $service
     * @param PriceListEventManager $eventManager
     */
    public function __construct(
        EntityManager $em,
        PriceListRepository $repository,
        ImageRepository $imageRepo,
        PriceListProductsService $service,
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
     * @param UserEntity $user
     * @return PriceListEntity
     * @throws CommonException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \Exception
     */
    public function createPriceListByType(PriceListModel $model, UserEntity $user)
    {
        $priceList = $this->fillEntity($model, new PriceListEntity(), $user);
        $priceList->setStatus(PriceListStatuses::ACTIVE);
        $priceList->setType(PriceListTypes::OPL);
        if(!in_array($model->getProductType(), ProductTypes::getList())) {
            throw new CommonException(sprintf("Invalid product type %s ", $model->getProductType()));
        }
        $priceList->setProductType($model->getProductType());
        $this->em->persist($priceList);
        $this->service->addProductsByType($priceList, $model->getProductType());
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
     * @param UserEntity $user
     * @return PriceListEntity
     * @throws CommonException
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \Runple\Devtools\Exception\CommonException
     */
    public function editPriceList(PriceListEntity $priceList, PriceListModel $model, UserEntity $user)
    {
        if($priceList->getStatus() !== PriceListStatuses::ACTIVE) {
            throw new \Runple\Devtools\Exception\CommonException('The price list should have only ACTIVE status');
        }
        if ($priceList->getTitle() != $model->getTitle() && $this->repository->getPriceListByTitle($model->getTitle())) {
            throw new CommonException("Duplicate unique Price List title");
        }
        $priceList = $this->fillEntity($model, $priceList, $user);
        $this->em->flush();
        return $priceList;
    }

    /**
     * @param PriceListModel $model
     * @param PriceListEntity $entity
     * @param UserEntity $user
     * @return PriceListEntity
     * @throws CommonException
     * @throws NonUniqueResultException
     */
    protected function fillEntity(PriceListModel $model, PriceListEntity $entity, UserEntity $user): PriceListEntity
    {
        if ($this->repository->getPriceListByTitle($model->getTitle())) {
            throw new CommonException("Duplicate unique keys Price List Title");
        }
        $entity->setTitle($model->getTitle());
        $entity->setDescription($model->getDescription());
        $entity->setManager($user);

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
     * @throws ORMException
     * @throws OptimisticLockException
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
     * @throws ORMException
     * @throws OptimisticLockException
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
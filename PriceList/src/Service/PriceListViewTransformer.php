<?php


namespace PriceList\Service;


use PriceList\Entity\PriceListEntity;
use PriceList\Entity\PriceListProductEntity;
use PriceList\Model\PriceListProductViewModel;
use PriceList\Model\PriceListViewModel;

/**
 * Class PriceListViewTransformer
 * @package PriceList\Service
 */
class PriceListViewTransformer
{

    /**
     * @param PriceListEntity[] $priceLists
     * @return PriceListViewModel[]
     */
    public function transformPriceListsToViewModel(array $priceLists): array
    {
        $models = [];
        foreach ($priceLists as $priceList) {
            $models[] = $this->transformPriceListToViewModel($priceList);
        }
        return $models;
    }

    /**
     * @param PriceListEntity $entity
     * @return PriceListViewModel
     */
    public function transformPriceListToViewModel(PriceListEntity $entity): PriceListViewModel
    {
        $model = new PriceListViewModel();
        $model->setId($entity->getId());
        $model->setTitle($entity->getTitle());
        $model->setStatus($entity->getStatus());
        $model->setDescription($entity->getDescription());
        $model->setManager($entity->getManager());
        $model->setCreatedAt($entity->getCreatedAt());
        $model->setUpdateAt($entity->getUpdatedAt());
        $model->setImage($entity->getImage());

        return $model;
    }

    /**
     * @param PriceListEntity $entity
     * @return PriceListViewModel
     */
    public function transformPriceList(PriceListEntity $entity): PriceListViewModel
    {
        $model = new PriceListViewModel();
        $model->setId($entity->getId());
        $model->setTitle($entity->getTitle());
        $model->setStatus($entity->getStatus());
        $model->setDescription($entity->getDescription());
        $model->setManager($entity->getManager());
        $model->setCreatedAt($entity->getCreatedAt());
        $model->setUpdateAt($entity->getUpdatedAt());
        $model->setImage($entity->getImage());

        $priceListProducts = $entity->getPriceListProducts();
        $productsModels = [];
        foreach ($priceListProducts as $priceListProduct) {
            $productsModels[] = $this->transformPriceListProductToViewModel($priceListProduct);
        }

        $model->setPriceListProducts($productsModels);

        return $model;
    }

    /**
     * @param PriceListProductEntity[] $priceListProducts
     * @return PriceListProductViewModel[]
     */
    public function transformPriceListProductsToViewModel(array $priceListProducts): array
    {
        $models = [];
        foreach ($priceListProducts as $priceListProduct) {
            $models[] = $this->transformPriceListProductToViewModel($priceListProduct);
        }
        return $models;
    }
    /**
     * @param PriceListProductEntity $entity
     * @return PriceListProductViewModel
     */
    public function transformPriceListProductToViewModel(PriceListProductEntity $entity): PriceListProductViewModel
    {
        $product = $entity->getProduct();

        $model = new PriceListProductViewModel();
        $model->setId($entity->getId());
        $model->setProductId($product->getId());
        $model->setRunpleId($product->getRunpleId());
        $model->setName($product->getName());
        $model->setNetPrice($entity->getNetPrice());
        $model->setVat($entity->getVat());
        $model->setGrossPrice($entity->getGrossPrice());
        $model->setRemark($entity->getRemark());
        $model->setCategory($product->getGeneralProduct()->getFamily()->getTitle());

        return $model;
    }
}
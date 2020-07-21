<?php


namespace PriceList\Service;


use Common\Exceptions\CommonException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use PriceList\Entity\PriceListEntity;
use PriceList\Entity\PriceListProductEntity;
use PriceList\Enum\VATs;
use PriceList\Model\PriceListProductModel;
use PriceList\Repository\PriceListProductRepository;
use Product\Products\Repository\ProductRepository;

/**
 * Class PriceListProductService
 * @package PriceList\Service
 */
class PriceListProductsService
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
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var PriceListTransfer
     */
    private $transfer;

    /**
     * PriceListProductService constructor.
     * @param EntityManager $em
     * @param PriceListProductRepository $repository
     * @param ProductRepository $productRepository
     * @param PriceListTransfer $transfer
     */
    public function __construct(
        EntityManager $em,
        PriceListProductRepository $repository,
        ProductRepository $productRepository,
        PriceListTransfer $transfer
    )
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->productRepository = $productRepository;
        $this->transfer = $transfer;
    }

    /**
     * @param PriceListEntity $priceList
     * @param string $productType
     * @throws ORMException
     * @throws Exception
     */
    public function addProductsByType(PriceListEntity $priceList, string $productType)
    {
        $products = $this->productRepository->getProductsByType($productType);
        foreach ($products as $product) {
            $priceListProduct = new PriceListProductEntity();
            $priceListProduct->setProduct($product);
            $priceListProduct->setVat(VATs::TWENTY);
            $this->em->persist($priceListProduct);
            $priceList->addPriceListProduct($priceListProduct);
        }
    }

    /**
     * @param PriceListProductEntity $product
     * @param PriceListProductModel $model
     * @return PriceListProductEntity
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws CommonException
     */
    public function updateProduct(PriceListProductEntity $product, PriceListProductModel $model): PriceListProductEntity
    {
        $newNetPrice = $model->getNetPrice();
        $newGrossPrice = $model->getGrossPrice();
        $newVat = $model->getVat();
        if($newNetPrice) {
            $product->setNetPrice($newNetPrice);
            $product->setGrossPrice($newNetPrice*(1+$product->getVat()/100));
        }
        if($newVat) {
            if(!in_array($newVat, VATs::getLabels())) {
                throw new CommonException(sprintf("Invalid VAT value %s ", $newVat));
            }
            $product->setVat($newVat);
            $product->setGrossPrice($product->getNetPrice()*(1+$newVat/100));
        }
        if($newGrossPrice) {
            $product->setGrossPrice($model->getGrossPrice());
            $product->setNetPrice($newGrossPrice*(1-$product->getVat()/100));
        }
        $this->em->flush();

        return $product;
    }
}

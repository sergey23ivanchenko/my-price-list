<?php


namespace PriceList\Service;


use CatalogManagement\Entity\CatalogEntity;
use CatalogManagement\Entity\CatalogProductEntity;
use CatalogManagement\Repository\CatalogRepository;
use Common\Tool\CurrencyConverter;
use Company\Service\LocalizationSettingsService;
use Doctrine\ORM\EntityManager;
use PriceList\Entity\PriceListEntity;
use PriceList\Entity\PriceListProductEntity;

class PriceListTransfer
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
     * @var CurrencyConverter
     */
    private $converter;

    /**
     * @var LocalizationSettingsService
     */
    private $localizations;


    /**
     * PriceListTransfer constructor.
     * @param EntityManager $em
     * @param CatalogRepository $cRepository
     * @param CurrencyConverter $converter
     * @param LocalizationSettingsService $localizations
     */
    public function __construct(
        EntityManager $em,
        CatalogRepository $cRepository,
        CurrencyConverter $converter,
        LocalizationSettingsService $localizations
    )
    {
        $this->em = $em;
        $this->cRepository = $cRepository;
        $this->converter = $converter;
        $this->localizations = $localizations;
    }

    /**
     * @param PriceListProductEntity[] $priceListProducts
     * @param CatalogEntity[] $catalogs
     * @return CatalogEntity[]
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function transfer(array $priceListProducts, array $catalogs)
    {

        $listPrice = [];

        foreach ($priceListProducts as $good) {
            $listPrice[$good->getGeneralGood()->getId()] = $good->getPrice();
        }
        $updatedCatalogs = [];
        foreach ($catalogs as $catalog) {
            $updatedCatalogs[] = $this->updateCatalog($catalog, $listPrice);
        }
        $this->em->flush();
        return $updatedCatalogs;
    }

    /**
     * @param CatalogEntity $catalogEntity
     * @param array $listPrice
     * @return CatalogEntity
     */
    protected function updateCatalog(CatalogEntity $catalogEntity, array $listPrice)
    {
        $defaultCurrency =  $this->localizations->getDefaultCurrency();
        $catalogCurrency = $catalogEntity->getCurrency();

        $catalogProduct = $catalogEntity->getCatalogProduct();

        $transferPrice = $this->converter->convertBatch($defaultCurrency, $catalogCurrency, $listPrice);

        /**
         * @var $catalogGood CatalogProductEntity
         */
        foreach ($catalogProduct as $catalogGood) {
            $ggId = $catalogGood->getGeneralGood()->getId();
            if(array_key_exists($ggId, $transferPrice)) {
                $catalogGood->setCatalogGoodPrice($transferPrice[$catalogGood->getGeneralGood()->getId()]);
            }
        }
        return $catalogEntity;
    }
}
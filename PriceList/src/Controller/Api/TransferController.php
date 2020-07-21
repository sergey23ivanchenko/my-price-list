<?php


namespace PriceList\Controller\Api;


use CatalogManagement\Service\CatalogReader;
use Common\Api\Controller\AbstractApiController;
use PriceList\Entity\PriceListEntity;
use PriceList\Service\PriceListReader;
use PriceList\Service\PriceListTransfer;
use Zend\Http\Response;

/**
 * Class TransferController
 * @package PriceList\Controller\Api
 */
class TransferController extends AbstractApiController
{

    /**
     * @var PriceListTransfer
     */
    private $transferService;

    /**
     * @var PriceListReader
     */
    private $reader;

    /**
     * @var CatalogReader
     */
    private $catalogReader;

    /**
     * TransferController constructor.
     * @param PriceListTransfer $transferService
     * @param PriceListReader $reader
     * @param CatalogReader $catalogReader
     */
    public function __construct(
        PriceListTransfer $transferService,
        PriceListReader $reader,
        CatalogReader $catalogReader
    )
    {
        $this->transferService = $transferService;
        $this->reader = $reader;
        $this->catalogReader = $catalogReader;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/price-lists/{id}/price-transfer/catalogs",
     *     deprecated=true,
     *     tags={"priceList"},
     *     security={
     *       {"api_key": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of Price List",
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     summary="Import price to catalog",
     *     @OA\RequestBody(
     *        request="GeneralGood",
     *        description="General good model for storing",
     *        required=true,
     *        @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="catalogs",
     *                  title="Catalogs assign",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/Catalog-Assign"),
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="catalogs",
     *                  title="Catalogs assign",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/Catalog"),
     *              )
     *          ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    /**
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function transferPriceToCatalogsAction()
    {
        $response = $this->getResponse();
        $ids = $this->getRequestBody()['catalogs'];
        $id = $this->toIntFromRoute('id');
        $priceList = $this->reader->getPriceListById($id);
        if (!$priceList instanceof PriceListEntity) {
            return $this->prepareErrorResponse($response, 'Price List not found', null, Response::STATUS_CODE_404 );
        }
        $priceListProducts = $priceList->getPriceListProduct()->toArray();
        $catalogs = $this->catalogReader->getCatalogByIds($ids);
        $updatedCatalogs = $this->transferService->transfer($priceListProducts, $catalogs);
        return $this->prepareSuccessResponse($response, $updatedCatalogs);
    }
}
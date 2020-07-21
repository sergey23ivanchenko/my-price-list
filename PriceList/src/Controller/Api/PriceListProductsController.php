<?php


namespace PriceList\Controller\Api;


use Common\Api\Controller\AbstractApiController;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use PriceList\Entity\PriceListEntity;
use PriceList\Entity\PriceListProductEntity;
use PriceList\Form\PriceListProductForm;
use PriceList\Model\PriceListProductModel;
use PriceList\Service\PriceListProductsReader;
use PriceList\Service\PriceListProductsService;
use PriceList\Service\PriceListReader;
use PriceList\Service\PriceListViewTransformer;
use Runple\Devtools\Pagination\Form\PaginationForm;
use Runple\Devtools\Pagination\Model\PaginationModel;
use Runple\Modules\Product\Family\Entity\ProductFamilyEntity;
use Runple\Modules\Product\Family\Service\FamilyReader;
use Zend\Http\Response;

/**
 * Class PriceListProductsController
 * @package PriceList\Controller\Api
 */
class PriceListProductsController extends AbstractApiController
{
    const PRICE_LIST_PRODUCT_DETAILS = 'price_list_product_details';

    /**
     * @var PriceListReader
     */
    private $priceListReader;

    /**
     * @var PriceListProductsReader
     */
    private $reader;

    /**
     * @var FamilyReader
     */
    private $familyReader;

    /**
     * @var PriceListProductsService
     */
    private $service;

    /**
     * @var PriceListViewTransformer
     */
    private $transformer;

    /**
     * PriceListProductsController constructor.
     * @param PriceListReader $priceListReader
     * @param PriceListProductsReader $reader
     * @param FamilyReader $familyReader
     * @param PriceListProductsService $service
     * @param PriceListViewTransformer $transformer
     */
    public function __construct(
        PriceListReader $priceListReader,
        PriceListProductsReader $reader,
        FamilyReader $familyReader,
        PriceListProductsService $service,
        PriceListViewTransformer $transformer
    )
    {
        $this->priceListReader = $priceListReader;
        $this->reader = $reader;
        $this->familyReader = $familyReader;
        $this->service = $service;
        $this->transformer = $transformer;
    }

    /**
     * @OA\Patch(
     *     path="/price-lists/{price_list_id}/products/{id}",
     *     tags={"priceList"},
     *     security={
     *       {"api_key": {}}
     *     },
     *     @OA\Parameter(
     *         name="price_list_id",
     *         in="path",
     *         description="ID of Price List",
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of Price List Products",
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     summary="Update Price List products",
     *     @OA\RequestBody(
     *        request="GeneralGood",
     *        description="General good model for storing",
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/Update-PriceList-Product")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="data",
     *                  title="Data container",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/View-PriceList-Product")
     *              )
     *          ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     * )
     */
    /**
     * @return Response
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function updatePriceListProductAction()
    {
        $response = $this->getResponse();
        $data = $this->getRequestBody();
        $priceListId = $this->toIntFromRoute('price_list_id');
        $priceList = $this->priceListReader->getPriceListById($priceListId);
        if (!$priceList instanceof PriceListEntity) {
            return $this->prepareErrorResponse($response, 'Price list not found', null, Response::STATUS_CODE_404);
        }

        $priceListProductId = $this->toIntFromRoute('id');
        $priceListProduct = $this->reader->getProductsByIdInPriceList($priceList, $priceListProductId);
        if (!$priceListProduct instanceof PriceListProductEntity) {
            return $this->prepareErrorResponse($response, 'Price list product not found', null, Response::STATUS_CODE_404);
        }

        /**
         * @var $form PriceListProductForm
         */
        $form = $this->formPlugin()->getForm(PriceListProductForm::class);
        $form->setData($data);
        if(!$form->isValid()) {
            $msg = 'Validation error';
            $errArray = $form->getMessages();
            return $this->prepareErrorResponse($response, $msg, $errArray, Response::STATUS_CODE_400 );
        }
        /** @var $model PriceListProductModel */
        $model = $form->getData();
        $priceListProducts = $this->service->updateProduct($priceListProduct, $model);
        $data = $this->transformer->transformPriceListProductToViewModel($priceListProducts);
        return $this->prepareSuccessResponse($response, $data, [self::PRICE_LIST_PRODUCT_DETAILS, 'default']);
    }

    /**
     * @OA\Get(
     *     path="/price-lists/{id}/products/family/{family_id}",
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
     *     @OA\Parameter(
     *         name="family_id",
     *         in="path",
     *         description="ID of Family Products",
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     summary="Update Price List products",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="data",
     *                  title="Data container",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/View-PriceList-Product")
     *              )
     *          ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     * )
     */
    /**
     * @return Response
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function getProductsListAction()
    {
        $response = $this->getResponse();
        $params = $this->getQueryParams()->toArray();
        $priceListId = $this->toIntFromRoute('id');
        $priceList = $this->priceListReader->getPriceListById($priceListId);
        if(!$priceList instanceof PriceListEntity) {
            return $this->prepareErrorResponse($response, 'Price list not found', null, Response::STATUS_CODE_404);
        }
        $familyId = $this->toIntFromRoute('family_id');
        $family = $this->familyReader->find($familyId);
        if(!$family instanceof ProductFamilyEntity) {
            return $this->prepareErrorResponse($response, 'Family not found', null, Response::STATUS_CODE_404);
        }
        $paginator = $this->formPlugin()->getForm(PaginationForm::class);
        $sorting = $this->getRequest()->getQuery('sort');
        $paginator->setData($params);
        if(!$paginator->isValid()) {
            $msg = 'The form contains errors';
            $errArray = $paginator->getMessages();
            return $this->prepareErrorResponse($response, $msg, $errArray, Response::STATUS_CODE_400 );
        }
        /** @var $pageModel PaginationModel */
        $pageModel = $paginator->getData();
        $priceListProducts = $this->reader->findPriceListProducts($priceList, $family, $pageModel, $sorting);
        $data = $this->transformer->transformPriceListProductsToViewModel($priceListProducts);
        return $this->prepareSuccessPaginatedResponse($response, $data, $pageModel, [self::PRICE_LIST_PRODUCT_DETAILS, 'default']);
    }
}
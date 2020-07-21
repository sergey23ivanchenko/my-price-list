<?php


namespace PriceList\Controller\Api;


use Common\Api\Controller\AbstractApiController;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use PriceList\Entity\PriceListEntity;
use PriceList\Form\CreatePriceListForm;
use PriceList\Form\EditPriceListForm;
use PriceList\Model\PriceListModel;
use PriceList\Service\PriceListReader;
use PriceList\Service\PriceListService;
use PriceList\Service\PriceListViewTransformer;
use Runple\Devtools\Exception\CommonException;
use Runple\Devtools\Filter\FilterContainer;
use Runple\Devtools\Pagination\Form\PaginationForm;
use Runple\Devtools\Pagination\Model\PaginationModel;
use Zend\Http\Response;

/**
 * Class PriceListController
 * @package PriceList\Controller\Api
 */
class PriceListController extends AbstractApiController
{
    const PRICE_LIST_DETAILS = 'price_list_details';
    const PRICE_LIST_PRODUCT_DETAILS = 'price_list_product_details';

    /**
     * @var PaginationForm
     */
    private $paginator;

    /**
     * @var PriceListService
     */
    private $service;

    /**
     * @var PriceListReader
     */
    private $reader;

    /**
     * @var PriceListViewTransformer
     */
    private $transformer;

    /**
     * PriceListController constructor.
     * @param PaginationForm $paginator
     * @param PriceListService $service
     * @param PriceListReader $reader
     * @param PriceListViewTransformer $transformer
     */
    public function __construct(
        PaginationForm $paginator,
        PriceListService $service,
        PriceListReader $reader,
        PriceListViewTransformer $transformer
    )
    {
        $this->paginator = $paginator;
        $this->service = $service;
        $this->reader = $reader;
        $this->transformer = $transformer;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/price-lists",
     *     tags={"priceList"},
     *     security={
     *       {"api_key": {}}
     *     },
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Status Price List (published, inactive, deleted)",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *    @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page",
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Count of result per page",
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Sort by 'Created at' (?sort[created_at]=asc)",
     *         @OA\Schema(
     *             type="string",
     *             format="string"
     *         )
     *     ),
     * summary="All price lists list",
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
     *                  @OA\Items(ref="#/components/schemas/Price-list")
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
     * @throws \Common\Exceptions\CommonException
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws NonUniqueResultException
     * @throws \ReflectionException
     */
    public function allPriceListsAction()
    {
        $params = $this->getQueryParams()->toArray();
        $response = $this->getResponse();

        $filters = new FilterContainer($this->getRequest());
        $sorting = $this->getRequest()->getQuery('sort');

        $this->paginator->setData($params);
        if(!$this->paginator->isValid()) {
            $msg = 'The form contains errors';
            $errArray = $this->paginator->getMessages();
            return $this->prepareErrorResponse($response, $msg, $errArray, Response::STATUS_CODE_400 );
        }
        /** @var $pageModel PaginationModel */
        $pageModel = $this->paginator->getData();

        $priceLists = $this->reader->findAll($filters, $pageModel, $sorting);
        $data = $this->transformer->transformPriceListsToViewModel($priceLists);
        $count = $this->reader->countAll($filters);
        $pageModel->setCount($count);
        return $this->prepareSuccessPaginatedResponse($response, $data, $pageModel, [self::PRICE_LIST_DETAILS, 'default']);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/price-lists/counters",
     *     tags={"priceList"},
     *     security={
     *       {"api_key": {}}
     *     },
     *     summary="Counters price list",
     *     @OA\Response(
     *         response=200,
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="data",
     *                  title="Data container",
     *                  type="object",
     *                  @OA\Property(property="inactive", title="count of status", example="16"),
     *                  @OA\Property(property="deleted", title="count of status", example="1"),
     *                  @OA\Property(property="active", title="count of status", example="1"),
     *              ),
     *          ),
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    /**
     * @return Response
     * @throws \Exception
     */
    public function getCountersAction()
    {
        $response = $this->getResponse();
        $counters = $this->reader->countByStatuses();
        return $this->prepareSuccessResponse($response, $counters);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/price-lists/{id}",
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
     *     summary="Get Price List by Id",
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
     *                  @OA\Items(ref="#/components/schemas/Price-list")
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
     * @throws NonUniqueResultException
     */
    public function getPriceListAction()
    {
        $response = $this->getResponse();
        $id = $this->toIntFromRoute('id');
        $priceList = $this->reader->getPriceListById($id);
        if (!$priceList instanceof PriceListEntity) {
            return $this->prepareErrorResponse($response, 'Price List not found', null, Response::STATUS_CODE_404);
        }
        $data = $this->transformer->transformPriceList($priceList);
        return $this->prepareSuccessResponse($response, $data, [self::PRICE_LIST_DETAILS, self::PRICE_LIST_PRODUCT_DETAILS, 'default']);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/price-lists",
     *     tags={"priceList"},
     *     security={
     *       {"api_key": {}}
     *     },
     *     summary="Create new Price List",
     *     @OA\RequestBody(
     *        request="GeneralGood",
     *        description="General good model for storing",
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/PriceList-Create")
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
     *                  @OA\Items(ref="#/components/schemas/Price-list")
     *              )
     *          ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Fail",
     *          @OA\JsonContent(ref="#/components/schemas/ErrorsCatalog")
     *     )
     * )
     */
    /**
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \Common\Exceptions\CommonException
     * @throws \Exception
     */
    public function createPriceListAction ()
    {
        $response = $this->getResponse();
        $data = $this->getRequestBody();
        /**
         * @var $form CreatePriceListForm
         */
        $form = $this->formPlugin()->getForm(CreatePriceListForm::class);
        $form->setData($data);
        if(!$form->isValid()) {
            $msg = 'Validation error';
            $errArray = $form->getMessages();
            return $this->prepareErrorResponse($response, $msg, $errArray, Response::STATUS_CODE_400 );
        }
        /** @var $model PriceListModel */
        $model = $form->getData();
        $user = $this->getCurrentUser();
        $priceList = $this->service->createPriceListByType($model, $user);
        $responseModel = $this->transformer->transformPriceList($priceList);
        return $this->prepareSuccessResponse($response, $responseModel, [self::PRICE_LIST_DETAILS, self::PRICE_LIST_PRODUCT_DETAILS, 'default']);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/price-lists/{id}",
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
     *     summary="Update Price List",
     *     @OA\RequestBody(
     *        request="GeneralGood",
     *        description="General good model for storing",
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/PriceList-Update")
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
     *                  @OA\Items(ref="#/components/schemas/Price-list")
     *              )
     *          ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Fail",
     *          @OA\JsonContent(ref="#/components/schemas/ErrorsCatalog")
     *     )
     * )
     */
    /**
     * @return Response
     * @throws \Common\Exceptions\CommonException
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws CommonException
     * @throws \Exception
     */
    public function editPriceListAction()
    {
        $response = $this->getResponse();
        $data = $this->getRequestBody();
        $id = $this->toIntFromRoute('id');
        $entity =$this->reader->getPriceListById($id);
        if (!$entity instanceof PriceListEntity) {
            return $this->prepareErrorResponse($response, 'Price List not found', null, Response::STATUS_CODE_404 );
        }
        $form = $this->formPlugin()->getForm(EditPriceListForm::class);
        $form->setData($data);
        if(!$form->isValid()) {
            $msg = 'Validation error';
            $errArray = $form->getMessages();
            return $this->prepareErrorResponse($response, $msg, $errArray, Response::STATUS_CODE_400 );
        }
        /** @var $model PriceListModel */
        $model = $form->getData();
        $user = $this->getCurrentUser();
        $priceList = $this->service->editPriceList($entity, $model, $user);
        $responseModel = $this->transformer->transformPriceList($priceList);
        return $this->prepareSuccessResponse($response, $responseModel, [self::PRICE_LIST_DETAILS, self::PRICE_LIST_PRODUCT_DETAILS, 'default']);
    }
    /**
     * @OA\Patch(
     *     path="/api/v1/price-lists/status",
     *     tags={"priceList"},
     *     security={
     *       {"api_key": {}}
     *     },
     *     summary="Change Price List status",
     *     @OA\RequestBody(
     *        request="ChangeStatus",
     *        description="Change Price List Status",
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/Change-price-list-status")
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
     *                  @OA\Items(ref="#/components/schemas/Price-list")
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
     * @throws \Common\Exceptions\CommonException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function changeStatusAction()
    {
        $response = $this->getResponse();
        $ids =  $this->getRequestBody()['ids'] ?? null;
        $entities =$this->reader->getPriceListByIds($ids);
        $newStatus = $this->getRequestBody()['status'] ?? null;
        if(!$newStatus) {
            return $this->prepareErrorResponse($response, 'Price List status is required', null, Response::STATUS_CODE_400 );
        }
        $status = $this->service->changePriceListsStatus($entities, $newStatus);
        return $this->prepareSuccessResponse($response, $status);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/price-lists",
     *     tags={"priceList"},
     *     security={
     *       {"api_key": {}}
     *     },
     *     summary="Delete Price List",
     *     @OA\RequestBody(
     *        request="DeletePriceList",
     *        description="Delete Price List",
     *        required=true,
     *        @OA\JsonContent(
     *             @OA\Property(property="ids", title="Price List ids", type="array",
     *                  @OA\Items(
     *                      title="ids container",
     *                      type="integer",
     *                      example="12"
     *                   )),
     *        ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="data",
     *                  title="Data container",
     *                  type="boolean",
     *                  example = "true"
     *              )
     *          ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    /**
     * @return Response
     * @throws \Common\Exceptions\CommonException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deletePriceListsAction()
    {
        $response = $this->getResponse();
        $ids =  $this->getRequestBody()['ids'] ?? null;
        $entities =$this->reader->getPriceListByIds($ids);
        if(is_null($entities)) {
            return $this->prepareErrorResponse($response, 'Price Lists are not found', null, Response::STATUS_CODE_404 );
        }
        $remove = $this->service->deletePriceLists($entities);
        return $this->prepareSuccessResponse($response, $remove);
    }
}
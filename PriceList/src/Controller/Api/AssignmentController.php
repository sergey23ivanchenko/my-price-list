<?php


namespace PriceList\Controller\Api;


use CatalogManagement\Service\CatalogReader;
use Common\Api\Controller\AbstractApiController;
use PriceList\Entity\PriceListEntity;
use PriceList\Service\PriceListAssignment;
use PriceList\Service\PriceListReader;
use Zend\Http\Response;

class AssignmentController extends AbstractApiController
{

    /**
     * @var PriceListAssignment
     */
    private $assignmentService;

    /**
     * @var PriceListReader
     */
    private $reader;

    /**
     * @var CatalogReader
     */
    private $catalogReader;

    /**
     * AssignmentController constructor.
     * @param PriceListAssignment $assignmentService
     * @param PriceListReader $reader
     * @param CatalogReader $catalogReader
     */
    public function __construct(
        PriceListAssignment $assignmentService,
        PriceListReader $reader,
        CatalogReader $catalogReader
    )
    {
        $this->assignmentService = $assignmentService;
        $this->reader = $reader;
        $this->catalogReader = $catalogReader;
    }

    /**
     * @OA\Put(
     *     path="/api/v1/price-lists/{id}/catalogs",
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
     *     summary="Assign price to catalog",
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
     * @throws \Runple\Devtools\Exception\CommonException
     */
    public function assignCatalogsAction()
    {
        $response = $this->getResponse();
        $catalogIds = $this->getRequestBody()['catalogs'];
        $id = $this->toIntFromRoute('id');
        $priceList =$this->reader->getPriceListById($id);
        if (!$priceList instanceof PriceListEntity) {
            return $this->prepareErrorResponse($response, 'Price List not found', null, Response::STATUS_CODE_404 );
        }
        $catalogs = $this->catalogReader->getCatalogByIds($catalogIds);
        $this->assignmentService->assignCatalogsToPriceList($priceList, $catalogs);
        return $this->prepareSuccessResponse($response, $priceList);
    }
}

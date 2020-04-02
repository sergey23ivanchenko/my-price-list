<?php
/**
 * @OA\Schema(
 *     description="Change Price List Status",
 *     type="object",
 *     title="ChangeStatus",
 *     schema="Change-price-list-status",
 *     @OA\Property(property="ids", title="ids", type="array",
 *          @OA\Items(
 *              title="ids container",
 *              type="integer",
 *              example="15"
 *          )),
 *     @OA\Property(property="status", title="Status of catalog", example="published"),
 * )
 */
/**
 * @OA\Schema(
 *     description="Price List Entity",
 *     type="object",
 *     title="Price List",
 *     schema="Price-list",
 *     @OA\Property(property="id", title="Id of Price List", example="31"),
 *     @OA\Property(property="title", title="Title of Price List", example="Price List 1"),
 *     @OA\Property(property="description", title="Description of Price List", example="Description 1"),
 *     @OA\Property(property="status", title="Status of Price List", example="inactive"),
 *     @OA\Property(property="image", type="object", title="Image objects", @OA\Property(property="id", title="Id of image", example="2", format="int32", type="integer")),
 *     @OA\Property(
 *          property="price_list_goods",
 *          title="Price List Goods",
 *          type="object",
 *          ref="#/components/schemas/Catalog-Goods"
 *     ),
 *     @OA\Property(
 *          property="price_list_catalogs",
 *          title="Price List assign catalog",
 *          type="object",
 *          ref="#/components/schemas/Catalog"
 *     ),
 * )
 */
/**
 * @OA\Schema(
 *     description="Price List - model for creating Price List",
 *     type="object",
 *     title="Price List",
 *     schema="PriceList-Create",
 *     @OA\Property(property="title", title="Title of price list", example="Price List 1"),
 *     @OA\Property(property="description", title="Description of price list", example="Description 1"),
 *     @OA\Property(property="image", type="object", title="Image objects", @OA\Property(property="id", title="Id of image", example="2", format="int32", type="integer")),
 *     @OA\Property(property="price_list_goods",
 *          title="Catalog goods array",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/Add-PriceLists-goods"),
 *      ),
 * )
 */
/**
 * @OA\Schema(
 *     description="Add price list goods",
 *     type="object",
 *     title="Add price list goods",
 *     schema="Add-PriceLists-goods",
 *     @OA\Property(
 *          property="product",
 *          title="Price List product",
 *          type="object",
 *          ref="#/components/schemas/Add-PriceLists-products"
 *     ),
 *     @OA\Property(property="price", type="int", example="23"),
 * )
 */
/**
 * @OA\Schema(
 *     description="Add price list product",
 *     type="object",
 *     title="Add price list product",
 *     schema="Add-PriceLists-products",
 *      @OA\Property(property="id", type="int", example="11"),
 * )
 */
/**
 * @OA\Schema(
 *     description="Price list Assign Catalog",
 *     type="object",
 *     title="Price list Assign",
 *     schema="Catalog-Assign",
 *      @OA\Property(property="id", type="int", example="11"),
 * )
 */
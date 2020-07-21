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
 *     @OA\Property(property="status", title="Status of catalog", example="inactive"),
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
 *     @OA\Property(property="manager", title="Manager", type="object", ref="#/components/schemas/Users-UserRO"),
 *     @OA\Property(property="image", title="Image", type="object", ref="#/components/schemas/File-Image"),
 * )
 */
/**
 * @OA\Schema(
 *     description="Price List - model for creating Price List",
 *     type="object",
 *     title="Price List",
 *     schema="PriceList-Create",
 *     @OA\Property(property="product_type", title="Product type", example="goods"),
 *     @OA\Property(property="title", title="Title of price list", example="Price List 1"),
 *     @OA\Property(property="description", title="Description of price list", example="Description 1"),
 *     @OA\Property(property="image", type="object", title="Image objects", @OA\Property(property="id", title="Id of image", example="2", format="int32", type="integer")),
 * )
 */
/**
 * @OA\Schema(
 *     description="Price List - model for update price list",
 *     type="object",
 *     title="Price List",
 *     schema="PriceList-Update",
 *     @OA\Property(property="title", title="Title of price list", example="Price List 1"),
 *     @OA\Property(property="description", title="Description of price list", example="Description 1"),
 *     @OA\Property(property="image", type="object", title="Image objects", @OA\Property(property="id", title="Id of image", example="2", format="int32", type="integer")),
 * )
 */
/**
 * @OA\Schema(
 *     description="Update Price list product",
 *     type="object",
 *     title="Price List Product",
 *     schema="Update-PriceList-Product",
 *     @OA\Property(property="net_price", title="Net price", example=10),
 *     @OA\Property(property="gross_price", title="Gross price", example=11),
 *     @OA\Property(property="vat", type="object", title="VAT", example=20),
 * )
 */
/**
 * @OA\Schema(
 *     description="View Price list product",
 *     type="object",
 *     title="Price List Product",
 *     schema="View-PriceList-Product",
 *     @OA\Property(property="id", title="NId of price list product", example=1),
 *     @OA\Property(property="product_id", title="NId of price list product", example=1),
 *     @OA\Property(property="runple_id", title="NId of price list product", example="GDS-191209-013"),
 *     @OA\Property(property="name", title="NId of price list product", example="Apple iPhone X"),
 *     @OA\Property(property="net_price", title="Net price", example=10),
 *     @OA\Property(property="gross_price", title="Gross price", example=11),
 *     @OA\Property(property="vat", type="object", title="VAT", example=20),
 *     @OA\Property(property="remark", type="object", title="Remark", example="Remark here"),
 *     @OA\Property(property="category", type="object", title="Category", example="Video Game Console"),
 * )
 */
/**
 * @OA\Schema(
 *     description="Add price list product",
 *     type="object",
 *     title="Add price list product",
 *     schema="Add-PriceLists-product",
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
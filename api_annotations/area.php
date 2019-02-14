<?php
/**
 *
 * @OA\Get(
 *     tags={"包"},
 *     summary="地区列表",
 *     path="/api/package/areas",
 *     @OA\Parameter(
 *         name="parent_id",
 *         in="query",
 *         description="父ID，不填获取省级列表",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *     @OA\Response(
 *          response="200",
 *          description="成功",
 *          @OA\MediaType(
 *             mediaType="application/json",
 *              @OA\Schema(
 *                  type="object",
 *                  @OA\Property(
 *                      property="data",
 *                      type="array",
 *                      @OA\Items(ref="#/components/schemas/Area"),
 *                      description="列表"
 *                  ),
 *              )
 *          )
 *      )
 * )
 *
 *  * @OA\Get(
 *     tags={"包"},
 *     summary="地区列表所有值",
 *     path="/api/package/areas/all",
 *     @OA\Response(
 *          response="200",
 *          description="成功",
 *          @OA\MediaType(
 *             mediaType="application/json",
 *              @OA\Schema(
 *                  type="object",
 *                  @OA\Property(
 *                      property="data",
 *                      type="array",
 *                      @OA\Items(ref="#/components/schemas/Area"),
 *                      description="列表"
 *                  ),
 *              )
 *          )
 *      )
 * )
 */


/**
 *
 * @OA\Schema(
 *     description="地区列表",
 *     type="object",
 *     schema="Area",
 *     @OA\Property(property="id", type="integer", description="ID"),
 *     @OA\Property(property="name", type="string", description="名称"),
 *     @OA\Property(property="depth", type="integer", description="区域级别"),
 *     @OA\Property(property="parent_id", type="integer", description="父ID"),
 *     @OA\Property(property="children", type="array", description="子地区", @OA\Items(ref="#/components/schemas/Area"))
 * )
 */
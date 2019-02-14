<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/15
 * Time: 9:47
 */
namespace SMG\Area;

use Modules\Base\Transformers\BaseTransformer;

class AreaTransformer extends BaseTransformer
{
    public function transform(AreaModel $area)
    {
        return [
            'id' => $area->id,
            'name' => $area->name,
            'depth' => $area->depth,
            'parent_id' => $area->parent_id ?? 0,
        ];
    }
}

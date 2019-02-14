<?php

namespace Veikejin\Area;

class ApiController extends \Modules\Base\Http\Controllers\Api\ApiController
{
    public function index(AreaModel $area)
    {
        return $this->response->collection(
            $area->getAreas(request('parent_id', false)),
            new AreaTransformer()
        );
    }

    public function all()
    {
        if (request('type', false) == 'js') {
            $data = \Cache::rememberForever('areas_all_new5', function () {
                return AreaModel::get(['id', 'name', 'parent_id', '_lft','_rgt', 'code'])->groupBy('parent_id')->toArray();
            });
            $data_name = request('data_name', 'areas');

            return "window.{$data_name}=".json_encode($data).';';
        } else {
            $data = \Cache::rememberForever('areas_all_new4', function () {
                return AreaModel::get(['id', 'name', 'parent_id', '_lft','_rgt', 'code'])->toTree()->toArray();
            });
        }

        return $this->response->array(['data' => $data]);
    }
}

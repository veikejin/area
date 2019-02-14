<?php

namespace Veikejin\Area;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class AreaModel extends Model
{
    use NodeTrait;

    protected $fillable = ['name', 'disabled', 'parent_id'];

    /**
     * Settings constructor.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnection(config('admin.database.connection') ?: config('database.default'));

        $this->setTable(config('admin.extensions.config.table', 'area'));
    }

    /**
     * @param $query
     *
     * @return QueryBuilder
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('disabled', 0);
    }

    /**
     * 返回包含有从顶级到当前级的字符串组合
     *
     * @param string $implode
     *
     * @return mixed|string
     */
    public function getArea($implode = '')
    {
        if (!$this->id) {
            throw \Exception('需要实例化的类');
        }

        if (!$this->parent_id) {
            return $this->name;
        }

        return implode($implode, $this->ancestors->pluck('name')->toArray()).$implode.$this->name;
    }

    /**
     * 返回当前区域的ID&父ID,以便供选择的时候设置默认选项
     *
     * @param string $implode
     *
     * @return mixed|string
     */
    public function getAreaIds($implode = ',')
    {
        if (!$this->id) {
            throw \Exception('需要实例化的类');
        }

        if (!$this->parent_id) {
            return $this->id;
        }

        return implode($implode, $this->ancestors->pluck('id')->toArray()).$implode.$this->id;
    }

    /**
     * 返回地区列表
     *
     * @param null $parent_id
     *
     * @return mixed
     */
    public function getAreas($parent_id = null)
    {
        $area = $this->notDeleted()->withDepth();
        if ($parent_id) {
            $area->where('parent_id', $parent_id);
        } else {
            $area->whereNull('parent_id');
        }

        return $area->get();
    }

    public static function boot()
    {
        static::creating(function ($model){
            $level = 0;
            if ($model->parent_id) {
                $level = static::withDepth()->whereKey($model->parent_id)->value('depth')+1;
                $query = static::where('parent_id',$model->parent_id);
            } else {
                $query = static::whereNull('parent_id');
            }

            $step = pow(10, (3-$level)*2);

            $parent_code = 0;
            if ($model->parent_id) {
                $parent_code = intval(static::whereKey($model->parent_id)->value('code'));
            } else {
                $parent_code = 10000000;
            }

            //按每级 100 个计算
            $ids = (clone $query)->pluck('code')->toArray();//同级下已用code
            $ids2 = range($parent_code + 1*$step, $parent_code + 99*$step, $step );//同级下所有code
            $diff = array_diff($ids2, $ids);//取出第一个可用的
            $code = array_first($diff);

            /*
            $prev = (clone $query)->max('code');
            //第一个
            if (!$prev && $model->parent_id) {
                $prev = $parent_code;
            }

            if (($prev + $step) > ($parent_code+100*$step) ) {
                $ids = (clone $query)->pluck('code')->toArray();//同级下所有code
                $ids2 = range(1*$step, 99*$step, $step );//同级下所有可用code
                $diff = array_diff($ids2, $ids);//取出一个没有用到的
                $code = array_first($diff);
            } else {
                $code = $prev + $step;
            }
            */

            $model->code=$code;

        });
        parent::boot(); // TODO: Change the autogenerated stub
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/20
 * Time: 10:02
 */
namespace Veikejin\Area;

trait HaveArea
{
    public function area()
    {
        return $this->belongsTo('Veikejin\Area\AreaModel');
    }

    public function getAreaIdsAttribute()
    {
        if (!$this->area) {
            return null;
        }

        return $this->area->getAreaIds();
    }

    public function getAreaStrAttribute()
    {
        if (!$this->area) {
            return null;
        }

        return $this->area->getArea();
    }
}

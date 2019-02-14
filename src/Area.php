<?php

namespace Veikejin\Area;

use Encore\Admin\Admin;
use Encore\Admin\Extension;

class Area extends Extension
{
    /**
     * @return void
     */
    public static function load()
    {
        //
    }

    /**
     * Bootstrap this package.
     *
     * @return void
     */
    public static function boot()
    {
        static::registerRoutes();
        Admin::extend('area', __CLASS__);
    }

    /**
     * Register routes for laravel-admin.
     *
     * @return void
     */
    protected static function registerRoutes()
    {
        parent::routes(
            function ($router) {
                /* @var \Illuminate\Routing\Router $router */
                $router->resource(
                    config('admin.extensions.area.name', 'area'),
                    config('admin.extensions.area.controller', 'Veikejin\Area\AreaController')
                );
            }
        );

        $api = app('Dingo\Api\Routing\Router');

        $api->version(
            'v1',
            array_merge(
                [
                    'prefix' => 'api/package',
                ],
                config('api.defaultSetting', [])
            ),
            function ($api) {
                $api->get('/areas', '\Veikejin\Area\ApiController@index')->name('api.package.area.home');
                $api->get('/areas/all', '\Veikejin\Area\ApiController@all')->name('api.package.area.all');
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function import()
    {
        parent::createMenu('地区管理', 'area', 'fa-sitemap', 2);

        parent::createPermission('地区管理', 'ext.area', 'area*');
    }
}

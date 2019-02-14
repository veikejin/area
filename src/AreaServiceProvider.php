<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/27
 * Time: 10:29
 */
namespace VEIKEJIN\Area;

use Illuminate\Support\ServiceProvider;

class AreaServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
        Area::boot();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
        //
    }
}

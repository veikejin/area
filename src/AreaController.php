<?php

namespace Veikejin\Area;

use Veikejin\Admin\Controllers\ModelForm;
use Veikejin\Admin\Facades\Admin;
use Veikejin\Admin\Form;
use Veikejin\Admin\Grid;
use Veikejin\Admin\Layout\Content;

class AreaController
{
    use ModelForm;

    public $depthAddLabel = ['市','区\\县','街道\\镇'];

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(
            function (Content $content) {
                $header = ($parent = $this->getCurrentParent()) ? $parent->name.' 管理' : '省列表';
                $content->header($header);
                $content->description('地区的增删改查..');

                $content->body($this->grid());
            }
        );
    }

    /**
     * Edit interface.
     *
     * @param $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(
            function (Content $content) use ($id) {
                $content->header('编辑');
                $content->description('');

                $content->body($this->form()->edit($id));
            }
        );
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(
            function (Content $content) {
                $title = ($parent = $this->getCurrentParent()) ? $parent->name : '添加';
                $content->header($title);
                //$content->description('description');

                $content->body($this->form());
            }
        );
    }

    public function grid()
    {
        return Admin::grid(
            AreaModel::class,
            function (Grid $grid) {
                $parent = $this->getCurrentParent();
                if ($parent) {
                    $grid->model()->where('parent_id', $parent->id);
                } else {
                    $grid->model()->whereNull('parent_id');
                }

                $grid->id('ID')->sortable();
                $grid->name('名称')->display(
                    function ($name) {
                        return "<a  class=\"btn btn-xs btn-twitter\" href='?parent_id=".$this->id."'>$name</a>";
                    }
                );
                $grid->column('code', '编号');
                $grid->column('disabled', '是否禁用')->editable('select', ['启用', '禁用']);

                $grid->disableRowSelector();
                $grid->disableCreateButton();
                $grid->disableExport();
                $grid->disableFilter();
                $grid->disablePagination();

                $grid->tools(
                    function ($tools) use ($parent, $grid) {
                        $linkParameter = $parent ? '?parent_id='.$parent->id : '';
                        $tools->append(
                            $this->getGridLinkTool(
                                $grid,
                                ['title' => '添加', 'class' => 'btn-success', 'link' => route('area.create').$linkParameter]
                            )
                        );

                        if ($parent) {
                            $linkParameter = $parent->parent_id ? '?parent_id='.$parent->parent_id : '';
                            $tools->append(
                                $this->getGridLinkTool(
                                    $grid,
                                    ['title' => '返回', 'class' => 'btn-primary', 'icon' => 'fa-mail-reply', 'link' => route('area.index').$linkParameter]
                                )
                            );
                        }
                    }
                );
            }
        );
    }

    public function form()
    {
        return Admin::form(
            AreaModel::class,
            function (Form $form) {
                $label = ($parent = $this->getCurrentParent()) ? $this->depthAddLabel[$parent->depth] : '省';
                $form->display('id', 'ID');
                $form->text('name', $label)->rules('required');
                if ($parent) {
                    $form->hidden('parent_id')->default($parent->id);
                }
                $form->radio('disabled', '禁用?')->options(['否', '是'])->default(0);
            }
        );
    }

    protected function getCurrentParent()
    {
        static $parent = null;

        if ($parent === null) {
            if ($parent_id = request('parent_id', false)) {
                $parent = AreaModel::withDepth()->findOrFail($parent_id);

                return $parent;
            } else {
                $parent = false;
            }
        }

        return $parent;
    }

    protected function getGridLinkTool($grid, $config)
    {
        return new class($grid, $config) extends \Encore\Admin\Grid\Tools\AbstractTool {
            protected $config;

            public function __construct(Grid $grid, $config = [])
            {
                $this->grid = $grid;
                $this->config = $config;
            }

            public function render()
            {
                $config = $this->config;
                $icon = isset($config['icon']) ? "<i class=\"fa {$config['icon']}\"></i>&nbsp;&nbsp;" : '';

                return <<<EOT

<div class="btn-group pull-right" style="margin-right: 10px">
    <a href="{$config['link']}" class="btn btn-sm {$config['class']}">
        {$icon}{$config['title']}
    </a>
</div>

EOT;
            }
        };
    }
}

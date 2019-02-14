<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCodeToAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('area', function (Blueprint $table) {
            $table->unsignedInteger('code')->unique()->nullable()->comment('编号');

        });

        $list = \SMG\Area\AreaModel::get(['id', 'name', 'parent_id', '_lft','_rgt'])->toTree();

        $start = 10000000;
        $i = 1;
        $level = 1;
        foreach ($list as $one) {
            $code = $start + $i*1000000;
            $this->updateCode($one,$code, $level);
            $i++;
        }
    }

    public function updateCode($cur,$code, $level=1)
    {
        $cur->code = $code;
        $cur->save();
        if ($cur->children) {
            $i = 1;
            $level++;
            foreach ($cur->children as $one) {
                $new_code = $code + $i*pow(10, (4-$level)*2);
                $this->updateCode($one, $new_code, $level);
                $i++;
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}

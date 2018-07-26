<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_files', function(Blueprint $table){
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'innodb';
            $table->increments('id')->comment('主键Id');
            $table->unsignedInteger('user_id')->comment('用户Id');
            $table->unsignedInteger('file_id')->comment('文件Id');
            $table->unsignedSmallInteger('times')->comment('文件引用次数');
            $table->unsignedInteger('create_time')->comment('创建时间');
            $table->unsignedInteger('update_time')->comment('更新时间');
            $table->unique(['user_id', 'file_id'], 'UK_user_id_file_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_files');
    }
}

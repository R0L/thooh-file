<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function(Blueprint $table){
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'myisam';

            $table->increments('id')->comment('文件ID');
            $table->char('name', 30)->default('')->comment('原始文件名');
            $table->char('savename', 20)->default('')->comment('保存名称');
            $table->char('savepath', 30)->default('')->comment('文件保存路径');
            $table->char('ext', 5)->default('')->comment('文件后缀');
            $table->char('mime', 40)->default('')->comment('文件mime类型');
            $table->unsignedInteger('size')->comment('文件大小');
            $table->char('md5', 32)->default('')->comment('文件md5');
            $table->char('sha1', 40)->default('')->comment('文件 sha1编码');
            $table->unsignedSmallInteger('location')->comment('文件保存位置');
            $table->unsignedInteger('create_time')->comment('创建时间');
            $table->unique(['md5', 'sha1'], 'UK_md5_sha1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}

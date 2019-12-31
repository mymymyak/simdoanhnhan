<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_blog', function (Blueprint $table) {
            $table->increments('blog_id');
            $table->string('blog_title');
            $table->text('blog_des');
            $table->text('blog_content');
            $table->string('blog_img',60);
            $table->integer('blog_cat_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_blog');
    }
}

<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class BlogTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('table_blog_cat')->insert(
                ['blog_cat_title' => str_random(10)],
                ['blog_cat_title' => str_random(10)]);
    }
}

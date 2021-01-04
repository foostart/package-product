<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('product_id');
            $table->integer('user_id')->length(11)->unsigned(); 
            $table->string('product_name', 500);
            $table->string('product_overview', 1000);
            $table->longText('product_description');
            $table->string('product_image', 255);
            $table->string('product_images', 255)->nullable();
            $table->float('product_price_root', 8, 2); 
            $table->float('product_price', 8, 2); 
            $table->float('product_price_sale', 8, 2);
            $table->integer('category_id')->length(11)->unsigned()->nullable(); 
            $table->text('cache_comments')->nullable();
            $table->text('cache_other_products')->nullable(); 
            $table->tinyInteger('product_status')->length(4);
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
        Schema::dropIfExists('products');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveFiltersWholeSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('product_filter_boxes');
        Schema::dropIfExists('product_filter_box_answers');
        Schema::dropIfExists('partner_products');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('partner_images');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_filter_settings');
        Schema::dropIfExists('serie_products');

        Schema::dropIfExists('customer_profile_products');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

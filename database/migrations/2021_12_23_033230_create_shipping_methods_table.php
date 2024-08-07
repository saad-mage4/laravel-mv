<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id');
            $table->string('title')->nullable();
            $table->double('fee')->default(0);
            $table->integer('is_free')->default(0);
            $table->integer('status ')->default(1);
            $table->text('description ')->nullable();
            $table->double('minimum_order')->default(0);
            $table->tinyInteger('super_admin_status')->default(0);
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
        Schema::dropIfExists('shipping_methods');
    }
}

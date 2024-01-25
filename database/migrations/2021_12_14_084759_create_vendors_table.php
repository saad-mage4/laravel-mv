<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->double('total_amount')->default(0);
            $table->string('banner_image')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('shop_name')->nullable();
            $table->string('slug')->nullable();
            $table->string('open_at')->nullable();
            $table->string('closed_at')->nullable();
            $table->string('address')->nullable();
            $table->text('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('description')->nullable();
            $table->text('greeting_msg')->nullable();
            $table->integer('status')->default(0);
            $table->integer('is_featured')->default(0);
            $table->integer('top_rated')->default(0);
            $table->string('verified_token')->nullable();
            $table->integer('is_verified')->default(0);
            $table->string('nic_front_image')->nullable();
            $table->string('nic_back_image')->nullable();
            $table->string('pdf')->nullable();
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
        Schema::dropIfExists('vendors');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSponsorshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sponsorships', function (Blueprint $table) {
            $table->id();
            $table->string('banner_position')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('price')->nullable();
            $table->string('days')->nullable();
            $table->string('is_booked')->nullable();
            $table->string('image_url')->nullable();
            $table->string('banner_redirect')->nullable();
            $table->string('sponsor_user_id')->nullable();
            $table->string('sponsor_name')->nullable();
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
        Schema::dropIfExists('sponsorships');
    }
}

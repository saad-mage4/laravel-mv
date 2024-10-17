<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('seller_id');
            $table->date('available_date')->nullable();
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0.00);
            $table->enum('status', ['requested', 'in_review', 'pending', 'available'])->default('requested');
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
        Schema::dropIfExists('withdraw_schedules');
    }
}

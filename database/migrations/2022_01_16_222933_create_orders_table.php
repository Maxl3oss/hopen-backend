<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_key');
            $table->string('order_qty');
            $table->string('order_total');
            $table->string('order_paytype');
            $table->string('order_image')->nullable();
            $table->unsignedBigInteger('user_id')->comment('Created By User');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('order_status', 15);
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
        Schema::dropIfExists('orders');

    }
}

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
            $table->string("full_name");
            $table->string("phone_number");
            $table->string("shipping_address");
            $table->string("card_number");
            $table->string("name_on_card");
            $table->string("exp_date");
            $table->string("cvv");
            $table->integer("total")->default(0);
            $table->string("order_placed");
            $table->string("status")->default("Invalid");
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

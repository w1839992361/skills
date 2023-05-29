<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string("edited_url")->nullable();
            $table->string("original_url")->nullable();
            $table->string("framed_url")->nullable();
            $table->foreignIdFor(\App\Models\Frame::class)->nullable()->constrained();
            $table->foreignIdFor(\App\Models\Size::class)->constrained();
            $table->integer("user_id");
            $table->foreignIdFor(\App\Models\Order::class)->nullable()->constrained();
            $table->string("status");
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
        Schema::dropIfExists('photos');
    }
}

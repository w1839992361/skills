<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFramesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frames', function (Blueprint $table) {
            $table->id();
            $table->string("url");
            $table->integer("price");
            $table->string("name");
//            $table->foreignIdFor(\App\Models\Size::class)->constrained()->cascadeOnDelete(); // 并定义了级联删除操作。当 sizes 表中的一条记录被删除时，关联的 frames 表中的所有记录也会被自动删除，从而保证数据的完整性和一致性。
            $table->foreignIdFor(\App\Models\Size::class)->constrained();
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
        Schema::dropIfExists('frames');
    }
}

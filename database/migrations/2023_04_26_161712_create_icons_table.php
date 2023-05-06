<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('icons'))
        {
        Schema::create('icons', function (Blueprint $table) {
            $table->id();
            $table->string('icon_type');
            $table->string('icon_name');
            $table->timestamps();
        });
    }
    }
    //changes
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('icons');
    }
};

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
        if (!Schema::hasTable('device_logins'))
        {
        Schema::create('device_logins', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('loggged_id');
            $table->foreignId('login_id')->constrained()->cascadeOnDelete();
            $table->string('ip_address', 45);
            $table->text('device');
            $table->text('os');
            $table->text('browser_agent');
            $table->text('user_agent');
        });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_logins');
    }
};

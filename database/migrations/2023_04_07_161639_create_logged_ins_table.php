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
        if (!Schema::hasTable('logins'))
        {
            Schema::create('logins', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('loginable_id');
                $table->string('loginable_type');
                $table->DateTime('login_at');
                $table->string('user_agent')->nullable();
                $table->index(['loginable_id', 'loginable_type']);
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
        Schema::dropIfExists('logins');
    }
};

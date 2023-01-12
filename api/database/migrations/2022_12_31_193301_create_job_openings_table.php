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
        Schema::create('job_openings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('principal_id')->nullable();
            $table->integer('job_order_id')->nullable();
            $table->integer('position_id')->nullable();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->date('date_pusblish')->nullable();
            $table->date('date_expiry')->nullable();
            $table->enum('status', ['Publish','Draft','Inactive'])->nullable();
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
        Schema::dropIfExists('job_openings');
    }
};

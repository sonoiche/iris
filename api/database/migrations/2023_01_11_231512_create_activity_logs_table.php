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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('username')->nullable();
            $table->enum('activity_type', ['access','crud'])->nullable();
            $table->string('applicant_id')->nullable();
            $table->string('applicant_name')->nullable();
            $table->enum('module', ['personal','education','employment','licenses','skills','reference','lineup','training','medical','document','payment'])->nullable();
            $table->enum('user_action', ['create','update','delete'])->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
};

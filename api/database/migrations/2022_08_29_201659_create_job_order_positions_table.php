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
        Schema::create('job_order_positions', function (Blueprint $table) {
            $table->id();
            $table->integer('job_order_id');
            $table->string('position_title')->nullable();
            $table->integer('number_of_male')->nullable()->default(0);
            $table->integer('number_of_female')->nullable()->default(0);
            $table->boolean('any_gender')->nullable();
            $table->integer('total_number')->nullable()->default(0);
            $table->string('propose_salary')->nullable();
            $table->string('propose_food_allowance')->nullable();
            $table->text('job_description')->nullable();
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
        Schema::dropIfExists('job_order_positions');
    }
};

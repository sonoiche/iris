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
        Schema::create('processings', function (Blueprint $table) {
            $table->id();
            $table->string('applicant_id', 20);
            $table->string('employer')->nullable();
            $table->integer('principal_id')->nullable();
            $table->string('salary')->nullable();
            $table->enum('direct_hire', ['yes','no'])->nullable();
            $table->string('worksite')->nullable();
            $table->integer('country_id')->nullable();
            $table->string('job_order_number')->nullable();
            $table->date('date_endorse')->nullable();
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
        Schema::dropIfExists('processings');
    }
};

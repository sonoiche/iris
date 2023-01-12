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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('applicant_id', 20);
            $table->string('title')->nullable();
            $table->string('provider')->nullable();
            $table->string('certificate_number')->nullable();
            $table->string('place_issue')->nullable();
            $table->date('date_issue')->nullable();
            $table->date('date_expiry')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('trainings');
    }
};

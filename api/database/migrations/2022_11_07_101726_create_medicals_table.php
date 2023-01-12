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
        Schema::create('medicals', function (Blueprint $table) {
            $table->id();
            $table->string('applicant_id', 20);
            $table->integer('clinic_id')->nullable();
            $table->date('date_referred')->nullable();
            $table->date('date_taken')->nullable();
            $table->date('date_result')->nullable();
            $table->date('date_expiry')->nullable();
            $table->enum('status', ['Fit','Unfit','Pending'])->nullable();
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
        Schema::dropIfExists('medicals');
    }
};

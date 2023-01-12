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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('applicant_number')->nullable();
            $table->date('date_applied')->nullable();
            $table->string('fname')->nullable();
            $table->string('mname')->nullable();
            $table->string('lname')->nullable();
            $table->string('suffix')->nullable();
            $table->string('email')->nullable();
            $table->string('landline')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('alt_mobile_number')->nullable();
            $table->date('birthdate')->nullable();
            $table->enum('gender', ['Male','Female'])->nullable();
            $table->string('birthplace')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('language_spoken')->nullable();
            $table->string('keywords')->nullable();
            $table->string('resume')->nullable();
            $table->text('position_applied')->nullable();
            $table->string('photo')->nullable();
            $table->decimal('expected_salary', 10, 2)->nullable();
            $table->enum('availability', ['Immediate','1 Week','2 Weeks','3 Weeks','1 Month','2 Months'])->nullable();
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
        Schema::dropIfExists('applicants');
    }
};

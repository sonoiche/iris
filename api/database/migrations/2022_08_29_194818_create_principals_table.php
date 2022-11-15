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
        Schema::create('principals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('logo')->nullable();
            $table->enum('status', ['Active','Inactive','Prospect'])->nullable();
            $table->string('address')->nullable();
            $table->string('website')->nullable();
            $table->string('landline')->nullable();
            $table->string('mobile_number')->nullable();
            $table->integer('country_id')->nullable();
            $table->string('accreditation_number')->nullable();
            $table->date('date_issue')->nullable();
            $table->date('date_expiry')->nullable();
            $table->integer('industry_id')->nullable();
            $table->text('assigned_users')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('principals');
    }
};

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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('applicant_id', 20);
            $table->string('name')->nullable();
            $table->integer('document_type_id')->nullable();
            $table->string('attachment')->nullable();
            $table->string('document_number')->nullable();
            $table->string('place_issue')->nullable();
            $table->date('date_issue')->nullable();
            $table->date('date_expiry')->nullable();
            $table->date('date_submitted')->nullable();
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
        Schema::dropIfExists('documents');
    }
};

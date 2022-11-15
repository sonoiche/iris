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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->integer('principal_id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('position')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('skype_username')->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('main_contact')->default(false)->nullable();
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
        Schema::dropIfExists('contacts');
    }
};

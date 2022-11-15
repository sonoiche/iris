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
        Schema::create('job_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('principal_id');
            $table->string('job_order_number')->nullable();
            $table->date('date_receive')->nullable();
            $table->date('date_needed')->nullable();
            $table->date('date_expiry')->nullable();
            $table->enum('status', ['Active','Inactive'])->nullable();
            $table->enum('job_type', ['Local','International'])->nullable();
            $table->text('assigned_users')->nullable();
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
        Schema::dropIfExists('job_orders');
    }
};

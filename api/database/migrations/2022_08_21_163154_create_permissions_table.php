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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->integer('role_id');
            $table->string('name')->nullable();
            $table->boolean('read')->nullable()->default(false);
            $table->boolean('write')->nullable()->default(false);
            $table->boolean('delete')->nullable()->default(false);
            $table->boolean('can_read')->nullable()->default(false);
            $table->boolean('can_write')->nullable()->default(false);
            $table->boolean('can_delete')->nullable()->default(false);
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
        Schema::dropIfExists('permissions');
    }
};

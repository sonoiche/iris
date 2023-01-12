<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->string('agency_name')->nullable();
            $table->string('agency_website')->nullable();
            $table->string('address')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('logo')->nullable();
            $table->integer('medical_day')->nullable();
            $table->integer('accreditation_day')->nullable();
            $table->integer('manpower_request_day')->nullable();
            $table->integer('processing_documents_day')->nullable();
            $table->string('sender_name')->nullable();
            $table->string('sender_email')->nullable();
            $table->text('signature')->nullable();
            $table->boolean('mr_notify_user')->nullable()->default(false);
            $table->string('mr_subject')->nullable();
            $table->text('mr_template')->nullable();
            $table->timestamps();
        });

        DB::insert('insert into configurations (id, agency_name, created_at, updated_at) values (?, ?, ?, ?)', [
            1, 'CJSeven Inc', Carbon::now(), Carbon::now()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configurations');
    }
};

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
        Schema::table('applicants', function (Blueprint $table) {
            $table->integer('source_id')->nullable()->after('resume');
            $table->string('height')->nullable()->after('availability');
            $table->string('weight')->nullable()->after('height');
            $table->string('marital_status')->nullable()->after('weight');
            $table->integer('nationality_id')->nullable()->after('marital_status');
            $table->string('religion')->nullable()->after('nationality_id');
            $table->string('passport_number')->nullable()->after('religion');
            $table->string('passport_place_issued')->nullable()->after('passport_number');
            $table->string('passport_file')->nullable()->after('passport_place_issued');
            $table->date('passport_date_submitted')->nullable()->after('passport_file');
            $table->date('passport_date_issued')->nullable()->after('passport_date_submitted');
            $table->date('passport_date_expiry')->nullable()->after('passport_date_issued');
            $table->string('sss_number')->nullable()->after('passport_date_expiry');
            $table->string('tin_number')->nullable()->after('sss_number');
            $table->string('philhealth')->nullable()->after('tin_number');
            $table->string('pagibig')->nullable()->after('philhealth');
            $table->text('remarks')->nullable()->after('pagibig');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn('source_id');
            $table->dropColumn('height');
            $table->dropColumn('weight');
            $table->dropColumn('marital_status');
            $table->dropColumn('nationality_id');
            $table->dropColumn('religion');
            $table->dropColumn('passport_number');
            $table->dropColumn('passport_place_issued');
            $table->dropColumn('passport_file');
            $table->dropColumn('passport_date_submitted');
            $table->dropColumn('passport_date_issued');
            $table->dropColumn('passport_date_expiry');
            $table->dropColumn('sss_number');
            $table->dropColumn('tin_number');
            $table->dropColumn('philhealth');
            $table->dropColumn('pagibig');
            $table->dropColumn('remarks');
        });
    }
};

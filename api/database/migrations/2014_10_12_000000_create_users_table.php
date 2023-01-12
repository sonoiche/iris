<?php

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fname')->nullable();
            $table->string('mname')->nullable();
            $table->string('lname')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->dateTime('two_factor_until')->nullable();
            $table->boolean('sms_authentication')->nullable();
            $table->string('sms_auth_number')->nullable();
            $table->string('sms_code', 10)->nullable();
            $table->string('contact_number')->nullable();
            $table->string('photo')->nullable();
            $table->integer('role_id')->nullable();
            $table->enum('status', ['Active','Inactive'])->nullable();
            $table->text('email_signature')->nullable();
            $table->text('access_token')->nullable();
            $table->text('invite_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::insert('insert into users (id, fname, lname, email, password, status, role_id) values (?, ?, ?, ?, ?, ?, ?)', [
            1, 'Rochelle', 'Lanto', 'rochellelanto27@gmail.com', bcrypt('12345678'), 'Active', 1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};

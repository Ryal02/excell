<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedunMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redun_members', function (Blueprint $table) {
            $table->id();
            $table->string('barangay')->nullable();
            $table->string('slp')->nullable();
            $table->string('member')->nullable();
            $table->string('age')->nullable()->default(0);
            $table->string('gender')->nullable();
            $table->string('birthdate')->nullable();
            $table->string('sitio_zone')->nullable();
            $table->string('cellphone')->nullable();
            $table->string('d2')->nullable();
            $table->string('brgy_d2')->nullable();
            $table->string('d1')->nullable();
            $table->string('brgy_d1')->nullable();
            $table->string('batch')->nullable();
            $table->timestamps();
        });

        // Migration for creating dependents table
        Schema::create('redun_dependents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade'); // Foreign key to members table
            $table->string('dependents')->nullable();
            $table->string('dep_age')->nullable()->default(0);
            $table->string('dep_d2')->nullable();
            $table->string('dep_brgy_d2')->nullable();
            $table->string('dep_d1')->nullable();
            $table->string('dep_brgy_d1')->nullable();
            $table->string('batch_belong')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('redun_members');
    }
}

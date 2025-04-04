<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  // Migration for creating members table
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('barangay')->nullable();
            $table->string('slp')->nullable();
            $table->string('member')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('birthdate')->nullable();
            $table->string('sitio_zone')->nullable();
            $table->string('cellphone')->nullable();
            $table->string('d2')->nullable();
            $table->string('brgy_2')->nullable();
            $table->string('d1')->nullable();
            $table->string('brgy_1')->nullable();
            $table->timestamps();
        });

        // Migration for creating dependents table
        Schema::create('dependents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade'); // Foreign key to members table
            $table->string('dependent_name')->nullable();
            $table->integer('dependent_age')->nullable();
            $table->string('dependent_d2')->nullable();
            $table->string('dependent_brgy_d2')->nullable();
            $table->string('dependent_d1')->nullable();
            $table->string('dependent_brgy_d1')->nullable();
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
        Schema::dropIfExists('members');
    }
}

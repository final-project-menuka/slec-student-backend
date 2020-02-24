<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLecturerAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lecturer_attendances', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->dateTime('attended_time');
            $table->string('lecturer_id');
            $table->string('module_code');
            $table->string('position');
            $table->string('lec_hall_id');
            $table->string('lecturer_name');
            $table->string('mac_address');
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
        Schema::dropIfExists('lecturer_attendances');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkshopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workshops', function (Blueprint $table) {
            $table->increments('workshop_id');
            $table->unsignedInteger('location_id');
            $table->string('name');
            $table->dateTime('startDate');
            $table->dateTime('endDate');
            $table->text('description');
            $table->timestamps();
            $table->unsignedInteger('created_by')->default(1);
            $table->unsignedTinyInteger('workshopEn')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workshops');
    }
}

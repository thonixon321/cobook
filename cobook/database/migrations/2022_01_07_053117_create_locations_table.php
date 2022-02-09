<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('location_id');
            $table->string('name');
            $table->string('address');
            $table->decimal('latitude', 6, 4);
            $table->decimal('longitude', 7, 4);
            $table->timestamps();
            $table->unsignedInteger('created_by')->default(1);
            $table->unsignedTinyInteger('locationEn')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}

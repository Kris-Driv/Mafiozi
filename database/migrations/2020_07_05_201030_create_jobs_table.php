<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->bigInteger('energy');
            $table->bigInteger('mafia')->nullable();
            $table->bigInteger('level');
            $table->string('name');
            $table->bigInteger('rm_min');
            $table->bigInteger('rm_max');
            $table->bigInteger('xp');
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
        Schema::dropIfExists('jobs');
    }
}

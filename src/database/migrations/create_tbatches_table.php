<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// Create table for storing types
        Schema::create('batches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue');
            $table->string('classname');
            $table->string('name');
            $table->string('description')->nullable();
            $table->double('progress')->nullable();
            $table->unsignedInteger("user_id")->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->unsignedTinyInteger('failed')->nullable();
            $table->unsignedTinyInteger('stop_on_fail')->nullable();
            $table->text('trace')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');


        });

        Schema::create('batch_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("batch_id");
            $table->string('classname');
            $table->string('name');
            $table->double('weight')->nullable();
            $table->double('wait')->nullable();
            $table->string('file_url')->nullable();
            $table->string('message')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->unsignedTinyInteger('failed')->nullable();
            $table->text('trace')->nullable();
            $table->foreign('batch_id')->references('id')->on('batches')->onUpdate('cascade')->onDelete('cascade');

        });
      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batch_jobs');
        Schema::dropIfExists('batches');
        
    }
}

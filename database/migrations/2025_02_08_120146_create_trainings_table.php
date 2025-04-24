<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description');
            $table->string('duration'); 
            $table->dateTime('start_date') ;
            $table->dateTime('end_date'); 
            $table->string('type'); 
            $table->decimal('price', 8, 3);
            $table->string('image');
            $table->unsignedBigInteger('category_id');
            $table->boolean('status')->default(true);
            $table->timestamp('publish_date')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->decimal('discount', 5, 2)->default(0); // Nouveau champ pour le pourcentage de remise
            $table->decimal('final_price', 10, 3)->default(0); // Nouveau champ pour le prix apr
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('total_seats')->default(0);


            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
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
        Schema::dropIfExists('trainings');
    }
}

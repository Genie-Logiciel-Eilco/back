<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string("isbn")->nullable();
            $table->string("name")->nullable();
            $table->string("subject")->nullable();
            $table->text('synopsis')->nullable();
            $table->string('fileLocation')->nullable();
            $table->string('imageLocation')->nullable();
            $table->date('publicationDate')->nullable();
            $table->integer('counter')->default(0);
            $table->boolean('isReady')->default(0);
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
        Schema::dropIfExists('books');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublishersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->date('foundationDate');
            $table->timestamps();
        });
        Schema::table('books', function (Blueprint $table) {
            //            $table->unsignedBigInteger('role_id');
            //            $table->foreign('role_id')->references('id')->on('roles'); or
            $table->foreignId('publisher_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publishers');
    }
}

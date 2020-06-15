<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formats', function (Blueprint $table) {
            $table->unsignedInteger('id', true);

            $table->unsignedInteger('fk_created_by');
            $table->foreign('fk_created_by')->references('id')->on('users');

            $table->unsignedInteger('fk_updated_by')->nullable();
            $table->foreign('fk_updated_by')->references('id')->on('users');

            $table->unsignedInteger('fk_book');
            $table->foreign('fk_book')->references('id')->on('books');

            $table->unsignedInteger('fk_editorial');
            $table->foreign('fk_editorial')->references('id')->on('editorials');

            $table->string('edition', 80);
            $table->string('image')->nullable();
            $table->unsignedFloat('price');

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
        Schema::dropIfExists('formats');
    }
}

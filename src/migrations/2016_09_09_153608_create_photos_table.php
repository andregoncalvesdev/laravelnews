<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotosTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('photos', function (Blueprint $table) {
      $table->increments('id');

      $table->string('name');
      $table->string('path');
      $table->string('thumb_path');

      $table->integer('order');
      $table->integer('gallery_id')->unsigned();
      $table->foreign('gallery_id')->references('id')->on('gallery');

      $table->boolean('is_published')->default(false);
      $table->boolean('is_main')->default(false);

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
    Schema::drop('photos');
  }
}

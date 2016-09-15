<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('news_translations', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('news_id')->unsigned();
        $table->string('locale')->index();

        $table->string('title');
        $table->string('summary');
        $table->text('text');

        $table->unique(['news_id','locale']);
        $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::drop('news_translations');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('country_translations', function(Blueprint $table) {
        $table->increments('id');
        $table->integer('country_id')->unsigned();
        $table->string('name');
        $table->string('locale')->index();

        $table->unique(['country_id','locale']);
        $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::drop('country_translations');
    }
}

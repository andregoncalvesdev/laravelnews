<?php

namespace AndreGoncalvesDev\LaravelNews\Models;

use Illuminate\Database\Eloquent\Model;

class NewsTranslation extends Model
{
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'news_translations';
  protected $fillable = ['title, summary, text'];

  public $timestamps = false;
}

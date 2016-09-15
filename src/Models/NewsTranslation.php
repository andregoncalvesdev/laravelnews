<?php

namespace AndreGoncalvesDev\LaravelNews\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class NewsTranslation extends Model
{
  use Sluggable;

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'news_translations';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['title, summary, text'];

  /**
   * The attributes that should be visible in arrays.
   *
   * @var array
   */
  protected $visible = ['slug'];

  public $timestamps = false;

  /**
   * Return the sluggable configuration array for this model.
   *
   * @return array
   */
  public function sluggable() {
      return ['slug' => ['source' => 'title']];
  }
}

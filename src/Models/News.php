<?php

namespace AndreGoncalvesDev\LaravelNews\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Dimsav\Translatable\Translatable;

class News extends Model
{
  use Sluggable;
  use Translatable;

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'news';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'title',
    'summary',
    'text',
    'gallery_id',
    'published_at',
    'category',
  ];

  /**
   * The attributes that should be visible in arrays.
   *
   * @var array
   */
  protected $visible = [
    'slug',
    'title',
    'summary',
    'text',
    'gallery'
  ];

  public $translatedAttributes = ['title', 'summary', 'text'];

  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */
  protected static function boot() {
    static::deleted(function ($news) {
      if ($news->gallery) {
        $news->gallery->delete();
      }
    });
  }

  /**
   * A News item has an associated gallery
   */
  public function gallery() {
    return $this->belongsTo('AndreGoncalvesDev\LaravelNews\Models\Gallery');
  }

  /**
   * Return the sluggable configuration array for this model.
   *
   * @return array
   */
  public function sluggable() {
      return ['slug' => ['source' => 'title']];
  }

  /**
   * Get all news
   */
   public static function getAll() {
     return News::all();
   }
}

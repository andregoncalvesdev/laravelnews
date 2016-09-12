<?php

namespace AndreGoncalvesDev\LaravelNews\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model implements SluggableInterface
{
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

  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */
  protected static function boot()
  {
    static::deleted(function ($news) {
      if ($news->gallery) {
        $news->gallery->delete();
      }
    });
  }

  /**
   * A News item has an associated gallery
   */
  public function gallery()
  {
    return $this->belongsTo('AndreGoncalvesDev\LaravelNews\Models\Gallery');
  }

  /**
   * Get all news
   */
   public function getAll() {
     return News::all();
   }
}

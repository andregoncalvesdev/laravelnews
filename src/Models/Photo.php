<?php

namespace AndreGoncalvesDev\LaravelNews\Models;

use Route;
use Image;
use File;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Photo extends Model
{
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'photos';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'path',
    'thumb_path',
    'is_main'
  ];

  /**
   * The attributes that should be visible in arrays.
   *
   * @var array
   */
  protected $visible = ['name', 'display_path', 'display_thumb_path'];

  /**
   * The attributes that should be visible in arrays when on the admin area
   *
   * @var array
   */
  protected $backendVisible = ['id'];

  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */
  protected $appends = ['display_path', 'display_thumb_path'];


  /**
   * The uploaded filename
   *
   * @var UploadedFile
   */
  protected $file;

  protected static function boot()
  {
    static::deleting(function ($photo) {
      $photo->cleanup();
    });
  }

  /**
   * The gallery this photo belongs to
   *
   * @return 'App\Models\Gallery' the gallery
   */
  public function gallery()
  {
    return $this->belongsTo('AndreGoncalvesDev\LaravelNews\Models\Gallery');
  }

  /**
   * Returns the base directory to where
   * photos are stores
   *
   * @return string the base directory
   */
  public function baseDir()
  {
    return 'uploads/photos';
  }

  /**
   * Returns a unique file name based on the original
   * uploaded file name
   *
   * @return string the unique file name
   */
  public function fileName()
  {
    $name = sha1(time() . $this->file->getClientOriginalName());
    $ext = $this->file->getClientOriginalExtension();

    return "{$name}.{$ext}";
  }

  /**
   * Returns the full path to the file
   *
   * @return string the path
   */
  public function filePath()
  {
    return "{$this->baseDir()}/{$this->fileName()}";
  }

  /**
   * Returns the full path to the thumbnail
   *
   * @return string the path
   */
  public function thumbPath()
  {
    return "{$this->baseDir()}/tn-{$this->fileName()}";
  }

  /**
   * Saves the file to the filesystem
   *
   * @return 'App\Models\Photo'
   */
  public function store()
  {
    $this->file->move($this->baseDir(), $this->fileName());

    $this->fitToSize(200, null, $this->thumbPath());

    return $this;
  }

  /**
   * Sets itself as published
   *
   * @return 'App\Models\Photo' self
   */
  public function publish()
  {
    $this->is_published = true;

    $this->save();

    return $this;
  }

  /**
   * Replaces the original image with one transformed to
   * fit the specified width and height
   *
   * @return 'App\Models\Photo' self
   */
  public function fitToSize($width, $height = null, $saveTo = null)
  {
    if (File::exists($this->filePath())) {
      Image::make($this->filePath())
      ->fit($width, $height, function ($constraint) {
        $constraint->upsize();
      })
      ->save($saveTo);
    }

    return $this;
  }

  /**
   * Returns a new Photo with data from the specified
   * uploaded file
   *
   * @param  UploadedFile $file the uploaded file
   *
   * @return Photo the newly created photo
   */
  public static function fromFile(UploadedFile $file)
  {
    $photo = new static;

    $photo->file = $file;

    return $photo->fill([
      'name'       => $photo->fileName(),
      'path'       => $photo->filePath(),
      'thumb_path' => $photo->thumbPath(),
    ]);
  }

  /**
   * Get the frontend formatted path
   *
   * @return string
   */
  public function getDisplayPathAttribute()
  {
    if (strpos($this->attributes['path'], 'http') === 0) {
      return $this->attributes['path'];
    }

    return "/{$this->attributes['path']}";
  }

  /**
   * Get the frontend formatted thumb_path
   *
   * @return string
   */
  public function getDisplayThumbPathAttribute()
  {
    if (strpos($this->attributes['thumb_path'], 'http') === 0) {
      return $this->attributes['thumb_path'];
    }

    return "/{$this->attributes['thumb_path']}";
  }

  /**
   * Deletes the associated files from the filesystem
   */
  protected function cleanup()
  {
    File::delete([
      $this->path,
      $this->thumb_path
    ]);
  }
}

<?php

namespace AndreGoncalvesDev\LaravelNews\Models;

use namespace AndreGoncalvesDev\LaravelNews\Models\Photo;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Gallery extends Model
{
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'gallery';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'title',
    'user_id'
  ];

  /**
   * The attributes that should be visible in arrays.
   *
   * @var array
   */
  protected $visible = ['title', 'photos', 'mainPhoto'];

  protected static function boot()
  {
    static::deleting(function ($gallery) {
      $gallery->deleteAllPhotos();
    });
  }

  public function hasPhotos()
  {
    $photos = Photo::where(['gallery_id' => $this->id])->get();

    if($photos->isEmpty()) { return false; }

    return true;
  }

  /**
   * Get this gallery's photos
   *
   * @return Collection the collection of photos
   */
  public function photos()
  {
    return $this
      ->hasMany('AndreGoncalvesDev\LaravelNews\Models\Photo')
      ->where(['is_main' => false])
      ->orderBy('order');
  }

  public function mainPhoto()
  {
    return $this
      ->hasOne('AndreGoncalvesDev\LaravelNews\Models\Photo')
      ->where(['is_main' => true]);
  }

  /**
   * Get the user that created this gallery
   *
   * @return 'App\Models\User' the user that created this gallery
   */
  public function user()
  {
    return $this->belongsTo('AndreGoncalvesDev\LaravelNews\Models\User');
  }

  /**
   * Returns if the gallery is owned by the specified user
   *
   * @param 'App\Models\User' the user
   *
   * @return Boolean
   */
  public function isOwnedBy(User $user)
  {
    return $this->user->id === $user->id;
  }

  /**
   * Removes all photos from the DB and
   * associated files from the filesystem
   */
  public function deleteAllPhotos()
  {
    Photo::where(['gallery_id' => $this->id])->get()->each(function($photo) {
      $photo->delete();
    });

    return $this;
  }

  /**
   * Removes all unpublished photos from the DB and
   * associated files from the filesystem
   */
  public function deleteUnpublishedPhotos()
  {
    Photo::where([
      'gallery_id'   => $this->id,
      'is_published' => false
    ])->delete();

    return $this;
  }

  /**
   * Adds a new photo to the collection
   */
  public function addPhoto(Photo $newPhoto)
  {
    $lastPhoto = $this->photos->last();

    $newPhoto->order = 0;

    if ($lastPhoto) {
      $newPhoto->order = $lastPhoto->order + 1;
    }

    $this->photos()->save($newPhoto);

    return $this;
  }

  /**
   * Sorts photos according to the specified ids
   */
  public function sort($photoIds)
  {
    collect($photoIds)->each(function($id, $index) {
      $photo = Photo::find($id);

      if ($photo && $photo->order != $index) {
        $photo->order = $index;

        $photo->save();
      }
    });

    return $this;
  }

  /**
   * Sets itself to not be a draft and
   * also publishes all the photos
   *
   * @return 'App\Models\Gallery' self
   */
  public function publish()
  {
    $this->is_draft = false;
    $this->save();

    $this->photos()
      ->where(['is_published' => false])
      ->get()
      ->each(function($photo) {
        $photo->publish();
      });

    $mainPhoto = $this->mainPhoto()->first();

    if ($mainPhoto) {
      $mainPhoto->publish();
    }

    return $this;
  }

  /**
   * Creates a new draft gallery for the specified User,
   * or deletes all photos of an already created draft
   *
   * @param  'App\Models\User' $user the user that owns the galleries
   */
  public static function resetDraftOfUser(User $user)
  {
    $gallery = self::where([
      'user_id' => $user->id,
      'is_draft' => true
    ])->first();

    if (!$gallery) {
      $gallery = self::createDraftForUser($user);
    }

    $gallery->deleteAllPhotos();
  }

  /**
   * Creates a new gallery draft for the specified User
   *
   * @param  'App\Models\User' $user the user that owns the galleries
   *
   * @return 'App\Models\Gallery' the newly created gallery
   */
  public static function createDraftForUser(User $user)
  {
    return self::create(['user_id' => $user->id, 'is_draft' => true]);
  }

  /**
   * Returns the gallery draft for the specified User
   *
   * @param  'App\Models\User' $user the user that owns the galleries
   *
   * @return 'App\Models\Gallery' the gallery
   */
  public static function getDraftOfUser(User $user)
  {
    return self::where(['user_id' => $user->id, 'is_draft' => true])
      ->with('photos')
      ->first();
  }
}

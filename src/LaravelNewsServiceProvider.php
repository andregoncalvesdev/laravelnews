<?php

namespace AndreGoncalvesDev\LaravelNews;

use Illuminate\Support\ServiceProvider;

class LaravelNewsServiceProvider extends ServiceProvider
{
  /**
   * Perform post-registration booting of services.
   *
   * @return void
   */
  public function boot()
  {
    $this->loadViewsFrom(base_path('resources/views'), 'laravelnews');

    $this->publishes([
      __DIR__.'/views' => base_path('resources/views')
    ]);

    $this->publishes([
      __DIR__.'/migrations' => database_path('migrations')
    ], 'migrations');
  }

  /**
   * Register any package services.
   *
   * @return void
   */
  public function register()
  {
      $this->app->register('Cviebrock\EloquentSluggable\ServiceProvider');
      $this->app->register('Dimsav\Translatable\TranslatableServiceProvider');
  }
}

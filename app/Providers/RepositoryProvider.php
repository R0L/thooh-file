<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 * @return void
	 */
	public function boot()
	{
		//
	}
	
	/**
	 * Register the application services.
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('App\Repositorys\Interfaces\FileInterface', 'App\Repositorys\Implement\File\FileService');
		$this->app->bind('App\Repositorys\Interfaces\UploadInterface', 'App\Repositorys\Implement\File\LocalUploadService');
	}
}

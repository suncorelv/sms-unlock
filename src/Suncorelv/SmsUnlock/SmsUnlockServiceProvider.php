<?php namespace Suncorelv\SmsUnlock;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class SmsUnlockServiceProvider extends ServiceProvider {

	/**
	* Indicates if loading of the provider is deferred.
	*
	* @var bool
	*/
	protected $defer = false;

	/**
	* Register the service provider.
	*
	* @return void
	*/
	public function register()
	{
		$this->app->bind('SCR', function() {
			return new SuncoreSCR;
		});
	}

	public function boot()
	{
		$this->package('suncorelv/sms-unlock');
		AliasLoader::getInstance()->alias('SCR', 'Suncorelv\SmsUnlock\Facade');
	}

	/**
	* Get the services provided by the provider.
	*
	* @return array
	*/
	public function provides()
	{
		return array();
	}

}

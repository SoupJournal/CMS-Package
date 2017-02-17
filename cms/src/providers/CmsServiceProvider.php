<?php namespace Soup\Cms;

use Illuminate\Support\ServiceProvider;

class CmsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;



	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		//$this->package('soup/cms');
		
		//publish config files
		$this->publishes([
		     __DIR__.'/../config/auth.php' => config_path('cms/auth.php'),
		     __DIR__.'/../config/config.php' => config_path('cms/config.php'),
		]);
		
		
		//merge with user modified config
		$this->mergeConfigFrom(
    	    __DIR__.'/../config/auth.php', 'cms/auth',
    	    __DIR__.'/../config/config.php', 'cms/config'
	    );
		
		
		//register middleware
		$router = $this->app['router'];
		if ($router) {
			$router->middleware('CMSAuth', 'your\namespace\MiddlewareClass');	
		}
		
		
		//check if CMS routing enabled
		$routeEnabled = config('cms.config.route.enabled'); 
		
		
		
		//include package routes
		if ($routeEnabled) {
			include __DIR__.'/../routes.php';
		}
		
		//include package composers
		include __DIR__.'/../composers.php';
		
		//include package helpers
		include __DIR__.'/../helpers/JSHelper.php';
		include __DIR__.'/../helpers/SQLHelper.php';
		include __DIR__.'/../helpers/DataHelper.php';
		include __DIR__.'/../helpers/CMSHelper.php';
		

	} //end boot()



	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//register package
		//$this->package('soup/cms');
		
		
		//apply auth config
		if (!isset($this->app['config']['auth'])) {
			\Config::set('auth', array());
		}
		//apply multi-auth config
		if (!isset($this->app['config']['auth']['multi'])) {
			\Config::set('auth.multi', array());
		}
		
		//setup cms auth (requires 'Ollieread\Multiauth')
		$cmsConfig = \Config::get('cms::auth');
		if (isset($cmsConfig)) {
			$mergedConfig = array_merge($this->app['config']['auth']['multi'], $cmsConfig);
			\Config::set('auth.multi', $mergedConfig);
		}
		

	} //end register()
	
	

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
		
	} //end provides()


} //end class CmsServiceProvider



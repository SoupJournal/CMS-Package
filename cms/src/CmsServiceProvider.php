<?php namespace Soup\CMS;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
//use Collective\Html\HtmlServiceProvider;
use Illuminate\Foundation\AliasLoader;

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
		//$loader = AliasLoader::getInstance();
		//$loader->addNamespace('Foo\Bar', '/path/to/packages/foo-bar/src');
		
		//add forms support (TODO: check if support already available)
		$loader = AliasLoader::getInstance();
		if ($loader) {
	        //$loader->alias('Collective\\Html', __DIR__.'vendor/laravelcollective/html/src/');
	        $loader->alias('Form', \Collective\Html\FormFacade::class);
        	$loader->alias('HTML', \Collective\Html\HtmlFacade::class);
        	//add removed Laravel libraries
        	//$loader->alias('Input', \Illuminate\Support\Facades\Input::class);
		}
		App::register('Collective\Html\HtmlServiceProvider');
		
		
		//publish config files
		$this->publishes([
		     __DIR__.'/config/auth.php' => config_path('cms/auth.php'),
		     __DIR__.'/config/config.php' => config_path('cms/config.php'),
		]);
		
		
		//merge with user modified config
		$this->mergeConfigFrom(
    	    __DIR__.'/config/auth.php', 'cms/auth',
    	    __DIR__.'/config/config.php', 'cms/config'
	    );
		
		
		//register middleware
		$router = $this->app['router'];
		if ($router) {
			$router->middleware('HTTPS', 'Soup\CMS\Middleware\HTTPSMiddleware');	
			$router->middleware('CMSAuth', 'Soup\CMS\Middleware\AuthMiddleware');	
			$router->middleware('CMSApp', 'Soup\CMS\Middleware\AppMiddleware');	
			$router->middleware('P_Security', 'Soup\CMS\Middleware\SecurityPermissionMiddleware');	
			$router->middleware('P_Form', 'Soup\CMS\Middleware\FormPermissionMiddleware');	
			$router->middleware('P_Input', 'Soup\CMS\Middleware\InputPermissionMiddleware');	
		}
		
		
		//check if CMS routing enabled
		$routeEnabled = config('cms.config.route.enabled'); 
		
		
		
		//include package routes
		if ($routeEnabled) {
			include __DIR__.'/routes.php';
		}
		
		//include package composers
		include __DIR__.'/composers.php';
		
		//include package helpers
		include __DIR__.'/helpers/JSHelper.php';
		include __DIR__.'/helpers/SQLHelper.php';
		include __DIR__.'/helpers/DataHelper.php';
		include __DIR__.'/helpers/FormHelper.php';
		include __DIR__.'/helpers/CMSHelper.php';
		
		
		//load views
		$this->loadViewsFrom(__DIR__.'/views', 'cms');
		
		
		//View::addNamespace('Soup\\Cms\\Lib', __DIR__.'/views/');
		
		//add namespaces to views
		//$this->app['view']->addNamespace('Soup\\CMS\\Lib', base_path() . '/lib/');

		//publish assets
		$this->publishes([
		    __DIR__.'/../public' => public_path('soup/cms'),
		], 'public');
		

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
		
		//apply auth providers config
		if (!isset($this->app['config']['auth']['providers'])) {
			\Config::set('auth.providers', array());
		}
		
		//apply auth guards config
		if (!isset($this->app['config']['auth']['guards'])) {
			\Config::set('auth.guards', array());
		}
		
//			print_r($this->app['config']['auth']['guards']);
//		exit(0);
		
		/*
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
		
*/
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



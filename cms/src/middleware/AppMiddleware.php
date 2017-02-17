<?php 

	namespace Soup\CMS\Middleware;
	//namespace App\Http\Middleware;

	use Soup\CMS\Lib\CMSAccess;

	use Closure;
	use Redirect;

	class AppMiddleware { //implements Middleware {

	    /**
	     * Handle an incoming request.
	     *
	     * @param  \Illuminate\Http\Request  $request
	     * @param  \Closure  $next
	     * @return mixed
	     */
	    public function handle($request, Closure $next)
	    {
	    	
	    	//get route
	    	$route = isset($request) ? $request->route() : null;
	    	
		   	//get appID
			$appId = isset($route) ? $route->getParameter('appId') : null;
		    
		    //invalid app ID
			if (!is_numeric($appId) || $appId<0 || !CMSAccess::validApplication($appId)) {
				//$url = action('\Soup\CMS\Controllers\CMSController@getIndex');
				//return redirect($url);
				//echo "redirect to index from app mw - route: "; 
				//print_r($route);
				//exit(0);
				return Redirect::route('cms.home');
//				return Redirect::action('CMSController@getIndex');
				//return Redirect::to('/cms/error/404');
			}
	
			//process request
	        return $next($request);
	        
	    } //end handle()
	
	} //end class AppMiddleware
	
?>
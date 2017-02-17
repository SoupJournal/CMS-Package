<?php 

	namespace Soup\CMS\Middleware; 


	use Soup\CMS\Lib\CMSAccess;

	use Closure;
	use Redirect;

	class SecurityPermissionMiddleware { 

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
			
			//valid app ID
			if (is_numeric($appId) && $appId>0) {
			
				//ensure user has permission
				if (!CMSAccess::validPermission(CMSAccess::$PERMISSION_EDIT_SECURITY, $appId)) { 
					
					//no security permission - redirect to overview
					return Redirect::route('cms.home', array('appId' => $appId));
				}
			
			}
			//invalid app ID
			else {
				return Redirect::route('cms.error', array('errorCode' => '404'));
			}
	        
			//process request
	        return $next($request);
	        
	    } //end handle()
	
	
	} //end class SecurityPermissionMiddleware
	
?>
<?php 

	namespace Soup\CMS\Middleware; 


	use Soup\CMS\Lib\CMSAccess;
	use Soup\CMS\Models\CMSApp;

	use Closure;
	use Redirect;

	abstract class BasePermissionMiddleware { 


		//required permissions
		protected $permissions = null;
		

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
	    	
			//get app key
			$appKey = isset($route) ? $route->getParameter('appKey') : null;


			//valid app Key
			if (!is_null($appKey) && strlen($appKey)>0) {
			
				//get application
				$app = CMSApp::where('key', $appKey)->first();
				if ($app) {
			
					//permissions required
					if (isset($this->permissions) && count($this->permissions)>0) {
			
						foreach ($this->permissions as $permission) {
			
							//ensure user has permission
							if (!CMSAccess::validPermission($permission, $app->id)) { 
								
								//no permission - redirect to overview
								return Redirect::route('cms.home', array('appKey' => $appKey));
							}
						
						} //end for()
						
					} //end if (permissions required)
					
				}
				
				//invalid app key
				else {
					return Redirect::route('cms.error', array('errorCode' => '404'));
				}
			
			}
			//no app key specified
			else {
				return Redirect::route('cms.error', array('errorCode' => '404'));
			}
			
			
			//process request
	        return $next($request);
	        
	    } //end handle()
	    
	
	} //end class BasePermissionMiddleware
	
?>
<?php 

	namespace Soup\CMS\Middleware; 

	use Soup\CMS\Middleware\BasePermissionMiddleware;
	use Soup\CMS\Lib\CMSAccess;
	use Soup\CMS\Models\CMSApp;

	use Closure;
	use Redirect;

	class FormPermissionMiddleware extends BasePermissionMiddleware { 


		public function __construct() {

			//set required permissions
			$this->permissions = [CMSAccess::$PERMISSION_EDIT_FORM];

		} //end constructor()

	    /**
	     * Handle an incoming request.
	     *
	     * @param  \Illuminate\Http\Request  $request
	     * @param  \Closure  $next
	     * @return mixed
	     */
	/*    public function handle($request, Closure $next)
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
			
					//ensure user has permission
					if (!CMSAccess::validPermission(CMSAccess::$PERMISSION_EDIT_FORM, $app->id)) { 
						
						//no security permission - redirect to overview
						return Redirect::route('cms.home', array('appKey' => $appKey));
					}
					
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
	   */ 
	
	} //end class FormPermissionMiddleware
	
?>
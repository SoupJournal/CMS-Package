<?php 

	namespace Soup\CMS\Middleware; 
//	namespace App\Http\Middleware;

	use Soup\CMS\Lib\CMSAccess;

	use Closure;
	use Redirect;
	use Illuminate\Support\Facades\Auth;

	class AuthMiddleware { //implements Middleware {

	    /**
	     * Handle an incoming request.
	     *
	     * @param  \Illuminate\Http\Request  $request
	     * @param  \Closure  $next
	     * @return mixed
	     */
	    public function handle($request, Closure $next)
	    {
//	    echo "CHECK result: " . Auth::guard(CMSAccess::$AUTH_GUARD)->check();
//	    exit(0);		
		    //ensure user is logged in
			if (!Auth::guard(CMSAccess::$AUTH_GUARD)->check()) {
		        //return Redirect::action('CMSController@getLogin');
		        return Redirect::route('cms.login');
		    }
		   
		   	//ensure https connection 
		    if (!$request->secure()) {
		    	return Redirect::secure( $request->path('/toSecureURL') );
		    }
	
			//process request
	        return $next($request);
	        
	    } //end handle()
	
	} //end class AuthMiddleware
	
?>
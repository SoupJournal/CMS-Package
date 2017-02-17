<?php 

	namespace Soup\CMS\Middleware; 


	use Closure;
	use Redirect;

	class HTTPSMiddleware {

	    /**
	     * Handle an incoming request.
	     *
	     * @param  \Illuminate\Http\Request  $request
	     * @param  \Closure  $next
	     * @return mixed
	     */
	    public function handle($request, Closure $next)
	    {
	    	
	    	//ensure https connection 
		    if (!$request->secure()) {
		    	return Redirect::secure( $request->path('/toSecureURL') );
		    }
	    	
//	         Test for an even vs. odd remote port
//	        if (($request->server->get('REMOTE_PORT') / 2) % 2 > 0)
//	        {
//	            throw new \Exception("WE DON'T LIKE ODD REMOTE PORTS");
//	        }
	
			//process request
	        return $next($request);
	        
	    } //end handle()
	
	} //end class HTTPSMiddleware
	
?>
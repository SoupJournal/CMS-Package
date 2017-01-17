<?php

	//define variables
	//$pageModules = null;

?>
<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        
        
        {{------------------ TITLE -------------------}}
        
        <title>@yield('title') | User Admin</title>
        
        {{---------------- END TITLE -----------------}}
        
        
        
        {{----------------- SCRIPTS ------------------}}
	    @include('cms::cms.scripts')
        
        
        
    </head>
    
    
    <body ng-app="cms-core"> 
    
    		

		<div class="container top-margin-small">
		    <div class="row">
		    

		    	<div class="col-md-12">
		            <div class="jumbotron">
				    	
				    	<div id="page-content-wrapper">
				        	<div class='container-fluid'>
				        	
				        		{{----------------- CONTENT ------------------}}
				        		@yield('content')
				        		{{--------------- END CONTENT ----------------}}
				        		
				       	 	</div>
				        </div>
		        
					</div>
           
        		</div>
        
        	</div>
        </div>
        
        
        
        
        {{----------------- SCRIPTS ------------------}}
	    @include('cms::cms.app', ['pageModules' => $pageModules])
	    
        
    	<?php
		//	} //end if (auth check)
		?>
        
    </body>
</html>
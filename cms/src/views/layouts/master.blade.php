<?php

	//define variables
	//$pageModules = [];

?>
<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        
        
        {{------------------ TITLE -------------------}}
        
        <title>@yield('title', '') | User Admin</title>
        
        {{---------------- END TITLE -----------------}}
        
        
        {{----------------- SCRIPTS ------------------}}
	    @include('cms::cms.scripts')
        

    </head>
    
    
    <body ng-app="cms-core"> 
    
    
   		{{----------------- HEADER -------------------}}
    	@include('cms::admin.layout.header')
   		



		<div class="container top-margin-small">
		    <div class="row">
		    
		    	<!-- sidebar -->
		        <div class="col-md-2">
     
			    	{{------------------ MENU --------------------}}
				    @include('cms::admin.layout.menu')
	
        		</div>
		    	<!-- end sidebar -->
		    	
		    	
		    	
		    	<div class="col-md-10">
		            <div class="jumbotron">
				    	
				    	<div id="page-content-wrapper">
				        	<div class='container-fluid'>


								{{-- display any messages --}}
								@if (Session::has('message'))
									<div class='alert alert-success'>{{ Session::get('message') }}</div>
								@endif
								

				        		{{----------------- CONTENT ------------------}}
				        		@yield('content', '')
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
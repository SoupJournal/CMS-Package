<?php

	//set custom page controllers
	//$pageModules = array_push($pageModules, 'ui.bootstrap.dropdown');

  	//user is logged in
  	$user = Auth::CMSuser()->user();
	if ($user) {

?>


	{{-- header padding for fixed width overlay --}}
	<div size-copy source="cms-page-header"></div>
			
			
	{{-- header --}}
	<div id="cms-page-header" class="navbar navbar-fixed-top bg-color-1">
	
		<div ng-controller="HeaderController">
	
		   	<div class="container-fluid text-center">
				<h1 class="color-1">CMS</h1>
		   		
		   		
		   		{{-- user menu --}}
		   		<div class="dropdown pull-right">
		   		
		   		
		   		    <span uib-dropdown on-toggle="toggled(open)">
				      	<a href id="simple-dropdown" uib-dropdown-toggle>
				        	{{ $user->first_name }} {{ $user->last_name }}<span class="caret"></span>
				      	</a>
				      	<ul class="dropdown-menu" uib-dropdown-menu role="menu" aria-labelledby="simple-dropdown">
				          	<li><a href="{{ URL::to('cms/logout') }}">logout</a></li>
			        		<!-- li class="divider"></li -->
				      	</ul>
				    </span>
		   		
		   		
		   		
			   		<!-- div class="btn-group" uib-dropdown is-open="status.isopen">
			   		
			   			{{-- drop down button --}}
			      		<button id="single-button" type="button" class="btn btn-primary" uib-dropdown-toggle ng-disabled="disabled">
			        		{{ $user->first_name }} {{ $user->last_name }}<span class="caret"></span>
			      		</button>
			      		
			      		{{-- drop down items --}}
			      		<ul class="dropdown-menu" uib-dropdown-menu role="menu" aria-labelledby="single-button">
			        		<li><a href="{{ URL::to('cms/logout') }}">logout</a></li>
			      		</ul>
			    	</div -->
	
				  	
				</div>
		   		
		   		
		   	</div>
	   	
		</div>
	  	{{-- end controller --}}
	   	
	</div>

<?php

	} //end if (logged in)
	
?>

    	
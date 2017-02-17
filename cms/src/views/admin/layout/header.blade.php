<?php

	use Soup\Cms\Lib\CMSAccess;

	//set custom page controllers
	//$pageModules = array_push($pageModules, 'ui.bootstrap.dropdown');


	//determine visible menu items
	$showCreateApp = CMSAccess::validPermissionFromList($userPermissions, CMSAccess::$PERMISSION_CREATE_APPLICATION);

	//get controller paths
	//$homePath = route('cms.home', ['appId' => (isset($appData) ? $appData->id : null)]);
	$logoutPath = route('cms.logout');
	$appPath = action($controllerNamespace . 'ApplicationController@getIndex');

//echo "USER: ";
//print_r($user);
//exit(0);

  	//user is logged in
	if (isset($user)) {

		//get list of users applications
		$applications = CMSAccess::userApplications();

		//get number of applications
		$numberOfApplications = count($applications);

?>


	{{-- header padding for fixed width overlay --}}
	<div size-copy source="cms-page-header"></div>
			
			
	{{-- header --}}
	<div id="cms-page-header" class="navbar navbar-fixed-top bg-color-1">
	
		<div ng-controller="HeaderController">
	
		   	<div class="container-fluid text-center">
				<h1 class="color-1">CMS</h1>
		   		
		   		
		   		
		   		{{-- applications menu --}}
		   		@if ($applications)
		   		
		   			{{-- multiple applications --}}
		   			@if ($numberOfApplications>=1 || $showCreateApp)
		   		
			   		
				   		<div class="dropdown pull-left">
				   		
				   		
				   		    <span uib-dropdown on-toggle="toggled(open)">
						      	<a href id="simple-dropdown" uib-dropdown-toggle>
						      		@if ($appName && strlen($appName))
						      			{{ $appName }}
						      		@else
						      			Applications
						      		@endif
						        	<span class="caret"></span>
						      	</a>
						      	<ul class="dropdown-menu" uib-dropdown-menu role="menu" aria-labelledby="simple-dropdown">
						      	
						      		{{-- list applications --}}
						      		@foreach ($applications as $appData)
						      			@if ($appData && isset($appData->name) && isset($appData->id) && strlen($appData->name))
								          	<li><a href="{{ route('cms.home', ['appId' => (isset($appData) ? $appData->id : null)]) }}">{{ $appData->name }}</a></li>
								        @endif
						          	@endforeach
						          	
						          	{{-- add create option --}}
						          	@if ($showCreateApp)
						        		<li class="divider"></li>
						          		<li><a href="{{ $appPath }}">Add new application</a></li>
						          	@endif
						      	</ul>
						    </span>
						    
						</div>
				
					
					{{-- no applications but permission to create new app --}}
					@elseif ($numberOfApplications==0 && $showCreateApp)
				
						<div class="dropdown pull-left">
							<span><a href="{{ $appPath }}">Add new application</a></span>
						</div>
				
				
					{{-- no application available and no create permission --}}
					@elseif ($appName && strlen($appName))
				
						<div class="dropdown pull-left">
							<span>No Applications available</span>
						</div>
				
					@endif
				
				@endif
		   		
		   		
		   		
		   		
		   		
		   		{{-- user menu --}}
		   		<div class="dropdown pull-right">
		   		
		   		
		   		    <span uib-dropdown on-toggle="toggled(open)">
				      	<a href id="simple-dropdown" uib-dropdown-toggle>
				        	{{ $user->first_name }} {{ $user->last_name }}<span class="caret"></span>
				      	</a>
				      	<ul class="dropdown-menu" uib-dropdown-menu role="menu" aria-labelledby="simple-dropdown">
				          	<li><a href="{{ $logoutPath }}">logout</a></li>
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

    	
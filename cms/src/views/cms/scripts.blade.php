<?php

	//CONSTANTS
	$bootstrapVersion = "3.3.2";
	$angularVersion = "1.3.2"; //"1.5.7";
	
	//PAGE PROPERTIES
	//$pageModules = null;
	
	//DEBUG
	$useLocalAPIs = false;
	
?>


	{{-- Load styles --}}
	@if (isset($useLocalAPIs) && $useLocalAPIs)
	   	<link href="//bootstrap.api.aberration.dev/css/bootstrap.min.css" rel="stylesheet">
	   	<link href="//bootstrap.api.aberration.dev/css/font-awesome.css" rel="stylesheet"> 	
	@else
	   	<link href="//maxcdn.bootstrapcdn.com/bootstrap/{{ $bootstrapVersion }}/css/bootstrap.min.css" rel="stylesheet">
	   	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet"> 
	@endif
      

	{{-- Add libraries --}}
	<!-- script src="//maxcdn.bootstrapcdn.com/bootstrap/{{ $bootstrapVersion }}/js/bootstrap.min.js"></script -->
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> -->
    <!-- add Angular JS support -->
    @if (isset($useLocalAPIs) && $useLocalAPIs)
		<script src="https://angular.api.aberration.dev/angular.min.js"></script>  
		<script src="https://angular.api.aberration.dev/angular-resource.min.js"></script> 	    
	@else
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/{{ $angularVersion }}/angular.min.js"></script>  
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/{{ $angularVersion }}/angular-resource.min.js"></script> 
	@endif
	<!-- script src="https://ajax.googleapis.com/ajax/libs/angularjs/{{ $angularVersion }}/angular-route.js"></script --> 
	{{ HTML::script($assetPath . '/js/bootstrap/ui-bootstrap-2.3.0.js') }}




	{{----------------- SCRIPTS ------------------}}
      
		@yield('scripts', '')

	{{--------------- END SCRIPTS ----------------}}



	{{-- custom app scripts --}}
	{{ HTML::script($assetPath . '/js/core/ajax.js') }}
	{{ HTML::script($assetPath . '/js/core/components.js') }}
	{{ HTML::script($assetPath . '/js/cms/general/cms.js') }}		
	{{ HTML::script($assetPath . '/js/cms/general/gui.js') }}
	{{-- cms scripts --}}
	{{ HTML::script($assetPath . '/js/cms/layout/header.js') }}
	
    
    {{-- debug script --}}
    {{ HTML::script($assetPath . '/js/core/debug.js') }}
    
    
    <!-- add style -->
    {{ HTML::style($assetPath . '/css/admin.css') }} 

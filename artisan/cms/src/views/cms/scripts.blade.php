<?php

	//CONSTANTS
	$bootstrapVersion = "3.3.2";
	$angularVersion = "1.3.2"; //"1.5.7";
	
	//PAGE PROPERTIES
	//$pageModules = null;
	
?>

	{{-- Load styles --}}
   	<link href="//maxcdn.bootstrapcdn.com/bootstrap/{{ $bootstrapVersion }}/css/bootstrap.min.css" rel="stylesheet">
   	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet"> 
      

	{{-- Add libraries --}}
	<!-- script src="//maxcdn.bootstrapcdn.com/bootstrap/{{ $bootstrapVersion }}/js/bootstrap.min.js"></script -->
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> -->
    <!-- add Angular JS support -->
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/{{ $angularVersion }}/angular.min.js"></script>  
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/{{ $angularVersion }}/angular-resource.min.js"></script> 
	<!-- script src="https://ajax.googleapis.com/ajax/libs/angularjs/{{ $angularVersion }}/angular-route.js"></script --> 
	{{ HTML::script('packages/artisan/cms/js/bootstrap/ui-bootstrap-2.3.0.js') }}



	{{----------------- SCRIPTS ------------------}}
      
		@yield('scripts')

	{{--------------- END SCRIPTS ----------------}}



	{{-- custom app scripts --}}
	{{ HTML::script('packages/artisan/cms/js/core/ajax.js') }}
	{{ HTML::script('packages/artisan/cms/js/core/components.js') }}
	{{ HTML::script('packages/artisan/cms/js/cms/general/cms.js') }}		
	{{ HTML::script('packages/artisan/cms/js/cms/general/gui.js') }}
	{{-- cms scripts --}}
	{{ HTML::script('packages/artisan/cms/js/cms/layout/header.js') }}
	
    
    {{-- debug script --}}
    {{ HTML::script('packages/artisan/cms/js/core/debug.js') }}
    
    
    <!-- add style -->
    {{ HTML::style('packages/artisan/cms/css/admin.css'); }} 

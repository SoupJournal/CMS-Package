@extends('cms::layouts.master')


{{------------------ TITLE -------------------}}

@section('title') Applications @stop

{{---------------- END TITLE -----------------}}




{{----------------- SCRIPTS ------------------}}

@section('scripts')

	{{-- HTML::script('packages/artisan/cms/js/cms/form/form.js') --}}	
	
	<?php
	
		//set custom page controllers
		//$pageModules = Array('cms-form');

	?>
	
@stop
{{--------------- END SCRIPTS ----------------}}





{{----------------- CONTENT ------------------}}

@section('content')

<?php

	//get AJAX URL's
	//$tableURL = action("FormController@getTable");
	//$fieldURL = action("FormController@getField");

	
?>

<div style="background-color: #EEEEEE">


	{{-- select database view --}}
	<!--div ng-controller="FormController" ng-init='' -->
	
	
		{{-- title --}}
		<h2>Applications</h2>
	
	

	
	
		<div class="form-group">
		

	

		{{-- end form group --}}
		</div>
		
	<!-- /div -->
	{{-- end controller --}}	



	<br>
	
</div>

@stop
{{--------------- END CONTENT ----------------}}

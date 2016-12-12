@extends('cms::layouts.master')


{{------------------ TITLE -------------------}}

@section('title') Form Creation @stop

{{---------------- END TITLE -----------------}}




{{----------------- SCRIPTS ------------------}}

@section('scripts')

	{{ HTML::script('packages/artisan/cms/js/cms/form/form.js') }}	
	
	<?php
	
		//set custom page controllers
		array_push($pageModules, 'cms.form');

	?>
	
@stop
{{--------------- END SCRIPTS ----------------}}





{{----------------- CONTENT ------------------}}

@section('content')

<?php

	//get AJAX URL's
	$tableURL = action("FormController@getTable");
	$fieldURL = action("FormController@getField");


	
?>

<div style="background-color: #EEEEEE">


	{{-- select database view --}}
	<div ng-controller="FormController" ng-init='";'>
	
	
		{{-- title --}}
		<h2>Forms</h2>
	
	

	
	
		<div class="form-group">
		
		
			{{-- add form button --}}
			<a href="{{ URL::to('cms/form/create') }}" class="btn btn-primary">Add Form</a>
	

		{{-- end form group --}}
		</div>
		
	</div>
	{{-- end controller --}}	



	<br>
	
</div>

@stop
{{--------------- END CONTENT ----------------}}

@extends('cms::layouts.master')


{{------------------ TITLE -------------------}}

@section('title') Form Creation @stop

{{---------------- END TITLE -----------------}}




{{----------------- SCRIPTS ------------------}}

@section('scripts')

	//{{ HTML::script('packages/artisan/cms/js/cms/form/form.js') }}	
	
	<?php
	
		//set custom page controllers
		//$pageModules = Array('cms.form');

	?>
	
@stop
{{--------------- END SCRIPTS ----------------}}





{{----------------- CONTENT ------------------}}

@section('content')

<?php


	
?>

<div style="background-color: #EEEEEE">


	{{-- select database view --}}
	<!-- div ng-controller="FormController" ng-init='' -->
	
	
		{{-- title --}}
		<h2>Home</h2>
	
	
		<div class="form-group">

			
		</div>
	
	
		{{-- end form group --}}
		</div>
		
	<!-- /div -->
	{{-- end controller --}}	



	<br>
	

</div>

@stop
{{--------------- END CONTENT ----------------}}

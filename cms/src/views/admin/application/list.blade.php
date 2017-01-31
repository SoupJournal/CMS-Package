@extends('cms::layouts.admin')


{{------------------ TITLE -------------------}}

@section('title') Applications @stop

{{---------------- END TITLE -----------------}}




{{----------------- SCRIPTS ------------------}}

@section('scripts')

	{{ HTML::script($assetPath . '/js/cms/pages/application.js') }}
	
	<?php
	
		//set custom page controllers
		array_push($pageModules, 'cms.application');

	?>
	
@stop
{{--------------- END SCRIPTS ----------------}}





{{----------------- CONTENT ------------------}}

@section('content')

<?php

	//get AJAX URL's
	$dataURL = URL::action('ApplicationController@getApplications');

	
?>

<div style="background-color: #EEEEEE">


	{{-- select database view --}}
	<div ng-controller="ApplicationController" ng-init="setDataURL('{{ $dataURL }}');">
	
	
		{{-- title --}}
		<h2>Applications</h2>
	
	

	
	
		<div class="form-group">
		
		
		
			
		
			{{-- draw table --}}
			@include('cms::cms.gui.table', array('title'=>'users', 'dataFunction'=>'initApplicationTable'))

	

		{{-- end form group --}}
		</div>
		
	</div>
	{{-- end controller --}}	



	<br>
	
</div>

@stop
{{--------------- END CONTENT ----------------}}

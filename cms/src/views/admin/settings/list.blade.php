@extends('cms::layouts.master')


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
	$dataURL = URL::to('cms/app/applications');

	
?>

<div style="background-color: #EEEEEE">


	{{-- select database view --}}
	<!-- div ng-controller="SettingsController" ng-init="setDataURL('{{ $dataURL }}');" -->
	
	
		{{-- title --}}
		<h2>Settings</h2>
	
	

	
	
		<div class="form-group">
		
			{{ Form::label('imageLocation', 'Base Image Upload Path') }}
	        {{ Form::text('imageLocation', null, Array ('placeholder' => 'Path', 'class' => 'form-control')) }}
			
			

		{{-- end form group --}}
		</div>
		
	<!-- /div -->
	{{-- end controller --}}	



	<br>
	
</div>

@stop
{{--------------- END CONTENT ----------------}}

@extends('cms::layouts.master')


{{------------------ TITLE -------------------}}

@section('title') Form Creation @stop

{{---------------- END TITLE -----------------}}




{{----------------- SCRIPTS ------------------}}

@section('scripts')

	{{-- {{ HTML::script($assetPath . '/js/cms/form/form.js') }} --}}
	
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


	{{-- application is specified --}}
	@if ($appId>0)

		{{-- title --}}
		<h2>{{ $appName }} Overview</h2>
	
	
		<div class="form-group">

	
		{{-- end form group --}}
		</div>
		

	@elseif (isset($applications) && count($applications)>0) 
	
		{{-- title --}}
		<h2>No Application Selected</h2>

		<h4>Please select an application from the list of available applications</h4>	
	

	{{-- no application specified --}}
	@else

		{{-- title --}}
		<h2>No Applications Available</h2>

		<h4>Please contact your site administrator to gain access to available applications</h4>

	@endif

	<br>
	

</div>

@stop
{{--------------- END CONTENT ----------------}}

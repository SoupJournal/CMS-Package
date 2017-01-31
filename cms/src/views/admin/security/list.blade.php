@extends('cms::layouts.master')


{{------------------ TITLE -------------------}}

@section('title') Applications @stop

{{---------------- END TITLE -----------------}}




{{----------------- SCRIPTS ------------------}}

@section('scripts')

	{{ HTML::script($assetPath . '/js/cms/pages/security.js') }}
	
	<?php
	
		//set custom page controllers
		array_push($pageModules, 'cms.security');

	?>
	
@stop
{{--------------- END SCRIPTS ----------------}}





{{----------------- CONTENT ------------------}}

@section('content')

<?php

	//get AJAX URL's
	$dataURL = action('SecurityController@getGroups', ['appId' => $appId]);

	
?>

<div style="background-color: #EEEEEE">


	{{-- select database view --}}
	<div ng-controller="SecurityController" ng-init="setDataURL('{{ $dataURL }}');">
	
	
		{{-- title --}}
		<h2>Security Groups</h2>
	
	
	
		<div class="form-group">
		

			{{-- draw table --}}
			@include('cms::cms.gui.table', array('title'=>'', 'dataFunction'=>'initSecurityTable'))

	
			{{-- add group button --}}
			<a href="{{ action('SecurityController@getEdit', ['appId' => $appId]) }}" class="btn btn-primary">Add Group</a>
	

		{{-- end form group --}}
		</div>
		
	</div>
	{{-- end controller --}}	



	<br>
	
</div>

@stop
{{--------------- END CONTENT ----------------}}

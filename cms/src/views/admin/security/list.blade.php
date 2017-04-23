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
	$dataURL = route('cms.security.groups', ['appKey' => $appKey]);
	$editURL = route('cms.security.edit', ['appKey' => $appKey, 'securityGroupId' => null]);

	//compile table parameters
	$tableParameters = array(
		'title'=>'', 
		'tableId' => 'securityTable',
		'dataFunction'=>'initSecurityTable', 
		//'editURL' => $editURL,
		//'editField' => 'id',
		//'columnSyntax' => Array(true)
	)
	
?>

<div style="background-color: #EEEEEE">


	{{-- select database view --}}
	<div ng-controller="SecurityController" ng-init="setDataURL('{{ $dataURL }}');  setEditURL('{{ $editURL }}');">
	
	
		{{-- title --}}
		<h2>Security Groups</h2>
	
	
	
		<div class="form-group">
		

			{{-- draw table --}}
			@include('cms::cms.gui.table', $tableParameters)

	
			{{-- add group button --}}
			<a href="{{ $editURL }}" class="btn btn-primary">Add Group</a>
	

		{{-- end form group --}}
		</div>
		
	</div>
	{{-- end controller --}}	



	<br>
	
</div>

@stop
{{--------------- END CONTENT ----------------}}

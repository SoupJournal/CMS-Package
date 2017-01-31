@extends('cms::layouts.master')


{{------------------ TITLE -------------------}}

@section('title') Form Creation @stop

{{---------------- END TITLE -----------------}}




{{----------------- SCRIPTS ------------------}}

@section('scripts')

	{{ HTML::script($assetPath . '/js/cms/pages/form.js') }}	
	
	<?php

		//set custom page controllers
		array_push($pageModules, 'cms.form');

	?>
	
@stop
{{--------------- END SCRIPTS ----------------}}





{{----------------- CONTENT ------------------}}

@section('content')

<?php

	//get form properties
	$formName = safeObjectValue('name', $form, "");

	//get AJAX URL's
	$dataURL = ""; //action("FormController@getTemplates");
	$editURL = ""; //URL::to('cms/' . $appId . '/form/edit/');

	//compile table parameters
	$tableParameters = array(
		'title'=>'', 
		'dataFunction'=>'initFormTable', 
		'editURL' => $editURL,
		'editField' => 'id',
		'columnSyntax' => Array(true)
	);
	
	
?>

<div style="background-color: #EEEEEE">


	{{-- select database view --}}
	<div ng-controller="FormController" ng-init="setDataURL('{{ $dataURL }}'); setEditURL('{{ $editURL }}');">
	
	
		{{-- title --}}
		<h2>{{ $formName }}</h2>

	
	
		<div class="form-group">
		
		
			{{-- draw table --}}
			@include('cms::cms.gui.table', $tableParameters)

	
		
			{{-- add form button --}}
			<a href="{{ URL::to('cms/' . $appId . '/form/edit') }}" class="btn btn-primary">Add Form</a>
	

		{{-- end form group --}}
		</div>
		
	</div>
	{{-- end controller --}}	



	<br>
	
</div>

@stop
{{--------------- END CONTENT ----------------}}

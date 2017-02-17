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

	//page properties
	$showExport = Request::old('showExport');
	$showExport = isset($showExport) && $showExport ? $showExport : 'false';

	//get form properties
	$formName = safeObjectValue('name', $form, "");

	//get AJAX URL's
	$dataURL = action($controllerNamespace . 'FormController@getTemplates', ['appId' => $appId, 'formId' => (isset($form) ? $form->id : null)]);
	$editURL = ""; //URL::to('cms/' . $appId . '/form/edit/');
	$exportURL = action($controllerNamespace . 'FormController@postExport', ['appId' => $appId, 'formId' => (isset($form) ? $form->id : null)]);

	//compile table parameters
	$tableParameters = array(
		'title'=>'', 
		'tableId' => 'templateTable',
		'dataFunction'=>'initTemplateTable', 
		'editURL' => $editURL,
		'editField' => 'id',
		'columnSyntax' => Array(true)
	);
	
	
?>

<div style="background-color: #EEEEEE">


	{{-- select database view --}}
	<div ng-controller="FormController" ng-init="setDataURL('{{ $dataURL }}'); setEditURL('{{ $editURL }}'); showExport = {{ $showExport }};">
	
	
		{{-- title --}}
		<h2>{{ $formName }}</h2>

	
	
		<div class="form-group">
		
		
			{{-- draw table --}}
			@include('cms::cms.gui.table', $tableParameters)


		</div>
		
		
		<div class="form-group">
		
			{{-- export button --}}
			<a href="javascript:void(0)" class="btn btn-primary" ng-click="showExport = !showExport">
				<span ng-show="showExport">Hide Export Options</span>
	    		<span ng-hide="showExport">Show Export Options</span>
			</a>
	
		</div>
	
		<div class="form-group">
	
			{{ Form::open(Array('role' => 'form', 'name' => 'exportForm', 'url' => $exportURL, 'method' => 'POST')) }}
	
				{{-- export options --}}
				<div class="form-inline" ng-show="showExport">
				
					{{ Form::label('range', 'Range') }}
					<input type="text" name="start" value="0" class="form-control input-small" required>
					{{ Form::label('to', 'To') }}
					<input type="text" name="end" value="0" class="form-control input-small" required>
				
					<span class="pull-right"><save-form-button name="Export"></save-button></span>
				
					{{-- export view status --}}
					<input type="hidden" name="showExport" ng-value="showExport">
				
				</div>
				
			{{ Form::close() }}

		</div>

		
		<div class="form-group">
		
			{{-- add entry button --}}
			{{-- <a href="" class="btn btn-primary">Add Entry</a> --}}
	

		</div>
		
		{{-- display form errors --}}
	    @if ($errors->has())
	        @foreach ($errors->all() as $error)
	            <div class='bg-danger alert'>{{ $error }}</div>
	        @endforeach
	    @endif
		
	</div>
	{{-- end controller --}}	



	<br>
	
</div>

@stop
{{--------------- END CONTENT ----------------}}

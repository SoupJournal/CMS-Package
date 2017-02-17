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

	//get AJAX URL's
	$tableURL = action($controllerNamespace . "FormController@getTable", ['appId' => $appId, 'dbConnection' => null]);
	$fieldURL = action($controllerNamespace . "FormController@getField", ['appId' => $appId, 'dbConnection' => null, 'dbTable' => null]);

	//get available databases	
	$databaseConnections = Config::get('database.connections');
	
	//find exposed databases
	$databaseConnectionNames = [];
	foreach ($databaseConnections as $connection => $connectionData) {
		if (isset($connectionData['exposed']) && $connectionData['exposed']) {
			array_push($databaseConnectionNames, $connection);
		}
	}
	
	//$databaseConnectionNames = array_keys($databaseConnections);
	$databaseConnectionNames = convertObjectToJS($databaseConnectionNames, true);
//	$databaseConnectionNames = json_encode($databaseConnectionNames);
	//$databaseConnectionNames = addslashes($databaseConnectionNames);
	//$databaseConnectionNames = htmlspecialchars($databaseConnectionNames);
	//echo "databaseConnections: " . $databaseConnectionNames;
	$formTypes = array(
		'form',
		'template',
		'service'
	);
	$formTypesString = convertObjectToJS($formTypes);
	
	
	//field form
	$fieldDataURL = action($controllerNamespace . 'FormController@getFields', ['appId' => $appId, 'formId' => ($form ? $form->id : '')]);

	//compile table parameters
	$tableParameters = array(
		'title' => '', 
		'tableId' => 'fieldTable',
		'dataFunction' => 'initFieldTable', 
		//'editURL' => $editURL,
		'editField' => 'id'
	);
	
?>

<div style="background-color: #EEEEEE">


	{{-- display form errors --}}
    @if ($errors->has())
        @foreach ($errors->all() as $error)
            <div class='bg-danger alert'>{{ $error }}</div>
        @endforeach
    @endif
    


	{{-- select database view --}}
	<div ng-controller="FormController" ng-init='connections={{ $databaseConnectionNames }}; tableURL="{{ $tableURL }}"; tableContainer="edit_table_container"; fieldURL="{{ $fieldURL }}"; setDataURL("{{ $fieldDataURL }}");  fieldContainer="edit_field_container"; formTypes={{ $formTypesString }};'>
	
		
		{{ Form::open(Array('role' => 'form', 'name' => 'securityForm')) }}
		
			{{-- title --}}
			@if (isset($form))
				<h2>Edit Form</h2>			
			@else
				<h2>Create Form</h2>
			@endif
		
		
			<div class="form-group">
					
				{{ Form::label('name', 'Form Name') }}
				{{ Form::text('name', (isset($form) ? $form->name : null), Array ('placeholder' => 'Form Name', 'class' => 'form-control', 'required' => '')) }}
	
			</div>
		


			<div class="form-group">					
			
				{{ Form::label('key', 'Form Id') }}
				{{ Form::text('key', (isset($form) ? $form->key : null), Array ('placeholder' => 'Form Id', 'class' => 'form-control', 'required' => '')) }}
	
			</div>
		
		
		{{-- TODO: implement type --}}
		{{--
			<div class="form-group">
					
				{{ Form::label('type', 'Form Type') }}
				<div class="form-group">
					{{ Form::select('type', $formTypes, (isset($form) ? $form->type : null), ['data-ng-model' => 'form.type', 'change'=>'selectType()']) }}
					<!--select data-ng-model="form.type" ng-options="str for str in formTypes" ng-change="selectType()"--> 
					</select>
				</div>
				
			</div>
		--}}
		
		
		
		
			<!-- vertical-spacer size="50"></verticalSpacer -->
			<hr>
			
		
		
			{{-- fields selection --}}
			<div class="form-group">
			
				{{-- Form fields --}}
				<h3>Current Fields</h3>
		
				{{-- draw table --}}
				@include('cms::cms.gui.table', $tableParameters)

	
		
			{{-- end form group --}}
			</div>
			
			
			
		
		
			{{--add fields button --}}
			<a href="javascript:void(0)" class="btn btn-primary" ng-click="showAddFields = !showAddFields">
				<span ng-show="showAddFields">Hide New Fields</span>
	    		<span ng-hide="showAddFields">Add New Fields</span>
			</a>
		
		
			{{-- fields selection --}}
			<div id="addFieldsSection" class="form-group" ng-show="showAddFields">
			
				{{-- Add new fields --}}
				<h3>Add Fields</h3>
	
			
			
				<div class="form-group">
					<h5>SELECT DATABASE:<h5>
					<!-- select data-ng-model="database.connection" ng-options="str for str in connections track by str" ng-change="selectDatabase()" -->
					<select data-ng-model="database.connection" ng-options="str for str in connections" ng-change="selectDatabase()">
					</select>
				</div>
		
				
				
				
				{{-- select table view --}}
				<div ng-controller="DynamicContentController" ng-init='getContent("{{ $tableURL }}", "edit_table_container");'> 
				
					<div id="edit_table_container"></div>
				
				</div>
				{{-- end controller --}}
				
				
				
				
				
				{{-- select fields view --}}
				<div ng-controller="DynamicContentController" ng-init='getContent("{{ $fieldURL }}", "edit_field_container");'> 
				
					<div id="edit_field_container"></div>
				
				</div>
				{{-- end controller --}}
		
		
		
		
				{{-- save button --}}
				<!-- div class="form-group">
					<save-button controller="FormController" action="saveForm"></save-button>
				</div -->
						
	
	
			{{-- end form group --}}
			</div>
			
			
		
			
			
			<br>
			<br>
			
			
			{{-- save button --}}
			<div class="form-group pull-right">
				<save-form-button confirm-form="securityForm" confirm-message="Add new form?"></save-button>
			</div>
			
			
			
		{{ Form::close() }}	
			
	</div>
	{{-- end controller --}}	



	<br>
	
	
	<!-- div class="form-group">
	
		<a class="admin_button">Add Field</a>
	
		{{-- new field view --}}
		<div ng-controller="DynamicContentController" ng-init='getContent("{{ $fieldURL }}", "new_field_container");'> 
		
			<div id="new_field_container"></div>
		
		</div>
	
	</div -->


	<!-- form method="POST">
	
		<input type="submit" value="Save">
	
	</form -->

</div>

@stop
{{--------------- END CONTENT ----------------}}

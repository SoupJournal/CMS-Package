@extends('cms::layouts.master')


{{------------------ TITLE -------------------}}

@section('title') Form Creation @stop

{{---------------- END TITLE -----------------}}




{{----------------- SCRIPTS ------------------}}

@section('scripts')

	{{ HTML::script('packages/artisan/cms/js/cms/pages/form.js') }}	
	
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
	$tableURL = URL::to('cms/' . $appId . '/form/table'); //action("FormController@getTable");
	$fieldURL = URL::to('cms/' . $appId . '/form/field'); //action("FormController@getField");

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
	
?>

<div style="background-color: #EEEEEE">


	{{-- display form errors --}}
    @if ($errors->has())
        @foreach ($errors->all() as $error)
            <div class='bg-danger alert'>{{ $error }}</div>
        @endforeach
    @endif
    


	{{-- select database view --}}
	<div ng-controller="FormController" ng-init='connections={{ $databaseConnectionNames }}; tableURL="{{ $tableURL }}"; tableContainer="edit_table_container"; fieldURL="{{ $fieldURL }}"; fieldContainer="edit_field_container";'>
	
		
		{{ Form::open(Array('role' => 'form', 'name' => 'securityForm')) }}
		
			{{-- title --}}
			<h2>Create Form</h2>
		
		
			<div class="form-group">
					
				{{ Form::label('name', 'Form Name') }}
				{{ Form::text('name', null, Array ('placeholder' => 'Form Name', 'class' => 'form-control', 'required' => '')) }}
	
			</div>
		
		
			<div class="form-group">
					
				{{ Form::label('type', 'Form Type') }}
				
			</div>
		
		
		
		
		
			<!-- vertical-spacer size="50"></verticalSpacer -->
			<hr>
			
		
		
			{{--add fields button --}}
			<a href="#" class="btn btn-primary" ng-click="showAddFields = !showAddFields">
				<span ng-show="showAddFields">Hide New Fields</span>
	    		<span ng-hide="showAddFields">Add New Fields</span>
			</a>
		
		
			{{-- fields selection --}}
			<div id="addFieldsSection" class="form-group" ng-show="showAddFields">
			
				{{-- title --}}
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
				<save-form-button></save-button>
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

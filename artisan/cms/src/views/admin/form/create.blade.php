@extends('cms::layouts.master')


{{------------------ TITLE -------------------}}

@section('title') Form Creation @stop

{{---------------- END TITLE -----------------}}




{{----------------- SCRIPTS ------------------}}

@section('scripts')

	{{ HTML::script('packages/artisan/cms/js/cms/form/form.js') }}	
	
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
	$tableURL = action("FormController@getTable");
	$fieldURL = action("FormController@getField");

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


	{{-- select database view --}}
	<div ng-controller="FormController" ng-init='connections={{ $databaseConnectionNames }}; tableURL="{{ $tableURL }}"; tableContainer="edit_table_container"; fieldURL="{{ $fieldURL }}"; fieldContainer="edit_field_container";'>
	
	
		{{-- title --}}
		<h2>Create Form</h2>
	
	
		<div class="form-group">
								
			<h5>NAME<h5>
			<input type="text">
			
		</div>
	
	
	
		<!-- vertical-spacer size="50"></verticalSpacer -->
		<hr>
		
	
	
		<div class="form-group">
		
			{{-- title --}}
			<h2>Add Fields</h2>

		
		
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

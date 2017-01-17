@extends('cms::layouts.admin')


{{------------------ TITLE -------------------}}

@section('title') Form Creation @stop

{{---------------- END TITLE -----------------}}




{{----------------- SCRIPTS ------------------}}

@section('scripts')

	{{ HTML::script('packages/artisan/cms/js/cms/form/form.js') }}	
	
	<?php
	
		//set custom page controllers
		$pageModules = Array('cms-form');

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
		<h2>Forms</h2>
	
	

	
	
		<div class="form-group">
		

	

		{{-- end form group --}}
		</div>
		
	</div>
	{{-- end controller --}}	



	<br>
	
</div>

@stop
{{--------------- END CONTENT ----------------}}

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
	$tableURL = action("FormController@getTable");
	$fieldURL = action("FormController@getField");
	$dataURL = URL::to('cms/' . $appId . '/form/forms');
	$editURL = URL::to('cms/' . $appId . '/form/edit/');

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
		<h2>Forms</h2>

	
	
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

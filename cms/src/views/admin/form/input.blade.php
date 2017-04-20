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

	//ensure properties set
	$form = isset($form) ? $form : null;
	$fields = isset($fields) ? $fields : null;
	$fieldValues = isset($fieldValues) ? $fieldValues : null;
	$filter = isset($filter) ? $filter : null;
	$formURL = isset($formURL) ? $formURL : "";

	//get form properties
	$formName = safeObjectValue('name', $form, "");
//	$fields = isset($form) ? $form->fields()->orderBy('order', 'DESC')->get() : null;
//	$fieldValues = isset($form) ? dataForForm($form->key) : null;

	//get filter properties
	$filterRow = safeArrayValue('row', $filter, '');
	
?>

<div style="background-color: #EEEEEE">


	{{-- select database view --}}
	<div ng-controller="FormController" ng-init="">
	
	
		{{-- title --}}
		<h2>{{ $formName }}</h2>

	
	
		{{-- display fields --}}
		@if (isset($fields) && count($fields)>0) 
		
			{{ Form::open(Array('url' => $formURL, 'role' => 'form', 'name' => 'inputForm')) }}
			
				<div class="form-group">
				
					<?php
					
						//draw form fields
						$fieldName = null;
						$fieldKey = null;
						$fieldValue = null;
						$fieldProperties = null;
						foreach ($fields as $field) {
	
							//get field properties
							$fieldName = safeObjectValue('name', $field, "");
							$fieldKey = safeObjectValue('key', $field, "");
							$fieldValue = safeArrayValue($fieldKey, $fieldValues, "");
							//TODO: JSON decode fieldProperties
					?>
	
							<div class="form-group">
								{{ Form::label('name', $fieldName) }}
								{{ Form::text($fieldKey, $fieldValue, Array ('placeholder' => $fieldName, 'class' => 'form-control')) }}
							</div>
	
					<?php
					
						} //end for()
					
					?>
					
					
					{{-- save button --}}
					<div class="form-group pull-right">
						<save-form-button confirm-form="inputForm" confirm-message="Save changes? Changes will be applied to the live site."></save-button>
					</div>
					
				</div>
			
			
				@if (isset($filter)) 
					<input type="hidden" name="filter_row" value="{{ $filterRow }}">
				@endif
			
			{{ Form::close() }}	
			
		@endif
		{{-- end display fields --}}
		
	</div>
	{{-- end controller --}}	



	<br>
	
</div>

@stop
{{--------------- END CONTENT ----------------}}

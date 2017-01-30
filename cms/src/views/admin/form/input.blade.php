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
	$fields = isset($form) ? $form->fields()->orderBy('order', 'DESC')->get() : null;
	$fieldValues = isset($form) ? dataForForm($form->key) : null;
	
?>

<div style="background-color: #EEEEEE">


	{{-- select database view --}}
	<div ng-controller="FormController" ng-init="">
	
	
		{{-- title --}}
		<h2>{{ $formName }}</h2>

	
	
		{{-- display fields --}}
		@if (isset($fields) && count($fields)>0) 
		
			{{ Form::open(Array('role' => 'form', 'name' => 'inputForm')) }}
			
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
						<save-form-button></save-button>
					</div>
					
				</div>
			
			{{ Form::close() }}	
			
		@endif
		{{-- end display fields --}}
		
	</div>
	{{-- end controller --}}	



	<br>
	
</div>

@stop
{{--------------- END CONTENT ----------------}}

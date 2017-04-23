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
						$fieldType = null;
						$fieldValue = null;
						$fieldProperties = null;
						foreach ($fields as $field) {

							//get field properties
							$fieldName = safeObjectValue('name', $field, "");
							$fieldKey = safeObjectValue('key', $field, "");
							$fieldType = strtolower(safeObjectValue('type', $field, ""));
							$fieldValue = safeArrayValue($fieldKey, $fieldValues, "");
							$fieldProperties = decodeJSON(safeObjectValue('properties', $field, null), true);
							$fieldEditable = safeObjectValue('editable', $field, false);
							
							//update field name
							if (is_null($fieldName) || strlen($fieldName)<=0) {
								$fieldName = ucwords($fieldKey);	
							}
					?>
	
							<div class="form-group">
	
								<?php
	
									//disabled attribute
									$disabledAttr = $fieldEditable ? [] : ['readonly' => 'readonly'];
									//$disabledAttr = $fieldEditable ? [] : ['disabled' => ''];
									
									
									//indicate label position
									$labelFirst = true;
									$labelClass = "";
									
									//input HTML
									$inputHTML = "";
									
									//draw field
									switch ($fieldType) {
										
										case 'number':
											$inputHTML = Form::number($fieldKey, $fieldValue, Array ('placeholder' => $fieldName, 'class' => 'form-control') + $disabledAttr);
										break;
										
										case 'textarea':
											$inputHTML = Form::textarea($fieldKey, $fieldValue, Array ('placeholder' => $fieldName, 'class' => 'form-control') + $disabledAttr);
										break;
										
										case 'html':
											$inputHTML = Form::textarea($fieldKey, $fieldValue, Array ('placeholder' => $fieldName, 'class' => 'form-control') + $disabledAttr);
										break;
										
										case 'json':
											$inputHTML = Form::textarea($fieldKey, $fieldValue, Array ('placeholder' => $fieldName, 'class' => 'form-control') + $disabledAttr);
										break;
										
										case 'check':
											$labelFirst = false;
											$labelClass = "inline";
											$inputHTML = Form::checkbox($fieldKey, "1", $fieldValue?true:false, Array ('class' => 'form-control cms-input-checkbox inline') + $disabledAttr);
										break;
										
										case 'select':
										{
											//get select options
											$options = safeArrayValue('options', $fieldProperties, []);
											
											//check if current value exists in drop down
											if (!array_key_exists($fieldValue, $options)) {
												$options += [$fieldValue => $fieldValue];	
											}
											
											//append field name as placeholder
											$options = ['' => $fieldName] + $options;
//											$placeholder = [
//												'name' => $fieldName,
//												'attributes' => ['disabled'=>'']
//											];
//											$options = ['' => $placeholder] + $options;

											//draw select field
											//$inputHTML = formSelect($fieldKey, $options, $fieldValue, ['class'=>'form-control']);
											$inputHTML = Form::select($fieldKey, $options, $fieldValue, Array (/*'placeholder' => $fieldName,*/ 'class' => 'form-control') + $disabledAttr);
										}
										break;
										
										case 'reference':
										{
											//get reference properties
											$connection = safeArrayValue('connection', $fieldProperties, null);
											$table = safeArrayValue('table', $fieldProperties, null);
											$field = safeArrayValue('field', $fieldProperties, null);
											$displayFields = safeArrayValue('display_fields', $fieldProperties, null);

											//valid reference
											$validReference = false;
											if ($connection && $table && $field && $displayFields && strlen($connection)>0 && strlen($table)>0 && strlen($field)>0 && count($displayFields)>0) {
												
												//create query
												$results = DB::connection($connection)
													->table($table)
													->select($displayFields)
													//->where($field, $fieldValue)
													->get();

												//found options
												if ($results) {
												
													//check if current value found in table
													$currentValueExists = false;
												
													//compile options
													$options = [];
													foreach ($results as $option) {

														//compile label
														$label = "";
														$firstLabel = true;
														foreach ($displayFields as $key) {
															
															//add space
															if (!$firstLabel) $label .= ' ';
															
															//add label
															$label .= safeArrayValue($key, $option, '');
															
															//indicate label added
															$firstLabel = false;
														}
														
														//create option
														$id = safeArrayValue($field, $option, null);
														if (!is_null($id)) {
															$options += [$id => $label];	
														}
														
														
														//check if current value found in table
														if (!$currentValueExists && strcmp($id, $fieldValue)==0) {
															$currentValueExists = true;
														}
														
													} //end for()
												

													//found options
													if ($options && count($options)>0) {
	
														//add current option if it doesn't exist in 
														if (!$currentValueExists) {
															$options += [$fieldValue => $fieldValue . " << missing value >>"];
														}
	
														//append field name as placeholder
														$options = ['' => $fieldName] + $options;
			
														//draw select field
														$inputHTML = Form::select($fieldKey, $options, $fieldValue, Array ( 'class' => 'form-control') + $disabledAttr);
														//indicate reference is valid
														$validReference = true;
														
													} //end if (found options)
													
												} //end if (found options)
												
											} //end if (valid reference)
											
											//invalid reference (default to text field)
											if (!$validReference) {
												$inputHTML = Form::text($fieldKey, $fieldValue, Array ('placeholder' => $fieldName, 'class' => 'form-control') + $disabledAttr);
											}
												
										}
										break;
										
										case 'image':
										
											$inputHTML = "" .
											"<div>\n" .
												"<div class=\"cms-input-image\">\n" .
													"<image class=\"cms-input-image-preview\" src=\"" . $fieldValue . "\">\n" .
												"</div>\n" .
												Form::text($fieldKey, $fieldValue, Array ('placeholder' => $fieldName, 'class' => 'form-control') + $disabledAttr) .
											"</div>\n";
										
										break;
										
										default:
											$inputHTML = Form::text($fieldKey, $fieldValue, Array ('placeholder' => $fieldName, 'class' => 'form-control') + $disabledAttr);
										break;
										
									} //end switch (type)
								
								
								
									//draw label
									if ($labelFirst) {
										echo Form::label('name', $fieldName, Array('class' => $labelClass));
									}
									
									//draw input
									echo $inputHTML;
									
									//draw label
									if (!$labelFirst) {
										echo Form::label('name', $fieldName, Array('class' => $labelClass));
									}
									
								?>

							</div>
	
					<?php
					
						} //end for()
					
					?>
					
					
					
					{{-- save button --}}
					<div class="form-group pull-right">
						<save-form-button confirm-form="inputForm" confirm-message="Save changes? Changes will be applied to the live site."></save-form-button>
					</div>
					
					{{-- cancel button --}}
					@if (isset($backURL))
						<a href="{{ $backURL }}" class="cms-form-button btn-secondary cms-button-margin-right pull-right">Cancel</a>
					@endif
					
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

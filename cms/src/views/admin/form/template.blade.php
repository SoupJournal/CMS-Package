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
	$appKey = isset($appKey) ? $appKey : null;
	$form = isset($form) ? $form : null;
	$triggers = isset($triggers) ? $triggers : null;
	$showExport = Request::old('showExport');
	$showExport = isset($showExport) && $showExport ? $showExport : 'false';

	//get form properties
	$formName = safeObjectValue('name', $form, "");
	$formId = safeObjectValue('id', $form, null);
	$appId = safeObjectValue('application', $form, null);

	//get AJAX URL's
	$dataURL = route('cms.form.templates', ['appKey' => $appKey, 'formId' => $formId]);
	$addURL = route('cms.form.input.id', ['appKey' => $appKey, 'formId' => $formId, 'rowId' => -1]); 
	$editURL = route('cms.form.input.id', ['appKey' => $appKey, 'formId' => $formId, 'rowId' => '']);
	$deleteURL = route('cms.form.input.delete', ['appKey' => $appKey, 'formId' => $formId, 'rowId' => '']);
	$exportURL = route('cms.form.export', ['appKey' => $appKey, 'formId' => $formId]);
//Route::delete($uri, $callback);


	//compile table parameters
	$tableParameters = array(
		'title'=>'', 
		'tableId' => 'templateTable',
		'dataFunction'=>'initTemplateTable', 
//		'editURL' => $editURL,
//		'deleteURL' => $deleteURL,
		'editField' => 'id',
		'columnSyntax' => Array(true)
	);
	
?>

<div style="background-color: #EEEEEE">


	{{-- select database view --}}
	<div ng-controller="FormController" ng-init="setDataURL('{{ $dataURL }}'); setEditURL('{{ $editURL }}'); setDeleteURL('{{ $deleteURL }}'); showExport = {{ $showExport }};">
	
	
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
	
				
			{{-- add entry button --}}
			<a href="{{ $addURL }}" class="btn btn-primary pull-right">Add Entry</a>
	
		</div>
	
		<div class="form-group">
	
			{{-- export form --}}
			{{ Form::open(Array('role' => 'form', 'name' => 'exportForm', 'url' => $exportURL, 'method' => 'POST')) }}
	
				{{-- export options --}}
				<div class="form-inline" ng-show="showExport">
				
					{{ Form::label('range', 'Range') }}
					<input type="text" name="start" value="0" class="form-control input-small" required>
					{{ Form::label('to', 'To') }}
					<input type="text" name="end" value="0" class="form-control input-small" required>
				
					<span class="pull-right"><button cms-button name="Export" download type="submit">Export</button></span>
				
					{{-- export view status --}}
					<input type="hidden" name="showExport" ng-value="showExport">
				
				</div>
				
			{{ Form::close() }}
			
			
			<hr></hr>
			
			{{-- trigger forms --}}
			@if ($triggers && count($triggers)>0) 
				
				<div class="form-group">
		
					{{-- options button --}}
					<a href="javascript:void(0)" class="btn btn-info" ng-click="showTriggers = !showTriggers">
						<span ng-show="showTriggers">Hide Form Options</span>
			    		<span ng-hide="showTriggers">Show Form Options</span>
					</a>
			
				</div>
				
				<?php
					
					foreach ($triggers as $trigger) {
					
						//get trigger properties
						$triggerId = safeObjectValue('id', $trigger, "");
						$triggerName = safeObjectValue('name', $trigger, "");
						$triggerStub = safeObjectValue('stub', $trigger, "");
						$triggerDisplayData = safeObjectValue('properties', $trigger, null);
						$triggerURL = route('cms.form.trigger', [
							'appKey' => $appKey,
							//'formId' => $formId,
							'triggerId' => $triggerId
						]);
	 					
	 					//decode trigger properties
	 					$triggerProperties = decodeJSON($triggerDisplayData);
	 					

						//valid form
						if ($triggerURL && strlen($triggerURL)>0) {
						
							//draw form
							echo Form::open(Array('role' => 'form', 'name' => $triggerStub, 'url' => $triggerURL, 'method' => 'POST'));
						?>	  
				
							<div class="form-inline" ng-show="showTriggers">
				
								{{ Form::label('range', 'Range') }}
								<input type="text" name="start" value="0" class="form-control input-small" required>
								{{ Form::label('to', 'To') }}
								<input type="text" name="end" value="0" class="form-control input-small" required>
							
								<span class="pull-right">
									<button cms-button="{{ $triggerStub }}" type="submit">{{ $triggerName }}</button>
								</span>
							
							</div>
							 
						<?php  
							 
							//handle display properties
							if ($triggerProperties) {
							 	
								//get properties data
	 					//$triggerURL = safeObjectValue('url', $triggerProperties, "");
						//$triggerMethod = safeObjectValue('method', $triggerProperties, "GET");
							 	
							} //end if (found properties)
							 
							//close form
							echo Form::close();
						 
						} //end if (valid form)
					 
 					

					} //end for()
			
				?>
					
			@endif
				
			
			
			{{-- delete form --}}
			{{ Form::open(Array('role' => 'form', 'name' => 'deleteForm', 'url' => $deleteURL, 'method' => 'DELETE')) }}
			
				{{ Form::hidden('key', 'id') }}
				
				{{-- Form::hidden('rows[]', '#{ row }#' , array('ng-repeat' => "row in selectedFields")) --}}
				<input name="rows[]" value="#{ row }#" ng-repeat="row in selectedFields">
			
			{{ Form::close() }}

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

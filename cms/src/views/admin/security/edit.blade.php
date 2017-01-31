@extends('cms::layouts.master')


{{------------------ TITLE -------------------}}

@section('title') Applications @stop

{{---------------- END TITLE -----------------}}




{{----------------- SCRIPTS ------------------}}

@section('scripts')

	{{ HTML::script($assetPath . '/js/cms/pages/security.js') }}
	
	<?php
	
		//set custom page controllers
		array_push($pageModules, 'cms.security');

	?>
	
@stop
{{--------------- END SCRIPTS ----------------}}





{{----------------- CONTENT ------------------}}

@section('content')

<?php

	//get AJAX URL's
	$dataURL = action('SecurityController@getGroups', ['appId' => $appId]);

	
?>

<div style="background-color: #EEEEEE">


	
	{{-- display form errors --}}
    @if ($errors->has())
        @foreach ($errors->all() as $error)
            <div class='bg-danger alert'>{{ $error }}</div>
        @endforeach
    @endif
    
    


	{{-- select database view --}}
	<div ng-controller="SecurityController" ng-init="setDataURL('{{ $dataURL }}');">
	
	
		{{ Form::open(Array('role' => 'form', 'name' => 'securityForm')) }}
	
		
			{{-- title --}}
			<div class="row">
				<h2>Security Group</h2>
				<br>
			</div>
		
		
		
			<div class="row">
				<div class="form-group">
				
					{{ Form::label('name', 'Group Name') }}
					{{ Form::text('name', null, Array ('placeholder' => 'Group Name', 'class' => 'form-control', 'required' => '')) }}
		
				</div>
			</div>
			
	
	
			{{-- list avavailable permissions --}}
			<div class="row">
				<div class="form-group">
					<h5>Permissions</h5>
					<?php
					
						//valid permissions
						if (isset($availablePermissions) && is_array($availablePermissions) && count($availablePermissions)>0) {
							
							$count = 0;
							foreach ($availablePermissions as $key => $value) {
							
								//valid permission
								if ($key>=0) {	
								
					?>
					
									{{-- draw permission option --}}
									<div class="checkbox">
										<label>
										{{ Form::checkbox('permission[' . $count .']', $value); }}
										{{ Form::label($value, $value) }}
										</label>
									</div>
					
					<?php
					
									//increment input count
									++$count;
					
								} //end if (valid permission)
					
							} //end for()
					
						} //end if (valid permissions)
					?>
				</div>
			</div>
	
	
			<div class="row">
				<div class="form-group">
					<h5>Users</h5>
					
					{{-- draw table --}}
					@include('cms::cms.gui.table', array('title'=>'', 'dataFunction'=>'initGroupTable'))
					
				</div>
			</div>
	
	
			{{-- save button --}}
			<div class="form-group">
				<save-form-button></save-form-button>
			</div>
						
			
			
		{{ Form::close() }}
		
		
		
	</div>
	{{-- end controller --}}	



	<br>
	
</div>

@stop
{{--------------- END CONTENT ----------------}}

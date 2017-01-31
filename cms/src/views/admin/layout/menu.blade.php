<?php
	
	//determine visible menu items
	$showSecurity = CMSAccess::validPermissionFromList($userPermissions, CMSAccess::$PERMISSION_EDIT_SECURITY);
	$showForms = CMSAccess::validPermissionFromList($userPermissions, CMSAccess::$PERMISSION_EDIT_FORM);
	$showSettings = CMSAccess::validPermissionFromList($userPermissions, CMSAccess::$PERMISSION_CREATE_APPLICATION)
				|| CMSAccess::validPermissionFromList($userPermissions, CMSAccess::$PERMISSION_EDIT_APPLICATION);


?>
<div class=" text-center">

	<ul class="nav text-left nav-menu menu-main">
	
			{{-- Main Menu --}}
	
			<li><a href="{{  URL::action('CMSController@getIndex') . '/' . $appId }}">Overview</a></li>
		@if ($showSettings)
			<li><a href="{{ URL::action('SettingsController@getIndex', ['appId' => $appId]) }}">Settings</a></li>
		@endif
		@if ($showForms)
			<li><a href="{{ URL::action('FormController@getIndex', ['appId' => $appId]) }}">Forms</a></li>
		@endif
			{{-- <li><a href="#">Pages</a></li>	--}}
		@if ($showSecurity)
			<li><a href="{{ URL::action('SecurityController@getIndex', ['appId' => $appId]) }}">Security Groups</a></li>
		@endif
			
		
	</ul>

	<ul class="nav text-left nav-menu menu-pages">

			<li class="nav-divider"></li>
			
			{{-- Pages --}}
			<?php
				if (isset($forms)) {
					
					//add form links
					$formName = null;
					$formKey = null;
					foreach ($forms as $form) { 
						
						//get form properties
						$formName = safeObjectValue('name', $form, null);
						$formId = safeObjectValue('id', $form, null);

						//valid properties
						if ($formName && $formId!=null && strlen($formName)>0 && $formId>=0) {
							
						?>
						
							<li><a href="{{ URL::action('FormController@getInput', ['appId' => $appId, 'formId' => $formId]) }}">{{ $formName }}</a></li>
				
						<?php
						
						} //end if (valid form)
				
					} //end for()
				
				} //end if (forms set)
			?>

	</ul>
	
	
</div>
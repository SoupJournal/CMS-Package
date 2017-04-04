<?php
	
	use Soup\Cms\Lib\CMSAccess;
	//use Soup\Cms\Controllers\CMSController;
	
	
	//determine visible menu items
	$showSecurity = CMSAccess::validPermissionFromList($userPermissions, CMSAccess::$PERMISSION_EDIT_SECURITY);
	$showForms = CMSAccess::validPermissionFromList($userPermissions, CMSAccess::$PERMISSION_EDIT_FORM);
	$showSettings = CMSAccess::validPermissionFromList($userPermissions, CMSAccess::$PERMISSION_CREATE_APPLICATION)
				|| CMSAccess::validPermissionFromList($userPermissions, CMSAccess::$PERMISSION_EDIT_APPLICATION);

	$showInput = CMSAccess::validPermissionFromList($userPermissions, CMSAccess::$PERMISSION_FORMS);


	//get controller paths
	$homePath = route('cms.home', ['appId' => $appId]);
	$settingsPath = action($controllerNamespace . 'SettingsController@getIndex', ['appId' => $appId]);
	$formsPath = action($controllerNamespace . 'FormController@getIndex', ['appId' => $appId]);
	//$inputPath = action($controllerNamespace . 'FormController@getInput', ['appId' => $appId]);
	$securityPath = action($controllerNamespace . 'SecurityController@getIndex', ['appId' => $appId]);
	

?>
<div class=" text-center">

	<ul class="nav text-left nav-menu menu-main">
	
			{{-- Main Menu --}}
	
			<li><a href="{{ $homePath }}">Overview</a></li>
		@if ($showSettings)
			<li><a href="{{ $settingsPath }}">Settings</a></li>
		@endif
		@if ($showForms)
			<li><a href="{{ $formsPath }}">Forms</a></li>
		@endif
			{{-- <li><a href="#">Pages</a></li>	--}}
		@if ($showSecurity)
			<li><a href="{{ $securityPath }}">Security Groups</a></li>
		@endif
			
	
	</ul>

	<ul class="nav text-left nav-menu menu-pages">

			<li class="nav-divider"></li>
			
			{{-- Pages --}}
			<?php
				if ($showInput && isset($forms)) {
					
					//add form links
					$formName = null;
					$formKey = null;
					foreach ($forms as $form) { 
						
						//get form properties
						$formName = safeObjectValue('name', $form, null);
						$formId = safeObjectValue('id', $form, null);
						$formType = safeObjectValue('type', $form, null);

						//valid properties
						if ($formName && $formId!=null && strlen($formName)>0 && $formId>=0) {
							
						?>

							<li><a href="{{ action($controllerNamespace . 'FormController@getInput', ['appId' => $appId, 'formId' => $formId]) }}" class="form_link_{{ $formType }}">{{ $formName }}</a></li>

						<?php
						
						} //end if (valid form)
				
					} //end for()
				
				} //end if (forms set)

			?>

	</ul>
	
	<div class="spacer-medium"></div>
	
</div>
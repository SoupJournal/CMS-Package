<?php

	//get user permissions
	$permissions = CMSAccess::userPermissions($appId);
	
	//get permission keys
	$formKey = CMSAccess::permissionKey(CMSAccess::$PERMISSION_EDIT_FORM);
	$securityKey = CMSAccess::permissionKey(CMSAccess::$PERMISSION_EDIT_SECURITY);
	
	//determine visible menu items
	$showSecurity = (isset($permissions) && array_key_exists($securityKey, $permissions) && $permissions[$securityKey]);
	$showForms = (isset($permissions) && array_key_exists($formKey, $permissions) && $permissions[$formKey]);


?>
<div class="affix text-center">

	<ul class="nav text-left">
	
			<li><a href="{{ URL::to('cms/' . $appId) }}">Overview</a></li>
			<li><a href="{{ URL::to('cms/app') }}">Applications</a></li> {{-- move to top nav?? --}}
		@if ($showForms)
			<li><a href="{{ URL::to('cms/' . $appId . '/form') }}">Forms</a></li>
		@endif
			<li><a href="#">Pages</a></li>	
		@if ($showSecurity)
			<li><a href="{{ URL::to('cms/' . $appId . '/security') }}">Security Groups</a></li>
		@endif
		
	</ul>
	
	
</div>
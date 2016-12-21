<?php
	
	//determine visible menu items
	$showSecurity = CMSAccess::validPermissionFromList($userPermissions, CMSAccess::$PERMISSION_EDIT_SECURITY);
	$showForms = CMSAccess::validPermissionFromList($userPermissions, CMSAccess::$PERMISSION_EDIT_FORM);
	$showSettings = CMSAccess::validPermissionFromList($userPermissions, CMSAccess::$PERMISSION_CREATE_APPLICATION)
				|| CMSAccess::validPermissionFromList($userPermissions, CMSAccess::$PERMISSION_EDIT_APPLICATION);

?>
<div class="affix text-center">

	<ul class="nav text-left">
	
	
			<li><a href="{{ URL::to('cms/' . $appId) }}">Overview</a></li>
		@if ($showSettings)
			<li><a href="{{ URL::to('cms/' . $appId . '/settings') }}">Settings</a></li>
		@endif
		@if ($showForms)
			<li><a href="{{ URL::to('cms/' . $appId . '/form') }}">Forms</a></li>
		@endif
			<li><a href="#">Pages</a></li>	
		@if ($showSecurity)
			<li><a href="{{ URL::to('cms/' . $appId . '/security') }}">Security Groups</a></li>
		@endif


	</ul>
	
	
</div>
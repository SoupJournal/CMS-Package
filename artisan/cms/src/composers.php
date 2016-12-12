<?php


	//==========================================================//
	//====					CMS COMPOSERS					====//
	//==========================================================//	


	View::composer(['cms::master', 'cms:page', 'cms::admin.*'], function($view)
	{
	    $view->with('pageModules', Array());
	});


?>
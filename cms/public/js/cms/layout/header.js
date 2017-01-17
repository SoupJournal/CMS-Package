//anonymous function to load features without name conflicts
(function() {
	
	//create module
	var module = angular.module('cms.header', ['ngResource']);

	
	//create controller
	module.controller('HeaderController', ['$scope', 'PageService', function($scope, $page) {
		
		
	}]); //end controller
		
})();
//end anonymous function
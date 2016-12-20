//anonymous function to load features without name conflicts
(function() {
	
	//create module
	var module = angular.module('cms.application', ['ngResource']);
	
	
	
	//create controller
	module.controller('ApplicationController', [ '$scope', 'PageService', function($scope, $page) {
		
		
		//set url for form data
		$scope.setDataURL = function(url) {
			$scope.appDataURL = url;	
		}
		
		
		
		//setup application table
		$scope.initApplicationTable = function(scope) {

			//valid scope
			if (scope) {
				scope.dataURL = $scope.appDataURL;	
				scope.includeKeys = ['name'];
				scope.excludeKeys = [];
			}
			
		} //end initApplicationTable()
		
		
	}]); //end controller
		
})();
//end anonymous function
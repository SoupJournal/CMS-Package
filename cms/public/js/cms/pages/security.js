//anonymous function to load features without name conflicts
(function() {
	
	//create module
	var module = angular.module('cms.security', ['ngResource']);
	
	
	
	//create controller
	module.controller('SecurityController', [ '$scope', 'PageService', function($scope, $page) {
		
		$scope.test = "hello";
		
		//set url for form data
		$scope.setDataURL = function(url) {
			$scope.dataURL = url;	
		}
		
		
		
		//setup security group table
		$scope.initSecurityTable = function(scope) {

			//valid scope
			if (scope) {
				scope.dataURL = $scope.dataURL;	
				scope.includeKeys = ['name', 'permission'];
				scope.excludeKeys = [];
			}
			
		} //end initSecurityTable()
		
		
		
		//setup security group users table
		$scope.initGroupTable = function(scope) {

			//valid scope
			if (scope) {
				scope.dataURL = $scope.dataURL;	
				scope.includeKeys = ['first_name', 'last_name'];
				scope.excludeKeys = [];
			}
			
		} //end initGroupTable()
		
		
		
		
		//save security group
		$scope.saveForm = function() {

			try {
				
				//valid form		
				var valid = false;
			
				//form exists
				if (securityForm) {
					
					//validate fields
					if (securityForm.name) {
						var value = securityForm.name.value; 	
						valid |= (value && value.length>0);
					} 
					
				} //end if (found form)
			
			
				//valid form
				if (valid) {
					
				}
				//error 
				else {
					
				}
				
			}
			catch (ex) {
				safeErr("Error submitting security form: " + ex);	
			}
			
		};
		
		
	}]); //end controller
		
})();
//end anonymous function
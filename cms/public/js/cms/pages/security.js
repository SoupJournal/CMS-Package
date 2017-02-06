//anonymous function to load features without name conflicts
(function() {
	
	//create module
	var module = angular.module('cms.security', ['ngResource']);
	
	
	
	//create controller
	module.controller('SecurityController', [ '$scope', 'PageService', function($scope, $page) {
		
		//set url for form data
		$scope.setDataURL = function(url) {
			$scope.dataURL = url;	
		}
		
		//set url for group editing
		$scope.setEditURL = function(url) {
			$scope.editURL = url;	
		}
		
		
		
		//setup security group table
		$scope.initSecurityTable = function(scope) {

			//valid scope
			if (scope) {
				scope.dataURL = $scope.dataURL;	
				scope.includeKeys = ['name', 'permission'];
				scope.excludeKeys = [];
				scope.columnProperties = { 
					2: {
						html: true			
					}
				};
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
		
		
		
		
		

		//respond to table update events
		$scope.$on('tableUpdated', function(event, obj) {

    	  	//valid data
    	  	if (obj) {
    	  		

    	  		//form table
    	  		if (obj.tableId == 'securityTable') {
    	  		
	    	  		//get object properties
	    	  		var rawData = obj.data;
	    	  		var filteredData = obj.results;

	    	  		//valid data
	    	  		if (rawData && filteredData && rawData.length>0 && filteredData.length>=rawData.length) {
	 
	 					//valid edit URL
	 					if ($scope.editURL && $scope.editURL.length>0) {
	 
		 					//process data
		 					for (var i=0; i<rawData.length; ++i) {
		 						
		 						//append edit field
		 						filteredData[i].push('<edit-button href="' + $scope.editURL + '/' + rawData[i]['id'] + '">edit</edit-button>');
		 						
		 					} //end for()
	 					
	 					
		 					//update values (no need to broadcast - editing same object instance)
//							$scope.$broadcast('applyValues', { 
//								tableId: 'formTable',
//								results: filteredData
//							});

	 					} //end if (valid edit URL)
	 					
	    	  		
	    	  		}
    	  		
    	  		} //end if (form table)
    	  		
    	  	} //end if (valid data)
    	  	
	    }); //end event handler()
		
		
	}]); //end controller
		
})();
//end anonymous function
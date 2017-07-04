//anonymous function to load features without name conflicts
(function() {
	
	//create module
	var module = angular.module('cms.form', ['ngResource']);
	
	
	
	//create controller
	module.controller('FormController', [ '$http', '$scope', '$window', 'PageService', function($http, $scope, $window, $page) {
		

		//list of selected fields
		$scope.selectedFields = [];


		//set url for form data
		$scope.setDataURL = function(url) {
			$scope.dataURL = url;	
		}
		
		//set url for form editing
		$scope.setEditURL = function(url) {
			$scope.editURL = url;	
		}
		
		//set url for form deletion
		$scope.setDeleteURL = function(url) {
			$scope.deleteURL = url;	
		}
		
		
		//setup forms table
		$scope.initFormTable = function(scope) {

			//valid scope
			if (scope) {
				scope.tableId = 'formTable';
				scope.dataURL = $scope.dataURL;	
				scope.includeKeys = ['name'];
				scope.excludeKeys = [];
				scope.columnProperties = { 
					1: {
						html: true			
					}
				};
			}
			
		} //end initFormTable()


		
		//setup fields table
		$scope.initFieldTable = function(scope) {

			//valid scope
			if (scope) {
				scope.tableId = 'fieldTable';
				scope.dataURL = $scope.dataURL;	
				scope.includeKeys = ['key', 'connection', 'table', 'field'];
				scope.excludeKeys = [];
			}
			
		} //end initFieldTable()



		//setup templates table
		$scope.initTemplateTable = function(scope) {

			//valid scope
			if (scope) {
				//scope.tableId = 'templateTable';
				scope.dataURL = $scope.dataURL;	
				//scope.includeKeys = ['key', 'connection', 'table', 'field'];
				scope.excludeKeys = [];
//				scope.columnProperties = { 
//					'-1': {
//						html: true			
//					}
//				};
			}
			
		} //end initTemplateTable()

		
		
		//handle database selection
		$scope.selectDatabase = function() {
			
			//valid model
			//if ($scope.database && $scope.database.connection) {
				
				//store connection
				$page.setVariable('connection', $scope.database.connection);
				
				
				//trigger update
				$scope.$broadcast('ajaxUpdate', { 
					url: $scope.tableURL + "/" + $scope.database.connection,
					containerID: $scope.tableContainer
				});
				
			//}	
				
			//update table selection
			$scope.selectTable();
			
				
		} //end selectDatabase()
		
		
		
		
		//handle table selection
		$scope.selectTable = function() {

			//valid model
			//if ($scope.database && $scope.database.table) {
				
				//store table
				$page.setVariable('table', $scope.database.table);
				
				//get connection name
				var connection = $page.getVariable('connection');

				//trigger update
				$scope.$emit('ajaxUpdate', { 
					url: $scope.fieldURL + "/" + connection + "/" + $scope.database.table,
					containerID: $scope.fieldContainer
				});
				
				
				//TODO: dynamically update selected fields
				
			//}
			
		}; //end selectTable()
		
		
		
		
		$scope.isFieldSelected = function(fieldIndex) {
			
			//valid index
			if (fieldIndex && fieldIndex.length>0) {
			
				//determine if field selected		
				return ($scope.selectedFields && $scope.selectedFields[fieldIndex]);
			
			}
			
			return false;
			
		} //end isFieldSelected()
		
		
		
		
		$scope.selectField = function(fieldIndex) {
			
			//valid index
			if (fieldIndex && fieldIndex.length>0) {
				
				//ensure selection exists
				if (!$scope.selectedFields) {
					$scope.selectedFields = {};	
				}
				
				//set field selection
				$scope.selectedFields[fieldIndex] = !$scope.selectedFields[fieldIndex];
				
			} //end if (valid index)
			
		};
		
		//clear service variables -- seems not to be needed
//		$scope.$on('$routeChangeSuccess', function (scope, next, current) {
//				console.log("page loaded");
//		});
		
		
//		$scope.saveForm = function() {
//			console.log("saved form");	
//		};
		
		
		
		
		
		//respond to table update events
		$scope.$on('tableUpdated', function(event, obj) {

    	  	//valid data
    	  	if (obj) {
    	  		

    	  		//form table
    	  		if (obj.tableId == 'formTable' || obj.tableId == 'templateTable') {
    	  		
	    	  		//get object properties
	    	  		var rawData = obj.data;
	    	  		var filteredData = obj.results;
	    	  		var dataProperties = obj.properties;

					//check for URL's
					var hasEditURL = ($scope.editURL && $scope.editURL.length>0);
					var hasDeleteURL = ($scope.deleteURL && $scope.deleteURL.length>0);

	    	  		//valid data
	    	  		if (rawData && filteredData && rawData.length>0 && filteredData.length>=rawData.length) {
	 
	 					//valid edit URL
	 					if (hasEditURL || hasDeleteURL) {
	 
		 					//process data
		 					for (var i=0; i<rawData.length; ++i) {
		 						
		 						//append edit field
		 						if (hasEditURL) {
		 							filteredData[i].push('<button cms-button="edit-button" button-data="' + rawData[i]['id'] + '">edit</button>');
		 							//filteredData[i].push('<edit-button href="' + $scope.editURL + '/' + rawData[i]['id'] + '">edit</edit-button>');
		 							
			 						//set column as HTML
			 						dataProperties[filteredData[i].length-1] = {
			 							html: true
			 						};
		 						}
		 						
		 						//append delete field
		 						if (hasDeleteURL) {
		 							filteredData[i].push('<button cms-button="delete-button" button-data="' + rawData[i]['id'] + '" confirm-title="Delete Item?" confirm-message="Are you sure you want to delete this entry?" class="btn-negative">delete</button>');
		 							//filteredData[i].push('<save-form-button href="' + $scope.deleteURL + '/' + rawData[i]['id'] + '" confirm-message="Delete Item? Changes will be applied to the live site." name="delete"></save-form-button>');
		 							
			 						//set column as HTML
			 						dataProperties[filteredData[i].length-1] = {
			 							html: true
			 						};
		 						}
		 						
		 						//TODO: for templates get id column
		 						//console.log("filtered data: " + filteredData.length);


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
		
		
		
		
		
		//respond to button events
		$scope.$on('cms-button-click', function(event, data) {

    	  	//valid data
    	  	if (data) {
    	console.log("click - button: "+ data.id);  		
    	  		//edit template row
    	  		if (data.id=='edit-button') {
    	  			
    	  			//open edit page
    	  			$window.location.assign($scope.editURL + '/' + data.data);
    	  			//$location.url($scope.editURL + '/' + data.data);
    	  		
    	  		}
    	  		
    	  	} //end if (valid data)
    	  	
	    }); //end event handler()
	    
	    
		
		
		//respond to button events
		$scope.$on('cms-button-confirm', function(event, data) {

    	  	//valid data
    	  	if (data) {
    	  		
    	  		//delete template row
    	  		if (data.id=='delete-button') {
    	  			
    	  			//get delete form
    	  			var form = document.forms ? document.forms['deleteForm'] : null;
    	  			if (form) {
    	  				
    	  				//clear selected
    	  				$scope.selectedFields.splice(0, $scope.selectedFields.length);
    	  				
    	  				//add delete row
						$scope.selectedFields.push(data.data);

		               	//apply changes (ensures scope changes are applied in ng-repeat)
		               	$scope.$apply();

    	  				//submit form
    	  				//setTimeout(function () {
	    	  				form.submit();
    	  				//}, 1000);
    	  				
    	  			} //end if (form exists)
    	  			
    	  		}
    	  		
    	  		
    	  	} //end if (valid data)
    	  	
	    }); //end event handler()
		
		
	}]); //end controller
		
})();
//end anonymous function
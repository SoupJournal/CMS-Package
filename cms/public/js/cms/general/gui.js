//anonymous function to load features without name conflicts
(function() {
	
	//setup module
	var module = angular.module('cms-gui', ['ngResource']); 
	
	
	
	
	
	//==========================================================//
	//====					BASE FUNCTIONS					====//
	//==========================================================//	
	
	
	//base GUI controller
	var GUIController = function($scope) {
	
			$scope.initController = function(initFunction) {

			//function specified
			if (initFunction && initFunction.length>0) {
				$scope.initFunction = initFunction;	
			}
			
			//call parent function
			if ($scope.$parent && typeof($scope.$parent[$scope.initFunction])=='function') {
				$scope.$parent[$scope.initFunction]($scope);
			}
			
		} //end initController()

	}; //end controller
	
	
	
	
	
	//action controller
	module.controller('ActionController', ['$scope', '$controller', function($scope, $controller) {
	        	
       	//perform action
       	$scope.performAction = function(data) {

//console.log("TESTTTT: " + propertiesString($scope.$parent, 2));
//console.log("TESTTTT1111: " + typeof($scope[$scope.action]));
//console.log('got action: ' + $scope.action + " - parent: " + $scope.$parent + " - func: " + typeof($scope.$parent[$scope.action]));
			//script exists
       		if ($scope.script && $scope.script.length>0) { 
       			
       			//evaluate script
       			try {
       				eval($scope.script);	
       			}
       			catch (ex) {
       				safeErr("ERROR triggering save script: " + ex);
       			}

       		}
       		
       		
       		
			//controller action exists
       		if ($scope.action && $scope.action.length>0) { 
       			
       			//controller defined
       			if ($scope.controller && $scope.controller.length>0) {
       			
	       			//trigger controller function
	       			try {
	       				
	       				//retrieve controller
	       				var controller = $scope.activeController ? $scope.activeController : $controller($scope.controller, { $scope: $scope });
	       				if (controller) {
	       					
	       					//cache controller
	       					$scope.activeController = controller;
	       					
	       					//valid function
	       					if ($scope[$scope.action] && typeof($scope[$scope.action])=='function') {
	       						$scope[$scope.action](data);	
	       					}
	       					//log error
	        				else {
	        					safeErr('ERROR controller[' + $scope.controller + '] function[' + $scope.action + '] not found');
	        				}
	       					
	       					
	       				} //end if (valid controller)
	       				
	       				//log error
	       				else {
	       					safeErr('ERROR controller[' + $scope.controller + '] not found');
	       				}
	       					
	       			}
	       			catch (ex) {
	       				safeErr('ERROR triggering controller[' + $scope.controller + '] function[' + $scope.action + ']: ' + ex);
	       			}
       			
       			}
       			
       			
       			
       			//no controller defined use parent controller
       			else if ($scope.$parent && typeof($scope.$parent[$scope.action])=='function') {
       				
       				try {
       				
       					//call parent function
						$scope.$parent[$scope.action](data);	
					
					}
	       			catch (ex) {
	       				safeErr('ERROR triggering parent controller function[' + $scope.action + ']: ' + ex);
	       			}
	       			
       			}
       			
       			
       			
       			//attempt call on current scope
       			else if (typeof($scope[$scope.action])=='function') {
       				
       				try {
       				
       					//call parent function
						$scope[$scope.action](data);	
					
					}
	       			catch (ex) {
	       				safeErr('ERROR triggering controller function[' + $scope.action + '] in current scope: ' + ex);
	       			}
	       			
       			}

       		} //end if (action defined)
       		
       	};
	
	}]); //end controller
	
	
	
	
	
	
	
	
	//==========================================================//
	//====						CONTROLLERS					====//
	//==========================================================//	
	
	
	
	
	//table controller
	module.controller('TableController', [ '$http', '$scope', '$sce', function($http, $scope, $sce) {

		//inherit from base controller 
		angular.extend(this, new GUIController($scope));

		//table variables
		$scope.tableId = null;
		$scope.columnProperties = null;

		//data variables
		$scope.dataURL = null;
		$scope.includeKeys = null;
		$scope.excludeKeys = null;
		$scope.valueFunction = null;
		$scope.showIndex = true;
		
		//paging variables
	  	$scope.data = [];
		$scope.totalPages = 0;
		$scope.currentPage = 0;
		$scope.range = [];


		//parent init function
		$scope.initFunction = null;


		//get value of property for column
		$scope.columnProperty = function(columnIndex, propertyName) {
			
			var value = undefined;

			//table has properties
			if ($scope.columnProperties) {

				//valid index (columnProperties may be an array or an object so don't compare against length)
				if (columnIndex>=0) {

					//valid property name
					if (propertyName && propertyName.length>0) {
						
						//properties exist for column
						if ($scope.columnProperties[columnIndex]) {

							//retrieve column value
							value = $scope.columnProperties[columnIndex][propertyName];
							
						}
						
					} //end if (valid propery name)
					
				} //end if (valid index)
				
			} //end if (table has properties)
			
			return value;
			
		} //end columnProperty()
		


		//return compiled HTML version of string
		$scope.getHTMLValue = function(html){
			
	        return $sce.trustAsHtml(html);
	        
	    }; //end getHTMLValue()
	    
	    
	    

		//retrieve table data
		$scope.getTableData = function(pageNumber){

			//valid URL
			if ($scope.dataURL && $scope.dataURL.length>0) {
				
				//compile url
				var url = $scope.dataURL + '?' 
					+ (pageNumber>=0 ? 'page='+pageNumber + '&' : '') 
					+ ($scope.visibleItems>0 ? 'limit=' + $scope.visibleItems : '');
				
				
				//request JSON data
				$http.get(url).success(function(response) {
	
					//store properties
					$scope.pageData     = response.data;
					$scope.totalPages   = parseInt(response.last_page);
					$scope.currentPage  = parseInt(response.current_page);
					$scope.itemsPerPage = parseInt(response.items_per_page);
	
					// Pagination Range
					var pages = [];
					
					//add page number
					for(var i=0; i<response.total_pages; i++) {          
						pages.push(i);
					}
					$scope.range = pages; 
					
					
					//filter/sort results
					var filteredKeys = [];
					var filteredResults = [];
					var rowData = null;
					if ($scope.pageData) {
						
						for (var i=0; i<$scope.pageData.length; ++i) {
						
							if (i==0) {
								filteredKeys = $scope.filterKeys($scope.pageData[i], true, $scope.includeKeys, $scope.excludeKeys);
							}
						
							rowData = $scope.filterKeys($scope.pageData[i], false, $scope.includeKeys, $scope.excludeKeys, $scope.valueFunction);
							if (rowData) {
								filteredResults.push(rowData);
							}
							
						} //end for()
						
					} //end if (found page data)
					
					$scope.filteredKeys = filteredKeys;
					$scope.filteredResults = filteredResults;

					//trigger update
					$scope.$emit('tableUpdated', { 
						tableId: $scope.tableId,
						keys: $scope.filteredKeys,
						data: $scope.pageData,
						results: $scope.filteredResults
					});

				
				});
			
			} //end if (valid data URL)
			
			
			
			//respond to update events
			$scope.$on('applyValues', function(event, obj) {

	    	  	//valid parameters
	    	  	if (obj) {
	 
	 				//check for valid table id
	 				if (!obj.tableId || obj.tableId.length<=0 || obj.tableID==$scope.tableId) {
	 
		    	  		//apply keys
		    	  		if (obj.keys!==undefined) {
		    	  			$scope.filteredKeys = obj.keys;
		    	  		}
	    	  		
	    	  			//apply data
		    	  		if (obj.results!==undefined) {
		    	  			$scope.filteredResults = obj.results;
		    	  		}
	    	  		
	 				} //end if (valid table id)
	    	  		
	    	  	} //end if (valid parameters)
	    	  	
		    }); //end event handler()
			
		
		};
	
	
	
		
		//filter function for sorting object keys
		$scope.filterKeys = function(itemData, sortKeys, includeKeys, excludeKeys, valueFunction) {
		
			var filtered = [];
			
			//valid object
			if (itemData) {
				
				//get keys list
				var keys = includeKeys;
				if (!keys && keys.length>0) {
					for(var key in itemData) {
						keys.push(key);
					}
				}
				
				//filter exclusion keys
				if (excludeKeys && excludeKeys.length>0) {
					
					//filter keys
					var finalKeys = [];
					var key = null;
					var safeKey = true;
					for (var i=0; i<keys.length; ++i) {
						
						//check if key is excluded
						safeKey = true; 
						for (var j=0; j<excludeKeys.length; ++j) {
							
							//compare key
							key = keys[i];
							if (key==excludeKeys[j]) {
								safeKey = false;
								break;
							}
							
						}
						
						//valid key
						if (safeKey) {
							finalKeys.push(key);
						}
						
					} //end for()
					
					//set keys
					keys = finalKeys;
					
				}
				
				
				//filter keys
				if (sortKeys) {
					filtered = keys;
				}
				//filter values
				else {
					
					//apply value function
					if (valueFunction && typeof(valueFunction)=='function') {
						for (var i=0; i<keys.length; ++i) {
							filtered.push(valueFunction(i, keys[i], itemData[keys[i]]));
						}
					}
					//update results
					else {
						for (var i=0; i<keys.length; ++i) {
							filtered.push(itemData[keys[i]]);
						}	
					}
				}
				
			} //end if (valid data)
			
	
			return filtered;
	
		};
	
	}]); //end controller

	
	

	
	//==========================================================//
	//====						DIRECTIVES					====//
	//==========================================================//	
	
	

	
	
	//editButton directive - standard edit button 
	module.directive('editButton', function($parse) {
	    return {
	    	restrict: 'AE',
	        template: '<a href="{{ editURL }}" class="{{ class }}">edit</button>',
	        link: function (scope, element, attrs) {
	        	
				//valid attributes
	        	if (attrs) {

	        		//store attributes in scope
	        		scope.editURL = (attrs.href && attrs.href.length>0) ? attrs.href : '#';
	        		scope.class = (attrs.class && attrs.class.length>0) ? attrs.class : 'cms-edit-button';
	        		
	        	} //end if (valid attributes)
	        	
	        },
	 	    replace: false
	    }
	}); //end directive
	
	
			
	//saveButton directive - standard save button 
	module.directive('saveFormButton', function($parse) {
	    return {
	    	restrict: 'AE',
	        template: '<button class="{{ class }}">{{ name }}</button>',
	        link: function (scope, element, attrs) {
	        	
				//valid attributes
	        	if (attrs) {
	        		
	        		//store attributes in scope
	        		scope.name = (attrs.name && attrs.name.length>0) ? attrs.name : 'Save';
	        		scope.class = (attrs.class && attrs.class.length>0) ? attrs.class : 'cms-form-button';
	        		
	        	} //end if (valid attributes)
	        	
	        },
	 	    replace: false
	    }
	}); //end directive
	 	    
	 	    
	 	    
		
	//saveButton directive - standard save button 
	module.directive('formButton', function($parse) {
	    return {
	    	restrict: 'AE',
	        template: '<button type="button" ng-click="performAction()" class="{{ class }}">{{ name }}</button>',
	 	    replace: false,
	        link: function (scope, element, attrs) {
	        	
				//valid attributes
	        	if (attrs) {
	        		
	        		//store attributes in scope
	        		scope.name = (attrs.name && attrs.name.length>0) ? attrs.name : 'Save';
	           		//scope.href = (attrs.href && attrs.href.length>0) ? attrs.href : '';
	           		scope.script = attrs.script; //(attrs.click && attrs.click.length>0) ? $parse(attrs.click) : null;
	           		
	           		scope.action = attrs.action;
	           		scope.controller = attrs.controller; //(attrs.controller && attrs.controller.length>0) ? attrs.controller : null;
	           		scope.class = (attrs.class && attrs.class.length>0) ? attrs.class : 'cms-form-button';
	           		
	        	} //end if (valid attributes)
	        	
	        },
	        controller: 'ActionController' 
	    }
	}); //end directive
	
	
	
	
	
	
	
	//pagination buttons
	module.directive('pagination', function() {
		  
	   return{
	      restrict: 'E',
	      template: '<ul class="pagination">'+
	      
		    //start / previous buttons
	        '<li ng-class="{button_disabled: currentPage<=0}">' +
	        	'<a href="javascript:void(0)" ng-click="getTableData(0)">&laquo;</a>' + 
	        '</li>'+ 						
	        '<li ng-class="{button_disabled: currentPage<=0}"><a href="javascript:void(0)" ng-click="getTableData(currentPage-1)">&lsaquo; Prev</a></li>'+	
	        
	        //page buttons
	        '<li ng-repeat="i in range" ng-class="{active : currentPage == i}">'+
	            '<a href="javascript:void(0)" ng-click="getTableData(i)">{{i+1}}</a>'+
	        '</li>'+
	        
	        //next / end buttons
	        '<li ng-class="{button_disabled: currentPage>=totalPages}"><a href="javascript:void(0)" ng-click="getTableData(currentPage+1);console.log(currentPage);">Next &rsaquo;</a></li>'+
	        '<li ng-class="{button_disabled: currentPage>=totalPages}"><a href="javascript:void(0)" ng-click="getTableData(totalPages-1)">&raquo;</a></li>'+		
	      '</ul>',
	      controller: ['$scope', GUIController]
	   };
	   
	}); //end directive
	
	
	
	
	
	
	
	
		
	
})();
//end anonymous function
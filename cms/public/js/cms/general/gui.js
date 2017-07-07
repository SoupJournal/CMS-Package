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
	module.controller('TableController', [ '$http', '$scope', '$rootScope', '$sce', function($http, $scope, $rootScope, $sce) {

		//inherit from base controller 
		angular.extend(this, new GUIController($scope));

		//table variables
		$scope.tableId = null;
		$scope.columnProperties = [];

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
		$scope.itemsPerPage = -1;
		$scope.range = [];
		$scope.maxNumberOfPageButtons = 10;


		//parent init function
		$scope.initFunction = null;


	    
	    
	    
	    //== AJAX METHODS ==//
	    

		//retrieve table data
		$scope.getTableData = function(pageNumber){

			//valid URL
			if ($scope.dataURL && $scope.dataURL.length>0) {
				
				//compile url
				var url = $scope.dataURL + '?' 
					+ (pageNumber>=0 ? 'page='+pageNumber + '&' : '') 
					+ ($scope.itemsPerPage>0 ? 'limit=' + $scope.itemsPerPage : '');
				

				//handle request completion
				var successHandler = function(response) {

					//valid response
					if (response) {

						//store properties
						$scope.pageData     = response.data;
						$scope.totalPages   = parseInt(response.last_page);
						$scope.currentPage  = parseInt(response.current_page);
						//$scope.itemsPerPage = parseInt(response.items_per_page);
		
		
		
						//TODO: move to pagination directive
						// Pagination Range
						var pages = [];
						
						//check if maximum applied
						var applyMaximum = $scope.maxNumberOfPageButtons > 0 && $scope.maxNumberOfPageButtons < response.total_pages;
						
						//add page number
						var numberOfButtons = applyMaximum ? $scope.maxNumberOfPageButtons : response.total_pages;
						
						//determine page button offset
						var buttonsOffset = applyMaximum ? parseInt($scope.maxNumberOfPageButtons * 0.5) : 0;
						var lastPageButton = applyMaximum ? response.total_pages - $scope.maxNumberOfPageButtons : 0;
						
						//determine first page button number
						var startButtonPage = $scope.currentPage - buttonsOffset;
						if (startButtonPage<0) startButtonPage = 0;
						if (startButtonPage>lastPageButton) startButtonPage = lastPageButton;
						
	
						//add page buttons
						for(var i=0; i<numberOfButtons; i++) {          
							pages.push(startButtonPage + i);
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
							results: $scope.filteredResults,
							properties: $scope.columnProperties,
							currentPage: $scope.currentPage,
							totalPages: $scope.totalPages,
							itemsPerPage: $scope.itemsPerPage
						});

					} //end if (valid response)
				
				}; //end success handler()
				
			
				//request JSON data
				var request = $http.get(url);
				if (request) {
					
					//new Angular versions
					if (request.success) {
						request.success(successHandler);
					}
					//older API versions
					else {
						request.then(successHandler);
					}
				
				} //end if (valid request)
			
			} //end if (valid data URL)
		
		};
	
	

			
		//== LISTENERS ==//

		//respond to update events (not required if editing the same object instance returned from 'tableUpdated' event)
		$scope.$on('applyValues', function(event, obj) {

    	  	//valid parameters
    	  	if (obj) {

 				//check for valid table id
 				if (obj.tableId==$scope.tableId) {

	    	  		//apply keys
	    	  		if (obj.keys!==undefined && typeof(obj.keys.splice)==='function') {
	    	  			$scope.filteredKeys = obj.keys.splice();
	    	  		}
    	  		
    	  			//apply data
	    	  		if (obj.results!==undefined && typeof(obj.results.splice)==='function') {
	    	  			$scope.filteredResults = obj.results.splice();
	    	  		}
    	  		
 				} //end if (valid table id)
    	  		
    	  	} //end if (valid parameters)
    	  	
	    }); //end event handler()
		
		
			
			
		$scope.$on('changePageSize', function(event, obj) {

			//valid parameters
    	  	if (obj) {
 
 				//check for valid table id
 				if (obj.tableId==$scope.tableId) {
 
 					//update visible items
 					$scope.itemsPerPage = obj.limit;
 
					//update table
					$scope.getTableData($scope.currentPage);

				} //end if (valid table id)
    	  		
    	  	} //end if (valid parameters)
			
		}); //end event handler()
	
	
	

		//== DATA METHODS ==//


		//get value of property for column
		$scope.columnProperty = function(columnIndex, propertyName) {
			
			var value = undefined;

			//table has properties
			if ($scope.columnProperties) {

				//parase index
				columnIndex = parseInt(columnIndex);

				//handle negative index (reference from last column)
				if ($scope.filteredResults && columnIndex<0 && columnIndex>-$scope.filteredKeys.length) {
					columnIndex += $scope.columnProperties.length;
				}

				//valid index (columnProperties may be an array or an object so don't compare against length)
				if (columnIndex>=0) {

					//valid property name
					if (propertyName && propertyName.length>0) {
						
						//properties exist for column
						if ($scope.columnProperties[columnIndex]) {

							//retrieve column value
							value = $scope.columnProperties[columnIndex][propertyName];
							
						}
						else if ($scope.columnProperties[columnIndex-$scope.filteredKeys.length]) {
							
							//retrieve column value
							value = $scope.columnProperties[columnIndex-$scope.filteredKeys.length][propertyName];
							
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
	    
	
		
		//filter function for sorting object keys
		$scope.filterKeys = function(itemData, sortKeys, includeKeys, excludeKeys, valueFunction) {
		
			var filtered = [];
			
			//valid object
			if (itemData) {

				//get keys list
				var keys = includeKeys ? includeKeys : [];
				if (!includeKeys && itemData) {
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
	
	

	
	
	//deleteButton directive - standard delete button 
	module.directive('cmsButton', ['$rootScope', function($rootScope, $parse) {
	    return {
	    	scope: {
	    		cmsButton: '@',
	    		buttonData: '@',
	    		confirmTitle: '@',
	    		confirmMessage: '@'
	    	},
	    	restrict: 'A',
	       // template: '<a href="{{ deleteURL }}" class="{{ class }}">delete</button>',
	        link: function (scope, element, attrs) {
	        	
				//valid element
	        	if (attrs) {

					//default to non-submit button
					if ((!attrs.type || attrs.type.lowercase!='submit') && element.prop('type')!='submit') {
						element.prop('type', 'button');
					}
	        		
	        	} //end if (valid attributes)
	        	
	        	//valid element
	        	if (element) {
	        		
	        		//add default class
	        		element.addClass('cms-form-button');
	        		
	        		
	        		//add listeners
	        		element.on('click', function(event) {
						
						//create data
						var data = {
							id: scope.cmsButton,
							data: scope.buttonData,
							element: element,
							title: scope.confirmTitle,
							message: scope.confirmMessage
						};

		        		//broadcast event
		        		$rootScope.$broadcast('cms-button-click', data);
		        		
		        		
		        		
			        	//compile message
			        	var message = (scope.confirmTitle && scope.confirmTitle.length>0) ? scope.confirmTitle + '\n\n' : '';
			        	message += (scope.confirmMessage && scope.confirmMessage.length>0) ? scope.confirmMessage : '';
			        		
	            		//dialoag message specified
	            		if (message && message.length>0) {
	            	
			            	//stop default handling
			            	if (event) {
			            		event.preventDefault();
			            	}
			            	
			            	//show alert
			                if (confirm(message)) {

				        		//broadcast event
				        		$rootScope.$broadcast('cms-button-confirm', data);

			                }
		                
	            		} //end if (dialog message specified)
		               	
	        		});
	        		
	        		
	        	} //end if (valid element)

	        },
	 	    replace: false
	    }
	}]); //end directive
	


	
	
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
	        template: '<button class="{{ class }}" confirm-click="{{ confirmForm }}" message="{{ confirmMessage }}">{{ name }}</button>',
	        link: function (scope, element, attrs) {
	        	
				//valid attributes
	        	if (attrs) {
	        		
	        		//store attributes in scope
	        		scope.name = (attrs.name && attrs.name.length>0) ? attrs.name : 'Save';
	        		scope.class = (attrs.class && attrs.class.length>0) ? attrs.class : 'cms-form-button';
	        		scope.confirmForm = (attrs.confirmForm && attrs.confirmForm.length>0) ? attrs.confirmForm : '';
	        		scope.confirmMessage = (attrs.confirmMessage && attrs.confirmMessage.length>0) ? attrs.confirmMessage : '';

	        	} //end if (valid attributes)
	        	
	        	
	        	//handle button click
	            element.bind('click', function(event) {

	            	//valid event
	            	if (event) {
	            	
	            		//dialoag message specified
	            		if (scope.confirmMessage && scope.confirmMessage.length>0) {
	            	
			            	//stop default handling
			            	event.preventDefault();
			            	
			            	//show alert
			                if (confirm(scope.confirmMessage)) {

			                	//form to submit
			                	var submitForm = null;
			                	
			                	//get form by name
			                	if (scope.confirmForm && scope.confirmForm.length>0) {
			                		submitForm = document.forms[scope.confirmForm];
			                	}
			                	
			                	//no form found - find nearest parent
			                	if (!submitForm) {
			                		if (element) {
			                			
			                			//find parent form
			                			var parentNode = element.parentNode;
			                			while (parentNode && parentNode.tag!='form' && parentNode!=window) {
			                				parentNode = parentNode.parentNode;
			                			}
			                			//found form
			                			if (parentNode.tag!='form') {
				                			submitForm = parentNode; 
			                			}
			                		} 
			                	}
			                	
			                	//found form
			                	if (submitForm) {
			                		submitForm.submit();
			                	}
			                }
		                
	            		} //end if (dialog message specified)
		                
	            	} //end if (valid event)
	            	
	            });
	        	
	        },
	 	    replace: false
	    }
	}); //end directive
	 	    
	 	    
	 	    
		
	//formButton directive - standard save button 
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
	
	
	/*
	//confirmation
	module.directive('confirmClick', [function() {
	    return {
	        restrict: 'A',
	        link: function(scope, element, attrs) {
	        	
	        	//set attributes
	        	if (attrs) {
	        		
	        		//store attributes
	        		scope.message = attrs.confirmMessage;
	        		scope.callback = attrs.confirmClick;
	        console.log("confirm click link: " + scope.confirmAction + " - message: " + scope.message);	
	        	
	        	} //end if (found attributes)
	        	
	        	//handle button click
	            element.bind('click', function(event) {
	            	console.log("got confirm click: " + scope.confirmClick + " - event: " + event + " - message: " + scope.message);
	            	//valid event
	            	if (event) {
	            	
		            	//stop default handling
		            	event.preventDefault();
		            	
		            	scope.showConfirm();
		            	

	                
	            	} //end if (valid event)
	            	
	            });
	        	
	        },
	        controller: function($scope) {
	        		
				$scope.showConfirm = function() {
					
					console.log("got controller call - message: " + $scope.message);
					
	            	//show alert
	                if ($scope.message && confirm($scope.message)) {
	                	
	                	//trigger callback
	                	if ($scope.confirmClick) {
	                    	$scope.$apply($scope.confirmClick);
	                	}
	                }
					
				} //end showConfirm()
	        }
	    }
	}]); //end directive
	*/
	
	
	//pagination settings
	module.directive('paginationSettings', function() {
		  
	   return{
	      restrict: 'E',
	      template: '<div class="pagination-settings"><span class="pagination-label">Items per page: </span><input type="number" size="3" value="{{ visibleItems }}" ng-model="visibleItems" ng-change="updateTable()" class="input-small"></div>',
	      link: function (scope, element, attrs) {
	      		
	      		//set attributes
	      		scope.visibleItems = parseFloat(attrs.limit);
	      		scope.table = attrs.table;
    		    	
		      	//update initial value
	      		scope.updateTable();
	      },
	      controller: function($scope, $rootScope) {
	      	
	      	//trigger table update
	      	$scope.updateTable = function() {

				//broadcast limit change
				$rootScope.$broadcast('changePageSize', {
					tableId: $scope.table,
					limit: $scope.visibleItems
				});

	      	}
	      	
	      },
	      replace: false,
	   };
	}); //end directive
	
	
	
	
	
	
	//pagination buttons
	module.directive('pagination', function() {
		  
	   return{
	      restrict: 'E',
	      template: '<ul class="pagination" style="visibility:{{ visibility }};">'+
	      
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
	        '<li ng-class="{button_disabled: currentPage>=totalPages}"><a href="javascript:void(0)" ng-click="getTableData(currentPage+1);">Next &rsaquo;</a></li>'+
	        '<li ng-class="{button_disabled: currentPage>=totalPages}"><a href="javascript:void(0)" ng-click="getTableData(totalPages-1)">&raquo;</a></li>'+		
	      '</ul>',
	      link: function (scope, element, attrs) {
	      	
	      		//set defaults
	      		scope.totalPages = 0;
	      		scope.visiblePages = 10;
	      	
	      		//set attributes
	      		scope.table = attrs.table;
	      		scope.visiblePages = attrs.visiblePages;
	      		
	      		//style properties
	      		scope.visibility = scope.totalPages > 0 ? 'visible' : 'hidden';
	      	
	      },
	      controller: function($scope, $rootScope) {
	      	
	      		$scope.$on('tableUpdated', function(event, obj) {

	      			//valid parameters
		    	  	if (obj) {
		 
		 				//check for valid table id
		 				if (obj.tableId==$scope.tableId) {
		 
		 					//set total pages
		 					$scope.totalPages = obj.totalPages;
		
							//update visibility
							$scope.visibility = $scope.totalPages > 0 ? 'visible' : 'hidden';
			
						} //end if (valid table id)
		    	  		
		    	  	} //end if (valid parameters)
	      			
	      		}); //end event handler()
	      	
	      }
	      //controller: ['$scope', GUIController]
	   };
	   
	}); //end directive
	
	
	
	
	
	
	
	
		
	
})();
//end anonymous function
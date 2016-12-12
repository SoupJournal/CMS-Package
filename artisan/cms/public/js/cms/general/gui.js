//anonymous function to load features without name conflicts
(function() {
	
	//setup module
	var module = angular.module('cms-gui', ['ngResource']); 
	
	
		
	//saveButton directive - standard save button 
	module.directive('saveButton', function($parse) {
	    return {
	    	restrict: 'AE',
	    	//terminal: true,
	    	//require: '^?ngResource',
	    	//transclude: true,
//	    	scope: {
//			  list: "=",
//			  // Bind the function as a function to the attribute from the directive
//			  click: "&"
//			},
	        //template: '<a href="{{ href }}" ng-click="click()" class="{{ class }}">{{ name }}</a>',
	        template: '<button ng-click="performClick()" class="{{ class }}">{{ name }}</button>',
	 	    replace: true,
	        link: function (scope, element, attrs) {
	        	
				//valid attributes
	        	if (attrs) {
	        		
	        		//store attributes in scope
	        		scope.name = (attrs.name && attrs.name.length>0) ? attrs.name : 'Save';
	           		//scope.href = (attrs.href && attrs.href.length>0) ? attrs.href : '';
	           		scope.script = attrs.script; //(attrs.click && attrs.click.length>0) ? $parse(attrs.click) : null;
	           		
	           		scope.action = attrs.action;
	           		scope.controller = attrs.controller; //(attrs.controller && attrs.controller.length>0) ? attrs.controller : null;
	           		scope.class = (attrs.class && attrs.class.length>0) ? 'cms-save-button ' + attrs.name : 'cms-save-button';
	           		
	        	} //end if (valid attributes)
	        	
	        },
	        controller: ['$scope', '$controller', function($scope, $controller) {
	        	
	        	//perform click action
	        	$scope.performClick = function() {

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
	        		if ($scope.controller && $scope.action && $scope.controller.length>0 && $scope.action.length>0) { 
	        			
	        			//trigger controller function
	        			try {
	        				
	        				//retrieve controller
	        				var controller = $scope.activeController ? $scope.activeController : $controller($scope.controller, { $scope: $scope });
	        				if (controller) {
	        					
	        					//cache controller
	        					$scope.activeController = controller;
	        					
	        					//valid function
	        					if ($scope[$scope.action] && typeof($scope[$scope.action])=='function') {
	        						$scope[$scope.action]();	
	        					}
	        					//log error
		        				else {
		        					safeErr('ERROR no controller function found');
		        				}
	        					
	        					
	        				} //end if (valid controller)
	        				
	        				//log error
	        				else {
	        					safeErr('ERROR controller not found');
	        				}
	        					
	        			}
	        			catch (ex) {
	        				safeErr("ERROR triggering controller function: " + ex);
	        			}

	        		}
	        		
	        	};
	        }]
	    }
	}); //end directive
	
	
})();
//end anonymous function
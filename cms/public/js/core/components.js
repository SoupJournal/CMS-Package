
//anonymous function to load features without name conflicts
(function() {
	
	//setup module
	var module = angular.module('core-components', ['ngResource']); 
	
	
	//add input directive from database type
	module.directive('databaseInput', function($compile) {
		return {
			compile: function(element, attrs) {

				if (element) {
					
					//compile html
					var html = '<input ';
					
					//has attributes
					if (attrs && attrs.$attr) {
					
						for (var property in attrs.$attr) {
					
							//TODO: handle DateTime
					
							//set input type
							if (property=='type') {

								//valid type
								if (attrs.type && attrs.type.length>0) {
									
									//number
									if (attrs.type=='Integer') {
										html += 'type="number" ';
									}
									else if (attrs.type=='String') {
										html += 'type="text" ';
									}
									else {
										html += 'type="hidden" ';
									}
								}
	
							} //end if (set property type)
						
							//directly apply property
							else {
								html += property + '="' + attrs[property] + '" ';
							}
						
						} //end for()
					
					}
					
					//close html
					html += ">";
					
					//apply html - TODO: check if $compile should be called
					element.html(html);
					
				} //end if (valid element)
			} 	
		};
		/*return function(scope, element, attrs){
			if (element) {
				var html = "<input type=\"text\">";
				element.
			}
		}*/
	});
	
	
	
	
	
	//spacer directive - add custom space between elements
	module.directive('verticalSpacer', function() {
		return {
			replace: true,
			template: '<div></div>',
			link: function(scope, element, attrs) {

				//source element ID specified
				if (attrs && attrs.size && attrs.size.length>0) {
					
					//update height
					element.css({
						height: parseInt(attrs.size) + 'px'
					});
					
				} //end if (source element ID specified)
			}
		}
	}); //end directive
	
	
	
	
	
	//sizeCopy directive - matches size of source element (used for padding under fixed elements)
	module.directive('sizeCopy', function($compile) {
		return {
			link: function(scope, element, attrs) {

				//source element ID specified
				if (attrs && attrs.source && attrs.source.length>0) {
					
					//find source element
					var sourceElement = document.getElementById(attrs.source);
					if (sourceElement) {

						element.css({
							width: sourceElement.offsetWidth + 'px',
							height: sourceElement.offsetHeight + 'px'
						});
					}
					
				} //end if (source element ID specified)
			}
		}
	}); //end directive
	
	


	//dynamicCompile directive - used to dynamically compile innerHTML changes
	module.directive('dynamicCompile', function($compile, $parse) {
		return {
			restrict: 'AE',
			link: function(scope, element, attr) {
			
			 	var parsed = $parse(attr.ngBindHtml);
			 
			 	//Recompile if the template changes
			 	scope.$watch(
				   	function() { 
				     	return (parsed(scope) || '').toString(); 
				   	}, 
				   	function() {

				       	//compile html
						$compile(element, null, -9999)(scope);

				   	}
			 	);
			}
		};
	}); //end directive
	
	
	
	//components controller
	//module.controller('ComponentController', [ '$http', '$scope', function($http, $scope) {
		
	//}]); //end controller
	
	
})();
//end anonymous function

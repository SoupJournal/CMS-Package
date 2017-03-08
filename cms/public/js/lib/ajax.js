
//anonymous function to load features without name conflicts
(function() {
	
	//setup module
	var module = angular.module('core-ajax', ['ngResource']); 
		
		
		/*
		
	
	module.directive('compiledHtml', function() {
		
	//		      return { template: "some template cCODDDDE Here"};
		
		
	 //   return function(scope, element, attr) {
	  // console.log("directive function called");
	   
	   return {
	   
//			scope: {
//		      rawHtml: '=compiledHtml'
//		    },
	   		compile: function(elem) {
	   			
	   				console.log("compile called");
	   			return function(s) {
	   				console.log("link function: "+ $compile);	
	   			};	
	   		},
	   
		   link: function(scope, element, attrs) {
		   	
		   		//store element
		   		scope.element = element;
		   	
		   		//add watch function
		      	scope.$watch(function($scope) {
		      		
		      			//check if value changed
		      			return element.html(); 
		      		
		      		}, 
		      		function(value) {
		      		
		      		console.log("watch triggered---------" + value);
		      		
		      		var compiledHTML = $compile(value);
		      		console.log("compiledHTML: " + compiledHTML);
		      		
		      		return "test test test";
			       // if (!value) return;
			        // we want to use the scope OUTSIDE of this directive
			        // (which itself is an isolate scope).
			        //var newElem = $compile(value)(scope.$parent);
			        //elem.contents().remove();
			        //elem.append(newElem);
		      	});
		    }
			   
	   
//		   link: function(scope, elem, attrs) {
//	   		scope.$watch("rawHtml", function(oldValue, newValue) {
//	            if(newValue) {
//	                console.log("there is a new value");
//	                console.log("the new value is " + newValue);
//	             }
//	         });
//		   }
	   
//	      var html = element.html();
//	      debugger;
//	      html = html.replace(/\[\[(\w+)\]\]/g, function(_, text) {
//	        return '<span translate="' + text + '"></span>';
//	      });
//	      element.html(html);
//	      $compile(element.contents())(scope); //<---- recompilation 
	      

	      
	    }; //end
	    
	    
	    
	});
		*/
		
		
	/*	
	//compiledHtml directive
	module.directive("compiledHtml", function(){
		
      	return{
         	link : function(scope, ele, attr){
	            //ele.bind("click", function(){
	            	
	            	
	                $.get('somewhere/aPage.html', function (d,s,j) {
	                   scope.$apply(function(){
	                       $("#subPage").html(d);
	                    });
	                 });
	        	//})
          	},
          	//controller:
      	}
  	});	
	*/	
		
		
		
	//==========================================================//
	//====						CONTROLLER					====//
	//==========================================================//	
	
		
		
	//dynamic content controller
	module.controller('DynamicContentController', [ '$http', '$scope', '$compile', function($http, $scope, $compile) {
		



	//----------------------------------------------------------//
	//----					REQUEST FUNCTIONS				----//
	//----------------------------------------------------------//



		$scope.getCompiledURLContent = function(urlFunction, containerID, parameters)  {
			
			//valid URL function
			if (urlFunction && urlFunction.length>0 && typeof($scope[urlFunction])=='function') {
				
				//safety check to prevent infinite loops
				if (urlFunction!='getCompiledURLContent') {
				
					//compile URL
					var url = $scope[urlFunction]();
					
					//fetch content
					$scope.getContent(url, containerID, parameters);
				
				}
				//log error
				else {
					console.log("[DynamicContentController] ERROR keyword 'getCompiledURLContent' can not be passed as the function name");
				}
				
			} //end if (valid function)
			
			
		} //end getCompiledURLContent()




		$scope.getContent = function(url, containerID, parameters)  {

			//get container element
			var container = (containerID && containerID.length>0) ? document.getElementById(containerID) : null;
			
			//valid url
			if (url && url.length>0) {
			
				//retrieve page
				$http.get(url)
				
				//success
				.success(function(response) {
					
					//update element
					if (container) {
						
						//ensure element contains some html tags
						var innerHTML = '<div>' + response + '</div>';
						
						
						//compile html
						var element = $compile(innerHTML)($scope);

						//get container angular object
						var containerObject = angular.element(container);
						if (containerObject) {
					       	containerObject.contents().remove();
				        	containerObject.append(element);
						}
					}
					
				})
				
				//error
				.error(function() {
					
					//update element
					if (container) {
						container.innerHTML = "Error connecting to URL: " + url;
					}
					
				});
			
			}
			//invalid url
			else {
				
				//clear element
				if (container) {
					container.innerHTML = "";
				}
			}
		};
			
			
			
			
			
			


	//----------------------------------------------------------//
	//----					EVENT HANDLERS					----//
	//----------------------------------------------------------//	


		//respond to update events
		$scope.$on('ajaxUpdate', function(event, obj) {

    	  	//valid parameters
    	  	if (obj && obj.url && obj.url.length>0) {
 
    	  		//trigger update
    	  		$scope.getContent(obj.url, obj.containerID, obj.parameters);
    	  		
    	  	} //end if (valid parameters)
    	  	
	    }); //end event handler()

			
			

	}]); //end controller
	
	
})();
//end anonymous function

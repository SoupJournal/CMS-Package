
//anonymous function to load features without name conflicts
(function() {
	
	//replace code brackets so as not to conflict with Blade
	var module = angular.module('cms-tools', ['ngResource']); 
	
	
		
	//create service
	module.service('PageService', function() {
		
		
		//stored variables
		var variables = {};
		
		
		this.setVariable = function(variableName, variable) {
			
			//valid variable name
			if (variableName && variableName.length>0) {
				
				//apply variable
				variables[variableName] = variable;
				
			}	
			
		}; //end setVariable()
		
		
		
		this.getVariable = function(variableName, defaultValue) {
			
			var variable = defaultValue;
			
			//valid variable name
			if (variableName && variableName.length>0 && variables[variableName]!==undefined) {
				
				//apply variable
				variable = variables[variableName];
				
			}	
			
			return variable;
			
		}; //end setVariable()
		
		
		
		this.clearVariables = function() {
			
			//clear variables
			variables = {};
			
		} //end clearVariables()
			
		
		
		
		this.printVariables = function() {
			
			//print variables
			console.log("Page variables\n" + propertiesString(variables));
			
		} //end printVariables()
		
		
	}); //end service
	
	
})();
//end anonymous function

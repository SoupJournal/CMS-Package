//anonymous function to load features without name conflicts
(function() {
	
	//create module
	var module = angular.module('cms.form', ['ngResource']);
	
	
	
	//create controller
	module.controller('FormController', [ '$http', '$scope', 'PageService', function($http, $scope, $page) {
		
		//$page.printVariables();
		
		
//		$scope.setServiceVariable = function(variableName, variable) {
//			
//			$page.setVariable(variableName, variable);
//
//		}; //end setServiceVariable()
		
		
		
		
		//handle database selection
		$scope.selectDatabase = function() {
			
			//valid model
			if ($scope.database && $scope.database.connection) {
				
				//store connection
				$page.setVariable('connection', $scope.database.connection);
				
				
				//trigger update
				$scope.$broadcast('ajaxUpdate', { 
					url: $scope.tableURL + "/" + $scope.database.connection,
					containerID: $scope.tableContainer
				});
				
				//dbcontroller.getContent();
				//$this->app->call([$controller, $method], $arguments);
			}	
				
		} //end selectDatabase()
		
		
		
		
		//handle table selection
		$scope.selectTable = function() {

			//valid model
			if ($scope.database && $scope.database.table) {
				
				//store table
				$page.setVariable('table', $scope.database.table);
				
				//get connection name
				var connection = $page.getVariable('connection');

				//trigger update
				$scope.$emit('ajaxUpdate', { 
					url: $scope.fieldURL + "/" + connection + "/" + $scope.database.table,
					containerID: $scope.fieldContainer
				});
				
			}
			
		}; //end selectTable()
		
		
		
		$scope.selectField = function() {
			console.log("selected field");	
		};
		
		//clear service variables -- seems not to be needed
//		$scope.$on('$routeChangeSuccess', function (scope, next, current) {
//				console.log("page loaded");
//		});
		
		
		$scope.saveForm = function() {
			console.log("saved form");	
		};
		
		
	}]); //end controller
		
})();
//end anonymous function
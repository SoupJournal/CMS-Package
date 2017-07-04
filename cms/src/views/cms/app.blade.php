<script type="text/javascript">
	     
	     
	//anonymous function to load features without name conflicts
	(function() {
	      
	      	//additional page controllers
	      	var pageModules = null;
	      	
	      	<?php
	     	
	      		//append page controllers
	      		if (isset($pageModules) && $pageModules!=null && is_array($pageModules) && count($pageModules)>0) {
	
	      			//set controllers
	      			$jsonModules = convertObjectToJS($pageModules);
	
	      			?>
	      			try {
	      				pageModules = <?php echo $jsonModules ?>;
	      			}
	      			catch (e) {
	      				console.log("ERROR parsing page controllers");
	      			}
	      			
	      			<?php
	      			//$jsonModules = json_encode($pageModules);
	      			
	      		}
	      	
	      	?>
	      
	      	//append page controllers
	      	var angularModules = [
	      		'ngResource', 
	      		'cms-tools', 
	      		'cms-gui', 
	      		'core-ajax', 
	      		'core-components', 
	      		'ui.bootstrap', 
	      		'cms.header'
	      	];
	      	if (pageModules && pageModules.length>0) {
	      		angularModules = angularModules.concat(pageModules);
	      	}
	//	printProperties(angularModules);
		
		//replace code brackets so as not to conflict with Blade
		var app = angular.module('cms-core', angularModules,  
		
			//replace code brackets so as not to conflict with Blade
			function($interpolateProvider) {
				$interpolateProvider.startSymbol('#{');
				$interpolateProvider.endSymbol('}#');
			}
			
		);
		
	})();
	//end anonymous function
	     
</script>
	     

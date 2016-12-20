<?php
			
	//title
	if (!isset($title)) {
		$title = "";
	}
	
	//data URL
	if (!isset($dataFunction)) {
		$dataFunction = "";
	}	

	//items per page
	//if (!isset($pageSize)) {
	//	$pageSize = -1;
	//}	


?>




<div class='data-table-container'>

    
    <div ng-controller="TableController" ng-init='initController("{{ $dataFunction }}"); getTableData();'>

		<div class="table-responsive">
				
			{{-- table --}}				
			<table class="table table-hover">
		
				<thead>
					<tr> 
						<th class="object-column" ng-if="showIndex">index</th>
						<th class="object-column" ng-repeat="property in filteredKeys track by $index">
							@{{ property }} 
						</th>
					</tr>
				</thead>
			
				<tr class="object-row" ng-repeat="rowData in filteredResults"> 
					<td class="object-column" ng-if="showIndex==true">@{{ $index + (itemsPerPage * currentPage) }}</td>
					<td class="object-column" ng-repeat="value in rowData track by $index">
						@{{ value }}
					</td>
				</tr>
	
			</table>
			
		
		
			{{-- table footer --}}
			<div class="table-footer button_export_padding">
			
				{{-- pagination buttons --}}
				<div class="left">
		        	<pagination></pagination>
		      	</div>
	
	
			</div>
		
		</div>

    </div> 
	
	
</div>
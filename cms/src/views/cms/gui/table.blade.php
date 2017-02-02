<?php
			
	//title
	if (!isset($title)) {
		$title = "";
	}
	
	//tableId
	if (!isset($tableId)) {
		$tableId = null;
	}
	
	//data URL
	if (!isset($dataFunction)) {
		$dataFunction = "";
	}	

	//item syntax
	if (!isset($columnSyntax)) {
		$columnSyntax = null;
	}

	//items per page
	if (!isset($pageSize)) {
		$pageSize = 20;
	}	


?>




<div class='data-table-container'>

    {{-- N.B. removed init getTableData() call because this is handled by pagination-settings directive --}}
    <div ng-controller="TableController" ng-init="tableId='{{ $tableId }}'; initController('{{ $dataFunction }}');">

		{{-- table header --}}
		<div class="table-header button_export_padding">
		
			{{-- pagination settings --}}
			<div class="pull-right">
	        	<pagination-settings table="{{ $tableId }}" limit="{{ $pageSize }}"></pagination-settings>
	      	</div>


		</div>



		{{-- table --}}	
		<div class="table-responsive">
							
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
						<span ng-if="!columnProperty($index, 'html')">@{{ value }}</span>
						<dynamic-compile ng-if="columnProperty($index, 'html')" ng-bind-html="getHTMLValue(value)"></dynamic-compile>
					</td>
				</tr>
	
			</table>
		
		</div>
		
		
		
		{{-- table footer --}}
		<div class="table-footer button_export_padding">
		
			{{-- pagination buttons --}}
			<div class="center">
	        	<pagination table="{{ $tableId }}"></pagination>
	      	</div>


		</div>

    </div> 
	
	
</div>

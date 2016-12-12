<?php

	class BaseCMSController extends Controller {
		

		public function __construct() {
			
			//set global angular modules variable
			//View::share ( 'pageModules', Array() );

		} //end constructor()
		
		
		
		
		
		protected function paginateRequestQuery($countQuery, $dataQueryFunction, $params, $objectData = null) {
			

			//create response
			$response = new StdClass;
		
		
			//valid queries
			if ($countQuery && $dataQueryFunction && strlen($countQuery)>0) {
		
			
				//get parameters
				$page = isset($params) && isset($params['page']) && is_numeric($params['page']) ? $params['page'] : 0;
				$limit = isset($params) && isset($params['limit']) && is_numeric($params['limit']) ? $params['limit'] : 0; 
				
				//bounds checks
				if ($page<0) $page = 0;
				if ($limit<0) $limit = 0;
				

				
				//process count query
				$countData = DB::select(DB::raw($countQuery));
				
				
				//process variables
				$index = 0;
				$count = 0;
				$totalPages = 0;
				if ($limit>0) {
					
					//determine number of pages
					if (isset($countData) && count($countData)>0) {
						$count = $countData[0]->count;	
						$response->rows = $count;
						if (is_numeric($count)) {
							$totalPages = ceil($count / $limit);
						}
					}
				
					//bounds check page
					if ($page>=$totalPages) {
						$page=$totalPages-1;
					}
					
					//set index
					$index = $page * $limit;
					
				}
				
				
				
				//process data query
				if ($limit>0) {
					$dataQuery = $dataQueryFunction($index, $limit, $objectData);
				}
				else {
					$dataQuery = $objectData;
				}
				$data = DB::select(DB::raw($dataQuery));
				
				
				//update parameters
				$response->current_page = $page;
				$response->items_per_page = $limit;
				$response->total_pages = $totalPages;
				$response->last_page = ($totalPages>0 ? ($totalPages-1) : 0);
				$response->data = $data;
			
			
			} //end if (valid query)
			
			//log error
			else {
				$response->error = "Invalid queries";
			}
			
			
			return $response;
		
			
		} //end paginateRequestQuery()
		
		
		
	} //end class BaseCMSController
	
	
?>
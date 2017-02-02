<?php

	class BaseCMSController extends Controller {
		
		
		//CMS database connection
		//protected $connection = null;
		

		public function __construct() {
			
			
			//initialise database connection (centralised in case CMS connection is not default)
			//$this->connection = DB::connection();
			
			
			//set global angular modules variable
			//View::share ( 'pageModules', Array() );

		} //end constructor()
		
		
		
		
		
		protected function paginateRequestQuery($query, $params) { //, $objectData = null) {
			

			//create response
			$response = new StdClass;
		
		
			//valid query
			if ($query) {
		
			
				//get parameters
				$index = isset($params) && isset($params['index']) && is_numeric($params['index']) ? $params['index'] : -1;
				$page = isset($params) && isset($params['page']) && is_numeric($params['page']) ? $params['page'] : 0;
				$limit = isset($params) && isset($params['limit']) && is_numeric($params['limit']) ? $params['limit'] : 0; 

				//bounds checks
				if ($page<0) $page = 0;
				if ($limit<0) $limit = 0;
				

				
				//process count query
				$count = $query->count();
				
				//validate count
				$count = isset($count) ? $count : 0;
				
				//store number of rows
				$response->rows = $count;
				
				//echo "count data: " . print_r($countData, true) . " - count: " . $countData . "<BR><BR>\n\n";
				//echo "count: " . $count . " - total: " . ceil(floatval($count) / $limit) ."<BR><BR>\n\n";
				//echo "page: " . $page . " - limit: " . $limit ."<BR><BR>\n\n";
				
				//process variables
				//$index = 0;
				$totalPages = 0;
				if ($limit>0) {
					
					//determine number of pages
					if (is_numeric($count)) {
						$totalPages = ceil(floatval($count) / $limit);
					}


					//bounds check page
					if ($page>=$totalPages) {
						$page=$totalPages>0 ? $totalPages-1 : 0;
					}

					//set index (if required)
					if ($index<0) {
						$index = $page * $limit;
					}
					
				}
				
				//bounds check index
				if ($index<0) $index = 0;
				if ($index>$count) $index = $count;
				
				
				//process data query
				$dataQuery = $query;
				if ($index>0) {
					$dataQuery = $dataQuery->offset($index);
				}
				if ($limit>0) {
					$dataQuery = $dataQuery->limit($limit);
				}
				//ensure limit is set if offset is used (avoids SQL error)
				else if ($index>0) {
					$dataQuery = $dataQuery->limit(PHP_INT_MAX);
				}

				//retrieve data
				$data = $dataQuery->get();

				
				//update parameters
				$response->current_page = $page;
				$response->items_per_page = $limit;
				$response->total_pages = $totalPages;
				$response->last_page = ($totalPages>0 ? ($totalPages-1) : 0);
				$response->data = ($data && !is_array($data)) ? $data->toArray() : $data;
							
			
			} //end if (valid query)
			
			//log error
			else {
				$response->error = "Invalid queries";
			}
			
			
			return $response;
		
			
		} //end paginateRequestQuery()
		
		
		
	} //end class BaseCMSController
	
	
?>
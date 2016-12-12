<?php

	class CMSController extends BaseCMSController {
		

		//public function __construct() {
			

		//} //end constructor()
		
		
		
		
		
		
		//==========================================================//
		//====				AUTHENTICATION METHODS				====//
		//==========================================================//	
		
		
		
		
		public function getIndex() {
			
			return View::make('cms::admin.home');
			
		} //end getIndex()
	
	
	
	
		public function getLogin() {
		
			return View::make('cms::admin.login');
			
		} //end getLogin()
	
	
	
	
		public function postLogin() {
		
			$username = Input::get('username');
			$password = Input::get('password');
	

			if (Auth::CMSuser()->attempt(Array ('username' => $username, 'password' => $password)))
			{
				return Redirect::secure('/cms/');
			}

			return Redirect::back()
				->withInput()
				->withErrors('Invalid username/password combination.');
				
		} //end postLogin()
	
	
	
	
		public function getLogout() {

			Auth::CMSuser()->logout();
	
			return Redirect::to('/cms/login');
			
		} //end getLogout()

		
		
		
		
		
		
		//==========================================================//
		//====					SERVICE METHODS					====//
		//==========================================================//	
		
		/*
		public function getVenueactivity() {
			
			//build querys
			$query = "SELECT page_id, title, SUM(count) AS visits, COUNT(DISTINCT user_id) AS distinct_users, SUM(duration) AS viewing_time FROM tracking_events LEFT JOIN data_venues ON tracking_events.page_id = data_venues.id LEFT JOIN tracking_sessions ON tracking_sessions.id = tracking_events.session_id WHERE page_type = 'Venue' GROUP BY page_id ORDER BY visits DESC, viewing_time DESC";
			$countQuery = "SELECT count(page_id) AS count FROM (" . $query . ") AS innerQuery";
			
			$pageQueryFunction = function($index, $limit, $objectData) {
				return $objectData . " LIMIT " . $index . ", " . $limit;
			};
			
			return $this->paginateRequestQuery($countQuery, $pageQueryFunction, $query);
			
		} //end getVenueactivity()
		
		
		
		
		public function getPlaylistactivity() {
			
			//build querys
			$query = "SELECT page_id, title, author, SUM(count) AS visits, COUNT(DISTINCT user_id) AS distinct_users, SUM(duration) AS viewing_time FROM tracking_events LEFT JOIN data_playlists ON tracking_events.page_id = data_playlists.id LEFT JOIN tracking_sessions ON tracking_sessions.id = tracking_events.session_id WHERE page_type = 'Playlist' GROUP BY page_id ORDER BY visits DESC, viewing_time DESC";
			$countQuery = "SELECT count(page_id) AS count FROM (" . $query . ") AS innerQuery";
			
			$pageQueryFunction = function($index, $limit, $objectData) {
				return $objectData . " LIMIT " . $index . ", " . $limit;
			};
			
			return $this->paginateRequestQuery($countQuery, $pageQueryFunction, $query);
			
		} //end getPlaylistactivity()
		
		
		
		public function getVibeactivity() {
			
			//build querys
			$query = "SELECT page_id, SUM(count) AS visits, COUNT(DISTINCT user_id) AS distinct_users, SUM(duration) AS viewing_time FROM tracking_events LEFT JOIN tracking_sessions ON tracking_sessions.id = tracking_events.session_id WHERE page_type = 'Discover' GROUP BY page_id ORDER BY visits DESC, viewing_time DESC";
			$countQuery = "SELECT count(page_id) AS count FROM (" . $query . ") AS innerQuery";
			
			$pageQueryFunction = function($index, $limit, $objectData) {
				return $objectData . " LIMIT " . $index . ", " . $limit;
			};
			
			return $this->paginateRequestQuery($countQuery, $pageQueryFunction, $query);
			
		} //end getVibeactivity()
		
		
		
		public function getLivevibeactivity() {
			
			//build querys
			$query = "SELECT data_venues.title AS venue, data_live_vibes.name AS vibe, COUNT(vibe_id) AS votes, MAX(date) AS last_vote_time FROM data_votes LEFT JOIN data_live_vibes ON data_live_vibes.id = data_votes.vibe_id LEFT JOIN data_venues ON data_venues.id = data_votes.venue_id  GROUP BY venue_id, vibe_id ORDER BY votes DESC, last_vote_time DESC";
			$countQuery = "SELECT count(venue) AS count FROM (" . $query . ") AS innerQuery";
			
			$pageQueryFunction = function($index, $limit, $objectData) {
				return $objectData . " LIMIT " . $index . ", " . $limit;
			};
			
			return $this->paginateRequestQuery($countQuery, $pageQueryFunction, $query);
			
		} //end getLivevibeactivity()
		
		
		
		public function getUseractivity() {
			
			//build querys
			$query = "SELECT user_id, first_name, last_name, email, facebook_id, gender, country, ip_address, SUM(foreground_duration) AS visible_duration, SUM(background_duration)  AS hidden_duration, COUNT(user_id) AS sessions, MAX(start_time) AS last_session, data_users.created_at FROM tracking_sessions LEFT JOIN data_users ON tracking_sessions.user_id = data_users.id GROUP BY user_id ORDER BY visible_duration DESC, sessions DESC";
			$countQuery = "SELECT count(user_id) AS count FROM (" . $query . ") AS innerQuery";
			
			$pageQueryFunction = function($index, $limit, $objectData) {
				return $objectData . " LIMIT " . $index . ", " . $limit;
			};
			
			return $this->paginateRequestQuery($countQuery, $pageQueryFunction, $query);
					
		} //end getUseractivity()
		
		
		
		
		public function getCuratoractivity() {
			
			//build querys
			$query = "SELECT page_id, handle, first_name, last_name, SUM(count) AS visits, COUNT(DISTINCT user_id) AS distinct_users, SUM(duration) AS viewing_time FROM tracking_events LEFT JOIN data_curators ON tracking_events.page_id = data_curators.id LEFT JOIN tracking_sessions ON tracking_sessions.id = tracking_events.session_id WHERE page_type = 'Curator' GROUP BY page_id ORDER BY visits DESC, viewing_time DESC";
			$countQuery = "SELECT count(page_id) AS count FROM (" . $query . ") AS innerQuery";
			
			$pageQueryFunction = function($index, $limit, $objectData) {
				return $objectData . " LIMIT " . $index . ", " . $limit;
			};
			
			return $this->paginateRequestQuery($countQuery, $pageQueryFunction, $query);
			
		} //end getCuratoractivity()
		
		
		
		public function getAnalytics() {
			
			//get authorised user
			$user = Auth::cmsuser()->user();
			if ($user) {
			
				//get users
				$allUsers = Users::all();
				//print_r($allUsers);
			
				//show analytics view
				return View::make('console::console.analytics')->with('users', $allUsers);

			}
			
			//invalid user
			else {
				return Redirect::to('/console');
			}
			
		} //end anyAnalytics()
		
		
		*/
		
		
		//==========================================================//
		//====					UTIL METHODS					====//
		//==========================================================//	
		
		
		
		
		public function paginateRequestQuery($countQuery, $dataQueryFunction, $objectData = null) {
			
			//get authorised user
			$user = Auth::cmsuser()->user();
			if ($user) {
			
				//create response
				$response = new StdClass;
			
			
				//valid queries
				if ($countQuery && $dataQueryFunction && strlen($countQuery)>0) {
			
				
					//get parameters
					$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 0;
					$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? $_GET['limit'] : 0; //10;
					
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
				
				
				//return Response::json($data);
				return Response::json($response);
			
			}
			
			//invalid user
			else {
				return Redirect::to('/cms');
			}
			
		} //end paginateRequestQuery()
		
					
	} //end class CMSController


?>
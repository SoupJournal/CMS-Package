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
	
	
			//validate login
			if (Auth::CMSuser()->attempt(Array ('username' => $username, 'password' => $password)))
			{
				//set current application
				//$appID = Session::get(CMSAccess::$SESSION_KEY_APP_ID);
				//if (!isset($appID)) {
					
					//find first user application
					
					
				//}
				
				//find app id
				$appId = null;
				
				//get list of available applications
				$applications = CMSAccess::userApplications();
				if ($applications && count($applications)>0) {
					$appId = $applications[0]->id;
				}
				
				//found application
				if ($appId>=0) {
					return Redirect::secure('/cms/' . $appId);
				}
				//no application available
				else {
					return Redirect::secure('/cms/');
				}
			}


			//error - redirect to login page with error message
			return Redirect::back()
				->withInput()
				->withErrors('Invalid username/password combination.');
				
				
		} //end postLogin()
	
	
	
	
		public function getLogout() {

			//logout user
			Auth::CMSuser()->logout();
			
			//clear session
			Session::flush();
	
			//redirect to login
			return Redirect::to('/cms/login');
			
		} //end getLogout()

		
		
		
		public function getError($errorCode = null) {
		
			//compile error message
			$errorTitle = null;
			$errorMessage = null;
			
			switch ($errorCode) {
				
				case 404:
				{
					$errorTitle = "Permission Denied";
					$errorMessage = "You do not have permission to view this page";
				}
				break;
					
			} //end switch (errorCode)
		
		
			//show error view
			return View::make('cms::admin.error')->with(array(
				'errorTitle' => $errorTitle,
				'errorMessage' => $errorMessage
			));
			
			
		} //end getError()
		
		
		
		
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
		
	
		
					
	} //end class CMSController


?>
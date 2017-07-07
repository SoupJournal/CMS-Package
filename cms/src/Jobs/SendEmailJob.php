<?php

	namespace Soup\CMS\Jobs;
	
	use Soup\CMS\Jobs\BaseJob;
	use Illuminate\Queue\SerializesModels;
	use Illuminate\Queue\InteractsWithQueue;
	use Illuminate\Contracts\Bus\SelfHandling;
	use Illuminate\Contracts\Queue\ShouldQueue;
	
	use Illuminate\Contracts\Mail\Mailer;
	
	
	class SendEmailJob extends BaseJob implements SelfHandling, ShouldQueue {
	
	
	    use InteractsWithQueue, SerializesModels;
	    
	    
	    //email properties
	    protected $properties;
	    
	    
	    /**
	     * Create a new job instance.
	     *
	     * @return void
	     */
	    public function __construct($properties) {
	    
	    	//set properties
	    	$this->properties = $properties;
	        
	    } //end constructor()
	    
	    
	    /**
	     * Execute the job.
	     *
	     * @return void
	     */
	    public function handle() //Mailer $mailer)
	    {
	    	
	    	//valid properties
	    	if ($this->properties) {
	    	
	    		//get properties
	    		$recipient = safeArrayValue('recipient', $this->properties, null);
	    		$sender = safeArrayValue('sender', $this->properties, "");
	    		$subject = safeArrayValue('subject', $this->properties, "");
	    		$viewName = safeArrayValue('view', $this->properties, null);
	    		$viewProperties = safeArrayValue('view_properties', $this->properties, null);
	    		
	    		//get sender properties
	    		$senderName = "";
	    		$senderEmail = "";
	    		if ($sender) {
	    			
	    			//array
	    			if (is_array($sender)) {
	    				$senderName = safeArrayValue('name', $sender, "");	
	    				$senderEmail = safeArrayValue('email', $sender, "");	
	    			}
	    			//string 
	    			else {
	    				$senderEmail = $sender;
	    			}
	    			
	    		}
	    	
		    	//valid recipient
		    	if ($recipient && strlen($recipient)>0) {
		    	
		    		//valid view name
		    		if ($viewName && strlen($viewName)>0) {
		    	
		    			//send result
		    			$result = false;
		    	
		    	/*
		    			try {
		    	
			    			//create view
			    			$view = \View::make($viewName);
			    			if ($view) {
			    				
		    					//apply view properties
		    					if ($viewProperties) {
			    					$view->with($viewProperties);
		    					}
			    	
								//create headers
								$headers = "MIME-Version: 1.0\r\n"
										 . "Content-type: text/html;charset=UTF-8\r\n"
										 . "From: " . $senderEmail . "\r\n";
			    	
			    	
						    	//send email through sendmail
								$result = mail($recipient, $subject, $view->render(), $headers);	
						    	
			    			} //end if (valid view)
				    	
		    			}
		    			catch (Exception $ex) {
		    				Log::error("ERROR sending mail with Sendmail service: " . $ex);
		    				$result = false;
		    			}

		    			//dd(\Mail::failures());
		    		*/	
		    			//retry with Laravel mail service
		    			if (!$result) {
		    				
							try {

								//send email (Using Laravel Mail Provider)
								$result = \Mail::send($viewName, $viewProperties, function ($data) use ($senderEmail, $senderName, $recipient, $subject) {
									$data->from($senderEmail, $senderName);
									$data->to($recipient); 
									$data->subject($subject);
								});
								
							}
							catch (\Exception $ex) {
								//dd($ex);
								\Log::error("ERROR sending mail with Laravel service: " . $ex);
							}
		    				
		    			} 
		    			
				    	
		    		} //end if (valid view name)
		    	
		    	} //end if (valid recipient)
	    	
	    	} //end if (has properties)
	 
	    } //end handle()
	    
	    
	    
	    
	    
//	    public function failed(Exception $exception) {
//
//			//parent::failed($exception);
//			echo "got queue fail event\n";
//var_dump( $exception );
////$this->info('Display this on the screen');
//		    // handle failure
////		    dd($exception);
//		    
//		} //end failed()
	    
	    
	} //end class SendEmailJob
	

?>
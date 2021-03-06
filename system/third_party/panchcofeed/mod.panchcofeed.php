<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panchcofeed {

    var 		$configured		= FALSE;
    var 		$return_data    = '';
    var 		$hashtag		= '';
    var 		$application	= '';
    var			$client_id		= '';
    var			$client_secret	= '';
    var			$props			= array('error_message'=>'');
    var 		$media_count	= 25;
    var 		$endpoint		= '';
    var 		$tag_delimiter	= ',';
    var 		$next_url		= '';
    var 		$page_id		= NULL;
    var 		$next_page		= NULL;
    var 		$ig_user		= NULL;
    var 		$ig_id			= NULL;
    var 		$ig_username	= NULL;
    
    
    
    function __construct()
    {

		ee()->lang->loadfile('panchcofeed');
		ee()->load->helper('panchco_curl');

	}
	    
// -----------------------------------------------------------------------------

	    /**
	     * Fetch the application row.
	     */
	     public function set_application()
	     {

		     	    // Get current parameter from template
		     	    $this->get_parameters();

		     	   
		    			
		    			$row	= ee()->db
		    			->order_by('app_id','DESC')
		    			->get('panchcofeed_applications')
		    			->row();

	    		

					if($row)
					{
							$this->configured		= TRUE;
							$this->app_id			= $row->app_id;
					    $this->client_id		= $row->client_id;
							$this->client_secret	= $row->client_secret;
							$this->access_token		= $row->access_token;
							$this->ig_user			= @unserialize($row->ig_user);
							$this->props['application']	= $row->application;
					} else {
						
						$this->props['error_message'] = lang('not_configured');
					}
	
	     }
	     
	     
// -----------------------------------------------------------------------------
	    
    
    /**
     * Get Instagram items by hashtag.
     */
    public function media_hashtag()
    {
    
    // Query db and set the application properties.
    	$this->set_application();
    	
    	if(TRUE === $this->configured && isset($this->props['hashtag']))
    	{ 

				// Build out the endpoint url
				$endpoint = "https://api.instagram.com/v1/tags/";
				$endpoint.= $this->props['hashtag'];
				$endpoint.= "/media/recent?client_id=";
				$endpoint.= $this->client_id.'&count='.$this->media_count;
				$endpoint.= '&max_tag_id='.$this->page_id;

				$this->props['endpoint']	= $endpoint;
				
				$this->parse_media();
				
    	} 
    	
    	if($this->props['total_media'] > 0)
    	{ 
	    	return ee()->TMPL->parse_variables(ee()->TMPL->tagdata,array($this->props));
    	} else {
	    	return ee()->TMPL->no_results();
    	} 	
    	
    
    }
 
 // -----------------------------------------------------------------------------   
    
   /**
    * Get feed of media items from Instagram user's athenticated user follows.
    */
    public function media_feed()
    {
		    
		   // Query db and set the application properties.
			 $this->set_application();
		    
		    	if(TRUE === $this->configured)
				{
					// Build out the endpoint url
					
					$endpoint	=	"https://api.instagram.com/v1/users/self/feed?access_token=";
					$endpoint.= $this->access_token . "&count=".$this->media_count;
					$endpoint.= '&max_id='.$this->page_id;
					
					$this->props['endpoint'] = $endpoint;
		
					$response	= CurlHelper::getCurl($this->props['endpoint']);
			
					$obj = json_decode($response);

					// Add pagination properties.
					if(isset($obj->pagination))
					{
						$this->add_pagination_properties($obj->pagination);
					}
					
					// Add meta properties.
					if(isset($obj->meta))
					{
						$this->add_meta_properties($obj->meta);
					
					} 
			
					// Add media data array 
					if(isset($obj->data))
					{
						$this->add_media_array($obj->data);
						
					} else {
						
						// Something went wrong, pass an empty array for the media property.
						$this->props['media'][] = array();
					}
				
				} 
				

				if($this->props['total_media'] > 0)
				{ 
	    		return ee()->TMPL->parse_variables(ee()->TMPL->tagdata,array($this->props));
    		} else {
	    		return ee()->TMPL->no_results();
    		} 
    }
    
// -----------------------------------------------------------------------------    
	
	/**
	 * Get Instagram media for the authenticated user.
	 */
	 public function media_self()
    {
    
        // Query db and set the application properties.
    	$this->set_application();

    	if(TRUE === $this->configured)
    	{
    	
    	// Set the authenticated user data to props.
    	$this->add_ig_user_array($this->ig_user);
		
			// Build out the endpoint url
			$endpoint	= "https://api.instagram.com/v1/users/" . $this->ig_user->id;
			$endpoint.= "/media/recent/?client_id=" . $this->client_id . "&count=";
			$endpoint.= $this->media_count .'&max_id='.$this->page_id;
		
			$this->props['endpoint'] = $endpoint;

			$this->parse_media();
		
			} 

			if($this->props['ig_user'])
			{
				return ee()->TMPL->parse_variables(ee()->TMPL->tagdata,array($this->props));
			} else {
				return ee()->TMPL->no_results();
			}
    
    }
    
// -----------------------------------------------------------------------------
    
  /**
	 * Get Instagram media like by  the authenticated user.
	 */
	 public function media_self_liked()
    {
    
        // Query db and set the application properties.
    	$this->set_application();

    	if(TRUE === $this->configured)
    	{
    	
    	// Set the authenticated user data to props.
    	$this->add_ig_user_array($this->ig_user);

			// Build out the endpoint url
			$endpoint	= "https://api.instagram.com/v1/users/self/media/liked?access_token=";
			$endpoint.= $this->access_token . "&count=" . $this->media_count . "&max_like_id=";
			$endpoint.= $this->page_id;
			
			$this->props['endpoint'] = $endpoint;
			
			$this->parse_media();
			
			} 
			
			if($this->props['ig_user'])
			{
				return ee()->TMPL->parse_variables(ee()->TMPL->tagdata,array($this->props));
			} else {
				return ee()->TMPL->no_results();
			}
    }
    
// -----------------------------------------------------------------------------
    
    /**
     * Return media for an Instagram username.
     */
     public function media_user()
     {
	     $this->set_application();

	     if(TRUE === $this->configured)
	     {
		     // Check for ig_id property.
		     if( $this->ig_id ) {
			     
			     $this->props['ig_id'] = $this->ig_id;
		     
		     // If that's not there, check for ig_username property.
		     } elseif($this->ig_username)  {
			     
			     $this->set_ig_user($this->ig_username);
			    
			     $this->ig_id = $this->props['ig_id'];
		     
		     // If neither of those are there, set the ig_id property from $this->ig_user;
		     } else {
			     
			    $this->props['ig_id'] = $this->ig_user->id;
			     
		     }
		        
		     $this->props['page_id'] = $this->page_id;
	
		     // Build out the endpoint url
		     $endpoint = "https://api.instagram.com/v1/users/" . $this->props['ig_id'];
		     $endpoint.= "/media/recent/?client_id=" . $this->client_id;
		     $endpoint.= "&count=" . $this->media_count . "&max_id=" . $this->page_id;
		     $this->props['endpoint'] = $endpoint;
		     
		     $this->parse_media();
	     
	     }

    	if($this->props['user_found']==1)
			{
			return ee()->TMPL->parse_variables(ee()->TMPL->tagdata,array($this->props));
			} else {
			return ee()->TMPL->no_results();
			}
	     
  	}
     
// -----------------------------------------------------------------------------
    
    /**
	  * Instagram user object for output to template.
	  */
	  public function user_profile()
	 {
		 	$data['user_found']	= FALSE;
		 	
		 	$this->set_application();
			
			if(TRUE === $this->configured)
		    {
			
				$this->set_application();
			
				$this->set_ig_user($this->ig_username);
			
			}
			

			if( isset($this->props['ig_user'][0]) )
			{

				foreach( $this->props['ig_user'][0] as $key => $row)
				{
					$data[$key] = $row;
				}
				
				unset($data['ig_user']);
				
			}
			
			if($data['user_found']==1)
			{
				return ee()->TMPL->parse_variables(ee()->TMPL->tagdata,array($data));
			} else {
				return ee()->TMPL->no_results();
			}
	
    	
	 }

// -----------------------------------------------------------------------------
        
        /**
         * Do the Instagram oAuth dance.
         */
        public function ig_auth()
        {
	        
	        // If Instagram has responded with an authorization code, submit that to get the access token.
	        if(ee()->input->get('code'))
	        {
	        
	        	// Get application row from the db
	        	// Get IG applcation settings from db.
			 	$row	= ee()->db
							->get('panchcofeed_applications')
							->row();
							
							

	        	$url						= 'https://api.instagram.com/oauth/access_token';
	        	$fields['client_id'] 		= $row->client_id;
	        	$fields['client_secret']	= $row->client_secret;
	        	$fields['redirect_uri']		= $row->redirect_uri;
	        	$fields['grant_type']		= $row->grant_type;
	        	$fields['code']				= ee()->input->get('code',TRUE);
	        	
	        	$response = json_decode(CurlHelper::postCurl($url,$fields));

	        	
	        	if( $response )
	        	{
		        	
		        	if(isset($response->access_token)) 
		        	{
			        	// Success, save the access token.
			        	
			        	$data['access_token']	= $response->access_token;
			        	$data['ig_user']		= serialize($response->user);
			        	
			        	ee()->db->where('app_id',$row->app_id)
			        		->update('panchcofeed_applications',$data);
			        		
			        		$vars['msg'] = lang('auth_success');
							
		        		
		        		} else {
			        		
			        		$vars['msg']	= lang('auth_fail');

		        	}
		        	
		        	$vars['close_window'] = lang('close_window');
		        	return ee()->load->view('auth_response',$vars);
		        	
	        	}
	        } 
        }
        
// -----------------------------------------------------------------------------
        
        /**
         * Confirm authentication for an application.
         */
         public function ajax_confirm_auth()
         {
         
         	ee()->load->model('applications_model','applications');
         	
         	 $vars['authenticated']	= FALSE;
	         $vars['authorize']	= lang('authorize');
	         $vars['app_id'] = ee()->input->get('app_id',TRUE);
	         $vars['client_id']	= ee()->input->post('client_id',TRUE);
	         
				if(ee()->applications->access_token_valid())
				{
					$vars['authenticated']	= TRUE;
				} 	
     
	         	return ee()->load->view("ajax_auth_row",$vars);
	         
	         die();
         }
         
         
// -----------------------------------------------------------------------------

    /**
     * Parse a media response object to template variables.
     * @return boolean.
     */
     private function parse_media()
     {
	     
	    $response	= CurlHelper::getCurl($this->props['endpoint']);
	    

	    $obj = json_decode($response);
		
		// Add pagination properties.
		if(isset($obj->pagination))
		{
			$this->add_pagination_properties($obj->pagination);
		}
		
		// Add meta properties.
		if(isset($obj->meta))
		{
			$this->add_meta_properties($obj->meta);
		
		} 

		// Add media data array 
		if(isset($obj->data))
		{
			$this->add_media_array($obj->data);
			
			
		} else {
			
			// Something went wrong, pass an empty array for the media property.
			$this->props['media'][] = array();
		}
		
		if(isset($this->props['media']))
		{
			$this->props['total_media'] = count($this->props['media']);
		} else {
			$this->props['total_media'] = 0;
		}

		return TRUE;
	     
     }
         
 // -----------------------------------------------------------------------------
         
	/**
	 * Fetch parameter being passed from the current template.  
	 */
	 private function get_parameters()
	 {
	 
	 	// Fetch the application parameter.
	 	if(ee()->TMPL->fetch_param('application'))
		{
			$this->props['application'] = ee()->TMPL->fetch_param('application');;
		}
	 
	 	// Fetch the hasthag parameter from tthe template.
	    if(ee()->TMPL->fetch_param('hashtag'))
	    {
	    	$this->props['hashtag'] = str_replace('#','', ee()->TMPL->fetch_param('hashtag'));
	    }
	    
	    // How many items? Fetch the media_count property.
	    if(ee()->TMPL->fetch_param('media_count'))
	    {
	    	$this->media_count = ee()->TMPL->fetch_param('media_count');
	    }
	    
	    // Fetch the tag_delimiter property.
	    if(ee()->TMPL->fetch_param('tag_delimiter'))
	    {
	    	$this->tag_delimiter = ee()->TMPL->fetch_param('tag_delimiter');
	    }
	    
		// Fetch the page_id property.
	    if(ee()->TMPL->fetch_param('page_id'))
	    {
	    	$this->page_id = ee()->TMPL->fetch_param('page_id');
	    }
	    
		// Fetch the ig_username property.
	    if(ee()->TMPL->fetch_param('ig_username'))
	    {
	    	$this->ig_username = ee()->TMPL->fetch_param('ig_username');
	    }
	    
	    // Fetch the ig_id property.
	    if(ee()->TMPL->fetch_param('ig_id'))
	    {
	    	$this->ig_id = ee()->TMPL->fetch_param('ig_id');
	    }

	 }
         
// -----------------------------------------------------------------------------
	 
	 /**
	  * Set an Instagram user object to this->props.
	  */
	  private function set_ig_user($ig_username)
	  {
			$obj = $this->user_find($ig_username);
			$this->props['ig_username'] = $obj->username;
			$this->props['ig_profile_picture'] = $obj->profile_picture;
			$this->props['ig_full_name'] = $obj->full_name;
			$this->props['ig_id'] = $obj->id;	
			$this->props['user_found'] = $obj->user_found; 
			
			return TRUE; 
	  }
         
// -----------------------------------------------------------------------------
         
  /**
	 * Add pagination object properties to this->props
	 * @param $pagination IG Pagination object from response.
	 * @return boolean.
	 */
	 private function add_pagination_properties($pagination)
	 {

	 	// Set null next_max_tag_id prop if one isn't returned.
			if( ! $pagination->next_max_tag_id)
			{
				if($pagination->next_max_id)
				{
					$pagination->next_max_tag_id = $pagination->next_max_id;
				} else {
					$pagination->next_max_tag_id = NULL;
				}
			}

			// Set null next_url prop to null if one isn't returned.
			if( ! $pagination->next_url)
			{
				$pagination->next_url = NULL;
			}
			
			if( $pagination->next_max_tag_id )
			{
				$pagination->next_page_id	= $pagination->next_max_tag_id;
				
				} elseif( $pagination->next_max_like_id ) {
					
					$pagination->next_page_id	= $pagination->next_max_like_id;
					
				} else {
					
					$pagination->next_page_id	= NULL;
				}
				

			foreach(  $pagination as $key=>$row)
			{
				$this->props[$key] = $row;
			}
			
		 return TRUE;
	 }
	 
// -----------------------------------------------------------------------------	 
	 
	/**
	 * Add meta object properties to this->props
	 * @param $meta IG meta object from response.
	 * @return boolean.
	 */
	 private function add_meta_properties($meta)
	 {
		 if(isset($meta->code))
		 {
		 	$this->props['metacode'] = $meta->code;
		 
		 } else {
		 
			 $this->props['metacode'] = NULL;
		 }
		 
		 if(isset($meta->error_type))
		 {
		 	$this->props['error_type'] = $meta->error_type;
		 
		 } else {
		 
			 $this->props['error_type'] = NULL;
		 }
		 
		 if(isset($meta->error_message))
		 {
		 	$this->props['error_message'] = $meta->error_message;
		 
		 } else {
		 
			 $this->props['error_message'] = NULL;
		 }
		 
		 return TRUE;
		 
	 }
	 

	 
	/**
	 * Add data array properties to this->props
	 * @param $data IG meta object from response.
	 * @return boolean.
	 */
	 private function add_media_array($data)
	 {
	 
	 	if(is_array($data))
	 	{
		 
		 	$this->props['total_media']	= count($data);
		 	$i = 0;
			 
			 foreach($data as $key=>$row)
			 {
			 
			 		$i++;
			 		
			 		// Videos
			 		$vids = new stdClass();
			 		
			 		if(isset($row->videos->low_bandwidth))
			 		{
				 		$vids->low_bandwidth_url = $row->videos->low_bandwidth->url;
				 		$vids->low_bandwidth_width = $row->videos->low_bandwidth->width;
				 		$vids->low_bandwidth_height = $row->videos->low_bandwidth->height;
			 		
				 		} else {
				 		
				 		$vids->low_bandwidth_url = NULL;
				 		$vids->low_bandwidth_width = NULL;
				 		$vids->low_bandwidth_height = NULL;
			 		}
			 		
			 		if(isset($row->videos->low_resolution))
			 		{
				 		$vids->low_resolution_url = $row->videos->low_resolution->url;
				 		$vids->low_resolution_width = $row->videos->low_resolution->width;
				 		$vids->low_resolution_height = $row->videos->low_resolution->height;
			 		
				 		} else {
				 		
				 		$vids->low_resolution_url = NULL;
				 		$vids->low_resolution_width = NULL;
				 		$vids->low_resolution_height = NULL;
			 		}
			 		
			 		if(isset($row->videos->standard_resolution))
			 		{
				 		$vids->standard_resolution_url = $row->videos->standard_resolution->url;
				 		$vids->standard_resolution_width = $row->videos->standard_resolution->width;
				 		$vids->standard_resolution_height = $row->videos->standard_resolution->height;
			 		
				 		} else {
				 		
				 		$vids->standard_resolution_url = NULL;
				 		$vids->standard_resolution_width = NULL;
				 		$vids->standard_resolution_height = NULL;
			 		}
			 		
			 		
			 		$this->props['media'][] = array(
				 							
				 						'link' => $row->link,
				 						'filter' => $row->filter,
				 						'created_time' => $row->created_time,
				 						'media_type' => $row->type,
				 						'likes' => $row->likes->count,
				 						'low_res_url' => $row->images->low_resolution->url,
				 						'low_res_width' => $row->images->low_resolution->width,
				 						'low_res_height' => $row->images->low_resolution->height,
				 						'thumb_url' => $row->images->thumbnail->url,
				 						'thumb_width' => $row->images->thumbnail->width,
				 						'thumb_height' => $row->images->thumbnail->height,
				 						'standard_url' => $row->images->standard_resolution->url,
				 						'standard_width' => $row->images->standard_resolution->width,
				 						'standard_height' => $row->images->standard_resolution->height,
				 						'id'	=> $row->id,
				 						'ig_username' => $row->user->username,
				 						'ig_user_profile_picture' => $row->user->profile_picture,
				 						'ig_user_full_name' => $row->user->full_name,
				 						'ig_user_id' => $row->user->id,
				 						'video_low_bandwidth_url' => $vids->low_bandwidth_url,
				 						'video_low_bandwidth_width' => $vids->low_bandwidth_width,
				 						'video_low_bandwidth_height' => $vids->low_bandwidth_height,
				 						'video_low_resolution_url' => $vids->low_resolution_url,
				 						'video_low_resolution_width' => $vids->low_resolution_width,
				 						'video_low_resolution_height' => $vids->low_resolution_height,
				 						'video_standard_resolution_url' => $vids->standard_resolution_url,
				 						'video_standard_resolution_width' => $vids->standard_resolution_width,
				 						'video_standard_resolution_height' => $vids->standard_resolution_height,
				 						'media_tags' => implode($this->tag_delimiter,$row->tags),
				 						'caption' => (isset($row->caption->text)) ? $row->caption->text : '',
				 						'media_count'	=> $i
				 			);
			 }
		 
		 }
		 
		 return TRUE; 
	 }

// -----------------------------------------------------------------------------	 
	 
	/**
	 * Add authenticated user array properties to this->props
	 * @param $ig_user ig_user object from $this->ig_user.
	 * @return boolean.
	 */
	 private function add_ig_user_array($ig_user)
	 {
	 
		 if(FALSE === isset($ig_user->id))
		 {
			 return $this->props['error_message'] = lang('not_authenticated');
			 
		 } else {
		 
		 	return $this->props['ig_user'][] = (array) $ig_user;
		 	
		 }

	 }   
	 
// -----------------------------------------------------------------------------
	 
	 /**
	  * Find Instagram user by username.
	  * $param $ig_username string
	  * @return $object
	  */
	  private function user_find($ig_username)
	  { 
		  
		   // Build out the endpoint url
		   $endpoint = "https://api.instagram.com/v1/users/search?q=";
		   $endpoint.= $ig_username . "&access_token=" . $this->access_token ."&count=1";
		  
		  $this->props['endpoint'] = $endpoint;
		  
		  $response = json_decode(CurlHelper::getCurl($this->props['endpoint'] ));
		 
		  // Set error message from Instagram response if there was one.
		  if(isset($response->meta->code) && $response->meta->code != 200) 
		  {
			  $this->props['error_message'] = implode("<br />\n",(array) $response->meta);
		  }
		  
		  // Set user object if response successful. 
		  if(isset($response->meta->code) && $response->meta->code == 200 && isset($response->data[0]))
		  {
			  
				$response->data[0]->user_found = TRUE;

		  
				} else {
			 
		  // Otherwise, return object with null values.	  
				  $response = new stdClass();
				  $response->data[0] = new stdClass();
				  $response->data[0]->user_found = FALSE;
				  $response->data[0]->username = NULL;
				  $response->data[0]->bio = NULL;
				  $response->data[0]->website = NULL;
				  $response->data[0]->profile_picture = NULL;
				  $response->data[0]->full_name = NULL;
				  $response->data[0]->id = NULL;

		  } 
		  
		  // Make user data available to template.
		  $this->add_ig_user_array($response->data[0]);
		  
		  return $response->data[0];; 
	  } 

// -----------------------------------------------------------------------------
    
}
/* End of file mod.panchcofeed.php */
/* Location: ./system/expressionengine/third_party/panchcofeed/mod.panchcofeed.php */
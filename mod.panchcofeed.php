<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panchcofeed {

    var 		$return_data    = '';
    var 		$hashtag		= '';
    var 		$application	= '';
    var			$client_id		= '';
    var			$client_secret	= '';
    var			$props			= array();
    var 		$media_count	= 1;
    var 		$endpoint		= '';
    var 		$tag_delimiter	= ',';
    
    
    function __construct()
    {

		ee()->lang->loadfile('panchcofeed');
		
		// Load cURL helper.
	        require_once(dirname(__FILE__) . '/helpers/curlhelper.php');
		
	    }
	    
	    
	    /**
	     * Fetch the application row.
	     */
	     public function set_application()
	     {

		     	    $this->get_parameters();

		     	    // Get IG applcation settings from db.
		     	    
		     	    if(isset($this->props['application']))
		     	    {
				 		$row	= ee()->db
		    			->where('application',$this->props['application'])
		    			->get('panchcofeed_applications')
		    			->row();
	    				
	    				} else {
		    			
		    			$row	= ee()->db
		    			->order_by('app_id','DESC')
		    			->get('panchcofeed_applications')
		    			->row();
		    			
	    			}
	    
					if($row)
					{
							$this->app_id			= $row->app_id;
					    	$this->client_id		= $row->client_id;
							$this->client_secret	= $row->client_secret;
							$this->access_token		= $row->access_token;
							$this->ig_user			= @unserialize($row->ig_user);
							$this->props['application']	= $row->application;
					}  					
	     }
	     
	     
	     
	     
	/**
	 * Fetch parameter being passed from the current template.  
	 */
	 public function get_parameters()
	 {
	 
	 	// Fetch the application parameter.
	 	if(ee()->TMPL->fetch_param('application'))
		{
			$this->props['application'] = ee()->TMPL->fetch_param('application');;
		}
	 
	 	// Fetch the hasthag parameter from tthe template.
	    if(ee()->TMPL->fetch_param('hashtag'))
	    {
	    	$this->props['hashtag'] = ee()->TMPL->fetch_param('hashtag');
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
		 
	 }
	    
	    
	/**
	 * Add pagination object properties to this->props
	 * @param $pagination IG Pagination object from response.
	 * @return boolean.
	 */
	 private function add_pagination_properties($pagination)
	 {
		 if(isset($pagination->next_max_tag_id))
		 {
		 	$this->props['next_max_tag_id'] = $pagination->next_max_tag_id;
		 
		 } else {
		 
			 $this->props['next_max_tag_id'] = NULL;
		 }
		 
		 if(isset($pagination->min_tag_id))
		 {
		 	$this->props['min_tag_id'] = $pagination->min_tag_id;
		 
		 } else {
		 
			 $this->props['min_tag_id'] = NULL;
		 }
		 
		 if(isset($pagination->next_url))
		 {
		 	$this->props['next_url'] = $pagination->next_url;
		 
		 } else {
		 
			 $this->props['next_url'] = NULL;
		 }
		 
		 return TRUE;
		 
	 }
	 
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
		 
			 foreach($data as $key=>$row)
			 {
			 
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
				 						'user_name' => $row->user->username,
				 						'user_profile_picture' => $row->user->profile_picture,
				 						'user_full_name' => $row->user->full_name,
				 						'user_bio' => $row->user->bio,
				 						'user_website' => $row->user->website,
				 						'user_id' => $row->user->id,
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
				 			);
	
			 }
		 
		 }
		 
		 return TRUE;
		 
	 }
	 
	 
	/**
	 * Add authenticated user array properties to this->props
	 * @param $ig_user ig_user object from $this->ig_user.
	 * @return boolean.
	 */
	 private function add_ig_user_array($ig_user)
	 {
	 
	 	return $this->props['ig_user'][] = (array) $ig_user;
	 
	 
	 }    
    
    /**
     * Get Instagram items by hashtag.
     */
    function media_hashtag()
    {
    
    	// Query db and set the application properties.
    	$this->set_application();
	   

		
		// Build out the endpoint url
		$this->props['endpoint'] = "https://api.instagram.com/v1/tags/".$this->props['hashtag']."/media/recent?client_id=".$this->client_id.'&count='.$this->media_count;

		//$response = $this->get_curl($this->props['endpoint']);
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
			
			$this->props['media'][] = array();
		}
		
		
    	$variables[] = $this->props;
    
    	return ee()->TMPL->parse_variables(ee()->TMPL->tagdata,$variables);
    
    }
    
    
    
   /**
    * Get feed of media items from Instagram users athenticated user follows.
    */
    function media_feed()
    {
    
    	// Query db and set the application properties.
    	$this->set_application();
	   

		
		// Build out the endpoint url
		$this->props['endpoint'] = "https://api.instagram.com/v1/users/self/feed?access_token=" . $this->access_token . "&count=".$this->media_count;


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
		
		
    	$variables[] = $this->props;
    
    	return ee()->TMPL->parse_variables(ee()->TMPL->tagdata,$variables);
    
    }
    
    
	
	/**
	 * Get media added by authenticated user.
	 */
	 function media_user()
    {
    
    	// Query db and set the application properties.
    	$this->set_application();
    	
    	// Set the authenticated user data to props.
    	$this->add_ig_user_array($this->ig_user);
		
		// Build out the endpoint url
		$this->props['endpoint'] = "https://api.instagram.com/v1/users/" . $this->ig_user->id . "/media/recent/?client_id=" . $this->client_id . "&count=".$this->media_count;


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
		
		
    	$variables[] = $this->props;
    
    	return ee()->TMPL->parse_variables(ee()->TMPL->tagdata,$variables);
    
    }
    

        
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
		        	
		        	if($response->access_token) 
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
		        	ee()->load->view('auth_response',$vars);
		        	
	        	}
	        	
	        	
	        } else {
		        
		        echo '...';
	        }

	        
        }
    
}
/* End of file mod.panchcofeed.php */
/* Location: ./system/expressionengine/third_party/panchcofeed/mod.panchcofeed.php */
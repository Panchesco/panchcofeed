<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panchcofeed {

    var 		$return_data    = '';
    var 		$hashtag		= '';
    var 		$application	= '';
    var			$client_id		= '';
    var			$client_secret	= '';
    var			$props			= array('client_id'=>'',
    									'hashtag'=>'',
    									'code'=>'',
    									'error_type'=>'',
    									'error_message'=>'',);
    var 		$media_count	= 1;
    var 		$endpoint		= '';
    var 		$tag_delimiter	= ',';
    
    
    function __construct()
    {
	    // Get the application name;
	    $this->application = ee()->TMPL->fetch_param('application');
	    $this->hashtag = ee()->TMPL->fetch_param('hashtag');
	    
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
	    
	    
	    // Get IG applcation settings from db.
	    $result	= ee()->db
	    			->where('application',$this->application)
	    			->get('panchcofeed_applications')
	    			->row();
	    
					if($result)
					{
					    	$this->client_id		= $result->client_id;
							$this->client_secret	= $result->client_secret;
					    	$this->props['hashtag']	= $this->hashtag;
					    	$this->props['application']	= $this->application;

					
					}  else {
						
							$this->props['hashtag']	= $this->hashtag;
					    	$this->props['application']	= '';
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
			 						'username' => $row->user->username,
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
		 
		 return TRUE;
		 
	 }
    
    
    function media_recent()
    {
		
		// Build out the endpoint url
		$this->props['endpoint'] = "https://api.instagram.com/v1/tags/".$this->hashtag."/media/recent?client_id=".$this->client_id.'&count='.$this->media_count;

		$response = $this->get_curl($this->props['endpoint']);

		$obj = json_decode($response);
		
		// Add pagination properties.
		$this->add_pagination_properties($obj->pagination);
		
		// Add meta properties.
		$this->add_meta_properties($obj->meta);
		
		// Add media data array 
		$this->add_media_array($obj->data);
		
		
    	$variables[] = $this->props;
    
    	return ee()->TMPL->parse_variables(ee()->TMPL->tagdata,$variables);
    
    }
    
    
    /**
    * CURL handling.
    * @param $uri string
    * @return object
    */
    public static function get_curl($url) {
            if(function_exists('curl_init')) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
                $output = curl_exec($ch);
                echo curl_error($ch);
                curl_close($ch);
                return $output;
            } else{
                return file_get_contents($url);
            }
        }
    
    
    
    
    
    

}
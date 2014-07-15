<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panchcofeed {

    var 		$return_data    = '';
    var 		$hashtag		= '';
    var 		$application	= '';
    var			$client_id		= '';
    var			$client_secret	= '';
    var			$props			= array();
    var 		$media_count	= 20;
    
    
    function __construct()
    {
	    // Get the application name;
	    $this->application = ee()->TMPL->fetch_param('application');
	    $this->hashtag = ee()->TMPL->fetch_param('hashtag');
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
    
    
    function media_recent()
    {
		
		
		// Build out the endpoint url
		$endpoint = "https://api.instagram.com/v1/tags/".$this->hashtag."/media/recent?client_id=".$this->client_id.'&count='.$this->media_count;
		
		
		/*
		print_r('<pre>');
		print_r($endpoint);
		print_r('</pre>');
		*/
		
								

		$response = $this->get_curl($endpoint);

		$obj = json_decode($response);
		
		/*
		print_r('<pre>');
		print_r($obj);
		print_r('</pre>');
		*/
		

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
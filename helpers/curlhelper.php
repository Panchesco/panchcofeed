<?php
/**
 * CurlHelper
 *
 * @package		InstaApi
 * @author		Richard Whitmer
 */

 if( false === class_exists('CurlHelper'))
 {
	class CurlHelper {
		
		
					/**
				    * CURL handling.
				    * @param $uri string
				    * @return object
				    */
				    public static function getCurl($url) {
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
						
						
					/**
				    * CURL handling.
				    * @param $uri string
				    * @param $fields array of urlencoded strings
				    * @return object
				    */
				    public static function postCurl($url,$fields) {
						    if(function_exists('curl_init')) {
								//url-ify the data for the POST
								$fields_string = '';
								foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
								rtrim($fields_string, '&');
								
								//open connection
								$ch = curl_init();
								
								//set the url, number of POST vars, POST data
								curl_setopt($ch,CURLOPT_URL, $url);
								curl_setopt($ch,CURLOPT_POST, count($fields));
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						        curl_setopt($ch, CURLOPT_HEADER, 0);
						        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
						        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
								curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
						        $output = curl_exec($ch);
						        echo curl_error($ch);
						        curl_close($ch);
						        return $output;
						    } else{
						        return file_get_contents($url);
						    }
						}
		
		
	}
	
}
/* End of file curlhelper.php */
/* Location: ./system/expressionengine/third_party/panchcofeed/helpers/curlhelper.php */
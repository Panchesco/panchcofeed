<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * PanchcoFeed
 *
 * @package		PanchcoFeed
 * @author		Richard Whitmer
 * @copyright	Copyright (c) 2014
 * @license		
 * @link		
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * ExpressionEngine Pages Model
 *
 * @package		PanchcoFeed
 * @subpackage	Third Party
 * @category	Model
 * @author		Richard Whitmer
 * @link		http://panchco.com
 */
class Applications_model extends CI_Model {
	
	var $app_id	= false;
	var $table	= 'panchcofeed_applications';
	
	/**
	 * Fetch Applications
	 *
	 * Return Array of applications.
	 *
	 * @access public
	 * @return array
	 */
	function fetch_all()
	{
		$this->db->select('*');
        $query = $this->db->get('panchcofeed_applications');

		return $query->result('site_pages');
	}

// ------------------------------------------------------------------------


	/**
	 * Create application row
	 *
	 * Insert a new panchcofeed_applications record.
	 *
	 * @access public
	 * @return boolean
	 */
	function create()
	{
		$data['application']	= ee()->input->post('application',TRUE);
		$data['client_id']		= ee()->input->post('client_id',TRUE);
		$data['client_secret']	= ee()->input->post('client_secret',TRUE);

		return $this->db->insert($this->table,$data);
	}
	
// ------------------------------------------------------------------------
	
	/**
	 * Fetch application row
	 *
	 * Fetch an exisiting panchcofeed_applications row.
	 *
	 * @access public
	 * @return array
	 */
	function fetch()
	{
	
		return $this->db
					->where('app_id',ee()->input->get('app_id',TRUE))
					->get($this->table)
					->row_array();
	}
	
	/**
	 * Modify application row
	 *
	 * Modify an exisiting panchcofeed_applications row.
	 *
	 * @access public
	 * @return boolean
	 */
	function modify()
	{
		$this->app_id			= ee()->input->post('app_id',TRUE);
		$data['application']	= ee()->input->post('application',TRUE);
		$data['client_id']		= ee()->input->post('client_id',TRUE);
		$data['client_secret']	= ee()->input->post('client_secret',TRUE);
	
		return $this->db
					->where('app_id',$this->app_id)
					->limit(1)
					->update($this->table,$data);
	}
	
	
	}
	// END CLASS

/* End of file Applications_model.php */
/* Location: ./system/expressionengine/third_party/panchcofeed/models/applications_model.php */
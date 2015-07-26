<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Panchcofeed_upd {

		public  $version = '1.0';

		public function install()
		{
			ee()->load->dbforge();
			
			$data	= array(
			
				'module_name'	=> 'Panchcofeed',
				'module_version'	=> $this->version,
				'has_cp_backend'	=> 'y',
				'has_publish_fields'	=> 'n'
			);
			
			
			ee()->db->insert('modules',$data);
			
			
			// Instagram Authentication Action
			$data = array(
				'class' => 'Panchcofeed',
				'method' => 'ig_auth',
			);
			
			ee()->db->insert('actions', $data);
			
			
			// AJAX confirm authentication action.
			$data = array(
				'class' => 'Panchcofeed',
				'method' => 'ajax_confirm_auth',
			);
			
			ee()->db->insert('actions', $data);

			
			// Module Data.
			$fields = array(
				'app_id'		=> array('type' => 'int',
															'constraint' => '10',
															'unsigned' => TRUE,
															'auto_increment' => TRUE ),
				'application'	=> array('type' => 'varchar',
																'constraint' => '72'),
				'client_id'		=> array('type' => 'varchar',
																'constraint' => '40'),
				'client_secret'	=> array('type' => 'varchar',
																	'constraint' => '40'),
				'website_url'	=> array('type' => 'varchar',
																'constraint' => '256'),
				'redirect_uri'	=> array('type' => 'varchar',
																	'constraint' => '256'),
				'grant_type'	=> array('type' => 'varchar',
																'constraint' => '40',
																'default' => 'authorization_code'),
				'redirect_uri'	=> array('type'	=> 'varchar',
																	'constraint' => '256'),
				'access_token'	=> array('type' => 'varchar',
																	'constraint' => '256'),
				'ig_user'		=> array('type' => 'text')	
				
			);
			
			
			ee()->dbforge->add_key('app_id',TRUE);
			
			ee()->dbforge->add_field($fields);
			
			ee()->dbforge->create_table('panchcofeed_applications');

			return TRUE;
		
		}
		
// -----------------------------------------------------------------------------		
		
		function uninstall()
		{
		    ee()->load->dbforge();
		
		    ee()->db->select('module_id');
		    $query = ee()->db->get_where('modules', array('module_name' => 'Panchcofeed'));
		
		    ee()->db->where('module_id', $query->row('module_id'));
		    ee()->db->delete('module_member_groups');
		
		    ee()->db->where('module_name', 'Panchcofeed');
		    ee()->db->delete('modules');
		
		    ee()->db->where('class', 'Panchcofeed');
		    ee()->db->delete('actions');
		
		    ee()->dbforge->drop_table('panchcofeed_applications');
		
		    // Required if your module includes fields on the publish page
		    ee()->load->library('layout');
		    ee()->layout->delete_layout_tabs($this->tabs(), 'panchcofeed');
		
		    return TRUE;
		}
		
// -----------------------------------------------------------------------------		
		
		function update($current = '')
		{
		    return FALSE;
		}
		
		
// -----------------------------------------------------------------------------
		
		function tabs()
		{
		    $tabs['panchco_tagfeed'] = array(
		        	'PanchcoFeed_field_ids'    => array(
		            'visible'   => 'true',
		            'collapse'  => 'false',
		            'htmlbuttons'   => 'false',
		            'width'     => '100%'
		        )
		    );
		
		    return $tabs;
		}
		
// -----------------------------------------------------------------------------		
		

	}
	/* End of file upd.panchcofeed.php */
	/* Location: ./system/expressionengine/third_party/panchcofeed/upd_panchcofeed.php */
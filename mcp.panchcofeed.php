<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



	class Panchcofeed_mcp {
		
		
		
		function __construct()
		{
			ee()->cp->set_right_nav(array(
        'add_application'  => BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'
            .AMP.'module=panchcofeed'.AMP.'method=create'
			));
			
			ee()->load->model('applications_model','applications');
			ee()->view->cp_page_title = lang('panchcofeed_module_name');
		}
		
		
		function index()
		{
			
			    ee()->load->library('javascript');
				ee()->load->library('table');
				ee()->load->helper('form');

				
				$vars['action_url'] = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=panchcofeed'.AMP.'';
				$vars['form_hidden'] = NULL;
				$vars['apps'] = ee()->applications->fetch_all();
				
			$vars['options'] = array(
				    'edit'  => lang('edit_selected'),
				);	
				
				
				if( empty($vars['apps']))
				{
										
					} else {
					
				}	
		
				return ee()->load->view('index', $vars, TRUE);
		
		
		}
		
		function create()
		{
				ee()->load->library('form_validation');
				
				$vars = array('application'=>'','username'=>'','client_id'=>'','client_secret'=>'','app_id'=>NULL,'redirect_uri'=>$this->redirect_uri()); 

				if(ee()->input->post('submit'))
				{
				
					$vars = ee()->input->post(NULL,TRUE);

					ee()->form_validation->set_rules('application', lang('application'), 'required');
					ee()->form_validation->set_rules('client_id', lang('client_id'), 'required');
					ee()->form_validation->set_rules('client_secret', lang('client_secret'), 'required');
					
					
					
					$valid	= ee()->form_validation->run();
					
					if($valid == TRUE && ee()->applications->create())
					{
						ee()->session->set_flashdata('message_success', lang('create_success'));
						ee()->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=panchcofeed');
						
						} else {
						
						
						ee()->session->set_flashdata('message_failure', validation_errors() . $vars['application']);
						ee()->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=panchcofeed'.AMP.'method=create');
					}
					
				}
					
				$vars['method']	= 'create';
				
				return ee()->load->view('create_modify', $vars, TRUE);
			
		}
		
		
		function modify()
		{
				ee()->applications->app_id	= ee()->input->get("app_id",TRUE);
				
				ee()->load->library('form_validation');
				
				$vars = ee()->applications->fetch();
				$vars['redirect_uri'] = $this->redirect_uri();

				if(ee()->input->post('submit'))
				{
				
					$vars = ee()->input->post(NULL,TRUE);

					ee()->form_validation->set_rules('application', lang('application'), 'required');
					ee()->form_validation->set_rules('client_id', lang('client_id'), 'required');
					ee()->form_validation->set_rules('client_secret', lang('client_secret'), 'required');

					$valid	= ee()->form_validation->run();
					
					if($valid == TRUE && ee()->applications->modify())
					{
						ee()->session->set_flashdata('message_success', lang('modify_success'));
						ee()->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=panchcofeed'.AMP.'method=modify'.AMP.'app_id='.ee()->applications->app_id);
						
						} else {

						ee()->session->set_flashdata('message_failure', validation_errors() . $vars['application']);
						ee()->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=panchcofeed'.AMP.'method=modify'.AMP.'app_id='.ee()->applications->app_id);
					}
					
				}
					
				$vars['method']	= 'modify';
				
				return ee()->load->view('create_modify', $vars, TRUE);
		}
		
		
		
		/**
		 * Query the actions table and create a redirect_uri value for user to give to Instagram.
		 * @return string
		 */
		 private function redirect_uri()
		 {
			 
			 
			 
			 $row = ee()->db->select('action_id')
			 			->where('class','Panchcofeed')
			 			->where('method','ig_auth')
			 			->get('actions')
			 			->row();
			 			
			 return site_url() . '?ACT=' . $row->action_id;
			 
		 }
		
		
		
			
		}
		/* End of file mcp.panchcofeed.php */
		/* Location: ./system/expressionengine/third_party/panchcofeed/mcp.panchcofeed.php */
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	
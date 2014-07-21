<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



	class Panchcofeed_mcp {
		
		
		
		function __construct()
		{
			ee()->cp->set_right_nav(array(
        'add_application'  => BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'
            .AMP.'module=panchcofeed'.AMP.'method=create'
			));
			
			ee()->load->helper('panchco_curl');
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
				
				$vars['authenticated'] = ee()->applications->access_token_valid();

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
		 
		 /** 
		  * Present delete_confirm page.
		  */
		  public function delete_confirm()
		  {
		  
		  
		  
			  
			  $vars['damned']	= (ee()->input->post('toggle',TRUE)) ? ee()->input->post('toggle',TRUE) : array();
			  
			  
			  if(count($vars['damned'])==0)
			  {
			  
			  	ee()->session->set_flashdata('message_failure', lang('nothing_selected'));
			  	ee()->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=panchcofeed');
			  
				  
			  } else {
			  
			  		$vars['apps'] = ee()->db
			  			->where_in('app_id',$vars['damned'])
			  			->order_by('application','ASC')
			  			->get('panchcofeed_applications')
			  			->result();
			  			
			  		
			  			
			  
				  return ee()->load->view('delete_confirm',$vars,TRUE);
			  }
		  }
		 
		 
		 /** 
		  * Handle request to delete applications.
		  */
		  public function delete()
		  {
			  foreach(ee()->input->post('delete',TRUE) as $app_id)
			  {
				  ee()->db
				  	->where('app_id',$app_id)
				  	->delete('panchcofeed_applications');
			  }
			  
			  ee()->session->set_flashdata('message_success', lang('delete_success'));
			  ee()->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=panchcofeed');
			  
		  }
		  
		  /**
		   * Cancel an action and redirect to the landing page for this module.
		   */
		   public function cancel()
		   {
			  ee()->session->set_flashdata('message_success', lang('action_cancelled'));
			  ee()->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=panchcofeed');
		   }
			
		}
		/* End of file mcp.panchcofeed.php */
		/* Location: ./system/expressionengine/third_party/panchcofeed/mcp.panchcofeed.php */
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	
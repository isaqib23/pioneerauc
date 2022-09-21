<?php error_reporting(0);if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acl_roles extends Loggedin_Controller {
	function __construct()
	{
		parent::__construct(); 
		$this->load->helper('date');
		$this->load->helper('url');
	}//end constructor
	
	public function index(){
		$view_role['all_roles'] = $this->acl_model->get_all_roles();
		$this->template->load_admin('acl/Acl_roles',$view_role);
	}

	public function permissions(){ 
		// ini_set('display_errors', 1);
		// ini_set('display_startup_errors', 1);
		// error_reporting(E_ALL);
		
		$this->load->library('controllerlist');
		
		// exit;
		$list = $this->controllerlist->getControllers();
		$all_controllers = array();
		$role_id = $this->uri->segment(4); 
		$view_role['formaction_path'] = 'save_acl_role_permission'; 
		$view_role['all_roles_permissions'] = $this->controllerlist->getControllers();
		$view_role['all_roles'] = $this->acl_model->get_roles();
		$view_role['all_acl_permissions'] = $this->acl_model->get_permission();
		$view_role['all_acl_role_permissions'] = $this->acl_model->get_role_permission_byid($role_id);

		foreach ($view_role['all_acl_role_permissions'] as $key => $value) {
			$all_permission_id[] = $value['acl_permission_id'];
		}
		foreach ($view_role['all_acl_permissions'] as $key => $value) {
			$all_controllers[] = $value['module'];
		}
		$view_role['all_controllers'] = array_unique($all_controllers);
		$view_role['all_permission_id'] = array_unique($all_permission_id); 
		$this->template->load_admin('acl/permissions',$view_role);
	}

	public function save_acl_role_permission(){
		$data_array = $this->input->post('check');
		if(isset($data_array)){
			 $role_id = $this->uri->segment(4); 

			 $check_role_ = $this->acl_model->clean_acl_role_permission($role_id);
			 
			foreach ($data_array as $key=> $data_v) { 
		 			$this->acl_model->save_acl_role_permission($role_id,$data_v);
			    } 
			$this->session->set_flashdata('msg','ACL Permissions has been Assigned');
			redirect(base_url().'acl/Acl_roles'); 
		}else{

			$this->session->set_flashdata('error','Nothing Selected'); 
			redirect(base_url().'acl/Acl_roles/permissions'); 
		}
	}

	public function hidden_permissions()
	{
		$data = array();
		$this->load->library('controllerlist');
		$view_role['all_roles_permissions'] = $this->controllerlist->getControllers();
		foreach ($view_role['all_roles_permissions'] as $controllerlist => $methodlist_array) {
			foreach ($methodlist_array as  $methodlist) {
				$data = array(
					'controller' => $controllerlist,
					'action' => $methodlist,
					'name' => $methodlist,
					'module' => $controllerlist
				);

				$check_role_ = $this->acl_model->check_acl_permissions($data['controller'],$data['action']);
				if($check_role_){
			 		// Nothing 
				}else{
			 	 // add new controller / method
					$this->acl_model->save_permission($data);
					// redirect(base_url().'acl/acl_roles/');
				}


			}
		}
		// redirect(base_url().'acl/acl_roles/');
	}

	public function add_acl_roles_form()
	{
		$view_role['small_title'] = 'Add';
		$view_role['formaction_path'] = 'save_acl_role_form';
		$view_role['user_id'] = $this->loginUser->id;
		$this->template->load_admin('acl/acl_roles_form',$view_role);
	}

	public function edit_acl_roles_form()
	{
		$id = $this->uri->segment(4);
		$view_role['small_title'] = 'Edit';
		$view_role['formaction_path'] = 'update_acl_role_form';
		$view_role['user_id'] = $this->loginUser->id;
		$view_role['all_roles'] = $this->acl_model->get_rolesbyid($id);
		$this->template->load_admin('acl/acl_roles_form',$view_role);
	}

	public function save_acl_role_form()
	{
		$this->load->library('form_validation');
		$data = $this->input->post();
		$name = $this->input->post('name');
		if($this->input->post())
		{
			$rules = array(
				 array(
					'field'   => 'name',
					'label'   => 'Role Name',
					'rules'   => 'trim|required'
				),
				array(
					'field'   => 'description',
					'label'   => 'Role Description',
					'rules'   => 'trim|required'
				)
			);
		}
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run()==FALSE)
		{  
			// $this->session->set_flashdata('error',validation_errors());
			// redirect(base_url().'acl/Acl_roles/add_acl_roles_form');
			echo validation_errors();
		}
		else
		{
			$check_role_name = $this->acl_model->get_rolesbyname($name);
			if($check_role_name->name == $name)	
			{ 
				echo 'Sorry! Some duplication found here';
			} 
			else
			{
				$result = $this->acl_model->save_acl_role($data);
				$this->session->set_flashdata('msg', 'Role Added Successfully');
				echo "success";
			}
		}
	}

	public function update_acl_role_form()
	{
		$this->load->library('form_validation');
		$data = $this->input->post();
		$id = $this->input->post('id');
		if($this->input->post()){
			$rules = array(
				array(
					'field'   => 'name',
					'label'   => 'Role Name',
					'rules'   => 'trim|required'
				),
				array(
					'field'   => 'description',
					'label'   => 'Role Description',
					'rules'   => 'trim|required'
				)
			);
		}
			
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run()==FALSE)
		{ 
			echo  validation_errors();
		}
		else
		{	
			$result = $this->acl_model->update_acl_role($id,$data);
			$this->session->set_flashdata('msg', 'Role Updated Successfully');

			echo 'success';
		}
	}
 
	public function delete(){
		$id 	= $this->input->post("id");
		$table 	= $this->input->post("obj");
		$res 	= false;

		if($table=="acl_roles"){
			$res = $this->acl_model->delete_aclrole_list_row($id);
		}
		if($res){
			echo $return = '{"type":"success","msg":"Ok"}';
		}else{
			echo $return = '{"type":"error","msg":"Something went wrong."}';
		}

	}
	 
	
}



<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Loggedin_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('Common_model', 'common_model');
		$this->load->model('user/Users_model', 'users_model');
	}
	public function index() {
		$data = array();
		$id = $this->loginUser->id;
		$data['small_title'] = 'Dashboard';
		$this->template->load_admin('dashbord', $data);
	}


	public function update_profile() {
		$data = array();
		$id = $this->loginUser->id;
		$data['small_title'] = 'Update Profile';
		$data['formaction_path'] = 'save_profile';
		$data['all_users'] = $this->users_model->get_user_by_id($id);
		$this->template->load_admin('profiles/profile_form', $data);
	}
    
	public function save_profile() {
		$this->load->library('form_validation');
		$id = $this->loginUser->id;
		if ($this->input->post()) {
			$rules = array(
				array(
					'field' => 'fname',
					'label' => 'First Name',
					'rules' => 'trim|required',
				),
				array(
					'field' => 'lname',
					'label' => 'Last Name',
					'rules' => 'trim|required',
				),
				// array(
				// 	'field' => 'email',
				// 	'label' => 'Email',
				// 	'rules' => 'trim|required|valid_email',
				// ),
			);
		}
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() == FALSE) {
			echo validation_errors();
			exit();
		} else {
			
			$data = array(
				'fname' => $this->input->post('fname'),
				'lname' => $this->input->post('lname'),
				'city' => $this->input->post('city'),
				'mobile'	=> "+".$this->input->post('mobile_code').$this->input->post('phone'),
				'username'	=> $this->input->post('fname').' '.$this->input->post('lname')
			);

			// if (!empty($_FILES['profile_picture']['name'])) {

			// 	$path = "uploads/profile_picture/".$id."/";
   //              // make path
   //              if ( !is_dir($path)) {
   //                  mkdir($path, 0777, TRUE);
   //              }
			// 	$newfilename = date('dmYHis');
			// 	// $path = './uploads/profile_picture/';
			// 	$id= $id;
			// 	$file_data =  $this->common_model->upload_files_venue_picture_with_extra('profile_picture'.$newfilename, $path,$_FILES['profile_picture']['name'], 'profile_picture',202, 100);
				
			// 	if ($file_data === FALSE) 
			// 	{
			// 		echo $this->upload->display_errors();
			// 		exit();
			// 	} 
			// 	else 
			// 	{
			// 		$old_image = './upload/profile_picture/'.$id.'/'.$this->input->post('old_profile_picture');
			// 		@unlink($old_image);
			// 		$data['picture'] = $file_data['file_name'];
			// 	}
			// }

			if( ! empty($_FILES['profile_picture']['name']) ){
                    // make path

                    $path = './uploads/profile_picture/'.$id."/";
                    if ( ! is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $sizes = [
                        ['width' => 660, 'height' => 453]
                    ];
                    
                    if (! empty($_FILES['profile_picture']['name'])) {

                    $uploaded_file_ids = $this->files_model->upload('profile_picture', $config, $sizes);

                    if (isset($uploaded_file_ids['error'])) {
                        echo $uploaded_file_ids['error'];
                        exit();
                    }
                    // upload and save to database
                    $data['picture'] =  implode(',', $uploaded_file_ids);

                    }

               
                }
                // print_r($data);die();

			$result = $this->users_model->update_user($id, $data);
			if (!empty($result)) {
				$data_session = (array)$this->session->userdata('logged_in');
				$data_session = array_merge($data_session, $data);
				$data_session = (object) $data_session;
				$this->session->set_userdata('logged_in', $data_session);
				$this->session->set_flashdata('msg', 'Profile updated successfully.');
				echo "success";
			}
		}
	}

	public function update_password() {
		$data = array();
		$id = $this->loginUser->id;
		$data['small_title'] = 'Change Password';
		$data['formaction_path'] = 'save_password';
		$data['all_users'] = $this->users_model->get_user_by_id($id);
		$this->template->load_admin('profiles/change_password', $data);
	}

	public function save_password() {
		$this->load->library('form_validation');
		$id = $this->loginUser->id;
		$password = hash( "sha256", $password);
		$old_password = hash("sha256",$this->input->post('old_password'));
		$new_password = hash("sha256",$this->input->post('new_password'));
		$conf_password = hash("sha256",$this->input->post('cong_password'));
		if ($this->input->post()) {
			$rules = array(
				// array(
				// 	'field' => 'new_password',
				// 	'label' => 'New Password',
				// 	'rules' => 'trim|min_length[5]',
				// ),
				array(
					'field' => 'conf_password',
					'label' => 'Confirm Password',
					'rules' => 'trim|min_length[5]',
				),

			);
		}
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() == FALSE) {
			echo validation_errors();
		} else {
			$data['updated_on'] = date('y-m-d H:m:s');
			if (($this->input->post('old_password') != null) && ($this->input->post('new_password') != null)) {
				$data['password'] = hash("sha256" ,$this->input->post('conf_password'));

				$check_user = $this->db->get_where('users', ['id' => $id,'password' => $old_password]);
				if ($check_user->num_rows() <= 0) {
					echo "Old password is incorrect.";
					exit();
				}
			}
			$result = $this->users_model->update_user( $id, $data);
			if (!empty($result)) {
				$this->session->set_flashdata('msg', 'Password updated successfully.');
				echo "success";
			}
		}
	}
}

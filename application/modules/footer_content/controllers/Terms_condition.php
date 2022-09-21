<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Terms_condition extends Loggedin_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('Terms_condition_model', 'terms_condition_model');
		$this->load->model('user/Users_model', 'users_model');
		$this->load->model('files/Files_model','files_model');
	}
	public function index() {
		$data = array();
		$id = $this->loginUser->id;
		$data['small_title'] = 'Terms & Constion';
		$data['current_page_terms_condition'] = 'current-page';
		$data['terms_condition_info'] = $this->terms_condition_model->get();   
		$this->template->load_admin('footer_content/terms_condition/list', $data);
	}

    public function add()
    {
        $data = array();
        $dynaic_information = array();
        $data['small_title'] = 'Add Terms & Condition';
        $data['current_page_terms_condition'] = 'current-page';

        if ($this->input->post()) 
        {
        $posted_data = $this->input->post();
        $this->load->library('form_validation');
            $rules = array(
                array(
                    'field' => 'title_english',
                    'label' => 'Title English',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'title_arabic',
                    'label' => 'Title Arabic',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'description_english',
                    'label' => 'Description English',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'description_arabic',
                    'label' => 'Description Arabic',
                    'rules' => 'trim|required')
             

            );
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
 				$posted_data['created_by'] = $this->loginUser->id;
 				$posted_data['created_on'] = date('Y-m-d h:i:s');
                
                $result = $this->terms_condition_model->insert($posted_data);

		    
                if (!empty($result))
                {
                  $this->session->set_flashdata('success', $this->lang->line('terms_condition_add_success'));
                  $msg = 'success';
                  echo json_encode(array('msg' => $msg, 'in_id' => $result));
                }
                else
                {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                }
            }
        }
        else
        {

            $this->template->load_admin('footer_content/terms_condition/form', $data);
        }

    }
	
    public function edit()
    {
        $data = array();
        $dynaic_information = array();
        $data['small_title'] = 'Edit Terms & Condition';
        $data['current_page_terms_condition'] = 'current-page';
        $data['formaction_path'] = 'edit';
        $terms_condition_id = $this->uri->segment(4);
         $data['terms_condition_info'] = $this->terms_condition_model->get($terms_condition_id);

        if ($this->input->post()) 
        {
        $posted_data = $this->input->post();
        $this->load->library('form_validation');
            $rules = array(
            array(
                    'field' => 'title_english',
                    'label' => 'Title English',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'title_arabic',
                    'label' => 'Title Arabic',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'description_english',
                    'label' => 'Description English',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'description_arabic',
                    'label' => 'Description Arabic',
                    'rules' => 'trim|required')

            );

             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
               
 				$id = $this->input->post('id');
                $result = $this->terms_condition_model->update($id,$posted_data);
                if (!empty($result))
                {

                  $this->session->set_flashdata('success', $this->lang->line('terms_condition_edit_success'));
                  $msg = 'success';
                  echo json_encode(array('msg' => $msg, 'in_id' => $result));
                  exit();
                }
                else
                {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                }
            }
        }
        else
        {
            $this->template->load_admin('footer_content/terms_condition/form', $data);
        }

    }

    //Delete Single Row
    public function delete( $id = NULL )
    {
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        $res = false;

        if ($table == "terms_condition") {
                      
           $res = $this->terms_condition_model->delete_terms_condition_row($id); 
        }
        //do not change below code
        $msg_success = $this->lang->line("Question_delete_success");
        $msg_error = $this->lang->line("media_delete_failed");
        if ($res) {
            echo $return = '{"type":"success","msg":"'.$msg_success.'"}';
        } else {
            echo $return = '{"type":"error","msg":"'.$msg_error.'"}';
        }
    }
    
 
}

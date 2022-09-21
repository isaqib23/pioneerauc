<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Question_answer extends Loggedin_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('Question_answer_model', 'question_answer_model');
		$this->load->model('user/Users_model', 'users_model');
		$this->load->model('files/Files_model','files_model');
	}
	public function index() {
		$data = array();
		$id = $this->loginUser->id;
		$data['small_title'] = 'Question & Answer';
		$data['current_page_ques_ans'] = 'current-page';
		$data['ques_ans_info'] = $this->question_answer_model->get();   

		$this->template->load_admin('footer_content/question_answer/list', $data);
	}

	// Add a new Media
    public function add()
    {
        $data = array();
        $dynaic_information = array();
        $data['small_title'] = 'Add FAQs';
        $data['current_page_media'] = 'current-page';

        if ($this->input->post()) 
        {
        $posted_data = $this->input->post();
        $this->load->library('form_validation');
            $rules = array(
                array(
                    'field' => 'question_english',
                    'label' => 'Title Question',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'question_arabic',
                    'label' => 'Title Arabic',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'answer_english',
                    'label' => 'Short Answer English',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'answer_arabic',
                    'label' => 'Answer Arabic',
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
                
                $result = $this->question_answer_model->insert($posted_data);

		    
                if (!empty($result))
                {
                  $this->session->set_flashdata('success', $this->lang->line('question_add_success'));
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

            $this->template->load_admin('footer_content/question_answer/form', $data);
        }

    }
	
    public function edit()
    {
        $data = array();
        $dynaic_information = array();
        $data['small_title'] = 'Edit Quesiton Answer';
        $data['current_page_ques_ans'] = 'current-page';
        $data['formaction_path'] = 'edit';
        $ques_ans_id = $this->uri->segment(4);
         $data['ques_ans_info'] = $this->question_answer_model->get($ques_ans_id);

        if ($this->input->post()) 
        {
        $posted_data = $this->input->post();
        $this->load->library('form_validation');
            $rules = array(
                 array(
                    'field' => 'question_english',
                    'label' => 'Title Question',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'question_arabic',
                    'label' => 'Title Arabic',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'answer_english',
                    'label' => 'Short Answer English',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'answer_arabic',
                    'label' => 'Answer Arabic',
                    'rules' => 'trim|required')

            );

             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
               
 				$id = $this->input->post('id');
                $result = $this->question_answer_model->update($id,$posted_data);
                if (!empty($result))
                {

                  $this->session->set_flashdata('success', $this->lang->line('question_answer_edit_success'));
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
            $this->template->load_admin('footer_content/question_answer/form', $data);
        }

    }

    //Delete Single Row
    public function delete( $id = NULL )
    {
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        $res = false;

        if ($table == "ques_ans") {
                      
           $res = $this->question_answer_model->delete_question_row($id); 
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

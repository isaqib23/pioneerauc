<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms extends Loggedin_Controller {
	
	function __construct() {
		parent::__construct();		 
		$this->load->model('Cms_model','cms_model');
         $this->load->model('files/Files_model','files_model');
		$this->load->library('TestUnifonic');
        $this->load->helper('general_helper');		
	}
	
    public function index()
    {
        $data =array();   
        $data['small_title'] = 'CMS';
        $data['current_page_terms'] = 'current-page';
        $this->template->load_admin('cms/index', $data);
    }
    //terms and condition function
    public function terms()
	{
		$data =array();   
        $data['small_title'] = 'Terms & Conditions';
        $data['formaction_path'] = 'terms';
        $data['current_page_terms'] = 'current-page';
        $data_row = $this->db->get('terms_condition')->row_array();
        $data['status_btn'] = (isset($data_row) && !empty($data_row)) ? 'Update' : 'Save';
        $data['terms_info'] = $data_row;
        if ($this->input->post()) {
        $this->load->library('form_validation');
        $posted_data = $this->input->post();
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
                    'rules' => 'trim|required'),  
            );
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
            }else{
                $posted_data['created_on'] = date('Y-m-d h:i:s');
                $posted_data['created_by'] = $this->loginUser->id;

                $title = [
                'english' => $posted_data['title_english'], 
                'arabic' => $posted_data['title_arabic'], 
                ];
                unset($posted_data['title_english']); 
                unset($posted_data['title_arabic']); 
                $posted_data['title'] = json_encode($title);

                $description = [
                'english' => $posted_data['description_english'],
                'arabic' => $posted_data['description_arabic'],
                ];
                unset($posted_data['description_english']);
                unset($posted_data['description_arabic']);
                $posted_data['description'] = json_encode($description);

                if(isset($data_row) && !empty($data_row)){
                    $result = $this->db->update('terms_condition', $posted_data, ['id'=> 1]);
                }else{
                    $result = $this->db->insert('terms_condition', $posted_data);
                }
                if($result){
                    echo json_encode(array('error' => false, 'msg' => 'Terms & Conditions has been updated successfully!'));
                }else{
                    echo json_encode(array('error' => false, 'msg' => 'Terms & Conditions has been failed to update.'));
                }
            } 
        }else{
		  $this->template->load_admin('cms/terms/terms', $data);
        }
    }

    public function policy()
    {
        $data =array();   
        $data['small_title'] = 'Privacy policies';
        $data['formaction_path'] = 'policy';
        $data['current_page_policy'] = 'current-page';
        $data_row = $this->db->get('privacy_policy')->row_array();
        $data['status_btn'] = (isset($data_row) && !empty($data_row)) ? 'Update' : 'Save';
        $data['terms_info'] = $data_row;
        if ($this->input->post()) {
        $this->load->library('form_validation');
        $posted_data = $this->input->post();
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
                    'rules' => 'trim|required'),
            );
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
            }else{
                $posted_data['created_on'] = date('Y-m-d h:i:s');
                $posted_data['created_by'] = $this->loginUser->id;

                $title = [
                'english' => $posted_data['title_english'], 
                'arabic' => $posted_data['title_arabic'], 
                ];
                unset($posted_data['title_english']); 
                unset($posted_data['title_arabic']); 
                $posted_data['title'] = json_encode($title);

                $description = [
                'english' => $posted_data['description_english'],
                'arabic' => $posted_data['description_arabic'],
                ];
                unset($posted_data['description_english']);
                unset($posted_data['description_arabic']);
                $posted_data['description'] = json_encode($description);

                if(isset($data_row) && !empty($data_row)){
                    $result = $this->db->update('privacy_policy', $posted_data, ['id'=> 1]);
                }else{
                    $result = $this->db->insert('privacy_policy', $posted_data);
                }
                if($result){
                    echo json_encode(array('error' => false, 'msg' => 'Privacy Policies has been updated successfully!'));
                }else{
                    echo json_encode(array('error' => false, 'msg' => 'Privacy Policies has been failed to update.'));
                }
            } 
        }else{
          $this->template->load_admin('cms/terms/policy', $data);
        }
    }
    //faqs functions
    public function faqs_listing()
    {
        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Question & Answer';
        $data['current_page_ques_ans'] = 'current-page';
        $data['formaction_path'] = 'add_faqs';
        $data['ques_ans_info'] = $this->cms_model->get_faqs_list();
        // print_r($data['ques_ans_info']);die('aaaaaa');   
        $this->template->load_admin('cms/qa/list', $data);
    }
     public function add_faqs()
    {
        $data = array();
        $dynaic_information = array();
        $data['small_title'] = 'Add FAQs';
        $data['current_page_media'] = 'current-page';
        $data['current_page_ques_ans'] = 'current-page';
        $data['formaction_path'] = "add_faqs";
         if ($this->input->post()) {
        $this->load->library('form_validation');
        $posted_data = $this->input->post();
            $rules = array(
               array(
                    'field' => 'question_english',
                    'label' => 'Question English',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'question_arabic',
                    'label' => 'Question Arabic',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'answer_english',
                    'label' => 'Answer English',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'answer_arabic',
                    'label' => 'Answer Arabic',
                    'rules' => 'trim|required'),
            );
           
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
            }else{
                $posted_data['created_on'] = date('Y-m-d h:i:s');
                $posted_data['created_by'] = $this->loginUser->id;

                $question = [
                'english' => $posted_data['question_english'], 
                'arabic' => $posted_data['question_arabic'], 
                ];
                unset($posted_data['question_english']); 
                unset($posted_data['question_arabic']); 
                $posted_data['question'] = json_encode($question);

                $answer = [
                'english' => $posted_data['answer_english'],
                'arabic' => $posted_data['answer_arabic'],
                ];
                unset($posted_data['answer_english']);
                unset($posted_data['answer_arabic']);
                $posted_data['answer'] = json_encode($answer);
                $result = $this->db->insert('ques_ans', $posted_data);
                if($result){
                $this->session->set_flashdata('success', 'FAQs Added Successfully');
                    $msg = "success";
                    echo json_encode(array('msg'=> $msg , 'mid' => 'FAQs has been insert update'));
                }
            } 
        }
        else
        {
            $this->template->load_admin('qa/form', $data);
        }

    }
    public function edit_faqs()
    {
        $data = array();
        $faq_id = $this->uri->segment(3);
        $data['small_title'] = 'Edit Question & Answer';
        $data['current_page_ques_ans'] = 'current-page';
        $data['formaction_path'] = 'update_faqs';
        $data['ques_ans_info'] = $this->cms_model->get_faqs_list($faq_id);
        // print_r($data['ques_ans_info']);die('aaaaaa');   
        $this->template->load_admin('cms/qa/form', $data);
    }
    public function  update_faqs()
    {

        if ($this->input->post()) {
        $this->load->library('form_validation');
        $posted_data = $this->input->post();
            $rules = array(
               array(
                    'field' => 'question_english',
                    'label' => 'Question English',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'question_arabic',
                    'label' => 'Question Arabic',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'answer_english',
                    'label' => 'Answer English',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'answer_arabic',
                    'label' => 'Answer Arabic',
                    'rules' => 'trim|required'),
            );

             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
            }else{
                $posted_data['updated_on'] = date('Y-m-d h:i:s');

                $question = [
                'english' => $posted_data['question_english'], 
                'arabic' => $posted_data['question_arabic'], 
                ];
                unset($posted_data['question_english']); 
                unset($posted_data['question_arabic']); 
                $posted_data['question'] = json_encode($question);

                $answer = [
                'english' => $posted_data['answer_english'],
                'arabic' => $posted_data['answer_arabic'],
                ];
                unset($posted_data['answer_english']);
                unset($posted_data['answer_arabic']);
                $posted_data['answer'] = json_encode($answer);
                $faq_id = $posted_data['id'];
                unset($posted_data['id']);
                $result = $this->db->update('ques_ans', $posted_data , ['id'=>$faq_id] );
                if($result){
                $this->session->set_flashdata('success', 'FAQs Updated Successfully');
                    $msg = "success";
                    echo json_encode(array('msg'=> $msg , 'mid' => 'FAQs has been Update update'));
                }
            } 
        }
    }
    public function delete( $id = NULL )
    {
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        $res = false;

        if ($table)
        {
           $res = $this->cms_model->delete_row($id,$table); 
        }
        //do not change below code
        $msg_success = $this->lang->line("delete_success");
        $msg_error = "Failed to delete.";
        if ($res) {
            echo $return = '{"type":"success","msg":"'.$msg_success.'"}';
        } else {
            echo $return = '{"type":"error","msg":"'.$msg_error.'"}';
        }

    }
    //contact us function
    public function contact_us()
    {
        $data =array();   
        $data['small_title'] = 'Contact Us';
        $data['formaction_path'] = 'contact_us';
        $data['current_page_contact'] = 'current-page';
        $data_row = $this->db->get('contact_us')->row_array();
        $data['status_btn'] = (isset($data_row) && !empty($data_row)) ? 'Update' : 'Save';
        $data['contact_us_info'] = $data_row;

         if ($this->input->post()) {
        $this->load->library('form_validation');
        $posted_data = $this->input->post();
            $rules = array(
               array(
                    'field' => 'address',
                    'label' => 'Address',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'website',
                    'label' => 'Website',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'address_arabic',
                    'label' => 'Arabic Address',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'fax',
                    'label' => 'fax',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'toll_free',
                    'label' => 'toll free',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'email',
                    'label' => 'email',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'mobile',
                    'label' => 'Mobile',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'phone',
                    'label' => 'Phone',
                    'rules' => 'trim|required'),
                 array(
                    'field' => 'business_hr',
                    'label' => 'Business Hours',
                    'rules' => 'trim|required'),
                  array(
                    'field' => 'working_hr',
                    'label' => 'Working Hours',
                    'rules' => 'trim|required')
            );
            $address = [
                    'english' => $posted_data['address'], 
                    'arabic' => $posted_data['address_arabic'], 
                ];
                unset($posted_data['address']); 
                unset($posted_data['address_arabic']); 
                $posted_data['address'] = json_encode($address);
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));

            }else{

                 if(isset($data_row) && !empty($data_row)){
                    $posted_data['updated_on'] = date('Y-m-d h:i:s');
                }else{
                    $posted_data['created_on'] = date('Y-m-d h:i:s');
                }

                $posted_data['mobile'] = "+".$this->input->post('mobile_code').$this->input->post('mobile');
                unset($posted_data['mobile_code']);
                if(isset($data_row) && !empty($data_row)){
                    $result = $this->db->update('contact_us', $posted_data, ['id'=> 1]);
                    echo json_encode(array('error' => false, 'msg' => 'Contact fields has been updated successfully!'));
                    exit();

                }else{
                    $result_ = $this->db->insert('contact_us', $posted_data);
                    echo json_encode(array('error' => false, 'msg' => 'Contact fields has been Insert successfully.'));
                    exit();
                }
            } 
        }else{
        $this->template->load_admin('cms/contact_us/contact_us', $data);
        }

    }
 
     public function our_team()
    {
        $data = array();
        $id = $this->loginUser->id;

        $data['small_title'] = 'Our Team';
        $data['current_page_our_team'] = 'current-page';
        $data['formaction_path'] = 'add_our_team';
        $data['our_team_info'] = $this->cms_model->get_team_list();
        $this->template->load_admin('team/team_list', $data);
    }
    
    public function add_our_team()
    {
        $data = array();
        $dynaic_information = array();
        $data['small_title'] = 'Add Team Member';
        $data['current_page_our_team'] = 'current-page';
        $data_row = $this->db->get('our_team')->row_array();
        $data['status_btn'] = (isset($data_row) && !empty($data_row)) ? 'Update' : 'Save';
        $data['row'] = $data_row;
        $data['formaction_path'] = "add_our_team";
         if ($this->input->post()) {
        $this->load->library('form_validation');
        $posted_data = $this->input->post();

            $rules = array(
               array(
                    'field' => 'name_english',
                    'label' => 'English name',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'name_arabic',
                    'label' => 'Arabic name',
                    'rules' => 'trim|required'),

            );
           
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
            }else{
                // unset($posted_data['image']);
        
                $posted_data['created_on'] = date('Y-m-d h:i:s');
                $posted_data['created_by'] = $this->loginUser->id;

                $question = [
                'english' => $posted_data['name_english'], 
                'arabic' => $posted_data['name_arabic'], 
                ];
                unset($posted_data['name_english']); 
                unset($posted_data['name_arabic']); 
                $posted_data['member_name'] = json_encode($question);

                $answer = [
                'english' => $posted_data['description_english'],
                'arabic' => $posted_data['description_arabic'],
                ];
                unset($posted_data['description_english']);
                unset($posted_data['description_arabic']);
                $posted_data['description'] = json_encode($answer);

                $designation = [
                'english' => $posted_data['english_designation'],
                'arabic' => $posted_data['arabic_designation'],
                ];
                unset($posted_data['english_designation']);
                unset($posted_data['arabic_designation']);
                $posted_data['designation'] = json_encode($designation);

                if(isset($data_row) && !empty($data_row)){

                }else{
                // $posted_data['created_on'] = date('Y-m-d h:i:s');
                }
              
                if( ! empty($_FILES['image']['name']) ){
                    // make path

                    $path = './uploads/our_team_image/';
                    if ( ! is_dir($path)) {
                        mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $sizes = [
                        ['width' => 660, 'height' => 453]
                    ];
                    
                    if (! empty($_FILES['image']['name'])) {
                    $uploaded_file_ids = $this->files_model->upload('image', $config, $sizes);
                    if (isset($uploaded_file_ids['error'])) {
                        echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                        exit();
                    }
                    // upload and save to database
                    $posted_data['image'] = implode(',', $uploaded_file_ids);
                    }
               
                }
                    $result = $this->db->insert('our_team', $posted_data);

                if($result){
                    $this->session->set_flashdata('success', 'Team fields has been Inserted successfully.');
                    echo json_encode(array('error' => false, 'msg' => 'Team fields has been Inserted successfully.'));
                } else {
                    $this->session->set_flashdata('error', 'Request Failed.');
                    echo json_encode(array('error' => false, 'msg' => 'Request Failed.'));
                }
            } 
        }
        else
        {
            $this->template->load_admin('team/team_form', $data);
        }

    }

    public function edit_our_team()
    {
        $data = array();
        $team_id = $this->uri->segment(3);
        $data['small_title'] = 'Edit our team';
        $data['current_page_our_team'] = 'current-page';
        $data['formaction_path'] = 'update_team';
        $data['our_team_info'] = $this->cms_model->get_ourteam_list($team_id);
        $this->template->load_admin('team/team_form', $data);
    }

    public function  update_team()
    {

        if ($this->input->post()) {
            $this->load->library('form_validation');
            $data_row = $this->db->get('our_team')->row_array();
            $data['status_btn'] = (isset($data_row) && !empty($data_row)) ? 'Update' : 'Save';
            $data['row'] = $data_row;
            $posted_data = $this->input->post();
            $rules = array(
              array(
                    'field' => 'name_english',
                    'label' => 'English name',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'name_arabic',
                    'label' => 'Arabic name',
                    'rules' => 'trim|required'),
   
            );

             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
            }else{
                $posted_data['updated_on'] = date('Y-m-d h:i:s');
                // $posted_data['updated_by'] = $this->loginUser->id;

                $question = [
                'english' => $posted_data['name_english'], 
                'arabic' => $posted_data['name_arabic'], 
                ];
                unset($posted_data['name_english']); 
                unset($posted_data['name_arabic']); 
                $posted_data['member_name'] = json_encode($question);

                $answer = [
                'english' => $posted_data['description_english'],
                'arabic' => $posted_data['description_arabic'],
                ];
                unset($posted_data['description_english']);
                unset($posted_data['description_arabic']);
                $posted_data['description'] = json_encode($answer);

                $designation = [
                'english' => $posted_data['english_designation'],
                'arabic' => $posted_data['arabic_designation'],
                ];
                unset($posted_data['english_designation']);
                unset($posted_data['arabic_designation']);
                $posted_data['designation'] = json_encode($designation);
                if(isset($data_row) && !empty($data_row)){
       
                }else{
                }
       
                if( ! empty($_FILES['image']['name'])){
                    // make path
                    $path = './uploads/our_team_image/';
                    if ( ! is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $sizes = [
                    ['width' => 660, 'height' => 453]
                    ];
                 
                    if (! empty($_FILES['image']['name']) && !empty($posted_data['c_old_file'])) {
                    $this->files_model->delete_by_name($posted_data['c_old_file'], $path, $sizes);
                    }
                   
                    if (! empty($_FILES['image']['name'])) {
                    $uploaded_file_ids = $this->files_model->upload('image', $config, $sizes);
                    if (isset($uploaded_file_ids['error'])) {
                    echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                    exit();
                    }
                    // upload and save to database
                    $posted_data['image'] = implode(',', $uploaded_file_ids);
                    }
               
                }
                unset($posted_data['c_old_file']);
                unset($posted_data['p_old_file']);
                if(isset($data_row) && !empty($data_row)){
                    $id = $posted_data['id'];
                    unset($posted_data['id']);
                    // $result = $this->cms_model->update_terms($privacypolicy_data);
                    // $this->session->set_flashdata('success', 'About Us fields has been updated successfully!');

                    $this->session->set_flashdata('success', 'Team fields has been updated successfully!');
                    $result = $this->db->update('our_team', $posted_data, ['id'=> $id]);
                    echo json_encode(array('error' => false, 'msg' => 'Team fields has been updated successfully!'));
                    exit();

                }else{
                    $this->session->set_flashdata('success', 'Team fields has been Inserted successfully.');
                    $result_ = $this->db->insert('our_team', $posted_data);
                    echo json_encode(array('error' => false, 'msg' => 'Team fields has been Inserted successfully.'));
                    exit();
                }
            }
        }else{
            $this->template->load_admin('cms/Team/team_form', $data);
        }

    }
    public function delete_team( $id = NULL )
    {
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        $res = false;

        if ($table)
        {
           $res = $this->cms_model->delete_row_team($id,$table); 
        }
        //do not change below code
        $msg_success = $this->lang->line("delete_success");
        $msg_error = $this->lang->line("media_delete_failed");
        if ($res) {
            echo $return = '{"type":"success","msg":"'.$msg_success.'"}';
        } else {
            echo $return = '{"type":"error","msg":"'.$msg_error.'"}';
        }

    } 

    //////////////// slider images ///////////////
    public function slider()
    {
        $data = array();
        $id = $this->loginUser->id;

        $data['small_title'] = 'Home Slider';
        $data['current_page_slider'] = 'current-page';
        $data['formaction_path'] = 'add_slider';
        $data['slider_info'] = $this->cms_model->slider_info();
        // print_r($data['our_team_info']);die('aaaaaa');   
        $this->template->load_admin('slider/slider_list', $data);
    }
    
    public function add_slider()
    {
        $data = array();
        $dynaic_information = array();
        $data['small_title'] = 'Add Slider';
        $data['current_page_slider'] = 'current-page';
        $data_row = $this->db->get('home_slider')->row_array();
        $data['sliderCategory'] = $this->db->get('item_category')->result_array();
        $data['status_btn'] = (isset($data_row) && !empty($data_row)) ? 'Update' : 'Save';
        $data['row'] = $data_row;
        $data['formaction_path'] = "add_slider";
         if ($this->input->post()) {
        $this->load->library('form_validation');
        $posted_data = $this->input->post();

            $rules = array(
                array(
                    'field' => 'small_english_heading',
                    'label' => 'Small English Heading',
                    'rules' => 'trim'),
                array(
                    'field' => 'small_arabic_title',
                    'label' => 'Small Arabic Heading',
                    'rules' => 'trim'),
                array(
                    'field' => 'english_title',
                    'label' => 'English Title',
                    'rules' => 'trim'),
                array(
                    'field' => 'arabic_title',
                    'label' => 'Arabis Title',
                    'rules' => 'trim'),
                array(
                    'field' => 'english_description',
                    'label' => 'English Decsription',
                    'rules' => 'trim'),
                array(
                    'field' => 'mobile_english_description',
                    'label' => 'Mobile English Decsription',
                    'rules' => 'trim'),
                array(
                    'field' => 'category_id',
                    'label' => 'Slider Category',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'arabic_description',
                    'label' => 'Arabic Decsription',
                    'rules' => 'trim'),
                array(
                    'field' => 'mobile_arabic_description',
                    'label' => 'Mobile Arabic Decsription',
                    'rules' => 'trim'),
            );
            //'rules' => 'trim|required'),
           
             $this->form_validation->set_rules($rules);
             if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
            }else{
                // unset($posted_data['image']);
            
                $posted_data['created_on'] = date('Y-m-d h:i:s');
                $posted_data['category_id'] = $this->input->post('category_id');
                $posted_data['created_by'] = $this->loginUser->id;

                $small_title = [
                'english' => $posted_data['small_english_heading'], 
                'arabic' => $posted_data['small_arabic_title'], 
                ];
                unset($posted_data['small_english_heading']); 
                unset($posted_data['small_arabic_title']); 
                $posted_data['small_title'] = json_encode($small_title);

                $title = [
                'english' => $posted_data['english_title'], 
                'arabic' => $posted_data['arabic_title'], 
                ];
                unset($posted_data['english_title']); 
                unset($posted_data['arabic_title']); 
                $posted_data['title'] = json_encode($title);

                $description = [
                'english' => $posted_data['english_description'], 
                'arabic' => $posted_data['arabic_description'], 
                ];
                unset($posted_data['english_description']); 
                unset($posted_data['arabic_description']); 
                $posted_data['description'] = json_encode($description);


                $mobile_description = [
                'english' => $posted_data['mobile_english_description'], 
                'arabic' => $posted_data['mobile_arabic_description'], 
                ];
                unset($posted_data['mobile_english_description']); 
                unset($posted_data['mobile_arabic_description']); 
                $posted_data['mobile_description'] = json_encode($mobile_description);


                if(isset($data_row) && !empty($data_row)){

                }else{
                }
              
                if( ! empty($_FILES['image']['name']) ){
                    // make path

                    $path = './uploads/slider_image/';
                    if ( ! is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $sizes = [
                        ['width' => 660, 'height' => 453]
                    ];
             
                    if (! empty($_FILES['image']['name'])) {
                    $uploaded_file_ids = $this->files_model->upload('image', $config, $sizes);
                    if (isset($uploaded_file_ids['error'])) {
                        echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                        exit();
                    }
                    // upload and save to database
                    $posted_data['image'] = implode(',', $uploaded_file_ids);
                    }
               
                }

                if( ! empty($_FILES['image_arabic']['name']) ){
                    // make path

                    $path = './uploads/slider_image/';
                    if ( ! is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $sizes = [
                        ['width' => 660, 'height' => 453]
                    ];
             
                    if (! empty($_FILES['image_arabic']['name'])) {
                    $uploaded_file_ids = $this->files_model->upload('image_arabic', $config, $sizes);
                    if (isset($uploaded_file_ids['error'])) {
                        echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                        exit();
                    }
                    // upload and save to database
                    $posted_data['image_arabic'] = implode(',', $uploaded_file_ids);
                    }
               
                }

                // mobile banners
                if( ! empty($_FILES['mobile_image_english']['name']) ){
                    // make path

                    $path = './uploads/slider_image/';
                    if ( ! is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $sizes = [
                        ['width' => 660, 'height' => 453]
                    ];
             
                    if (! empty($_FILES['mobile_image_english']['name'])) {
                    $uploaded_file_ids = $this->files_model->upload('mobile_image_english', $config, $sizes);
                    if (isset($uploaded_file_ids['error'])) {
                        echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                        exit();
                    }
                    // upload and save to database
                    $posted_data['mobile_image_english'] = implode(',', $uploaded_file_ids);
                    }
               
                }
                if( ! empty($_FILES['mobile_image_arabic']['name']) ){
                    // make path

                    $path = './uploads/slider_image/';
                    if ( ! is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $sizes = [
                        ['width' => 660, 'height' => 453]
                    ];
             
                    if (! empty($_FILES['mobile_image_arabic']['name'])) {
                    $uploaded_file_ids = $this->files_model->upload('mobile_image_arabic', $config, $sizes);
                    if (isset($uploaded_file_ids['error'])) {
                        echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                        exit();
                    }
                    // upload and save to database
                    $posted_data['mobile_image_arabic'] = implode(',', $uploaded_file_ids);
                    }
               
                }


                $result = $this->db->insert('home_slider', $posted_data);
                // print_r($result);die();
                if($result){
                $this->session->set_flashdata('success', 'Slider Added Successfully');
                    $msg = "Slider Added Successfully.";

                    echo json_encode(array('msg'=> $msg , 'mid' => 'Slider has been insert Successfully'));
                        // redirect('cms/slider');
                }
            } 
        }
        else
        {
            $this->template->load_admin('slider/slider_form', $data);
        }
    }

    public function edit_slider()
    {
        $data = array();
        $team_id = $this->uri->segment(3);
        $data['small_title'] = 'Edit slider';
        $data['current_page_slider'] = 'current-page';
        $data['formaction_path'] = 'update_slider';
        $data['slider_info'] = $this->cms_model->get_slider_list($team_id);
        $data['sliderCategory'] = $this->db->get('item_category')->result_array();
        // print_r($data['slider_info']);die();
        $this->template->load_admin('slider/slider_form', $data);
    }

    public function  update_slider()
    {

        if ($this->input->post()) {
            $this->load->library('form_validation');
            $data_row = $this->db->get('home_slider')->row_array();
            // print_r($data_row);die();
            $data['status_btn'] = (isset($data_row) && !empty($data_row)) ? 'Update' : 'Save';
            $data['row'] = $data_row;
            $posted_data = $this->input->post();

            $rules = array(
                array(
                    'field' => 'small_english_heading',
                    'label' => 'Small English Heading',
                    'rules' => 'trim'),
                array(
                    'field' => 'small_arabic_title',
                    'label' => 'Small Arabic Heading',
                    'rules' => 'trim'),
                array(
                    'field' => 'english_title',
                    'label' => 'English Title',
                    'rules' => 'trim'),
                array(
                    'field' => 'arabic_title',
                    'label' => 'Arabis Title',
                    'rules' => 'trim'),
                array(
                    'field' => 'category_id',
                    'label' => 'Slider Category',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'english_description',
                    'label' => 'English Decsription',
                    'rules' => 'trim'),
                array(
                    'field' => 'arabic_description',
                    'label' => 'Arabic Decsription',
                    'rules' => 'trim'),
                array(
                    'field' => 'mobile_english_description',
                    'label' => 'Mobile English Decsription',
                    'rules' => 'trim'),
                array(
                    'field' => 'mobile_arabic_description',
                    'label' => 'Mobile Arabic Decsription',
                    'rules' => 'trim'),
            );
           
            $this->form_validation->set_rules($rules);
                // print_r($posted_data);die();
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
            }else{
                $posted_data['category_id'] = $this->input->post('category_id');
                // unset($posted_data['image']);
        
                // $posted_data['created_on'] = date('Y-m-d h:i:s');
                // $posted_data['created_by'] = $this->loginUser->id;

                $small_title = [
                'english' => $posted_data['small_english_heading'], 
                'arabic' => $posted_data['small_arabic_title'], 
                ];
                unset($posted_data['small_english_heading']); 
                unset($posted_data['small_arabic_title']); 
                $posted_data['small_title'] = json_encode($small_title);

                $title = [
                'english' => $posted_data['english_title'], 
                'arabic' => $posted_data['arabic_title'], 
                ];
                unset($posted_data['english_title']); 
                unset($posted_data['arabic_title']); 
                $posted_data['title'] = json_encode($title);

                $description = [
                'english' => $posted_data['english_description'], 
                'arabic' => $posted_data['arabic_description'], 
                ];
                unset($posted_data['english_description']); 
                unset($posted_data['arabic_description']); 
                $posted_data['description'] = json_encode($description);



                $mobile_description = [
                'english' => $posted_data['mobile_english_description'], 
                'arabic' => $posted_data['mobile_arabic_description'], 
                ];
                unset($posted_data['mobile_english_description']); 
                unset($posted_data['mobile_arabic_description']); 
                $posted_data['mobile_description'] = json_encode($mobile_description);




                if(isset($data_row) && !empty($data_row)){

                }else{
                }
       
                if( ! empty($_FILES['image']['name'])){
                // make path
                    $path = './uploads/slider_image/';
                    if ( ! is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $sizes = [
                    ['width' => 660, 'height' => 453]
                    ];
                 
                    if (! empty($_FILES['image']['name']) && !empty($posted_data['p_old_file'])) {
                    $this->files_model->delete_by_name($posted_data['p_old_file'], $path, $sizes);
                    }
                   
                    if (! empty($_FILES['image']['name'])) {
                    $uploaded_file_ids = $this->files_model->upload('image', $config, $sizes);
                    if (isset($uploaded_file_ids['error'])) {
                    echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                    exit();
                    }
                    // upload and save to database
                    $posted_data['image'] = implode(',', $uploaded_file_ids);
                    }

                }
                unset($posted_data['p_old_file']);

                if( ! empty($_FILES['image_arabic']['name'])){
                // make path
                    $path = './uploads/slider_image/';
                    if ( ! is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $sizes = [
                    ['width' => 660, 'height' => 453]
                    ];
                 
                    if (! empty($_FILES['image_arabic']['name']) && !empty($posted_data['p_old_file'])) {
                    $this->files_model->delete_by_name($posted_data['p_old_file'], $path, $sizes);
                    }
                   
                    if (! empty($_FILES['image_arabic']['name'])) {
                    $uploaded_file_ids = $this->files_model->upload('image_arabic', $config, $sizes);
                    if (isset($uploaded_file_ids['error'])) {
                    echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                    exit();
                    }
                    // upload and save to database
                    $posted_data['image_arabic'] = implode(',', $uploaded_file_ids);
                    }

                }
                 unset($posted_data['p_old_file_arabic']);


                 // mobile banners
                 if( ! empty($_FILES['mobile_image_english']['name'])){
                // make path
                    $path = './uploads/slider_image/';
                    if ( ! is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $sizes = [
                    ['width' => 660, 'height' => 453]
                    ];
                 
                    if (! empty($_FILES['mobile_image_english']['name']) && !empty($posted_data['p_old_file'])) {
                    $this->files_model->delete_by_name($posted_data['p_old_file'], $path, $sizes);
                    }
                   
                    if (! empty($_FILES['mobile_image_english']['name'])) {
                    $uploaded_file_ids = $this->files_model->upload('mobile_image_english', $config, $sizes);
                    if (isset($uploaded_file_ids['error'])) {
                    echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                    exit();
                    }
                    // upload and save to database
                    $posted_data['mobile_image_english'] = implode(',', $uploaded_file_ids);
                    }

                }
                 unset($posted_data['p_old_file_mobile_image_english']);

                 if( ! empty($_FILES['mobile_image_arabic']['name'])){
                // make path
                    $path = './uploads/slider_image/';
                    if ( ! is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $sizes = [
                    ['width' => 660, 'height' => 453]
                    ];
                 
                    if (! empty($_FILES['mobile_image_arabic']['name']) && !empty($posted_data['p_old_file'])) {
                    $this->files_model->delete_by_name($posted_data['p_old_file'], $path, $sizes);
                    }
                   
                    if (! empty($_FILES['mobile_image_arabic']['name'])) {
                    $uploaded_file_ids = $this->files_model->upload('mobile_image_arabic', $config, $sizes);
                    if (isset($uploaded_file_ids['error'])) {
                    echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                    exit();
                    }
                    // upload and save to database
                    $posted_data['mobile_image_arabic'] = implode(',', $uploaded_file_ids);
                    }

                }
                 unset($posted_data['p_old_file_mobile_image_arabic']);
                 // end

                if(isset($data_row) && !empty($data_row)){
                $id = $posted_data['id'];
                unset($posted_data['id']);
                // $result = $this->cms_model->update_terms($privacypolicy_data);
                // $this->session->set_flashdata('success', 'About Us fields has been updated successfully!');

                $this->session->set_flashdata('success', 'Slider has been updated successfully!');
                $result = $this->db->update('home_slider', $posted_data, ['id'=> $id]);
                echo json_encode(array('error' => false, 'msg' => 'Slider has been updated successfully!'));
                // redirect('cms/slider');
                exit();

                }else{
                $this->session->set_flashdata('success', 'Slider has been Inserted successfully.');
                $result_ = $this->db->insert('home_slider', $posted_data);
                echo json_encode(array('error' => false, 'msg' => 'Slider has been Inserted successfully.'));
                exit();
                }
            }
        } else {
            $this->template->load_admin('cms/slider/slider_form', $data);
        }

    }


    public function delete_slider( $id = NULL )
    {
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        $res = false;

        if ($table)
        {
           $res = $this->cms_model->delete_row_team($id,$table); 
        }
        //do not change below code
        $msg_success = $this->lang->line("delete_success");
        $msg_error = $this->lang->line("media_delete_failed");
        if ($res) {
            echo $return = '{"type":"success","msg":"'.$msg_success.'"}';
        } else {
            echo $return = '{"type":"error","msg":"'.$msg_error.'"}';
        }

    }
    ///////////// End Slider images /////////////

    //////////////// home side banner ///////////////
    public function side_banner()
    {
        $data = array();
        $id = $this->loginUser->id;

        $data['small_title'] = 'Home Banner';
        $data['current_page_header_info'] = 'current-page';
        $data['formaction_path'] = 'add_side_banner';
        $data['banner_info'] = $this->cms_model->side_banner_info(); 
        $this->template->load_admin('side_banner/banner_list', $data);
    }
    
    public function add_side_banner()
    {
        $data = array();
        $dynaic_information = array();
        $data['small_title'] = 'Add Side Banner';
        $data['current_page_banner'] = 'current-page';
        $data['formaction_path'] = "add_side_banner";
         if ($this->input->post()) {
        $this->load->library('form_validation');
        $posted_data = $this->input->post();

            $rules = array(
                array(
                    'field' => 'english_title',
                    'label' => 'English Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'arabic_title',
                    'label' => 'Arabis Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'english_description',
                    'label' => 'English Decsription',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'arabic_description',
                    'label' => 'Arabic Decsription',
                    'rules' => 'trim|required'),
            );
           
             $this->form_validation->set_rules($rules);
             if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
            }else{
                // unset($posted_data['image']);
        
                $posted_data['created_on'] = date('Y-m-d h:i:s');
                $posted_data['created_by'] = $this->loginUser->id;

                $title = [
                    'english' => $posted_data['english_title'], 
                    'arabic' => $posted_data['arabic_title'], 
                ];
                unset($posted_data['english_title']); 
                unset($posted_data['arabic_title']); 
                $posted_data['title'] = json_encode($title);

                $description = [
                    'english' => $posted_data['english_description'], 
                    'arabic' => $posted_data['arabic_description'], 
                ];
                unset($posted_data['english_description']); 
                unset($posted_data['arabic_description']); 
                $posted_data['description'] = json_encode($description);


                if(isset($data_row) && !empty($data_row)){

                }else{
                }
              
                if( ! empty($_FILES['image']['name']) ){
                    // make path

                    $path = './uploads/banner_image/';
                    if ( ! is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $sizes = [
                        ['width' => 660, 'height' => 453]
                    ];
             
                    if (! empty($_FILES['image']['name'])) {
                    $uploaded_file_ids = $this->files_model->upload('image', $config, $sizes);
                    if (isset($uploaded_file_ids['error'])) {
                        echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                        exit();
                    }
                    // upload and save to database
                    $posted_data['image'] = implode(',', $uploaded_file_ids);
                    }
               
                }

                    $result = $this->db->insert('home_side_banner', $posted_data);
                    // print_r($result);die();
                if($result){
                $this->session->set_flashdata('success', 'Banner has been added successfully.');
                    $msg = "Banner has been added successfully";
                    echo json_encode(array('msg'=> $msg , 'mid' => 'Slider has been added successfully.'));
                    redirect(base_url('cms/side_banner'));
                }
            } 
        }
        else
        {
            $this->template->load_admin('side_banner/banner_form', $data);
        }

    }
   public function edit_side_banner()
    {
        $data = array();
        $banner_id = $this->uri->segment(3);
        $data['small_title'] = 'Edit Banner';
        $data['current_page_banner'] = 'current-page';
        $data['formaction_path'] = 'update_side_banner';
        $data['banner_info'] = $this->db->get_where('home_side_banner', ['id' => $banner_id])->row_array();
        // print_r($data['slider_info']);die();
        $this->template->load_admin('side_banner/banner_form', $data);
    }

    public function  update_side_banner()
    {

        if ($this->input->post()) {
            $this->load->library('form_validation');
            $posted_data = $this->input->post();

            $rules = array(
                array(
                    'field' => 'english_title',
                    'label' => 'English Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'arabic_title',
                    'label' => 'Arabis Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'english_description',
                    'label' => 'English Decsription',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'arabic_description',
                    'label' => 'Arabic Decsription',
                    'rules' => 'trim|required'),
            );
           
            $this->form_validation->set_rules($rules);
                // print_r($posted_data);die();
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
            }else{
                // unset($posted_data['image']);
        
                // $posted_data['created_on'] = date('Y-m-d h:i:s');
                $posted_data['updated_by'] = $this->loginUser->id;

                $title = [
                    'english' => $posted_data['english_title'], 
                    'arabic' => $posted_data['arabic_title'], 
                ];
                unset($posted_data['english_title']); 
                unset($posted_data['arabic_title']); 
                $posted_data['title'] = json_encode($title);

                $description = [
                    'english' => $posted_data['english_description'], 
                    'arabic' => $posted_data['arabic_description'], 
                ];
                unset($posted_data['english_description']); 
                unset($posted_data['arabic_description']); 
                $posted_data['description'] = json_encode($description);


                if(isset($data_row) && !empty($data_row)){

                }else{
                }
       
                if( ! empty($_FILES['image']['name'])){
                // make path
                    $path = './uploads/slider_image/';
                    if ( ! is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $sizes = [
                    ['width' => 660, 'height' => 453]
                    ];
                 
                    if (! empty($_FILES['image']['name']) && !empty($posted_data['p_old_file'])) {
                    $this->files_model->delete_by_name($posted_data['p_old_file'], $path, $sizes);
                    }
                   
                    if (! empty($_FILES['image']['name'])) {
                    $uploaded_file_ids = $this->files_model->upload('image', $config, $sizes);
                    if (isset($uploaded_file_ids['error'])) {
                    echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                    exit();
                    }
                    // upload and save to database
                    $posted_data['image'] = implode(',', $uploaded_file_ids);
                    }

                }
                unset($posted_data['p_old_file']);
                $id = $posted_data['id'];
                unset($posted_data['id']);

                $result = $this->db->update('home_side_banner', $posted_data, ['id'=> $id]);
                if(isset($result) && !empty($result)){

                    echo json_encode(array('error' => false, 'success' => 'Banner has been updated successfully!'));
                    $this->session->set_flashdata('success', 'Banner has been updated successfully!');

                    exit();

                }else{
                    $this->session->set_flashdata('success', 'Banner has been failed to update.');
                    echo json_encode(array('error' => true, 'msg' => 'Banner has been failed to update.'));
                    exit();
                }
            }
        } else {
            $this->template->load_admin('side_banner/banner_form', $data);
        }

    }


    public function delete_side_banner( $id = NULL )
    {
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        $res = false;

        if ($table)
        {
           $res = $this->cms_model->delete_row_team($id,$table); 
        }
        //do not change below code
        $msg_success = $this->lang->line("delete_success");
        $msg_error = $this->lang->line("media_delete_failed");
        if ($res) {
            echo $return = '{"type":"success","msg":"'.$msg_success.'"}';
        } else {
            echo $return = '{"type":"error","msg":"'.$msg_error.'"}';
        }

    }
    ///////////// End side bannner /////////////

    ///////////////////// Team page information ////////////////

     public function team_information()
    {
        $data =array();   
        $data['small_title'] = 'Team Information';
        $data['formaction_path'] = 'team_information';
        $data['current_page_team_information'] = 'current-page';
        $data_row = $this->db->get('our_team')->row_array();
        // print_r($data_row);die();
        $data['status_btn'] = (isset($data_row) && !empty($data_row)) ? 'Update' : 'Save';
        $data['team_information'] = $data_row;
         if ($this->input->post()) {
        $this->load->library('form_validation');
        $posted_data = $this->input->post();
            // $rules = array(
            //    array(
            //         'field' => 'facebook',
            //         'label' => 'facebook',
            //         'rules' => 'trim|required'),
            // );
            //  $this->form_validation->set_rules($rules);
            // if ($this->form_validation->run() == false) {
            //     echo json_encode(array('error' => true, 'msg' => validation_errors()));

            // }else{
        // print_r($this->input->post());
        // die;
            $description = [
                'english' => $posted_data['description_english'],
                'arabic' => $posted_data['description_arabic'],
                ];
                unset($posted_data['description_english']);
                unset($posted_data['description_arabic']);
                $posted_data['description'] = json_encode($description);
                // print_r($posted_data);die();

                 if(isset($data_row) && !empty($data_row)){
                    $posted_data['updated_on'] = date('Y-m-d h:i:s');
                }else{
                    $posted_data['created_on'] = date('Y-m-d h:i:s');
                }
                  

                if(isset($data_row) && !empty($data_row)){
                    $result = $this->db->update('our_team', $posted_data, ['id'=> 1]);
                    $this->session->set_flashdata('msg','Team information has been updated successfully!');
                    // redirect('cms/team_information');
                    echo json_encode(array('error' => false, 'msg' => 'Team information has been updated successfully!'));
                    exit();

                }else{
                    $result_ = $this->db->insert('our_team', $posted_data);
                    // $this->session->set_flashdata('msg','Team information has been Insert successfully.!');
                    echo json_encode(array('error' => false, 'msg' => 'Team information has been Insert successfully.'));
                    $this->session->set_flashdata('msg','Team information has been inserted successfully!');
                    redirect('cms/team_information');
                    exit();
                }
            // } 
        }else{
        $this->template->load_admin('cms/team/pageInfo_form', $data);
        }

    }
    ///////////////////// Team page information End ////////////////
    //contact us function
    //about us function
public function about_us()
    {
        $data =array();   
        $data['small_title'] = 'About Us';
        $data['formaction_path'] = 'about_us';
        $data['current_page_aboutus'] = 'current-page';
        $data_row = $this->db->get('about_us')->row_array();
        $data['status_btn'] = (isset($data_row) && !empty($data_row)) ? 'Update' : 'Save';
        $data['row'] = $data_row;
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $posted_data = $this->input->post();
            $rules = array(
               array(
                    'field' => 'english_title',
                    'label' => 'English Title',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'arabic_title',
                    'label' => 'Arabic Title',
                    'rules' => 'trim|required'),
            );
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
                exit();
            }else{
                unset($posted_data['phimage']);
                unset($posted_data['chimage']);
                unset($posted_data['himage']);
                $title = [
                    'english' => $posted_data['english_title'],
                    'arabic' => $posted_data['arabic_title'],
                ];
                unset($posted_data['english_title']);
                unset($posted_data['arabic_title']);
                $posted_data['title'] = json_encode($title);

                $description = [
                    'english' => $posted_data['english_description'],
                    'arabic' => $posted_data['arabic_description'],
                ];
                unset($posted_data['english_description']);
                unset($posted_data['arabic_description']);
                $posted_data['description'] = json_encode($description);

                 if(isset($data_row) && !empty($data_row)){
                    // $result = $this->cms_model->update_terms($privacypolicy_data);
                    // $posted_data['updated_on'] = date('Y-m-d h:i:s');
                }else{
                    // $posted_data['created_on'] = date('Y-m-d h:i:s');
                }
                if( ! empty($_FILES['image']['name']) || ! empty($_FILES['chairman_image']['name']) || ! empty($_FILES['policy_image']['name'])){
                    // make path
                    $path = './uploads/about_us_images/';
                    if ( ! is_dir($path)) {
                        mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $sizes = [
                        ['width' => 1110, 'height' => 276]
                    ];
                     if (! empty($_FILES['chairman_image']['name']) && !empty($posted_data['c_old_file'])) {
                        $this->files_model->delete_by_name($posted_data['c_old_file'], $path, $sizes);
                    }
                    if (! empty($_FILES['policy_image']['name']) && !empty($posted_data['p_old_file'])) {
                        $this->files_model->delete_by_name($posted_data['p_old_file'], $path, $sizes);
                    }
                    if (! empty($_FILES['image']['name']) && !empty($posted_data['old_file'])) {
                        $this->files_model->delete_by_name($posted_data['old_file'], $path, $sizes);
                    }
                    if (! empty($_FILES['image']['name'])) {
                        $uploaded_file_ids = $this->files_model->upload('image', $config, $sizes);
                        if (isset($uploaded_file_ids['error'])) {
                            echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                            exit();
                        }
                         // upload and save to database
                        $posted_data['image'] = implode(',', $uploaded_file_ids);
                    }
                    if (! empty($_FILES['chairman_image']['name'])) {
                        $uploaded_file_ids = $this->files_model->upload('chairman_image', $config, $sizes);
                        if (isset($uploaded_file_ids['error'])) {
                            echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                            exit();
                        }
                         // upload and save to database
                        $posted_data['chairman_image'] = implode(',', $uploaded_file_ids);
                    }
                    if (! empty($_FILES['policy_image']['name'])) {
                        $uploaded_file_ids = $this->files_model->upload('policy_image', $config, $sizes);
                        if (isset($uploaded_file_ids['error'])) {
                            echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                            exit();
                        }
                         // upload and save to database
                        $posted_data['policy_image'] = implode(',', $uploaded_file_ids);
                    }
                }
                unset($posted_data['c_old_file']);
                unset($posted_data['p_old_file']);
                unset($posted_data['old_file']);
                if(isset($data_row) && !empty($data_row)){
                    $id = $posted_data['id'];
                    unset($posted_data['id']);
                    // $result = $this->cms_model->update_terms($privacypolicy_data);
                    $this->session->set_flashdata('success', 'About Us fields has been updated successfully!');
                    
                    $result = $this->db->update('about_us', $posted_data, ['id'=> $id]);
                    echo json_encode(array('error' => false, 'msg' => 'About Us fields has been updated successfully!'));
                    exit();

                }else{
                    $this->session->set_flashdata('success', 'About Us fields has been Inserted successfully.');
                    $result_ = $this->db->insert('about_us', $posted_data);
                    echo json_encode(array('error' => false, 'msg' => 'About Us fields has been Inserted successfully.'));
                    exit();
                }
            } 
        }else{
            $this->template->load_admin('cms/about_us/about-us', $data);
        }

    }


    public function how_to_register()
    {
        $data =array();   
        $data['small_title'] = 'How To Register';
        $data['formaction_path'] = 'how_to_register';
        $data['current_page_how_to_register'] = 'current-page';
        $data_row = $this->db->get('how_to_register')->row_array();
        $data['status_btn'] = (isset($data_row) && !empty($data_row)) ? 'Update' : 'Save';
        $data['row'] = $data_row;
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $posted_data = $this->input->post();
            $rules = array(
               array(
                    'field' => 'english_title',
                    'label' => 'English Title',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'arabic_title',
                    'label' => 'Arabic Title',
                    'rules' => 'trim|required'),
                 array(
                    'field' => 'arabic_description',
                    'label' => 'Arabic Description',
                    'rules' => 'trim|required'),
                   array(
                    'field' => 'english_description',
                    'label' => 'English Description',
                    'rules' => 'trim|required'),
            );
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
                exit();
            }else{
                unset($posted_data['himage']);
                $title = [
                    'english' => $posted_data['english_title'],
                    'arabic' => $posted_data['arabic_title'],
                ];
                unset($posted_data['english_title']);
                unset($posted_data['arabic_title']);
                $posted_data['register_title'] = json_encode($title);

                $description = [
                    'english' => $posted_data['english_description'],
                    'arabic' => $posted_data['arabic_description'],
                ];
                unset($posted_data['english_description']);
                unset($posted_data['arabic_description']);
                $posted_data['register_description'] = json_encode($description);
                if(!empty($_FILES['image']['name'])){
                    // make path
                    $path = './uploads/how_to_register/';
                    if ( ! is_dir($path)) {
                        mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $sizes = [
                        ['width' => 1110, 'height' => 276]
                    ];
                    
                    if (! empty($_FILES['image']['name']) && !empty($posted_data['old_file'])) {
                        $this->files_model->delete_by_name($posted_data['old_file'], $path, $sizes);
                    }
                    if (! empty($_FILES['image']['name'])) {
                        $uploaded_file_ids = $this->files_model->upload('image', $config, $sizes);
                        if (isset($uploaded_file_ids['error'])) {
                            echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                            exit();
                        }
                         // upload and save to database
                        $posted_data['register_image'] = implode(',', $uploaded_file_ids);
                    }
                }
                unset($posted_data['c_old_file']);
                unset($posted_data['p_old_file']);
                unset($posted_data['old_file']);
                if(isset($data_row) && !empty($data_row)){
                    $id = $posted_data['id'];
                    unset($posted_data['id']);

                    // $result = $this->cms_model->update_terms($privacypolicy_data);
                    $this->session->set_flashdata('success', 'How to register has been updated successfully!');
                    
                    $result = $this->db->update('how_to_register', $posted_data, ['id'=> $id]);
                    echo json_encode(array('error' => false, 'msg' => 'How to register has been updated successfully!'));
                    exit();

                }else{
                    $this->session->set_flashdata('success', 'How to register has been Inserted successfully.');
                // print_r($posted_data);die('kk');

                    $result_ = $this->db->insert('how_to_register', $posted_data);
                    echo json_encode(array('error' => false, 'msg' => 'How to register has been Inserted successfully.'));
                    exit();
                }
            } 
        }else{
            $this->template->load_admin('cms/how_to_register/how_to_register', $data);
        }

    }

    public function how_to_deposit()
    {
        $data =array();   
        $data['small_title'] = 'How To Deposit';
        $data['formaction_path'] = 'how_to_deposit';
        $data['current_page_how_to_deposit'] = 'current-page';
        $data_row = $this->db->get('how_to_deposit')->row_array();
        $data['status_btn'] = (isset($data_row) && !empty($data_row)) ? 'Update' : 'Save';
        $data['row'] = $data_row;
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $posted_data = $this->input->post();
            $rules = array(
               array(
                    'field' => 'english_title',
                    'label' => 'English Title',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'arabic_title',
                    'label' => 'Arabic Title',
                    'rules' => 'trim|required'),
                 array(
                    'field' => 'arabic_description',
                    'label' => 'Arabic Description',
                    'rules' => 'trim|required'),
                   array(
                    'field' => 'english_description',
                    'label' => 'English Description',
                    'rules' => 'trim|required'),
            );
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
                exit();
            }else{
                unset($posted_data['himage']);
                $title = [
                    'english' => $posted_data['english_title'],
                    'arabic' => $posted_data['arabic_title'],
                ];
                unset($posted_data['english_title']);
                unset($posted_data['arabic_title']);
                $posted_data['deposit_title'] = json_encode($title);

                $description = [
                    'english' => $posted_data['english_description'],
                    'arabic' => $posted_data['arabic_description'],
                ];
                unset($posted_data['english_description']);
                unset($posted_data['arabic_description']);
                $posted_data['deposit_description'] = json_encode($description);
                if(!empty($_FILES['image']['name'])){
                    // make path
                    $path = './uploads/how_to_deposit/';
                    if ( ! is_dir($path)) {
                        mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $sizes = [
                        ['width' => 1110, 'height' => 276]
                    ];
                    
                    if (! empty($_FILES['image']['name']) && !empty($posted_data['old_file'])) {
                        $this->files_model->delete_by_name($posted_data['old_file'], $path, $sizes);
                    }
                    if (! empty($_FILES['image']['name'])) {
                        $uploaded_file_ids = $this->files_model->upload('image', $config, $sizes);
                        if (isset($uploaded_file_ids['error'])) {
                            echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                            exit();
                        }
                         // upload and save to database
                        $posted_data['deposit_image'] = implode(',', $uploaded_file_ids);
                    }
                }
                unset($posted_data['c_old_file']);
                unset($posted_data['p_old_file']);
                unset($posted_data['old_file']);
                if(isset($data_row) && !empty($data_row)){
                    $id = $posted_data['id'];
                    unset($posted_data['id']);

                    // $result = $this->cms_model->update_terms($privacypolicy_data);
                    $this->session->set_flashdata('success', 'How to deposit has been updated successfully!');
                    
                    $result = $this->db->update('how_to_deposit', $posted_data, ['id'=> $id]);
                    echo json_encode(array('error' => false, 'msg' => 'How to deposit has been updated successfully!'));
                    exit();

                }else{
                    $this->session->set_flashdata('success', 'How to deposit has been Inserted successfully.');
                // print_r($posted_data);die('kk');

                    $result_ = $this->db->insert('how_to_deposit', $posted_data);
                    echo json_encode(array('error' => false, 'msg' => 'How to deposit has been Inserted successfully.'));
                    exit();
                }
            } 
        }else{
            $this->template->load_admin('cms/how_to_deposite/how_to_deposit', $data);
        }

    }
    public function auction_guide()
    {
        $data =array();   
        $data['small_title'] = 'Auction Guide';
        $data['formaction_path'] = 'auction_guide';
        $data['current_page_auction_guide'] = 'current-page';
        $data_row = $this->db->get('auction_guide')->row_array();
        $data['status_btn'] = (isset($data_row) && !empty($data_row)) ? 'Update' : 'Save';
        $data['row'] = $data_row;
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $posted_data = $this->input->post();
            $rules = array(
               array(
                    'field' => 'english_title',
                    'label' => 'English Title',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'arabic_title',
                    'label' => 'Arabic Title',
                    'rules' => 'trim|required'),
                 array(
                    'field' => 'arabic_description',
                    'label' => 'Arabic Description',
                    'rules' => 'trim|required'),
                   array(
                    'field' => 'english_description',
                    'label' => 'English Description',
                    'rules' => 'trim|required'),
            );
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
                exit();
            }else{
                unset($posted_data['himage']);
                $title = [
                    'english' => $posted_data['english_title'],
                    'arabic' => $posted_data['arabic_title'],
                ];
                unset($posted_data['english_title']);
                unset($posted_data['arabic_title']);
                $posted_data['auction_guide_title'] = json_encode($title);

                $description = [
                    'english' => $posted_data['english_description'],
                    'arabic' => $posted_data['arabic_description'],
                ];
                unset($posted_data['english_description']);
                unset($posted_data['arabic_description']);
                $posted_data['auction_guide_description'] = json_encode($description);

                if(!empty($_FILES['image']['name'])){
                    // make path
                    $path = './uploads/auction_guide/';
                    if ( ! is_dir($path)) {
                        mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $sizes = [
                        ['width' => 1110, 'height' => 276]
                    ];
                    
                    if (! empty($_FILES['image']['name']) && !empty($posted_data['old_file'])) {
                        $this->files_model->delete_by_name($posted_data['old_file'], $path, $sizes);
                    }
                    if (! empty($_FILES['image']['name'])) {
                        $uploaded_file_ids = $this->files_model->upload('image', $config, $sizes);
                        if (isset($uploaded_file_ids['error'])) {
                            echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                            exit();
                        }
                         // upload and save to database
                        $posted_data['auction_guide_image'] = implode(',', $uploaded_file_ids);
                    }
                }
                unset($posted_data['c_old_file']);
                unset($posted_data['p_old_file']);
                unset($posted_data['old_file']);
                if(isset($data_row) && !empty($data_row)){
                    $id = $posted_data['id'];
                    unset($posted_data['id']);

                    // $result = $this->cms_model->update_terms($privacypolicy_data);
                    $this->session->set_flashdata('success', 'Auction Guide has been updated successfully!');
                    
                    $result = $this->db->update('auction_guide', $posted_data, ['id'=> $id]);
                    echo json_encode(array('error' => false, 'msg' => 'Auction Guide has been updated successfully!'));
                    exit();

                }else{
                    $this->session->set_flashdata('success', 'Auction Guide has been Inserted successfully.');
                // print_r($posted_data);die('kk');

                    $result_ = $this->db->insert('auction_guide', $posted_data);
                    echo json_encode(array('error' => false, 'msg' => 'Auction Guide has been Inserted successfully.'));
                    exit();
                }
            } 
        }else{
            $this->template->load_admin('cms/auction_guide/auction_guide', $data);
        }

    }

    public function quality_policy()
    {
        $data =array();   
        $data['small_title'] = 'Quality Policy';
        $data['formaction_path'] = 'quality_policy';
        $data['current_page_quality_policy'] = 'current-page';
        $data_row = $this->db->get('quality_policy')->row_array();
        $data['status_btn'] = (isset($data_row) && !empty($data_row)) ? 'Update' : 'Save';
        $data['row'] = $data_row;
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $posted_data = $this->input->post();
            $rules = array(
               array(
                    'field' => 'english_title',
                    'label' => 'English Title',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'arabic_title',
                    'label' => 'Arabic Title',
                    'rules' => 'trim|required'),
                 array(
                    'field' => 'arabic_description',
                    'label' => 'Arabic Description',
                    'rules' => 'trim|required'),
                   array(
                    'field' => 'english_description',
                    'label' => 'English Description',
                    'rules' => 'trim|required'),
            );
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
                exit();
            }else{
                unset($posted_data['himage']);
                $title = [
                    'english' => $posted_data['english_title'],
                    'arabic' => $posted_data['arabic_title'],
                ];
                unset($posted_data['english_title']);
                unset($posted_data['arabic_title']);
                $posted_data['quality_policy_title'] = json_encode($title);

                $description = [
                    'english' => $posted_data['english_description'],
                    'arabic' => $posted_data['arabic_description'],
                ];
                unset($posted_data['english_description']);
                unset($posted_data['arabic_description']);
                $posted_data['quality_policy_description'] = json_encode($description);

                if(!empty($_FILES['image']['name'])){
                    // make path
                    $path = './uploads/quality_policy/';
                    if ( ! is_dir($path)) {
                        mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $sizes = [
                        ['width' => 1110, 'height' => 276]
                    ];
                    
                    if (! empty($_FILES['image']['name']) && !empty($posted_data['old_file'])) {
                        $this->files_model->delete_by_name($posted_data['old_file'], $path, $sizes);
                    }
                    if (! empty($_FILES['image']['name'])) {
                        $uploaded_file_ids = $this->files_model->upload('image', $config, $sizes);
                        if (isset($uploaded_file_ids['error'])) {
                            echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                            exit();
                        }
                         // upload and save to database
                        $posted_data['quality_policy_image'] = implode(',', $uploaded_file_ids);
                    }
                }
                unset($posted_data['c_old_file']);
                unset($posted_data['p_old_file']);
                unset($posted_data['old_file']);
                if(isset($data_row) && !empty($data_row)){
                    $id = $posted_data['id'];
                    unset($posted_data['id']);

                    // $result = $this->cms_model->update_terms($privacypolicy_data);
                    $this->session->set_flashdata('success', 'Quality policy has been updated successfully!');
                    
                    $result = $this->db->update('quality_policy', $posted_data, ['id'=> $id]);
                    echo json_encode(array('error' => false, 'msg' => 'Quality policy has been updated successfully!'));
                    exit();

                }else{
                    $this->session->set_flashdata('success', 'Quality policy has been Inserted successfully.');
                // print_r($posted_data);die('kk');

                    $result_ = $this->db->insert('quality_policy', $posted_data);
                    echo json_encode(array('error' => false, 'msg' => 'Quality policy has been Inserted successfully.'));
                    exit();
                }
            } 
        }else{
            $this->template->load_admin('cms/quality_policy/quality_policy', $data);
        }

    }

    //about us history functions
    public function aboutus_history_listing()
    {
        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'About Us History';
        $data['about_us_history'] = 'current-page';
        $data['formaction_path'] = 'add_history';
        $data['list'] = $this->db->get('about_us_history')->result_array();
        // print_r($data['ques_ans_info']);die('aaaaaa');   
        $this->template->load_admin('cms/about_us/list', $data);
    }
     public function add_history()
    {
        $data = array();
        $dynaic_information = array();
        $data['small_title'] = 'Add About Us History';
        $data['about_us_history'] = 'current-page';
        $data['formaction_path'] = "add_history";
         if ($this->input->post()) {
        $this->load->library('form_validation');
        $posted_data = $this->input->post();
            $rules = array(
               array(
                    'field' => 'english_heading',
                    'label' => 'English Heading',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'arabic_heading',
                    'label' => 'Arabic Heading',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'english_title',
                    'label' => 'English Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'arabic_title',
                    'label' => 'Arabic Title',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'english_description',
                    'label' => 'English Description',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'arabic_description',
                    'label' => 'Arabic Description',
                    'rules' => 'trim|required'),
            );
           
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
            }else{
                $posted_data['created_on'] = date('Y-m-d h:i:s');
                
                $heading = [
                'english' => $posted_data['english_heading'], 
                'arabic' => $posted_data['arabic_heading'], 
                ];
                unset($posted_data['english_heading']); 
                unset($posted_data['arabic_heading']); 
                $posted_data['heading'] = json_encode($heading);

                $title = [
                'english' => $posted_data['english_title'],
                'arabic' => $posted_data['arabic_title'],
                ];
                unset($posted_data['english_title']);
                unset($posted_data['arabic_title']);
                $posted_data['title'] = json_encode($title);

                $description = [
                'english' => $posted_data['english_description'],
                'arabic' => $posted_data['arabic_description'],
                ];
                unset($posted_data['english_description']);
                unset($posted_data['arabic_description']);
                $posted_data['description'] = json_encode($description);
                $result = $this->db->insert('about_us_history', $posted_data);
                if($result){
                $this->session->set_flashdata('success', 'About us added successfully');
                    echo json_encode(array('success'=> true , 'msg' => 'About us has been inserted successfully!'));
                }
            } 
        }
        else
        {
            $this->template->load_admin('cms/about_us/form', $data);
        }

    }
    public function edit_history()
    {
        $data = array();
        $id = $this->uri->segment(3);
        $data['small_title'] = 'Edit About Us History';
        $data['about_us_history'] = 'current-page';
        $data['formaction_path'] = 'update_history';
        $data['edit'] = $this->db->get_where('about_us_history', ['id' => $id])->row_array();
        $this->template->load_admin('cms/about_us/form', $data);
    }
    public function  update_history()
    {

        if ($this->input->post()) {
        $this->load->library('form_validation');
        $posted_data = $this->input->post();
            $rules = array(
               array(
                    'field' => 'english_heading',
                    'label' => 'English Heading',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'arabic_heading',
                    'label' => 'Arabic Heading',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'english_title',
                    'label' => 'English Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'arabic_title',
                    'label' => 'Arabic Title',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'english_description',
                    'label' => 'English Description',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'arabic_description',
                    'label' => 'Arabic Description',
                    'rules' => 'trim|required'),
            );

             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
            }else{
                $posted_data['updated_on'] = date('Y-m-d h:i:s');
                $heading = [
                'english' => $posted_data['english_heading'], 
                'arabic' => $posted_data['arabic_heading'], 
                ];
                unset($posted_data['english_heading']); 
                unset($posted_data['arabic_heading']); 
                $posted_data['heading'] = json_encode($heading);

                $title = [
                'english' => $posted_data['english_title'],
                'arabic' => $posted_data['arabic_title'],
                ];
                unset($posted_data['english_title']);
                unset($posted_data['arabic_title']);
                $posted_data['title'] = json_encode($title);

                $description = [
                'english' => $posted_data['english_description'],
                'arabic' => $posted_data['arabic_description'],
                ];
                unset($posted_data['english_description']);
                unset($posted_data['arabic_description']);
                $posted_data['description'] = json_encode($description);
                $id = $posted_data['id'];
                unset($posted_data['id']);
                $result = $this->db->update('about_us_history', $posted_data , ['id'=>$id] );
                if($result){
                $this->session->set_flashdata('success', 'About Us Updated Successfully');
                    echo json_encode(array('success'=> true , 'msg' => 'About Us has been Update update'));
                }
            } 
        }
    }
    public function delete_history( $id = NULL )
    {
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        $res = false;

        if ($table)
        {
           $res = $this->cms_model->delete_row($id,$table); 
        }
        //do not change below code
        $msg_success = $this->lang->line("delete_success");
        $msg_error = $this->lang->line("media_delete_failed");
        if ($res) {
            echo $return = '{"type":"success","msg":"'.$msg_success.'"}';
        } else {
            echo $return = '{"type":"error","msg":"'.$msg_error.'"}';
        }

    }

    //media functions
    public function media_listing()
    {
        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Media History';
        $data['media'] = 'current-page';
        $data['formaction_path'] = 'add_history';
        $data['list'] = $this->db->get('media')->result_array();
        $this->template->load_admin('cms/media/list', $data);
    }
    public function add_media()
    {
        $data = array();
        $dynaic_information = array();
        $data['small_title'] = 'Add Media History';
        $data['current_page_media'] = 'current-page';
        $data['formaction_path'] = "add_history";
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $posted_data = $this->input->post();
            $rules = array(
                array(
                    'field' => 'english_title',
                    'label' => 'English Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'arabic_title',
                    'label' => 'Arabic Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'english_description',
                    'label' => 'English Description',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'arabic_description',
                    'label' => 'Arabic Description',
                    'rules' => 'trim|required'),
            );
           
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
            }else{
                $posted_data['created_on'] = date('Y-m-d h:i:s');
                $heading = [
                'english' => $posted_data['english_heading'], 
                'arabic' => $posted_data['arabic_heading'], 
                ];
                unset($posted_data['english_heading']); 
                unset($posted_data['arabic_heading']); 
                $posted_data['heading'] = json_encode($heading);

                $title = [
                'english' => $posted_data['english_title'],
                'arabic' => $posted_data['arabic_title'],
                ];
                unset($posted_data['english_title']);
                unset($posted_data['arabic_title']);
                $posted_data['title'] = json_encode($title);

                $description = [
                'english' => $posted_data['english_description'],
                'arabic' => $posted_data['arabic_description'],
                ];
                unset($posted_data['english_description']);
                unset($posted_data['arabic_description']);
                $posted_data['description'] = json_encode($description);
                $result = $this->db->insert('media', $posted_data);
                if($result){
                $this->session->set_flashdata('success', 'About us added successfully');
                    echo json_encode(array('success'=> true , 'msg' => 'About us has been inserted successfully!'));
                }
            } 
        }
        else
        {
            $this->template->load_admin('cms/media/form', $data);
        }

    }
    public function edit_media()
    {
        $data = array();
        $id = $this->uri->segment(3);
        $data['small_title'] = 'Edit Media History';
        $data['media'] = 'current-page';
        $data['formaction_path'] = 'update_history';
        $data['edit'] = $this->db->get_where('media', ['id' => $id])->row_array();
        // print_r($data['ques_ans_info']);die('aaaaaa');   
        $this->template->load_admin('cms/media/form', $data);
    }
    public function  update_media()
    {

        if ($this->input->post()) {
        $this->load->library('form_validation');
        $posted_data = $this->input->post();
            $rules = array(
               array(
                    'field' => 'english_heading',
                    'label' => 'English Heading',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'arabic_heading',
                    'label' => 'Arabic Heading',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'english_title',
                    'label' => 'English Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'arabic_title',
                    'label' => 'Arabic Title',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'english_description',
                    'label' => 'English Description',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'arabic_description',
                    'label' => 'Arabic Description',
                    'rules' => 'trim|required'),
            );

             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
            }else{
                $posted_data['updated_on'] = date('Y-m-d h:i:s');
                $heading = [
                'english' => $posted_data['english_heading'], 
                'arabic' => $posted_data['arabic_heading'], 
                ];
                unset($posted_data['english_heading']); 
                unset($posted_data['arabic_heading']); 
                $posted_data['heading'] = json_encode($heading);

                $title = [
                'english' => $posted_data['english_title'],
                'arabic' => $posted_data['arabic_title'],
                ];
                unset($posted_data['english_title']);
                unset($posted_data['arabic_title']);
                $posted_data['title'] = json_encode($title);

                $description = [
                'english' => $posted_data['english_description'],
                'arabic' => $posted_data['arabic_description'],
                ];
                unset($posted_data['english_description']);
                unset($posted_data['arabic_description']);
                $posted_data['description'] = json_encode($description);
                $id = $posted_data['id'];
                unset($posted_data['id']);
                $result = $this->db->update('media', $posted_data , ['id'=>$id] );
                if($result){
                $this->session->set_flashdata('success', 'About Us Updated Successfully');
                    echo json_encode(array('success'=> true , 'msg' => 'About Us has been Update update'));
                }
            } 
        }
    }
    public function delete_media( $id = NULL )
    {
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        $res = false;

        if ($table)
        {
           $res = $this->cms_model->delete_row($id,$table); 
        }
        //do not change below code
        $msg_success = $this->lang->line("delete_success");
        $msg_error = $this->lang->line("media_delete_failed");
        if ($res) {
            echo $return = '{"type":"success","msg":"'.$msg_success.'"}';
        } else {
            echo $return = '{"type":"error","msg":"'.$msg_error.'"}';
        }

    }

     public function socialLink()
    {
        $data =array();   
        $data['small_title'] = 'Social Links';
        $data['formaction_path'] = 'socialLink';
        $data['current_page_socialLinks'] = 'current-page';
        $data_row = $this->db->get('social_links')->row_array();
        $data['status_btn'] = (isset($data_row) && !empty($data_row)) ? 'Update' : 'Save';
        $data['social_info'] = $data_row;
         if ($this->input->post()) {
        $this->load->library('form_validation');
        $posted_data = $this->input->post();
            $rules = array(
               array(
                    'field' => 'facebook',
                    'label' => 'facebook',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'twitter',
                    'label' => 'twitter',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'linked_in',
                    'label' => 'linkedin',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'google_plus',
                    'label' => 'google plus',
                    'rules' => 'trim|required')
               
            );
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));

            }else{

                 if(isset($data_row) && !empty($data_row)){
                    $posted_data['updated_on'] = date('Y-m-d h:i:s');
                }else{
                    $posted_data['created_on'] = date('Y-m-d h:i:s');
                }
                if(isset($data_row) && !empty($data_row)){
                    $result = $this->db->update('social_links', $posted_data, ['id'=> 1]);
                    $this->session->set_flashdata('msg','Social Links has been updated successfully!');
                    redirect('cms/socialLink');
                    echo json_encode(array('error' => false, 'msg' => 'Social Links has been updated successfully!'));
                    exit();

                }else{
                    $result_ = $this->db->insert('social_links', $posted_data);
                    // $this->session->set_flashdata('msg','Social Links has been Insert successfully.!');
                    echo json_encode(array('error' => false, 'msg' => 'Social Links has been Insert successfully.'));
                    exit();
                }
            } 
        }else{
        $this->template->load_admin('cms/socialLink/social_link', $data);
        }

    }

    public function storeLinks()
    {
        $data =array();   
        $data['small_title'] = 'App Store Links';
        $data['formaction_path'] = 'storeLinks';
        $data['current_page_storeLinks'] = 'current-page';
        $data_row = $this->db->get('store_links')->row_array();
        $data['status_btn'] = (isset($data_row) && !empty($data_row)) ? 'Update' : 'Save';
        $data['store_links'] = $data_row;
         if ($this->input->post()) {
        $this->load->library('form_validation');
        $posted_data = $this->input->post();
            $rules = array(
               array(
                    'field' => 'google_play',
                    'label' => 'google store',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'apple_store',
                    'label' => 'apple store',
                    'rules' => 'trim|required')
               
            );
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));

            }else{

                 if(isset($data_row) && !empty($data_row)){
                    $posted_data['updated_on'] = date('Y-m-d h:i:s');
                }else{
                    $posted_data['created_on'] = date('Y-m-d h:i:s');
                }
                if(isset($data_row) && !empty($data_row)){
                    $result = $this->db->update('store_links', $posted_data, ['id'=> 1]);
                    $this->session->set_flashdata('msg','App store links has been updated successfully!');
                    redirect('cms/storeLinks');
                    echo json_encode(array('error' => false, 'msg' => 'App store links has been updated successfully!'));
                    exit();

                }else{
                    $result_ = $this->db->insert('store_links', $posted_data);
                    $this->session->set_flashdata('msg','App store links has been Insert successfully.!');
                    redirect('cms/storeLinks');
                    echo json_encode(array('error' => false, 'msg' => 'App store links has been Insert successfully.'));
                    redirect('cms/storeLinks');
                    exit();
                }
            } 
        }else{
        $this->template->load_admin('cms/storelinks/store_link', $data);
        }

    }

    ////////////////////////////////////
    //our partners functions
    public function partners_listing()
    {
        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Our Partners';
        $data['current_page_partners'] = 'current-page';
        $data['formaction_path'] = 'add_partners';
        $data['list'] = $this->db->get('partners')->result_array();
        // print_r($data['ques_ans_info']);die('aaaaaa');   
        $this->template->load_admin('cms/partners/list', $data);
    }
     public function add_partners()
    {
        $data = array();
        $dynaic_information = array();
        $data['small_title'] = 'Add Our Partner';
        $data['current_page_partners'] = 'current-page';
        $data['formaction_path'] = "add_partners";
         if ($this->input->post()) {
        $this->load->library('form_validation');
        $posted_data = $this->input->post();
            $rules = array(
               array(
                    'field' => 'english_title',
                    'label' => 'English Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'arabic_title',
                    'label' => 'Arabic Title',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'english_description',
                    'label' => 'English Description',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'arabic_description',
                    'label' => 'Arabic Description',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'himage',
                    'label' => 'Image',
                    'rules' => 'trim|required'),
            );
           
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
            }else{
                unset($posted_data['himage']);

                $title = [
                'english' => $posted_data['english_title'],
                'arabic' => $posted_data['arabic_title'],
                ];
                unset($posted_data['english_title']);
                unset($posted_data['arabic_title']);
                $posted_data['title'] = json_encode($title);

                $description = [
                'english' => $posted_data['english_description'],
                'arabic' => $posted_data['arabic_description'],
                ];
                unset($posted_data['english_description']);
                unset($posted_data['arabic_description']);
                $posted_data['description'] = json_encode($description);
                //upload file
                if( ! empty($_FILES['image']['name'])){
                    // make path
                    $path = './uploads/partner_images/';
                    if ( ! is_dir($path)) {
                        mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $sizes = [
                        ['width' => 1110, 'height' => 276]
                    ];
                    if (! empty($_FILES['image']['name']) && !empty($posted_data['old_file'])) {
                        $this->files_model->delete_by_name($posted_data['old_file'], $path, $sizes);
                    }
                    if (! empty($_FILES['image']['name'])) {
                        $uploaded_file_ids = $this->files_model->upload('image', $config, $sizes);
                        if (isset($uploaded_file_ids['error'])) {
                            echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                            exit();
                        }
                         // upload and save to database
                        $posted_data['image'] = implode(',', $uploaded_file_ids);
                    }
                }
                $result = $this->db->insert('partners', $posted_data);
                if($result){
                $this->session->set_flashdata('success', 'Partner added successfully.');
                    echo json_encode(array('success'=> true , 'msg' => 'Partner has been added successfully!'));
                }
            } 
        }
        else
        {
            $this->template->load_admin('cms/partners/form', $data);
        }

    }
    public function edit_partners()
    {
        $data = array();
        $id = $this->uri->segment(3);
        $data['small_title'] = 'Edit Our Partner';
        $data['current_page_partners'] = 'current-page';
        $data['formaction_path'] = 'update_partners';
        $data['edit'] = $this->db->get_where('partners', ['id' => $id])->row_array();
        $this->template->load_admin('cms/partners/form', $data);
    }
    public function  update_partners()
    {

        if ($this->input->post()) {
        $this->load->library('form_validation');
        $posted_data = $this->input->post();
            $rules = array(
               array(
                    'field' => 'english_title',
                    'label' => 'English Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'arabic_title',
                    'label' => 'Arabic Title',
                    'rules' => 'trim|required'),
               array(
                    'field' => 'english_description',
                    'label' => 'English Description',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'arabic_description',
                    'label' => 'Arabic Description',
                    'rules' => 'trim|required'),
            );

             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
            }else{
                unset($posted_data['himage']);

                $title = [
                'english' => $posted_data['english_title'],
                'arabic' => $posted_data['arabic_title'],
                ];
                unset($posted_data['english_title']);
                unset($posted_data['arabic_title']);
                $posted_data['title'] = json_encode($title);

                $description = [
                'english' => $posted_data['english_description'],
                'arabic' => $posted_data['arabic_description'],
                ];
                unset($posted_data['english_description']);
                unset($posted_data['arabic_description']);
                $posted_data['description'] = json_encode($description);
                $id = $posted_data['id'];
                unset($posted_data['id']);
                //update image
                if( ! empty($_FILES['image']['name'])){
                    // make path
                    $path = './uploads/partner_images/';
                    if ( ! is_dir($path)) {
                        mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $sizes = [
                        ['width' => 1110, 'height' => 276]
                    ];
                    if (! empty($_FILES['image']['name']) && !empty($posted_data['old_file'])) {
                        $this->files_model->delete_by_name($posted_data['old_file'], $path, $sizes);
                    }
                    if (! empty($_FILES['image']['name'])) {
                        $uploaded_file_ids = $this->files_model->upload('image', $config, $sizes);
                        if (isset($uploaded_file_ids['error'])) {
                            echo json_encode(array('error' => true, 'msg' => $uploaded_file_ids['error']));
                            exit();
                        }
                         // upload and save to database
                        $posted_data['image'] = implode(',', $uploaded_file_ids);
                    }
                }
                unset($posted_data['old_file']);
                $result = $this->db->update('partners', $posted_data , ['id'=>$id] );
                if($result){
                $this->session->set_flashdata('success', 'Partner Updated Successfully');
                    echo json_encode(array('success'=> true , 'msg' => 'Partner has been Update update'));
                }
            } 
        }
    }
     
  
}

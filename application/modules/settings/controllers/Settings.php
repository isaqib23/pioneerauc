<?php defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends Loggedin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Setting_model', 'setting_model');
        $this->load->model('crm/Crm_model', 'crm_model');
        $this->load->model('email/Email_model', 'email_model');
        $this->load->model('files/Files_model', 'files_model');
    }

    public function index()
    {
        $data = array();
        $data['small_title'] = 'Setting';
        $this->template->load_admin('settings/setting_list', $data);
    }

    public function email_template_form()
    {
        $data = array();
        $data['small_title'] = 'Email Template Setting';
        $data['current_page_email_template'] = 'current-page';
        $data['status_btn'] = 'Save';
        $data['formaction_path'] = 'email_template';
        $this->template->load_admin('settings/email/email_setting', $data);
    }

    public function email_template()
    {
        $data = array();
        $data['small_title'] = 'Email Template Setting';
        $data['current_page_email_template'] = 'current-page';
        $data['status_btn'] = 'Save';
        $data['formaction_path'] = 'email_template';
        $id = '';
        if ($this->input->post('id')) {
            $data['status_btn'] = 'Update';
            $id = $this->input->post('id');
        }
        if ($this->uri->segment(3)) {
            $id = $this->uri->segment(3);
        }
        $data['email_template'] = $this->setting_model->get_email_template($id);
        if ($this->input->post()) {
            $posted_data = $this->input->post();
            unset($posted_data['content_message']);
            $this->load->library('form_validation');
            $rules = array(
                array(
                    'field' => 'template_name',
                    'label' => 'Template Name',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'subject',
                    'label' => 'Sunject',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'body',
                    'label' => 'Email Body',
                    'rules' => 'trim|required')
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('msg' => validation_errors()));
            } else {
                if (!empty($id)) {
                    $this->session->set_flashdata('msg', 'Email template setting updated successfully.');
                    $posted_data['updated_by'] = $this->loginUser->id;
                } else {
                    $posted_data['created_on'] = date('Y-m-d h:i:s');
                    $posted_data['created_by'] = $this->loginUser->id;
                    $this->session->set_flashdata('msg', 'Email template setting saved successfully.');
                }
                $result = $this->setting_model->save_email_template($id, $posted_data);
                if ($result) {
                    echo json_encode(array('msg' => 'success'));
                } else {
                    echo json_encode(array('msg' => 'error'));
                }
            }

        } else {
            $this->template->load_admin('settings/email/email_setting', $data);
        }
    }


    // Add a new popup
    public function popup()
    {


        $data = array();
        $data['small_title'] = 'Add';
        $data['current_page_category'] = 'current-page';
        $data['formaction_path'] = 'popup';
        $data['popup_data'] = $this->db->select('*')->from('popup')->get()->row_array();

        if ($this->input->post()) {
            $this->load->library('form_validation');
            $rules = array(
                array(
                    'field' => 'title_english',
                    'label' => 'Title English',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'title_arabic',
                    'label' => 'Title Arabic',
                    'rules' => 'trim|required'));
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                echo json_encode(array('msg' => validation_errors()));
            } else {

                $posted_data = array(
                    'created_on' => date('Y-m-d H:i:s'),
                    'created_by' => $this->loginUser->id,
                    'status' => $this->input->post('active_status')
                );
                $title = [
                    'english' => $this->input->post('title_english'),
                    'arabic' => $this->input->post('title_arabic'),
                ];
                $posted_data['title'] = json_encode($title);


                if  ($this->input->post('old_files')) {
                    $posted_data['popup_image'] = $this->input->post('old_files');

                }

                if (!empty($_FILES)) {
                    // make path
                    $path = './uploads/popup/';
                    if (!is_dir($path)) {
                        mkdir($path, 0777, TRUE);
                    }
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    // print_r($config);die();
                    if (isset($_FILES['popup_image']['name']) && !empty($_FILES['popup_image']['name'])) {
                        $uploaded_file_ids = $this->files_model->upload('popup_image', $config);// upload and save to database
                        $posted_data['popup_image'] = implode(',', $uploaded_file_ids);
                    }
                }


                $result = $this->setting_model->insert_popup($posted_data);
                if (!empty($result)) {
                    $this->session->set_flashdata('msg', 'Popup Added Successfully');
                    $msg = 'success';
                    echo json_encode(array('msg' => $msg, 'in_id' => $result));
                } else {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                }

            }
        } else {

            $this->template->load_admin('popup', $data);
        }

    }


    public function notification()
    {
        $data = array();
        $data['small_title'] = 'Send Notification';
        $data['status_btn'] = 'Send';
        $data['formaction_path'] = 'notification';
        $data['current_page_notification'] = 'current-page';
        $data['email_template_body'] = $this->setting_model->get_email_template_active();
        $data['customer_list'] = $this->crm_model->crm_customers_list();
        if ($this->input->post()) {
            $posted_data = $this->input->post();
            $this->load->library('form_validation');
            $rules = array(
                array(
                    'field' => 'body',
                    'label' => 'Email Template',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'to_email[]',
                    'label' => 'Email To',
                    'rules' => 'trim|required')

            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('msg' => validation_errors()));
            } else {

                // $body = $this->setting_model->get_email_body($posted_data['template_id'][0]);
                $to_email_arr = $posted_data['to_email'];
                $body = $posted_data['body'];
                // add email send functionality here..
                $this->email_model->direct_template($to_email_arr, $posted_data['subject'], $body, $vars = array());

                //print_r($email_data);die();
                $response = array(
                    'msg' => 'success'
                );
                echo json_encode($response);
            }

        } else {
            $this->template->load_admin('settings/email/email_notification', $data);
        }
    }

    // Delete Single Row
    public function delete($id = NULL)
    {
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        $res = false;

        if ($table == "crm_email_template") {
            $res = $this->setting_model->delete_setting_row($id);
        }

        //do not change below code
        if ($res) {
            echo json_encode(array('msg' => 'success', 'mid' => 'data'));
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }

    public function configuration()
    {
        $data = array();
        // $this->template->load_admin('profiles/profile_form', $data);
        $id = $this->loginUser->id;
        $data['title'] = 'Bid Setting';
        $data['title2'] = 'VAT Setting';
        $data['title3'] = 'Organization Setting';
        $data['title4'] = 'System Setting';
        $data['formaction_path'] = 'save_buyer_comission';
        $data['comission'] = array();
        $data['current_page_general_setting'] = 'current-page';
        $data['buyer_comission'] = $this->setting_model->buyer_list();
        $this->template->load_admin('settings/configuration', $data);


    }

    public function save_buyer_comission()
    {
        if ($this->input->post()) {
            $this->load->library('form_validation');
            if ($this->input->post()) {
                $rules = array(
                    array(
                        'field' => 'value',
                        'label' => 'Value',
                        'rules' => 'trim|required'),
                );
            }
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('msg' => validation_errors()));
            } else {
                // $old_comission = $this->setting_model->buyer_list();
                $old_comission = $this->db->get_where('settings', ['code_key' => 'min_deposit'])->result_array();
                if (!empty($old_comission)) {
                    // $id = $this->input->post('id');
                    // print_r($id);die('ddd');
                    $data = $this->input->post();
                    $data['updated_on'] = date('Y-m-d H:i:s');
                    // $result = $this->setting_model->update_buyer_comission($data);
                    $result = $this->db->update('settings', $data, ['code_key' => 'min_deposit']);
                    // print_r($result);die('ddd');
                    if (!empty($result)) {
                        $this->session->set_flashdata('msg', 'Minimun Deposit Amount Updated Successfully.');
                        redirect('settings/configuration');
                        $msg = 'success';
                        echo json_encode(array('msg' => $msg, 'mid' => $result));
                        exit();
                    }
                } else {
                    $data = $this->input->post();
                    $id = $this->input->post('id');
                    $data['title'] = 'Minimum Deposit';
                    $data['updated_on'] = date('Y-m-d H:i:s');
                    $result = $this->db->insert('settings', $data, ['code_key' => 'min_deposit']);
                    if (!empty($result)) {
                        $this->session->set_flashdata('msg', 'Minimun Deposit Amount Added Successfully.');
                        redirect('settings/configuration');
                        $msg = 'success';
                        echo json_encode(array('msg' => $msg, 'mid' => $result));

                    }
                }
                // $data['buyer_comission'] = $this->input->post('buyer_comission');

            }

        }
    }

    public function save_payments()
    {
        if ($this->input->post()) {
            $this->load->library('form_validation');
            if ($this->input->post()) {
                $rules = array(
                    array(
                        'field' => 'value',
                        'label' => 'Value',
                        'rules' => 'trim|required'),
                );
            }
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('msg' => validation_errors()));
            } else {
                // $old_comission = $this->setting_model->buyer_list();
                $old_comission = $this->db->get_where('settings', ['code_key' => 'p_amount'])->result_array();
                if (!empty($old_comission)) {
                    // $id = $this->input->post('id');
                    // print_r($id);die('ddd');
                    $data = $this->input->post();
                    $data['updated_on'] = date('Y-m-d H:i:s');
                    // $result = $this->setting_model->update_buyer_comission($data);
                    $result = $this->db->update('settings', $data, ['code_key' => 'p_amount']);
                    // print_r($result);die('ddd');
                    if (!empty($result)) {
                        $this->session->set_flashdata('msg', 'Percentage Amount Updated Successfully.');
                        redirect('settings/configuration');
                        $msg = 'success';
                        echo json_encode(array('msg' => $msg, 'mid' => $result));
                        exit();
                    }
                } else {
                    $data = $this->input->post();
                    $data['title'] = 'Percentage Amount';
                    $data['updated_on'] = date('Y-m-d H:i:s');
                    $result = $this->db->insert('settings', $data, ['code_key' => 'p_amount']);
                    if (!empty($result)) {
                        $this->session->set_flashdata('msg', 'Percentage Amount Added Successfully.');
                        redirect('settings/configuration');
                        $msg = 'success';
                        echo json_encode(array('msg' => $msg, 'mid' => $result));

                    }
                }
                // $data['buyer_comission'] = $this->input->post('buyer_comission');

            }
        }
    }

    public function save_vat()
    {
        if ($this->input->post()) {
            $this->load->library('form_validation');
            if ($this->input->post()) {
                $rules = array(
                    array(
                        'field' => 'value',
                        'label' => 'Value',
                        'rules' => 'trim|required'),
                );
            }
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('msg' => validation_errors()));
            } else {
                // $old_comission = $this->setting_model->buyer_list();
                $old_comission = $this->db->get_where('settings', ['code_key' => 'vat'])->result_array();
                if (!empty($old_comission)) {
                    $data = $this->input->post();
                    $data['updated_on'] = date('Y-m-d H:i:s');
                    // $result = $this->setting_model->update_buyer_comission($data);
                    $result = $this->db->update('settings', $data, ['code_key' => 'vat']);
                    // print_r($result);die('ddd');
                    if (!empty($result)) {
                        $this->session->set_flashdata('msg', ' VAT Updated Successfully.');
                        redirect('settings/configuration');
                        $msg = 'success';
                        echo json_encode(array('msg' => $msg, 'mid' => $result));
                        exit();
                    }
                } else {
                    $data = $this->input->post();
                    $data['title'] = 'VAT';
                    $data['updated_on'] = date('Y-m-d H:i:s');
                    $result = $this->db->insert('settings', $data, ['code_key' => 'vat']);
                    if (!empty($result)) {
                        $this->session->set_flashdata('msg', 'VAT Added Successfully.');
                        redirect('settings/configuration');
                        $msg = 'success';
                        echo json_encode(array('msg' => $msg, 'mid' => $result));

                    }
                }
                // $data['buyer_comission'] = $this->input->post('buyer_comission');

            }

        }
    }

    public function save_infoemail()
    {
        if ($this->input->post()) {
            $this->load->library('form_validation');
            if ($this->input->post()) {
                $rules = array(
                    array(
                        'field' => 'value',
                        'label' => 'Value',
                        'rules' => 'trim|required'),
                );
            }
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('msg' => validation_errors()));
            } else {
                // $old_comission = $this->setting_model->buyer_list();
                $old_comission = $this->db->get_where('settings', ['code_key' => 'info_email'])->result_array();
                if (!empty($old_comission)) {
                    $data = $this->input->post();
                    $data['updated_on'] = date('Y-m-d H:i:s');
                    // $result = $this->setting_model->update_buyer_comission($data);
                    $result = $this->db->update('settings', $data, ['code_key' => 'info_email']);
                    // print_r($result);die('ddd');
                    if (!empty($result)) {
                        $this->session->set_flashdata('msg', 'Info Email Updated Successfully.');
                        redirect('settings/configuration');
                        $msg = 'success';
                        echo json_encode(array('msg' => $msg, 'mid' => $result));
                        exit();
                    }
                } else {
                    $data = $this->input->post();
                    $id = $this->input->post('id');
                    $data['title'] = 'Info Email';
                    $data['updated_on'] = date('Y-m-d H:i:s');
                    $result = $this->db->insert('settings', $data, ['code_key' => 'info_email']);
                    if (!empty($result)) {
                        $this->session->set_flashdata('msg', 'Info Email Added Successfully.');
                        redirect('settings/configuration');
                        $msg = 'success';
                        echo json_encode(array('msg' => $msg, 'mid' => $result));

                    }
                }
                // $data['buyer_comission'] = $this->input->post('buyer_comission');

            }

        }
    }

    public function save_defaultCurrency()
    {
        if ($this->input->post()) {
            $this->load->library('form_validation');
            if ($this->input->post()) {
                $rules = array(
                    array(
                        'field' => 'value',
                        'label' => 'Value',
                        'rules' => 'trim|required'),
                );
            }
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('msg' => validation_errors()));
            } else {
                // $old_comission = $this->setting_model->buyer_list();
                $old_comission = $this->db->get_where('settings', ['code_key' => 'currency'])->result_array();
                if (!empty($old_comission)) {
                    $data = $this->input->post();
                    $data['updated_on'] = date('Y-m-d H:i:s');
                    // $result = $this->setting_model->update_buyer_comission($data);
                    $result = $this->db->update('settings', $data, ['code_key' => 'currency']);
                    // print_r($result);die('ddd');
                    if (!empty($result)) {
                        $this->session->set_flashdata('msg', 'Default Currency Updated Successfully.');
                        redirect('settings/configuration');
                        $msg = 'success';
                        echo json_encode(array('msg' => $msg, 'mid' => $result));
                        exit();
                    }
                } else {
                    $data = $this->input->post();
                    $id = $this->input->post('id');
                    $data['title'] = 'Default Currency';
                    $data['updated_on'] = date('Y-m-d H:i:s');
                    $result = $this->db->insert('settings', $data, ['code_key' => 'currency']);
                    if (!empty($result)) {
                        $this->session->set_flashdata('msg', 'Default Currency Added Successfully.');
                        redirect('settings/configuration');
                        $msg = 'success';
                        echo json_encode(array('msg' => $msg, 'mid' => $result));

                    }
                }
                // $data['buyer_comission'] = $this->input->post('buyer_comission');

            }

        }
    }

    public function htmlmail()
    {
        $config = Array(
            'protocol' => 'sendmail',
            'smtp_host' => 'your domain SMTP host',
            'smtp_port' => 25,
            'smtp_user' => 'SMTP Username',
            'smtp_pass' => 'SMTP Password',
            'smtp_timeout' => '4',
            'mailtype' => 'html',
            'charset' => 'iso-8859-1'
        );
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");

        $this->email->from('your mail id', 'Anil Labs');
        $data = array(
            'userName' => 'Adil Jabbar',
            'user_name' => 'adiljabbar96',
            'password' => '12345'
        );
        $subject = $_GET['subject'];
        $userEmail = $_GET['email'];
        $this->email->to($userEmail);  // replace it with receiver mail id
        $this->email->subject($subject); // replace it with relevant subject 

        $body = $this->template->load_admin('settings/email/email_testing', $data, TRUE);
        // echo $body;die('aaaaaaa');
        $this->email->message($body);
        $this->email->send();
    }

    public function email_template_list()
    {
        $data['email_template_list'] = $this->setting_model->email_template_list();
        $data['small_title'] = "Email Templates";
        $data['current_page_email_template'] = 'current-page';
        $this->template->load_admin('settings/email/email_template_list', $data);

    }

    public function chage_status()
    {
        $status = array('status' => $_GET['status']);
        $user_id = $_GET['user_id'];


        $return = $this->setting_model->chahge_status($status, $user_id);
        $data = $this->setting_model->email_listing($user_id);


        if ($data[0]['status'] == "active") {
            $this->session->set_flashdata('msg', 'Email Templates Active Successfully');
        }
        if ($data[0]['status'] == "inactive") {
            $this->session->set_flashdata('msg', 'Email Templates InActive Successfully');
        }
        redirect(base_url() . 'settings/email_template_list');

    }

    public function seller()
    {
        $data = array();
        $data['small_title'] = 'Seller Charges';
        $data['current_page_seller_settings'] = 'current-page';
        $data['formaction_path'] = 'save_charges';
        $data['commissions_info'] = $this->db->get_where('seller_charges', ['user_type' => 'seller'])->result_array();
        // print($data['commissions_info']);die('hh');
        $this->template->load_admin('settings/seller/seller_charges', $data);
    }

    public function buyer()
    {
        $data = array();
        $data['small_title'] = 'Buyer Charges';
        $data['formaction_path'] = 'save_charges';
        $data['current_page_buyer_settings'] = 'current-page';
        $data['commissions_info'] = $this->db->get_where('seller_charges', ['user_type' => 'buyer'])->result_array();
        $this->template->load_admin('settings/buyer/buyer_charges', $data);
    }


    // Add a new Category
    public function save_charges()
    {
        // die('kkkk');
        $data = array();
        $data['small_title'] = 'Add Charges';
        $data['formaction_path'] = 'save_charges';
        $user_type = $this->input->get('type');
        if ($user_type == 'buyer') {
            $data['current_page_buyer_settings'] = 'current-page';
        }
        if ($user_type == 'seller') {
            $data['current_page_seller_settings'] = 'current-page';
        }
        $data['current_page_category'] = 'current-page';
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $rules = array(
                array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'arabic_title',
                    'label' => 'Arabic Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'commission',
                    'label' => 'Charges',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'type',
                    'label' => 'Type',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required')
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                //echo json_encode(array('error' => true, 'message' => validation_errors()));
                $this->session->set_flashdata('error', validation_errors());
                if ($user_type == 'seller') {
                    $this->template->load_admin('settings/seller/charges_form', $data);
                } else {
                    $this->template->load_admin('settings/buyer/charges_form', $data);
                }
            } else {
                $posted_data = array(
                    'description' => $this->input->post('description'),
                    'status' => $this->input->post('status'),
                    'type' => $this->input->post('type'),
                    'user_type' => $user_type,
                    'apply_vat' => $this->input->post('apply_vat'),
                    'commission' => $this->input->post('commission'),
                    'created_on' => date('Y-m-d H:i:s'),
                    'created_by' => $this->loginUser->id
                );
                $result = $this->input->post();
                $title = [
                    'english' => $result['title'],
                    'arabic' => $result['arabic_title'],
                ];
                unset($result['title']);
                unset($result['arabic_title']);
                $posted_data['title'] = json_encode($title);
                if ($user_type == 'buyer') {
                    $insert_data = $this->db->insert('seller_charges', $posted_data);
                    if ($insert_data) {
                        $this->session->set_flashdata('msg', 'Buyer commission added 
                        successfully.');
                        echo json_encode(array('error' => false, 'message' => 'success'));
                        // redirect(base_url('settings/buyer'));
                        exit();
                    }

                }
                if ($user_type == 'seller') {
                    $insert_data = $this->db->insert('seller_charges', $posted_data);
                    if ($insert_data) {
                        $this->session->set_flashdata('msg', 'Seller commission added successfully.');
                        echo json_encode(array('error' => false, 'message' => 'success'));
                        // redirect(base_url('settings/seller'));
                        exit();
                    }

                }
                // if (!empty($insert_data)) {
                //     $this->session->set_flashdata('msg', 'Seller commission added successfully.');
                //     redirect(base_url('settings/seller'));
                //     exit();
                //     $msg = 'success';
                //     echo json_encode(array('error' => false, 'message' => $insert_data));
                // }else{
                //     $this->session->set_flashdata('msg', 'Not created there was some error.');
                //     redirect(base_url('settings/seller'));
                //     exit();
                //     $msg = 'Not created there was some error.';
                //     echo json_encode(array('error' => true, 'message' => $msg));
                // }
            }
        } else {
            if ($user_type == 'seller') {
                $this->template->load_admin('settings/seller/charges_form', $data);
            } else {
                $this->template->load_admin('settings/buyer/charges_form', $data);
            }
        }

    }

    // Update a Category
    public function update_charges()
    {
        $data = array();
        $data['small_title'] = 'Update';
        $data['current_page_category'] = 'current-page';
        $data['formaction_path'] = 'update_charges';
        $commission_id = $this->uri->segment(3);
        $user_type = $_GET['type'];
        if ($user_type == 'buyer') {
            $data['current_page_buyer_settings'] = 'current-page';
        }
        if ($user_type == 'seller') {
            $data['current_page_seller_settings'] = 'current-page';
        }
        $data['charges_info'] = $this->db->get_where('seller_charges', ['id' => $commission_id])->row_array();
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $rules = array(
                array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'commission',
                    'label' => 'Charges',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'type',
                    'label' => 'Type',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'));
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'message' => validation_errors()));
                exit();
            } else {
                $id = $this->input->post('id');
                $posted_data = array(
                    'description' => $this->input->post('description'),
                    // 'title' => $this->input->post('title'),
                    'status' => $this->input->post('status'),
                    'type' => $this->input->post('type'),
                    'user_type' => $user_type,
                    'apply_vat' => $this->input->post('apply_vat'),
                    'commission' => $this->input->post('commission'),
                    'updated_by' => $this->loginUser->id
                );
                $result = $this->input->post();
                $title = [
                    'english' => $result['title'],
                    'arabic' => $result['arabic_title'],
                ];
                unset($result['title']);
                unset($result['arabic_title']);
                $posted_data['title'] = json_encode($title);

                $result = $this->db->update('seller_charges', $posted_data, ['id' => $id]);
                if (!empty($result)) {
                    $msg = 'success';
                    $this->session->set_flashdata('msg', 'Commission has been updated successfully');
                    echo json_encode(array('error' => false, 'message' => 'success'));
                    exit();
                } else {
                    $msg = 'Not created there was some error.';
                    echo json_encode(array('error' => true, 'message' => $msg));
                    exit();
                }
            }
        } else {
            if ($user_type == 'seller') {
                $this->template->load_admin('settings/seller/charges_form', $data);
            } else {
                $this->template->load_admin('settings/buyer/charges_form', $data);
            }
        }

    }

    public function bankDetail()
    {
        $data = array();
        $data['small_title'] = 'Bank Details';
        $data['formaction_path'] = 'bankDetail';
        $data['current_page_bankDetail'] = 'current-page';
        $data['user'] = $this->db->get('bank_info')->result_array();
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $posted_data = $this->input->post();
            $rules = array(
                array(
                    'field' => 'ac_name',
                    'label' => 'account name',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'bank_name',
                    'label' => 'bank name',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'iban',
                    'label' => 'linkedin',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'ac_number',
                    'label' => 'account number',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'swift_code',
                    'label' => 'swift code',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'routing_number',
                    'label' => 'routing number',
                    'rules' => 'trim|required')

            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('error' => true, 'msg' => validation_errors()));
            } else {
                $posted_data['created_on'] = date('Y-m-d h:i:s');
                $result_ = $this->db->insert('bank_info', $posted_data);
                $this->session->set_flashdata('msg', 'Bank details has been Insert successfully.');
                echo json_encode(array('error' => false, 'msg' => 'Bank details has been Insert successfully.'));
                redirect('settings/bankDetail');
                exit();
            }
        } else {
            $this->template->load_admin('settings/bank_info', $data);
        }

    }

    public function remove_bankDetail()
    {
        $id = $this->input->post('id');
        $table = $this->input->post('obj');
        if (!empty($id)) {
            $result = $this->db->delete($table, ['id' => $id]);
            if ($result) {
                echo $return = '{"type":"success","msg":"Action Succeded"}';
            } else {
                echo $return = '{"type":"error","msg":"Something went wrong."}';
            }
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }


}

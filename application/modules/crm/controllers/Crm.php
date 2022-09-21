<?php defined('BASEPATH') or exit('No direct script access allowed');
class Crm extends Loggedin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('admin/Common_Model', 'common_model');
        $this->load->model('email/Email_model','email_model');
        $this->load->model('Crm_model', 'crm_model');
        $this->load->model('user/Users_model', 'users_model');
        $this->load->model('settings/Setting_model', 'settings_model');
        $this->load->library('email');
    }

    public function index()
    {
        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'CRM';
        $data['current_page_crm'] = 'current-page';
        $data['crm_list'] = $this->crm_model->crm_list();
        $this->template->load_admin('crm/crm_list', $data);
    }
   

    public function crmList()
    {
        $posted_data = $this->input->post();
        ## Read value
        $draw = $posted_data['draw'];
        $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
        $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 10; // Rows display per page
        $columnIndex = $posted_data['order'][0]['column']; // Column index
        $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
        $searchValue = $posted_data['search']['value']; // Search value
         ## Search 
        $search_arr = array();
        $searchQuery = "";
        if($searchValue != ''){
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'"; // Search value
            $search_arr[] = " crm_detail.name like {$searchValue}";
            $search_arr[] = " crm_detail.email like {$searchValue}";
            $search_arr[] = " crm_customer_type.title like {$searchValue}";
            $search_arr[] = " users.fname like {$searchValue}";
            $search_arr[] = " users.lname like {$searchValue}";
            $search_arr[] = " us.fname like {$searchValue}";
            $search_arr[] = " us.lname like {$searchValue}";
        }
        if(count($search_arr) > 0){
            $searchQuery = implode(" OR ",$search_arr);
        }
            ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('crm_detail')->result();
        $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
        $this->db->select('crm_detail.*,us.fname as assigned_by_fname,us.lname as assigned_by_lname,users.fname,users.lname,crm_customer_type.title as customer_type,crm_lead_source.title as lead_source_name,crm_lead_category.title as lead_category_name');
        $this->db->from('crm_detail');
        $this->db->join('crm_customer_type', 'crm_detail.customer_type_id = crm_customer_type.id', 'left');
        $this->db->join('crm_lead_source', 'crm_detail.lead_source_id = crm_lead_source.id', 'left');
        $this->db->join('crm_lead_category', 'crm_detail.lead_category_id = crm_lead_category.id', 'left');
        $this->db->join('users', 'crm_detail.assigned_to = users.id', 'left');
        $this->db->join('users us', 'crm_detail.assigned_by = us.id', 'left');
        
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $query = $this->db->get();
        $records = $query->result_array();
        $totalRecordwithFilter = (isset($records) && !empty($records)) ? count($records) : 0;
        ## Fetch records
        $this->db->select('crm_detail.*,us.fname as assigned_by_fname,us.lname as assigned_by_lname,users.fname,users.lname,crm_customer_type.title as customer_type,crm_lead_source.title as lead_source_name,crm_lead_category.title as lead_category_name');
        $this->db->from('crm_detail');
        $this->db->join('crm_customer_type', 'crm_detail.customer_type_id = crm_customer_type.id', 'left');
        $this->db->join('crm_lead_source', 'crm_detail.lead_source_id = crm_lead_source.id', 'left');
        $this->db->join('crm_lead_category', 'crm_detail.lead_category_id = crm_lead_category.id', 'left');
        $this->db->join('users', 'crm_detail.assigned_to = users.id', 'left');
        $this->db->join('users us', 'crm_detail.assigned_by = us.id', 'left');

        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $query = $this->db->get();
        $records = $query->result();
        $data = array();
        $have_documents = false;
        foreach($records as $record ){
            $users_by = $this->users_model->get_userName_by_id($record->assigned_by);
            if($record->final_status == 'won'){
              $status_final = 'green';
            }elseif($record->final_status == 'lost'){
              $status_final = 'red';
            }else{
              $status_final = 'blue yellow';
            }
            $action = '';
            if(isset($record->user_status) && $record->user_status == 'immature'){
                $action .= '<button onclick="matureStatus(this)" type="button" id="'.$record->id.'" data-toggle="modal" data-target=".bs-example-modal-sm2" class="btn btn-info btn-xs oz_assignto_model2"><i class="fa fa-pencil-square-o"></i> Mature status</button>';
            }
            $action .= '<button onclick="get_assign_to(this)"  type="button" id="'.$record->id.'" data-toggle="modal" data-target=".bs-example-modal-sm" class="btn btn-info btn-xs oz_assignto_model"><i class="fa fa-pencil-square-o"></i> Assign</button>';
            $action .= ' <a href="'.base_url().'crm/update_crm/'.$record->id.'" class="btn btn-info btn-xs" title="Edit"><i class="fa fa-pencil"></i> </a>';
            $action .= ' <button onclick="deleteRecord(this)" type="button" data-obj="crm_detail" data-token-name="'.$this->security->get_csrf_token_name().'" data-token-value="'.$this->security->get_csrf_hash().'" data-id="'.$record->id.'" data-url="'.base_url().'crm/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> </button>';
            $data[] = array( 
                "id" => $record->id,
                "crm_status" => '<i class="fa-2x fa fa-square '.$status_final.'">',
                "name"=> $record->name,
                "date"=> date('Y-m-d',strtotime($record->date)),
                "updated_on"=> date('Y-m-d',strtotime($record->updated_on)),
                "email"=>$record->email,
                "customer_type"=>$record->customer_type,
                "assigned_by"=>$record->assigned_by_fname.' '.$record->assigned_by_lname,
                "assigned_to"=>$record->fname.' '.$record->lname,
                "action"=> $action
            ); 
        }
            ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );
        echo json_encode($response);    
    }

    public function reports()
    {
        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Reports';
         $data['formaction_path'] = 'filter_report';
         $data['current_page_reports'] = 'current-page';
        $data['crm_list'] = $this->crm_model->crm_list();
        $data['customer_type_list'] = $this->crm_model->customer_type_list_active();
        $data['assigned_to_list'] = $this->users_model->get_all_sales_user();
        $this->template->load_admin('crm/reports/report_list', $data);
    }

    public function filterCRMReport()
    {
         $posted_data = $this->input->post();
        ## Read value
        $draw = $posted_data['draw'];
        $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
        $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 10; // Rows display per page
        $columnIndex = $posted_data['order'][0]['column']; // Column index
        $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
        $searchValue = $posted_data['search']['value']; // Search value
         ## Search 
        $search_arr = array();
        $searchQuery = "";
        if($searchValue != ''){
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $search_arr[] = " crm_detail.name like {$searchValue}";
            $search_arr[] = " crm_detail.email like {$searchValue}";
            $search_arr[] = " crm_customer_type.title like {$searchValue}";
            $search_arr[] = " users.fname like {$searchValue}";
            $search_arr[] = " users.lname like {$searchValue}";
            $search_arr[] = " us.fname like {$searchValue}";
            $search_arr[] = " us.lname like {$searchValue}";
        }

          if (isset($posted_data['customer_type_id']) && !empty($posted_data['customer_type_id'])) {
            $customer_id = implode(",",$posted_data['customer_type_id']);
            $search_arr[] = " crm_detail.customer_type_id IN ($customer_id) ";
        }

        if (isset($posted_data['assigned_to']) && !empty($posted_data['assigned_to'])) {
            $assigned_to = implode(",",$posted_data['assigned_to']);
            $search_arr[] = " crm_detail.assigned_to IN ($assigned_to) ";
        }
        
        if(isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {
            $start_date = $posted_data['datefrom'];
            $end_date = $posted_data['dateto'];
            $search_arr[] = " (DATE(crm_detail.date) between '".$start_date."' and '".$end_date."') ";
        }

        if(count($search_arr) > 0){
            $searchQuery = implode(" OR ",$search_arr);
        }
            ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('crm_detail')->result();
        $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
        $this->db->select('crm_detail.*,us.fname as assigned_by_fname,us.lname as assigned_by_lname,users.fname,users.lname,crm_customer_type.title as customer_type,crm_lead_source.title as lead_source_name,crm_lead_category.title as lead_category_name');
        $this->db->from('crm_detail');
        $this->db->join('crm_customer_type', 'crm_detail.customer_type_id = crm_customer_type.id', 'left');
        $this->db->join('crm_lead_source', 'crm_detail.lead_source_id = crm_lead_source.id', 'left');
        $this->db->join('crm_lead_category', 'crm_detail.lead_category_id = crm_lead_category.id', 'left');
        $this->db->join('users', 'crm_detail.assigned_to = users.id', 'left');
        $this->db->join('users us', 'crm_detail.assigned_by = us.id', 'left');
        
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $query = $this->db->get();
        $records = $query->result_array();
        $totalRecordwithFilter = (isset($records) && !empty($records)) ? count($records) : 0;
        ## Fetch records
        $this->db->select('crm_detail.*,us.fname as assigned_by_fname,us.lname as assigned_by_lname,users.fname,users.lname,crm_customer_type.title as customer_type,crm_lead_source.title as lead_source_name,crm_lead_category.title as lead_category_name');
        $this->db->from('crm_detail');
        $this->db->join('crm_customer_type', 'crm_detail.customer_type_id = crm_customer_type.id', 'left');
        $this->db->join('crm_lead_source', 'crm_detail.lead_source_id = crm_lead_source.id', 'left');
        $this->db->join('crm_lead_category', 'crm_detail.lead_category_id = crm_lead_category.id', 'left');
        $this->db->join('users', 'crm_detail.assigned_to = users.id', 'left');
        $this->db->join('users us', 'crm_detail.assigned_by = us.id', 'left');

        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $query = $this->db->get();
        $records = $query->result();

        $data = array();
        $i=0;
        foreach($records as $record ){
            $i++;
            $data[] = array( 
                "id" => $record->id,
                "number" => $i,
                "name"=> $record->name,
                "date"=> date('Y-m-d',strtotime($record->date)),
                "updated_on"=> date('Y-m-d',strtotime($record->updated_on)),
                "email"=>$record->email,
                "customer_type"=>$record->customer_type,
                "assigned_by"=>$record->assigned_by_fname.' '.$record->assigned_by_lname,
                "assigned_to"=>$record->fname.' '.$record->lname
            ); 
        }
            ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );
        echo json_encode($response);    
    }
    public function filter_report()
    {
        $data = array();
        if($this->input->post())
        {
            $posted_data = $this->input->post();
            unset($sql);
        if (isset($posted_data['customer_type_id']) && !empty($posted_data['customer_type_id'])) {
            $customer_id = implode(",",$posted_data['customer_type_id']);
            $sql[] = " crm_detail.customer_type_id IN ($customer_id) ";
        }

        if (isset($posted_data['assigned_to']) && !empty($posted_data['assigned_to'])) {
            $assigned_to = implode(",",$posted_data['assigned_to']);
            $sql[] = " crm_detail.assigned_to IN ($assigned_to) ";
        }

        if (isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {
            $start_date = $posted_data['datefrom'];
            $end_date = $posted_data['dateto'];
            $sql[] = " (DATE(crm_detail.created_on) between '$start_date' and '$end_date') ";
        }
            $query = "";
        if (!empty($sql)) {
            $query .= ' ' . implode(' OR ', $sql);
        }

            $data['crm_list'] = $this->crm_model->crm_filter_list($query);
            $data_view = $this->load->view('crm/reports/filter_data', $data, true);
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found'));
        }
    }

    public function get_column_names($table)
    {
        $sql = 'DESCRIBE ' . $table;
        $result = $this->db->query($sql);
        $result = $result->result_array();
        $rows = array();
        foreach ($result as $row) {
            $rows[] = $row['Field'];
        }

        return $rows;
    }

    public function get_multiArray_search($products, $field, $value)
    {
       foreach($products as $key => $product)
       {
          if ( $product[$field] === $value )
             return $key;
       }
       return false;
    }

    public function get_assign_to()
    {
        $data = array();
        $data['crm_info'] = $this->input->post('id');
        $data['crm_list'] = $this->crm_model->crm_list($data['crm_info']);
        $data['assigned_to_list'] = $this->users_model->get_all_sales_user();
        $data_view = $this->load->view('crm/assign_to', $data, true);
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    }

    public function get_user_info_form()
    {
        $data = array();
        $data['crm_info'] = $this->input->post('id');

        $data['crm_list'] = $this->crm_model->crm_list($data['crm_info']);
        $user_by_crm = $this->users_model->get_user_by_crm_id($this->input->post('id'));
        $data['formaction_path'] = 'save_crm_user';
        if(isset($user_by_crm) && !empty($user_by_crm['0']['crm_id'])){
        $data['all_users'] = $this->users_model->get_user_by_id($user_by_crm['0']['id']);
            
        }

        // $role_id = $this->loginUser->id;
        $data['all_roles'] = $this->users_model->get_all_user_roles(); 
        // print_r($data['all_roles']);
        if(isset($user_by_crm['0']['role']) && !empty($user_by_crm['0']['role']))
        {
        $data['get_users_list_by_id'] = $this->users_model->get_users_list_by_id($user_by_crm['0']['role']);
        // print_r($data['get_users_list_by_id']);
        }
        else
        {
            $data['get_users_list_by_id'] = array();
        }

        $sales_person_id = 3;
        $data['sales_person'] = $this->users_model->users_list($sales_person_id); 
        $data_view = $this->load->view('crm/crm_user_form', $data, true);
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
        
    }
   
    public function save_crm_user()
    {
       $this->load->library('form_validation');
       $id = $this->input->post('user_id');
       $msg_txt = 'Successfully added';
       // print_r($id);die();
        $data['all_users'] = $this->users_model->users_list($id);
        if($id){
            $all_users = $this->users_model->users_list($id);
        }
            
        if ($this->input->post()) {
            // $items_documents_data = $this->session->userdata('items_documents');
            // $this->session->unset_userdata('items_documents');
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
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'trim|required|valid_email',
                ),
            );

            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
                exit();
            } else {
                $data = array(
                    'crm_id' => $this->input->post('crm_id'),
                    'fname' => $this->input->post('fname'),
                    'lname' => $this->input->post('lname'),
                    'username' =>$this->input->post('fname').' '.$this->input->post('lname'),
                    'city' => $this->input->post('city'),
                    'email' => $this->input->post('email'),
                    'type' => $this->input->post('type'),
                    'role' => $this->input->post('role_id'),
                    'address' => $this->input->post('address'),
                    'id_number' => $this->input->post('id_number'),
                    'po_box' => $this->input->post('po_box'),
                    'job_title' =>$this->input->post('job_title'),

                );

                $data['code'] = $this->getNumber(4);
                $data['email_verification_code'] = $this->getNumber(4);
              
                if($this->input->post('sales_person')){
                    $data['sales_id'] = $this->input->post('sales_person');
                }

                if($this->input->post('id_number')){
                    $data['id_number'] = $this->input->post('id_number');
                }
                if($this->input->post('po_box')){
                    $data['po_box'] = $this->input->post('po_box');
                }
                if($this->input->post('id_number')){
                    // $data['fax'] =$this->input->post('fax');
                }
                if($this->input->post('id_number')){
                    $data['job_title'] =$this->input->post('job_title');     
                }

                if($this->input->post('vet_number')){
                    $data['vet_number'] =$this->input->post('vet_number');     
                }
                if($this->input->post('company_name')){
                    $data['company_name'] =$this->input->post('company_name');     
                }
                if($this->input->post('remarks')){
                    $data['remarks'] =$this->input->post('remarks');     
                }
                if($this->input->post('description')){
                    $data['description'] =$this->input->post('description');     
                }
                if($this->input->post('vat')){
                    $data['vat'] =$this->input->post('vat');     
                }
                if($this->input->post('vat_number')){
                    $data['vat_number'] =$this->input->post('vat_number');     
                }
                if($this->input->post('prefered_language')){
                    $data['prefered_language'] =$this->input->post('prefered_language');     
                }
                 if($this->input->post('social')){
                    $data['social'] =$this->input->post('social');     
                }
                if($this->input->post('mobile')){
                    $data['mobile'] = $this->input->post('mobile');     
                }
                if($this->input->post('reg_type')){
                    $data['reg_type'] =$this->input->post('reg_type');     
                }
                if(!empty($this->input->post('buyer_commission')))
                {
                    $data['buyer_commission'] =$this->input->post('buyer_commission');     
                }
                if(!empty($this->input->post('buyer_commission'))) {
                     // $data['buyer_commission'] = $this->settings_model->get_setting_row()['buyer_comission'];
                }
                // If profile picture is selected
                $id = $this->input->post('user_id');

                if ($id) {
                    $data_for_delete_files = $this->users_model->users_listing($id); // fetch data for delete fles from folder
                    $get_file_name = $this->db->get_where('files',['id' => $data_for_delete_files[0]['picture']])->row_array();
                    if (isset($get_file_name) && !empty($get_file_name['name'])) {
                        $old_data = FCPATH .'uploads/profile_picture/'.$id.'/'.$get_file_name['name'];
                    }
                }

                $files = '';
                $path = "uploads/profile_picture/".$id."/";
                // make path
                if ( !is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                }
                $config['upload_path'] = $path;
                $config['allowed_types'] = 'ico|png|jpg|jpeg';
                // if(count($_FILES)>0){
                if(count($_FILES)>0){
                    // $files .= $this->uploadFiles($_FILES);
                    $profile_pic_array = $this->files_model->upload('profile_picture', $config);
                    $files = implode(',', $profile_pic_array);
                }else {
                    return false;
                }

                if(isset($data_for_delete_files[0]['picture']) && !empty($data_for_delete_files[0]['picture']) && $files!='' && !empty($id)){
                    unlink($old_data);
                }
                if(isset($_FILES['profile_picture']['name']) && !empty($_FILES['profile_picture']['name']))
                {
                $data['picture'] = $files;

                }

                $id =$this->input->post('user_id');
                if(isset($id) && !empty($id))
                {
                    $q = $this->db->query('select password from users where id ='.$id );
                    $old_pass = $q->result_array();
                }
                else{
                    $old_pass = array();
                }  
                $data['updated_on'] = date('Y-m-d H:i:s');
                $data['status'] = 1;
                // print_r($all_users);die();
                if(isset($all_users) && !empty($all_users))
                {

                if(isset($all_users[0]['crm_id']) && !empty($all_users[0]['crm_id']))
                {
                
                    $crm_id = $all_users[0]['crm_id'];
                    $crm_data = array(
                    'user_status' => 'mature',
                    'updated_by' => $this->loginUser->id

                    );
                    $result_crm = $this->crm_model->update_crm($crm_id,$crm_data);    
                }

                $ran_password = $this->randomPassword();
                $data['password'] = hash("sha256", $this->input->post('password'));

                $user_email = $this->input->post('email');  
                $password = $ran_password;        
                
                $to = $user_email;
                $subject = "Following are your Email and Password ";
                $txt = "Your Email is : ".$user_email."\n\n Your Password is :".$password;

                // $email = $this->input->post('email');
                // $link_activate = base_url() . 'home/verify_email/' . $data['email_verification_code'];
                // $vars = [
                //     '{username}' => $data['fname'],
                //     '{email}' => $data['email'],
                //     '{password}' => $this->input->post('password'),
                //     '{login_link}' => $this->lang->line("please_login") . ':<a href="'.base_url().'?loginUrl=loginFirst" > ' . $this->lang->line("go_to_account") . ' </a>',
                //     // '{email_verification_code}'=>$user['email_verification_code']
                //     '{activation_link}' => $link_activate,
                //     // '{btn_link}' => $link_activate,
                //     '{btn_text}' => $this->lang->line('activate_your_account')
                // ];
                // $send = $this->email_model->email_template($email, 'user_registration', $vars, true);

                $previous_id = $this->crm_model->get_max_id();
                // $data['code'] = sprintf('%08d', $previous_id[0]['max(id)']);
                
                $result = $this->users_model->update_user($this->input->post('post_id'), $data);

                $msg_txt = "CRM User Updated Successfully";
                }
                else
                {

                    if ($this->input->post('mobile')) {
                        //SMS verification process start
                        $this->load->library('SendSmart');
                        $sms = $this->lang->line('pioneer_verification_code_is') . $mobile_verification_code;
                        $number = $this->input->post('mobile');
                        $number2 = '971561387755';
                        $sms_response = $this->sendsmart->sms($number, $sms);
                    }

                    $email = $this->input->post('email');
                    $link_activate = base_url() . 'home/verify_email/' . $data['email_verification_code'];
                    $vars = [
                        '{username}' => $data['fname'],
                        '{email}' => $data['email'],
                        '{password}' => $this->input->post('password'),
                        '{login_link}' => $this->lang->line("please_login") . ':<a href="'.base_url().'?loginUrl=loginFirst" > ' . $this->lang->line("go_to_account") . ' </a>',
                        // '{email_verification_code}'=>$user['email_verification_code']
                        '{activation_link}' => $link_activate,
                        // '{btn_link}' => $link_activate,
                        '{btn_text}' => $this->lang->line('activate_your_account')
                    ];
                    $send = $this->email_model->email_template($email, 'user_registration', $vars, true);
                    if($this->input->post('sales_person'))
                    {
                        // $sales_person_id = $this->input->post('sales_person');

                        // $query_sales_person_email = $this->db->query('select email from users where id ='.$sales_person_id);
                        // $sales_person_email = $query_sales_person_email->result_array();
                        // $to = $sales_person_email[0]['email'];

                        // $q = $this->db->query('select * from crm_email_template where slug = "user_registration"');
                        // $email_message = $q->result_array();

                        // $email_messagee = str_replace("{username}", $cust_name,$email_message[0]['body']);
                        // $email_messageee = str_replace("{password}",$password, $email_messagee );

                        // $this->email->to($to);
                        // $this->email->subject($email_message[0]['subject']);
                        // $this->email->message($email_messageee);
                        // $this->email->send();
                    }

                    // $previous_id = $this->users_model->insert_user_details();
                    // $data['code'] = sprintf('%08d', $previous_id[0]['max(id)']);
                    $result = $this->users_model->insert_user_details($data);
                    $msg_txt = "CRM User added Successfully";
                }
                 

                if (!empty($result)) {
                    if (!empty($result)) {
                        if($this->input->post('role_id')==4){
                        $this->session->set_flashdata('msg', $msg_txt);
                        $redict_users_list = "user_listing";
                        echo json_encode(array('msg'=>$redict_users_list,'mid'=>$result));
                        exit();
                        }
                        else{
                        $this->session->set_flashdata('msg', $msg_txt);
                         $redirect_apllication_user = "apllication_user";
                        echo json_encode(array('msg'=>$redirect_apllication_user,'mid'=>$result));
                        }
                    }
                  
                }
            }
        }
    }

    public function update_assign_to()
    {
      $row = array();
      $id = $this->input->post('id');
      $row['assigned_to'] = $this->input->post('assigned_to');
      $row['updated_by'] = $this->loginUser->id;
      $result = $this->crm_model->update_crm($id,$row);
      if($result)
      {
        $this->session->set_flashdata('msg', 'CRM Info Updated Successfully');
        echo json_encode(array( 'msg'=>'success', 'data' => 'Updated Successfully'));
      }
      else
      {
        echo json_encode(array( 'msg'=>'error', 'data' => 'Error Found'));
      }
    }

    public function save_form()
    {
        $data = $_REQUEST['formData'];
        $cat_id = $_REQUEST['category_id'];
        // get table fields
        //$col_names =  $this->get_column_names("item_category_fields");
        foreach ($data as $row) {
        if(($row['type']) == 'checkbox-group')
        {
            $row['values'] = json_encode($row['values']);    
        }
         if(($row['type']) == 'autocomplete')
        {
            $row['values'] = json_encode($row['values']);    
        }
        if(($row['type']) == 'radio-group')
        {
            $row['values'] = json_encode($row['values']);    
        }
        if(($row['type']) == 'select')
        {
            $row['values'] = json_encode($row['values']);    
        }
         $row['category_id'] = $cat_id;   
         $row['created_by'] = $this->loginUser->id;
         $row['updated_by'] = $this->loginUser->id;
         $result=$this->db->insert("item_category_fields",$row);

        }
     }

    public function update_form()
    {
        $data = $_REQUEST['formData'];
        // print_r($data);die();
        $cat_id = $_REQUEST['category_id'];
        // get table fields
        //$col_names =  $this->get_column_names("item_category_fields");
        foreach ($data as $row) {
        if(($row['type']) == 'checkbox-group')
        {
            $row['values'] = json_encode($row['values']);    
        }
         if(($row['type']) == 'autocomplete')
        {
            $row['values'] = json_encode($row['values']);    
        }
        if(($row['type']) == 'radio-group')
        {
            $row['values'] = json_encode($row['values']);    
        }
        if(($row['type']) == 'select')
        {
            $row['values'] = json_encode($row['values']);    
        }
         $row['category_id'] = $cat_id;   
         $row['created_by'] = $this->loginUser->id;
         $row['updated_by'] = $this->loginUser->id;
         $result=$this->db->update("item_category_fields",$row);

        }
     }
 

       public function category_fields($cat_id)
    {
        if($cat_id!=""){
            $datafields = $this->crm_model->fields_data($cat_id);
            //print_r($data);
            //$data['fields_data'] = array();
            $fdata = array();
            foreach ($datafields as $fields) {
                $fields['values'] = json_decode($fields['values'],true);   
                $fdata[] = $fields;
            }
         $data['category_id'] = $cat_id;   
         $data['fields_data'] = $fdata;
         $this->template->load_admin('crm/form_build', $data);    
        }
        
    }

    public function crm_sales_list(){
        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'CRM';
        $data['current_page_sales_list'] = 'current-page';
        $data['crm_sales_list'] = $this->crm_model->crm_sales_list($id);
        $this->template->load_admin('crm/crm_sales_list', $data);
    }
    public function crmSaleList()
    {
        $posted_data = $this->input->post();
        $id = $this->loginUser->id;
        ## Read value
        $draw = $posted_data['draw'];
        $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
        $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 10; // Rows display per page
        $columnIndex = $posted_data['order'][0]['column']; // Column index
        $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
        $searchValue = $posted_data['search']['value']; // Search value
         ## Search 
        $search_arr = array();
        $searchQuery = "";
        if($searchValue != ''){
            $searchValue = "'%".$this->db->escape_like_str($searchValue)."%' ESCAPE '!'";
            $search_arr[] = " crm_detail.name like {$searchValue}";
            $search_arr[] = " crm_detail.email like {$searchValue}";
            $search_arr[] = " crm_customer_type.title like {$searchValue}";
            $search_arr[] = " crm_lead_source.title like {$searchValue}";
            $search_arr[] = " crm_lead_category.title like {$searchValue}";
            $search_arr[] = " us.fname like {$searchValue}";
            $search_arr[] = " us.lname like {$searchValue}";
        }
        if(count($search_arr) > 0){
            $searchQuery = " ( ".implode(" OR ",$search_arr)." ) ";
        }
            ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->where('crm_detail.assigned_to', $id);
        $records = $this->db->get('crm_detail')->result();
        $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
        $this->db->select('crm_detail.*,us.fname as assigned_by_fname,us.lname as assigned_by_lname,users.fname,users.lname,crm_customer_type.title as customer_type,crm_lead_source.title as lead_source_name,crm_lead_category.title as lead_category_name');
        $this->db->from('crm_detail');
        $this->db->join('crm_customer_type', 'crm_detail.customer_type_id = crm_customer_type.id', 'left');
        $this->db->join('crm_lead_source', 'crm_detail.lead_source_id = crm_lead_source.id', 'left');
        $this->db->join('crm_lead_category', 'crm_detail.lead_category_id = crm_lead_category.id', 'left');
        $this->db->join('users', 'crm_detail.assigned_to = users.id', 'left');
        $this->db->join('users us', 'crm_detail.assigned_by = us.id', 'left');
        
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->where('crm_detail.assigned_to', $id);
        $query = $this->db->get();
        $records = $query->result_array();
        $totalRecordwithFilter = (isset($records) && !empty($records)) ? count($records) : 0;
        ## Fetch records
        $this->db->select('crm_detail.*,us.fname as assigned_by_fname,us.lname as assigned_by_lname,users.fname,users.lname,crm_customer_type.title as customer_type,crm_lead_source.title as lead_source_name,crm_lead_category.title as lead_category_name');
        $this->db->from('crm_detail');
        $this->db->join('crm_customer_type', 'crm_detail.customer_type_id = crm_customer_type.id', 'left');
        $this->db->join('crm_lead_source', 'crm_detail.lead_source_id = crm_lead_source.id', 'left');
        $this->db->join('crm_lead_category', 'crm_detail.lead_category_id = crm_lead_category.id', 'left');
        $this->db->join('users', 'crm_detail.assigned_to = users.id', 'left');
        $this->db->join('users us', 'crm_detail.assigned_by = us.id', 'left');

        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->where('crm_detail.assigned_to', $id);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $query = $this->db->get();
        $records = $query->result();
        $data = array();
        $have_documents = false;
        foreach($records as $record ){
            $users_by = $this->users_model->get_userName_by_id($record->assigned_by);
            if($record->final_status == 'won'){
              $status_final = 'green';
            }elseif($record->final_status == 'lost'){
              $status_final = 'red';
            }else{
              $status_final = 'blue yellow';
            }
            $action = '';  
            $action .= ' <a href="'.base_url().'crm/update_crm_sales/'.$record->id.'" class="btn btn-info btn-xs" title="Edit"><i class="fa fa-pencil"></i> </a>';
            $action .= ' <button onclick="deleteRecord(this)" type="button" data-obj="crm_detail" data-token-name="'.$this->security->get_csrf_token_name().'" data-token-value="'.$this->security->get_csrf_hash().'" data-id="'.$record->id.'" data-url="'.base_url().'crm/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> </button>';

            $data[] = array( 
                "id" => $record->id,
                "name"=> $record->name,
                "date"=> date('Y-m-d',strtotime($record->date)),
                "updated_on"=> date('Y-m-d',strtotime($record->updated_on)),
                "email"=>$record->email,
                "customer_type"=>$record->customer_type,
                "assigned_by"=>$record->assigned_by_fname.' '.$record->assigned_by_lname,
                "lead_source_name"=>$record->lead_source_name,
                "lead_category_name"=>$record->lead_category_name,
                "crm_status" => '<i class="fa-2x fa fa-square '.$status_final.'">',
                "action"=> $action
            ); 
        }
            ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );
        echo json_encode($response);       
    }
   
    // Add a new Customer
    public function crm_form()
    {
        $data = array();
        $data['small_title'] = 'Add CRM Detail';
        $data['current_page_sales_list'] = 'current-page';
        $data['formaction_path'] = 'crm_form';
        $data['customer_type_list'] = $this->crm_model->customer_type_list_active();
        $data['lead_source_list'] = $this->crm_model->lead_source_list_active();
        $data['lead_category_list'] = $this->crm_model->lead_category_list_active();
        $data['lead_stage_list'] = $this->crm_model->lead_stage_list_active();
        $data['next_step_list'] = $this->crm_model->next_step_list_active();
        $data['assigned_to_list'] = $this->users_model->get_all_sales_user();
        $data['assigned_by_list'] = $this->users_model->get_all_user_without_sales();
        $data['loss_reason_list'] = $this->crm_model->loss_reasons_list_active();
        if ($this->input->post()) {
            $this->load->library('form_validation');
               $original_value = $this->users_model->check_unique_email($this->input->post('email'));
            if(count($original_value) > 0) {
                $is_unique =  '|is_unique[users.email]';
            } else {
                $is_unique =  '';
            }

            $original_value_mobile = $this->users_model->check_unique_mobile($this->input->post('mobile'));
            if(count($original_value_mobile) > 0) {
                $is_unique_mobile =  '|is_unique[users.mobile]';
            } else {
                $is_unique_mobile =  '';
            }

            $rules = array(
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'trim|required'.$is_unique),
                array(
                    'field' => 'lead_source_id',
                    'label' => 'Lead Source',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'name',
                    'label' => 'Name',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'mobile',
                    'label' => 'Mobile',
                    'rules' => 'trim|required'.$is_unique_mobile),
                array(
                    'field' => 'interested',
                    'label' => 'Interested',
                    'rules' => 'trim|required')
                );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                echo json_encode(array('msg' => validation_errors()));
            } else {
               
                $posted_data = $this->input->post();
                $posted_data['created_by'] = $this->loginUser->id;
                $posted_data['assigned_by'] = $this->loginUser->id;
                $posted_data['assigned_to'] = $this->loginUser->id;
                $posted_data['created_on'] = date('Y-m-d H:i:s');
                $posted_data['date'] = date('Y-m-d H:i:s');

                if(empty($this->input->post('estimated_reserve_price')))
                {
                $posted_data['estimated_reserve_price'] = NULL;
                }
                else
                {
                $posted_data['estimated_reserve_price'] = $this->input->post('estimated_reserve_price');
                }
                if(empty($this->input->post('estimated_commission')))
                {
                $posted_data['estimated_commission'] = NULL;
                }
                else
                {
                $posted_data['estimated_commission'] = $this->input->post('estimated_commission');
                }
                $result = $this->crm_model->insert_crm_details($posted_data);
                if (!empty($result)) {
                    $ran_password = $this->randomPassword();
                    $user_data = array('fname' => $this->input->post('name'),
                                    'email' => $this->input->post('email'),
                                    'mobile' => $this->input->post('mobile'),
                                    'crm_id' => $result,
                                    'status' => 0,
                                    'type' => 'crm',
                                    'role'  =>4,
                                    'crm_status' => 'immature',
                                    'password' => $ran_password,
                                    'created_on' => date('Y-m-d h:i:s'),
                                    'created_by' => $this->loginUser->id

                                );
                    $this->users_model->save_user($user_data);

                    $this->session->set_flashdata('msg', 'CRM Info Added Successfully');
                    $msg = 'success';
                    echo json_encode(array('msg' => $msg, 'in_id' => $result));
                } else {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                }
            }
        } else {


            $this->template->load_admin('crm/crm_sales_form', $data);
        }

    }

    public function update_crm_sales()
    {
        $data = array();
        $data['small_title'] = 'Update';
        $data['current_page_sales_list'] = 'current-page';
        $data['formaction_path'] = 'update_crm_sales/'.$this->uri->segment(3);
        $crm_id = $this->uri->segment(3);
        $data['crm_info'] = $this->crm_model->crm_list($crm_id);
        // print_r($data['crm_info']);
        $data['customer_type_list'] = $this->crm_model->customer_type_list_active();
        $key_searched = $this->get_multiArray_search($data['customer_type_list'],'id',$data['crm_info'][0]['customer_type_id']);
        $data['customer_type_title'] = $data['customer_type_list'][$key_searched]['title'];
       
        $key = array_search($data['crm_info'][0]['customer_type_id'], $data['customer_type_list']);
        $data['lead_source_list'] = $this->crm_model->lead_source_list_active();
        $data['lead_category_list'] = $this->crm_model->lead_category_list_active();
        $data['lead_stage_list'] = $this->crm_model->lead_stage_list_active();
        $data['next_step_list'] = $this->crm_model->next_step_list_active();
        // $data['assigned_to_list'] = $this->users_model->get_all_user_with_role();
        // $data['assigned_by_list'] = $this->users_model->get_all_user_with_role();

        $data['assigned_to_list'] = $this->users_model->get_all_sales_user();
        $data['assigned_by_list'] = $this->users_model->get_all_user_without_sales();
        $data['loss_reason_list'] = $this->crm_model->loss_reasons_list_active();

        if ($this->input->post()) {
            $this->load->library('form_validation');

            $original_value = $this->users_model->check_unique_email($this->input->post('email'));
            if(count($original_value) > 0 && $data['crm_info'][0]['email'] != $this->input->post('email')) {
                $is_unique =  '|is_unique[users.email]';
            } else {
                $is_unique =  '';
            }
             
            $original_value_mobile = $this->users_model->check_unique_mobile($this->input->post('mobile'));
            if(count($original_value_mobile) > 0 && $data['crm_info'][0]['mobile'] != $this->input->post('mobile')) {
                $is_unique_mobile =  '|is_unique[users.mobile]';
            } else {
                $is_unique_mobile =  '';
            }

             $rules = array(
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'trim'.$is_unique),
                array(
                    'field' => 'lead_source_id',
                    'label' => 'Lead Source',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'name',
                    'label' => 'Name',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'mobile',
                    'label' => 'Mobile',
                    'rules' => 'trim|required'.$is_unique_mobile),
                array(
                    'field' => 'interested',
                    'label' => 'Interested',
                    'rules' => 'trim|required')
                );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                echo json_encode(array('msg' => validation_errors()));
            } else {
                $id = $this->input->post('id');
               
                $posted_data = $this->input->post();
                $posted_data['updated_by'] = $this->loginUser->id;
                if(empty($this->input->post('estimated_reserve_price')))
                {
                $posted_data['estimated_reserve_price'] = NULL;
                }
                else
                {
                $posted_data['estimated_reserve_price'] = $this->input->post('estimated_reserve_price');
                }
                if(empty($this->input->post('estimated_commission')))
                {
                $posted_data['estimated_commission'] = NULL;
                }
                else
                {
                $posted_data['estimated_commission'] = $this->input->post('estimated_commission');
                }

                $result = $this->crm_model->update_crm($id, $posted_data);
                if (!empty($result)) {
                    $get_user_id = $this->users_model->get_user_by_crm_id($id);
                    $user_data = array('fname' => $this->input->post('name'),
                                    'email' => $this->input->post('email'),
                                    'mobile' => $this->input->post('mobile'),
                                    'updated_by' => $this->loginUser->id
                                );
                    $this->users_model->update_user($get_user_id[0]['id'], $user_data);
                    $this->session->set_flashdata('msg', 'CRM Sales Info Updated Successfully');
                    $msg = "success";
                    echo json_encode(array("msg" => $msg, "in_id" => $result));

                } else {
                    $msg = "DB Error found.";
                    echo json_encode(array("msg" => $msg, "error" => $result));
                }
            }
        } else {

            $this->template->load_admin('crm/crm_sales_form', $data);
        }

    }

 public function validate_mobile()
    {

        $existed_mobile = $this->uri->segment(3);
        if(!empty($existed_mobile)){
            $check_number = $this->db->get_where('users', ["mobile"=>$_GET['mobile']])->row_array();
            // $check_number = $this->users_model->check_user_number($_GET['mobile']);
            $existed_CRM = $this->db->get_where('users', ["mobile" => $existed_mobile])->row_array();
            // echo '<pre>';
            // print_r($existed_CRM['mobile']);
            // print_r($check_number['mobile']);
            // echo '</pre>';
            if(($existed_CRM) && ($check_number) && ($existed_CRM['mobile'] == $check_number['mobile'])){
                echo '200';
            }else{
                if(isset($check_number['mobile']) && !empty($check_number['mobile'])){
                    echo '404';
                }else{
                    echo '200';
                }    
            }

        }else{
            $check_number = $this->users_model->check_user_number($_GET['mobile']);
        if(isset($check_number) && !empty($check_number)){
            echo '404';
        }else{
            echo '200';
        }
        }
       
    }

    public function validate_email()
    {
        $existed_email = $this->uri->segment(3);
        if(!empty($existed_email)){
            // $check_email = $this->users_model->check_email($_GET['email']);
            $check_email = $this->db->get_where('users', ["email" => $_GET['email']])->row_array();

            $existed_CRM = $this->db->get_where('users', ["email" => urldecode($existed_email)])->row_array();

            if($existed_CRM['email'] == $check_email['email']){
                echo '200';
            }else{
                if(isset($check_email['email']) && !empty($check_email['email'])){
                    echo '404';
                }else{
                    echo '200';
                }    
            }
        }else{
            $check_email = $this->users_model->check_email(urldecode($_GET['email']));
            if(isset($check_email) && !empty($check_email))
            {
                echo '404';
            }
            else
            {
                echo '200';
            }
        }
    }

    // Add a new Customer
    public function save_crm()
    {
        $data = array();
        $data['small_title'] = 'Add';
        $data['formaction_path'] = 'save_crm';
        $data['current_page_crm'] = 'current-page';
        $data['customer_type_list'] = $this->crm_model->customer_type_list_active();
        $data['lead_source_list'] = $this->crm_model->lead_source_list_active();
        $data['lead_category_list'] = $this->crm_model->lead_category_list_active();
        $data['lead_stage_list'] = $this->crm_model->lead_stage_list_active();
        $data['next_step_list'] = $this->crm_model->next_step_list_active();
        $data['assigned_to_list'] = $this->users_model->get_all_sales_user();
        $data['assigned_by_list'] = $this->users_model->get_all_user_sales_managers();
        $data['loss_reason_list'] = $this->crm_model->loss_reasons_list_active();
        if ($this->input->post()) {
            $this->load->library('form_validation');

            $original_value = $this->users_model->check_unique_email($this->input->post('email'));
            if(count($original_value) > 0) {
                $is_unique =  '|is_unique[users.email]';
            } else {
                $is_unique =  '';
            }
            $number = "+".$this->input->post('mobile_code').$this->input->post('mobile');
            $original_value_mobile = $this->users_model->check_unique_mobile($number);
            if(count($original_value_mobile) > 0) {
                $is_unique_mobile =  '|is_unique[users.mobile]';
            } else {
                $is_unique_mobile =  '';
            }

            $rules = array(
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'trim|valid_email'.$is_unique,
                    // 'rules' => 'trim|required|valid_email|is_unique[users.email]|is_unique[crm_detail.email]'
                    ),
                array(
                    'field' => 'lead_source_id',
                    'label' => 'Lead Source',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'name',
                    'label' => 'Name',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'mobile',
                    'label' => 'Mobile',
                    'rules' => 'trim|required'.$is_unique_mobile),
                array(
                    'field' => 'interested',
                    'label' => 'Interested',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'assigned_by',
                    'label' => 'Assigned By',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'assigned_to',
                    'label' => 'Assigned To',
                    'rules' => 'trim|required'),
                );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                echo json_encode(array('msg' => validation_errors()));
            } else {
               
                $posted_data = $this->input->post();
                $posted_data['created_by'] = $this->loginUser->id;
                $posted_data['created_on'] = date('Y-m-d H:i:s');
                $posted_data['date'] = date('Y-m-d H:i:s');
                if(empty($this->input->post('estimated_reserve_price')))
                {
                $posted_data['estimated_reserve_price'] = NULL;
                }
                else
                {
                $posted_data['estimated_reserve_price'] = $this->input->post('estimated_reserve_price');
                }
                if(empty($this->input->post('estimated_commission')))
                {
                $posted_data['estimated_commission'] = NULL;
                }
                else
                {
                $posted_data['estimated_commission'] = $this->input->post('estimated_commission');
                }
                    unset($posted_data['mobile_code']);
                    unset($posted_data['mobile1']);
               
                $result = $this->crm_model->insert_crm_details($posted_data);
                if (!empty($result)) {
                    $ran_password = $this->randomPassword();
                    $user_data = array('fname' => $this->input->post('name'),
                                    'email' => $this->input->post('email'),
                                    'mobile' => "+".$this->input->post('mobile_code').$this->input->post('mobile'),
                                    'crm_id' => $result,
                                    'status' => 0,
                                    'type' => 'crm',
                                    'role' => 4,
                                    'crm_status' => 'immature',
                                    // 'password' => $ran_password,
                                    'created_on' => date('Y-m-d h:i:s'),
                                    'created_by' => $this->loginUser->id

                                );
                    $this->users_model->save_user($user_data);
                    $this->session->set_flashdata('msg', 'CRM Info Added Successfully');
                    $msg = 'success';
                    echo json_encode(array('msg' => $msg, 'in_id' => $result));
                } else {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                }
            }
        } else { 
            $this->template->load_admin('crm/crm_form', $data);
        }

    }

    public function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }


    // Update a Category
    public function update_crm()
    {
        $data = array();
        $data['small_title'] = 'Update';
        $data['current_page_crm'] = 'current-page';
        $data['formaction_path'] = 'update_crm/'.$this->uri->segment(3);
        $crm_id = $this->uri->segment(3);
        $data['crm_info'] = $this->crm_model->crm_list($crm_id);
        $data['customer_type_list'] = $this->crm_model->customer_type_list_active();
        $data['lead_source_list'] = $this->crm_model->lead_source_list_active();
        $data['lead_category_list'] = $this->crm_model->lead_category_list_active();
        $data['lead_stage_list'] = $this->crm_model->lead_stage_list_active();
        $data['next_step_list'] = $this->crm_model->next_step_list_active();
        $data['assigned_to_list'] = $this->users_model->get_all_sales_user();
        $data['assigned_by_list'] = $this->users_model->get_all_user_sales_managers();
        $data['loss_reason_list'] = $this->crm_model->loss_reasons_list_active();

        if ($this->input->post()) {
            $this->load->library('form_validation');
            // print_r($this->input->post());die();
           
            $original_value = $this->users_model->check_unique_email($this->input->post('email'));
            if(count($original_value) > 0 && $data['crm_info'][0]['email'] != $this->input->post('email')) {
                $is_unique =  '|is_unique[users.email]';
            } else {
                $is_unique =  '';
            }

            $original_value_mobile = $this->users_model->check_unique_mobile($this->input->post('mobile'));
            if(count($original_value_mobile) > 0 && $data['crm_info'][0]['mobile'] != $this->input->post('mobile')) {
                $is_unique_mobile =  '|is_unique[users.mobile]';
            } else {
                $is_unique_mobile =  '';
            }

           $rules = array(
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'trim|valid_email'.$is_unique
                ),
                array(
                    'field' => 'lead_source_id',
                    'label' => 'Lead Source',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'name',
                    'label' => 'Name',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'mobile',
                    'label' => 'Mobile',
                    'rules' => 'trim|required'.$is_unique_mobile),
                array(
                    'field' => 'interested',
                    'label' => 'Interested',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'assigned_by',
                    'label' => 'Assigned By',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'assigned_to',
                    'label' => 'Assigned To',
                    'rules' => 'trim|required'),
                );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                echo json_encode(array('msg' => validation_errors()));
            } else {
                $id = $this->input->post('id');
              
                    $posted_data = $this->input->post();
                    $posted_data['updated_by'] = $this->loginUser->id;
                    if(empty($this->input->post('estimated_reserve_price')))
                    {
                    $posted_data['estimated_reserve_price'] = NULL;
                    }
                    else
                    {
                    $posted_data['estimated_reserve_price'] = $this->input->post('estimated_reserve_price');
                    }
                    if(empty($this->input->post('estimated_commission')))
                    {
                    $posted_data['estimated_commission'] = NULL;
                    }
                    else
                    {
                    $posted_data['estimated_commission'] = $this->input->post('estimated_commission');
                    }
                    
                $result = $this->crm_model->update_crm($id, $posted_data);
                if (!empty($result)) {
                    $get_user_id = $this->users_model->get_user_by_crm_id($id);
                    $user_data = array('fname' => $this->input->post('name'),
                                    'email' => $this->input->post('email'),
                                    'mobile' =>"+".$this->input->post('mobile_code').$this->input->post('mobile'),
                                    'updated_by' => $this->loginUser->id
                                );
                    $this->users_model->update_user($get_user_id[0]['id'], $user_data);
                    $this->session->set_flashdata('msg', 'CRM Info Updated Successfully');
                    $msg = 'success';
                    echo json_encode(array('msg' => $msg, 'in_id' => $result));

                } else {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                }
            }
        } else {

            $this->template->load_admin('crm/crm_form', $data);
        }

    }


    public function assigned_by_api()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " fname like '%".$_POST['search']."%' ";
                $sql[] = " lname like '%".$_POST['search']."%' ";
            }
            // print_r($_POST['search']);die();    
            $query = "";
            $otherSQL = ' AND role IN (2) AND status = 1';
            if (!empty($sql)){
                 $query .= ' (' . implode(' OR ', $sql).') '.$otherSQL;
            }
            $columns = "id, CONCAT(`fname`,' ', `lname`) AS Name";
            $values = $_POST['values'];
            // set option array for SELECT2 JQuery response result 
            $select2_array = array(
                'page' => $_POST['page'],
                'columns' => $columns,
                'table' => $_POST['table'],
                'where' => $query,
                'values' => $values,
            );
            $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
        } else {
            $empty[] = ['id'=>'', 'value'=>'', 'total_count'=>'']; 
            echo json_encode($empty);
        }
    }

    public function assigned_to_api()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " fname like '%".$_POST['search']."%' ";
                $sql[] = " lname like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        $otherSQL = ' AND role IN (3) AND status = 1';
        if (!empty($sql)){
             $query .= ' (' . implode(' OR ', $sql).') '.$otherSQL;
        }
        $columns = "id, CONCAT(`fname`,' ', `lname`) AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    }


  
    public function loss_reasons()
    {
        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Loss Reasons';
        $data['current_page_loss_reason'] = 'current-page';
        $data['loss_reasons_list'] = $this->crm_model->loss_reasons_list();
        $this->template->load_admin('crm/loss_reason_list', $data);
    }


    public function new_loss_reason()
    {
        $data = array();
        $id = $this->loginUser->id;
        $data['status'] = array();
        $data['small_title'] = 'Add Loss Reason';
        $data['current_page_loss_reason'] = 'current-page';
        $data['formaction_path'] = 'save_loss_reason';
        $data['customer_loss_reason'] = array();
        $this->template->load_admin('crm/loss_reason_form', $data);
    }

    public function save_loss_reason()
    {
        $this->load->library('form_validation');
        $data = $this->input->post();
        if ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required')
            );

        }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {

            echo json_encode(array('msg' => validation_errors()));
        } else {

            $id = $this->loginUser->id;
            $data['created_by'] = $id;
            $data['created_on'] = date('Y-m-d H:i:s');
            $result = $this->crm_model->save_loss_reason($data);
            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Loss Reason Added Successfully');

                $msg = 'success';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
            }
        }
    }

    public function edit_loss_reason()
    {
        $this->load->library('form_validation');
        if ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required')
            );

            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                echo json_encode(array('msg' => validation_errors()));
            }
      
            $loss_reason_id = $this->input->post('id');
            $data = $this->input->post();
            $data['updated_by'] = $this->loginUser->id;
            $result = $this->crm_model->update_loss_reason($loss_reason_id, $data);
            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Loss Reason Updated Successfully');
                $msg = 'success';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
                // redirect(base_url() . 'crm/loss_reasons');
            }
        } else {
            $reason_id = $this->uri->segment(3);
            $data['small_title'] = 'Edit';
            $data['title'] = 'Edit Loss Reason';
            $data['current_page_loss_reason'] = 'current-page';
            $data['formaction_path'] = 'edit_loss_reason';
            $data['customer_loss_reason'] = $this->crm_model->loss_reasons_list($reason_id);
            $this->template->load_admin('crm/loss_reason_form', $data);
        }
    }

    public function customer_type()
    {

        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Customer Type';
        $data['current_page_customer_type'] = 'current-page';
        $data['customer_type_list'] = $this->crm_model->customer_type_list();
        $this->template->load_admin('crm/customer_type_list', $data);
    }

    public function new_customer_type()
    {
        $data = array();
        // $this->template->load_admin('profiles/profile_form', $data);
        $id = $this->loginUser->id;
        $data['status'] = array();
        $data['small_title'] = 'Add Customer Type';
        $data['current_page_customer_type'] = 'current-page';
        $data['formaction_path'] = 'save_customer_type';
        $data['customer_type_list'] = array();
        //$data['all_users'] = $this->users_model->get_user_by_id($id);
        $this->template->load_admin('crm/new_customer_type', $data);
    }

    public function save_customer_type()
    {
        $this->load->library('form_validation');
        $data = $this->input->post();
        if ($this->input->post()) {
            $rules = array(array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required'), array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'));

        }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {

            echo json_encode(array('msg' => validation_errors()));
        } else {

            $id = $this->loginUser->id;
            $data['created_by'] = $id;
            $data['created_on'] = date('Y-m-d H:i:s');
            // $data['millage_to']=$this->cars_model->get_millage_to_name($millage_to_id);
            // $data['millage_from']=$this->cars_model->get_millage_from_name($millage_from_id);
            $result = $this->crm_model->save_customer_type($data);
            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Customer Type Added Successfully');

                $msg = 'success';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
            }
        }
    }

    public function edit_customer_type()
    {
        $this->load->library('form_validation');
        if ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required'), 
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required')
            );

            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                echo json_encode(array('msg' => validation_errors()));
            }
       
            $customer_id = $this->input->post('id');
            $data = $this->input->post();
            $data['updated_by'] = $this->loginUser->id;
            $result = $this->crm_model->update_customer_type($customer_id, $data);
            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Customer Type Updated Successfully');
                // redirect(base_url() . 'crm/customer_type');
                $msg = 'success';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
            }
        } else {
            $customer_id = $this->uri->segment(3);
            $data['small_title'] = 'Edit';
            $data['title'] = 'Edit Customer';
            $data['current_page_customer_type'] = 'current-page';
            $data['formaction_path'] = 'edit_customer_type';
            $data['customer_type_list'] = $this->crm_model->customer_type_list($customer_id);
            $this->template->load_admin('crm/new_customer_type', $data);
        }
    }

    public function source()
    {

        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Lead Source';
        $data['current_page_source'] = 'current-page';
        $data['source_list'] = $this->crm_model->source_list();
        $this->template->load_admin('crm/source_list', $data);
    }

    public function new_source()
    {
        $data = array();
        // $this->template->load_admin('profiles/profile_form', $data);
        $id = $this->loginUser->id;
        $data['status'] = array();
        $data['small_title'] = 'Add Lead Source';
        $data['current_page_source'] = 'current-page';
        $data['formaction_path'] = 'save_source';
        $data['source_list'] = array();
        //$data['all_users'] = $this->users_model->get_user_by_id($id);
        $this->template->load_admin('crm/new_source', $data);
    }
    public function save_source()
    {
        $this->load->library('form_validation');
        $data = $this->input->post();
        if ($this->input->post()) {
            $rules = array(array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required'), array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'));

        }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {

            echo json_encode(array('msg' => validation_errors()));
        } else {

            $id = $this->loginUser->id;
            $data['created_by'] = $id;
            $data['created_on'] = date('Y-m-d H:i:s');
            // $data['millage_to']=$this->cars_model->get_millage_to_name($millage_to_id);
            // $data['millage_from']=$this->cars_model->get_millage_from_name($millage_from_id);
            $result = $this->crm_model->save_source($data);
            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Source Added Successfully');

                $msg = 'success';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
            }
        }
    }
    public function edit_source()
    {
        if ($this->input->post()) {
            $source_id = $this->input->post('id');
            $data = $this->input->post();
            $data['updated_by'] = $this->loginUser->id;
            $data['updated_on'] = date('Y-m-d H:i:s');
            $result = $this->crm_model->update_source($source_id, $data);
            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Source Updated Successfully');
                $msg = 'success';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
            }
        } else {
            $source_id = $this->uri->segment(3);
            $data['small_title'] = 'Edit';
            $data['current_page_source'] = 'current-page';
            $data['title'] = 'Edit Source';
            $data['formaction_path'] = 'edit_source';
            $data['source_list'] = $this->crm_model->source_list($source_id);
            $this->template->load_admin('crm/new_source', $data);
        }
    }

    public function lead_category()
    {

        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Lead Category';
        $data['current_page_lead_category'] = 'current-page';
        $data['lead_category_list'] = $this->crm_model->lead_category_list();
        $this->template->load_admin('crm/lead_category_list', $data);
    }

    public function new_lead_category()
    {
        $data = array();
        // $this->template->load_admin('profiles/profile_form', $data);
        $id = $this->loginUser->id;
        $data['status'] = array();
        $data['small_title'] = 'Add Lead Category';
        $data['current_page_lead_category'] = 'current-page';
        $data['formaction_path'] = 'save_lead_category';
        $data['lead_category_list'] = array();
        //$data['all_users'] = $this->users_model->get_user_by_id($id);
        $this->template->load_admin('crm/new_lead_category', $data);
    }
    public function save_lead_category()
    {
        $this->load->library('form_validation');
        $data = $this->input->post();
        if ($this->input->post()) {
            $rules = array(array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required'), array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'));

        }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {

            echo json_encode(array('msg' => validation_errors()));
        } else {

            $id = $this->loginUser->id;
            $data['created_by'] = $id;
            $data['created_on'] = date('Y-m-d H:i:s');
            // $data['millage_to']=$this->cars_model->get_millage_to_name($millage_to_id);
            // $data['millage_from']=$this->cars_model->get_millage_from_name($millage_from_id);
            $result = $this->crm_model->save_lead_category($data);
            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Lead Category Added Successfully');

                $msg = 'success';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
            }
        }
    }
    public function edit_lead_category()
    {
        if ($this->input->post()) {
            $lead_category_id = $this->input->post('id');
            $data = $this->input->post();
            $data['updated_by'] = $this->loginUser->id;
            $data['updated_on'] = date('Y-m-d H:i:s');
            $result = $this->crm_model->update_lead_category($lead_category_id, $data);
            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Lead Category Updated Successfully');
                $msg = 'success';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
            }
        } else {
            $lead_category_id = $this->uri->segment(3);
            $data['small_title'] = 'Edit';
            $data['current_page_lead_category'] = 'current-page';
            $data['title'] = 'Edit Lead Category';
            $data['formaction_path'] = 'edit_lead_category';
            $data['lead_category_list'] = $this->crm_model->lead_category_list($lead_category_id);
            $this->template->load_admin('crm/new_lead_category', $data);
        }
    }
    public function delete()
    {
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        $res = false;

        if ($table == "crm_lead_source") {
            $res = $this->crm_model->delete_lead_source_row($id);
        }
        if ($table == "crm_customer_type") {
            $res = $this->crm_model->delete_customer_type_row($id);
        }
        if ($table == "crm_lead_category") {
            $res = $this->crm_model->delete_lead_category_row($id);
        }
        if ($table == "crm_lead_stage") {
            $res = $this->crm_model->delete_lead_stage_row($id);
        }
        if ($table == "crm_next_step") {
            $res = $this->crm_model->delete_next_step_row($id);
        }

        if ($table == "crm_detail") {
            $res = $this->crm_model->delete_crm_detail_row($id);
        }
        if ($table == "crm_loss_reasons") {
            $res = $this->crm_model->delete_crm_loss_reason($id);
        }

        //do not change below code
        if ($res) {
            echo $return = '{"type":"success","msg":"Ok"}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }

    public function lead_stage()
    {

        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Lead Stage';
        $data['current_page_lead_stage'] = 'current-page';
        $data['lead_stage_list'] = $this->crm_model->lead_stage_list();
        $this->template->load_admin('crm/lead_stage_list', $data);
    }

    public function new_lead_stage()
    {
        $data = array();
        // $this->template->load_admin('profiles/profile_form', $data);
        $id = $this->loginUser->id;
        $data['status'] = array();
        $data['current_page_lead_stage'] = 'current-page';
        $data['small_title'] = 'Add Lead Stage';
        $data['formaction_path'] = 'save_lead_stage';
        $data['lead_stage_list'] = array();
        //$data['all_users'] = $this->users_model->get_user_by_id($id);
        $this->template->load_admin('crm/new_lead_stage', $data);
    }
    public function save_lead_stage()
    {
        $this->load->library('form_validation');
        $data = $this->input->post();
        if ($this->input->post()) {
            $rules = array(array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required'), array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'));

        }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {

            echo json_encode(array('msg' => validation_errors()));
        } else {

            $id = $this->loginUser->id;
            $data['created_by'] = $id;
            $data['created_on'] = date('Y-m-d H:i:s');
            // $data['millage_to']=$this->cars_model->get_millage_to_name($millage_to_id);
            // $data['millage_from']=$this->cars_model->get_millage_from_name($millage_from_id);
            $result = $this->crm_model->save_lead_stage($data);
            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Lead Stage Added Successfully');

                $msg = 'success';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
            }
        }
    }
    public function edit_lead_stage()
    {
        if ($this->input->post()) {
            $lead_stage_id = $this->input->post('id');
            $data = $this->input->post();
            $data['updated_by'] = $this->loginUser->id;
            $data['updated_on'] = date('Y-m-d H:i:s');
            $result = $this->crm_model->update_lead_stage($lead_stage_id, $data);
            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Lead Stage Updated Successfully');
                $msg = 'success';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
            }
        } else {
            $lead_stage_id = $this->uri->segment(3);
            $data['small_title'] = 'Edit';
            $data['title'] = 'Edit Lead Stage';
            $data['formaction_path'] = 'edit_lead_stage';
            $data['current_page_lead_stage'] = 'current-page';
            $data['lead_stage_list'] = $this->crm_model->lead_stage_list($lead_stage_id);
            $this->template->load_admin('crm/new_lead_stage', $data);
        }
    }

    public function next_step()
    {

        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Next Step';
        $data['current_page_next_step'] = 'current-page';
        $data['next_step_list'] = $this->crm_model->next_step_list();
        $this->template->load_admin('crm/next_step_list', $data);
    }

    public function new_next_step()
    {
        $data = array();
        // $this->template->load_admin('profiles/profile_form', $data);
        $id = $this->loginUser->id;
        $data['status'] = array();
        $data['small_title'] = 'Add Next Step';
        $data['formaction_path'] = 'save_next_step';
        $data['current_page_next_step'] = 'current-page';
        $data['next_step_list'] = array();
        //$data['all_users'] = $this->users_model->get_user_by_id($id);
        $this->template->load_admin('crm/new_next_step', $data);
    }
    public function save_next_step()
    {
        $this->load->library('form_validation');
        $data = $this->input->post();
        if ($this->input->post()) {
            $rules = array(array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required'), array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'));

        }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {

            echo json_encode(array('msg' => validation_errors()));
        } else {

            $id = $this->loginUser->id;
            $data['created_by'] = $id;
            $data['created_on'] = date('Y-m-d H:i:s');
            // $data['millage_to']=$this->cars_model->get_millage_to_name($millage_to_id);
            // $data['millage_from']=$this->cars_model->get_millage_from_name($millage_from_id);
            $result = $this->crm_model->save_next_step($data);
            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Next Step Added Successfully');

                $msg = 'success';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
            }
        }
    }
    public function edit_next_step()
    {
        if ($this->input->post()) {
            $next_step_id = $this->input->post('id');
            $data = $this->input->post();
            $data['updated_by'] = $this->loginUser->id;
            $data['updated_on'] = date('Y-m-d H:i:s');
            $result = $this->crm_model->update_next_step($next_step_id, $data);
            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Next Step Updated Successfully');
                $msg = 'success';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
            }
        } else {
            $next_step_id = $this->uri->segment(3);
            $data['small_title'] = 'Edit';
            $data['title'] = 'Edit Next Step';
            $data['formaction_path'] = 'edit_next_step';
            $data['current_page_next_step'] = 'current-page';
            $data['next_step_list'] = $this->crm_model->next_step_list($next_step_id);
            $this->template->load_admin('crm/new_next_step', $data);
        }
    }


    //Delete Multiple Rows
    public function delete_bulk()
    {

        $ids_array =  rtrim($_REQUEST['id'], ",");
        $table = $this->input->post("obj");
         if ($table == "crm_customer_type") {
            $result = $this->db->query('delete from ' . $table . ' where id in (' . $ids_array . ') ');
        } 
        if ($table == "crm_lead_source") {
            $result = $this->db->query('delete from ' . $table . ' where id in (' . $ids_array . ') ');
        } 
        if ($table == "crm_lead_category") {
            $result = $this->db->query('delete from ' . $table . ' where id in (' . $ids_array . ') ');
        }
        if ($table == "crm_lead_stage") {
            $result = $this->db->query('delete from ' . $table . ' where id in (' . $ids_array . ') ');
        } 
        if ($table == "crm_next_step") {
            $result = $this->db->query('delete from ' . $table . ' where id in (' . $ids_array . ') ');
        }
        if ($table == "crm_detail") {
            $result = $this->db->query('delete from ' . $table . ' where id in (' . $ids_array . ') ');
        }
        if ($table == "crm_loss_reasons") {
            $result = $this->db->query('delete from ' . $table . ' where id in (' . $ids_array . ') ');
        }
      
        //do not change below code
        if ($result) {
            echo $return = '{"type":"success","msg":"Data Deleted Successfully."}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }

    public function check_dublication_mobile() {
        $data = array();
        $id = $this->loginUser->id;
        $mobile = $this->input->post('mobile'); 
        $mobile_code = $this->input->post('mobile_code');
        $user_id = $_GET['user_id'];
        if($user_id != 0)
        {
            $user_mobile = $this->db->get_where('users',['mobile',$user_id])->row_array();
            $number = $this->input->post('mobile');
            if(($user_mobile) && $user_mobile['mobile'] == $number)
            {
                $msg = 'not_duplicate';
                echo json_encode(array('msg' => $msg));
                exit();
            }

        }
        $number = $this->input->post('mobile');
        $check_number = $this->users_model->check_user_number($number);
        if($check_number == true)
        {   
            $msg = 'duplicate';
            echo json_encode(array('msg' => $msg));
        }
        if($check_number == false)
        {   
            $msg = 'not_duplicate';
            echo json_encode(array('msg' => $msg));
        }
    }

    public function getNumber($n) { 
        $characters = '0123456789'; 
        $randomString = ''; 
      
        for ($i = 0; $i < $n; $i++) { 
            $index = rand(0, strlen($characters) - 1); 
            $randomString .= $characters[$index]; 
        } 
        return $randomString; 
    }
}

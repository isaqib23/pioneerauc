<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jobcard extends Loggedin_Controller {
	function __construct() {
		parent::__construct();
        $this->load->model('users/Users_model','users_model');
		$this->load->model('Jobcard_model','jobcard_model');
		// $this->load->model('Common_Model', 'common_model');
        $this->load->model('acl/Acl_model', 'acl_model');
        $this->load->model('settings/Setting_model', 'setting_model');
        $this->load->model('items/Items_model', 'items_model');
        $this->load->model('files/Files_model','files_model');
         $this->load->model('admin/Common_model','common_model');
	}
	public function index()
    {
        $data = array();
        $data['small_title'] = 'Job Card Users List';
        $data['current_page_jobcard_user'] = 'current-page';
        $data['formaction_path'] = 'filter_report_users';
        $role_id = $this->loginUser->role;
        $all_jobcard_roles = array(5,6);
        $tasker_jobcard_role = array(6);
        $tasker_role = 6;
        if($role_id == 1)
        {
            $data['tasker_list'] = $this->jobcard_model->jobcard_user_list($all_jobcard_roles);
        }
        else
        {
            $data['tasker_list'] = $this->jobcard_model->jobcard_user_list($tasker_jobcard_role);
        }
        
        $this->template->load_admin('jobcard/jobcard_user_list', $data);
    }

    public function save_user()
    {
        $data['small_title'] = 'Add';
        $data['current_page_jobcard_user'] = 'current-page';
        $data['formaction_path'] = 'save_user';
        $data['all_users'] = array();
        $user_id = $this->loginUser->id;
        $role_id = $this->loginUser->role;
        $all_jobcard_roles = array(5,6);
        $tasker_jobcard_role = array(5);
        if($role_id == 1)
        {
            $data['job_cart_users'] = $this->jobcard_model->jobcard_roles($all_jobcard_roles);    
        }
        else
        {
            $data['job_cart_users'] = $this->jobcard_model->jobcard_roles($tasker_jobcard_role);    
        }

        $customer_check = $this->input->get('user_type');
        $sales_person_id = 3;
        $data['sales_person'] = $this->users_model->users_list($sales_person_id);    
        $this->load->library('form_validation');
        if ($this->input->post()) 
        {
            $users_attachment = array();
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
                    'rules' => 'required|valid_email',
                ),
                 array(
                    'field' => 'mobile',
                    'label' => 'Mobile ',
                    'rules' => 'trim|required',
                ),
            );

            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) 
            {
                 echo json_encode(array('msg' => "error", 'mid' => validation_errors()));
                exit();
            } 
            if($this->input->post())
            {
                $number = "+".$this->input->post('mobile_code').$this->input->post('mobile');
                $check_number = $this->jobcard_model->check_jobcard_user_mobile($number);
                $email = $this->input->post('email');
                $result = $this->jobcard_model->check_jobcard_user_email($email);
                if($check_number == true)
                {   
                    $this->session->set_flashdata('error', 'Number already exist.');
                    $msg = 'duplicate';
                    echo json_encode(array('msg' => $msg,'mid' => 'Number already exist.'));
                    exit();
                }
                elseif($result == true)
                {   
                    $this->session->set_flashdata('error', 'Email already exist.');
                    $msg = 'duplicate';
                    echo json_encode(array('msg' => $msg,'mid' => 'Email already exist.'));
                    exit();
                }
                else
                {

                    $data = array(
                        'fname' => $this->input->post('fname'),
                        'lname' => $this->input->post('lname'),
                        'username' =>$this->input->post('fname').' '.$this->input->post('lname'),
                        'city' => $this->input->post('city'),
                        'mobile' => $this->input->post('mobile'),
                        'email' => $this->input->post('email'),
                        'role' => 6,
                        'type' => $this->input->post('type'),
                        'password' => hash("sha256",$this->input->post('password')),
                        'status' => $this->input->post('status')
                    );

                    $data['created_on'] = date('Y-m-d H:i:s');
                    $previous_id = $this->jobcard_model->get_max_id();
                    $data['code'] = sprintf('%08d', $previous_id[0]['max(id)']);
                    $data['mobile'] = "+".$this->input->post('mobile_code').$this->input->post('mobile');
                    // print_r($data);die('aaaaaa');
                    $data['updated_on'] = date('Y-m-d H:i:s');
                    // print_r($data);die('asa');
                    $result = $this->jobcard_model->insert_tasker_detail($data);
                    // print_r($result);die('aaa');
                if( !empty($_FILES['images']['name']))
                {
                    $jobcard_user_id = $result;
                    // make path
                    $path = './uploads/profile_picture/';
                    if ( ! is_dir($path.$jobcard_user_id)) 
                    {
                        mkdir($path.$jobcard_user_id, 0777, TRUE);
                    }
                    $path = $path.$jobcard_user_id.'/'; 
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $uploaded_file_ids = $this->files_model->upload('images', $config); // upload and save to database
                    $uploaded_file_ids = implode(',', $uploaded_file_ids);
                    $update = [
                        'picture' => $uploaded_file_ids
                    ];
                    // die('yyyyyyyyyyyy');
                    $jobcard_user_update = ($this->jobcard_model->update_user($jobcard_user_id,$update)) ? 'true' : 'false';

                }



                    if (!empty($result)) 
                    {
                        $this->session->set_flashdata('msg', 'User Add Successfully');
                        $redict_users_list = "success";
                        echo json_encode(array('msg'=>$redict_users_list,'mid'=>'User add Successfully'));
                        exit();
                    }
                }
            }
        }
        else
        {
            $this->template->load_admin('jobcard/jobcard_form',$data);
        }
    }


    public function update_tasker() {
        $id = $this->uri->segment(3);
        $role_id = $this->loginUser->id;
        $data['small_title'] = 'Update';
        $data['formaction_path'] = 'update_tasker';
        $data['current_page_jobcard_user'] = 'current-page';
         $sales_person_id = 3;
        $customer_check = $this->input->get('user_type');
      
        $this->load->library('form_validation');
        $data['all_users'] = $this->jobcard_model->users_listing($id);
        // print_r( $data['all_users'] );
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
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'trim|required|valid_email',
                ),
                array(
                    'field' => 'mobile',
                    'label' => 'Mobile',
                    'rules' => 'trim|required',
                ),
            );

            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                echo json_encode(array('msg' => "error", 'mid' => validation_errors()));
            } 
            else
            {
                    $id = $this->input->post('user_id');
                    $q = $this->db->query('select email from users where id ='.$id);
                    $query = $q->result_array();
                    if(isset($query[0]['email']))
                    {
                    if($this->input->post('email') != $query[0]['email'])
                    {
                        $email = $this->input->post('email');
                         $result = $this->users_model->check_email($email);
                         if($result == true)
                         {   
                         $this->session->set_flashdata('error', 'Email already exist.');
                            $msg = 'duplicate';
                            echo json_encode(array('msg' => $msg,'mid' => 'Email already exist.'));
                            exit();
                         }
                    } 
                }   

                    $q_for_number = $this->db->query('select mobile from users where id ='.$id);
                    $query_for_number = $q_for_number->result_array();
                    if($this->input->post('mobile') != $query_for_number[0]['mobile'])
                    {
                        $mobile = $this->input->post('mobile');
                         $result = $this->setting_model->check_user_number($mobile);
                         if($result == true)
                         {   
                         $this->session->set_flashdata('error', 'Number already exist.');
                             $msg = 'duplicate';
                             echo json_encode(array('msg' => $msg,'mid' => 'Number already exist.'));
                             // exit();
                         }
                    }
 
                $data = array(
                    'fname' => $this->input->post('fname'),
                    'lname' => $this->input->post('lname'),
                    'username' =>$this->input->post('fname').' '.$this->input->post('lname'),
                    'mobile' => $this->input->post('mobile'),
                    'email' => $this->input->post('email'),
                    'type' => $this->input->post('type'),
                    'status' => $this->input->post('status'),
                    'role' => 6,
                    // 'password' => md5($this->input->post('password')),
                    // 'documents' => $items_documents_data,
              

                );
                // echo "<pre>";
                // print_r($data);
                $data['mobile'] = "+".$this->input->post('mobile_code').$this->input->post('mobile');
                $id = $this->input->post('user_id');

                $data_for_delete_files = $this->users_model->users_listing($id); // fetch data for delete fles from folder
            
              
                $id =$this->input->post('user_id');
               
                $q = $this->db->query('select password from users where id ='.$id );
                $old_pass = $q->result_array();

                if($this->input->post('password') != $old_pass[0])
                {
                $user_email = $this->input->post('email');
                $password = $this->input->post('password');         
        
                // $link_address=base_url('user/reset_password/').$unique_id;
                $to = $user_email;
                $subject = "Following are your Updated Email and Password ";
                $txt = "Your Email is : ".$user_email."\n\n Your Password is :".$password;
                
                }   
                $data['updated_on'] = date('Y-m-d H:i:s');  
                $result = $this->jobcard_model->update_user($this->input->post('post_id'), $data);

                if( !empty($_FILES['images']['name']))
                {

                    $user_detail = $this->db->get_where('users',['id'=>$this->input->post('post_id')])->row_array();
                    $jobcard_user_id = $user_detail['id'];
                   $file_id = $user_detail['picture'];
                    // make path
                    $path = './uploads/profile_picture/';
                    if($file_id)
                    {
                    $path_for_unlink = './uploads/profile_picture/'.$jobcard_user_id.'/';
                    $image_unlink = $this->files_model->delete_by_id($file_id,$path_for_unlink);
                    }
                    if ( ! is_dir($path.$jobcard_user_id)) 
                    {
                        mkdir($path.$jobcard_user_id, 0777, TRUE);
                    }
                    $path = $path.$jobcard_user_id.'/'; 
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'ico|png|jpg|jpeg';
                    $uploaded_file_ids = $this->files_model->upload('images', $config); // upload and save to database
               
                    $uploaded_file_ids = implode(',', $uploaded_file_ids);
                    // print_r($uploaded_file_ids);die('aaaa');
                    $update = [
                        'picture' => $uploaded_file_ids
                    ];
                    $jobcard_user_update = ($this->jobcard_model->update_user($jobcard_user_id,$update)) ? 'true' : 'false';

                }




                    if (!empty($result)) {
                       
                        $this->session->set_flashdata('msg', 'Tasker Updated Successfully');
                        $redict_users_list = "success";
                        echo json_encode(array("msg"=>$redict_users_list,"mid"=>$result));
                        exit();
                        
                       
                    }
            }
        }
        else
        {
            $this->template->load_admin('jobcard/jobcard_form',$data);
        }
        
    }

    public function validate_check_user_email()
    {
        $check_email = $this->jobcard_model->check_jobcard_user_email(urldecode($this->uri->segment(3)));
        if(isset($check_email) && !empty($check_email))
        {
            echo '404';
        }
        else
        {
            echo '200';
        }
    }

    public function validate_check_user_mobile()
    {
        $check_number = $this->jobcard_model->check_jobcard_user_mobile($this->uri->segment(3));
        if(isset($check_number) && !empty($check_number))
        {
            echo '404';
        }
        else
        {
            echo '200';
        }
    }

    public function task_list()
    {
        $data = array();
        $data['small_title'] = 'Task List';
        $data['formaction_path'] = 'filter_report';
        $data['current_page_task'] = 'current-page';
        $data['task_list'] = $this->jobcard_model->tasks_list();



         // $data['user_id'] = $this->loginUser->id;
        $this->template->load_admin('jobcard/task_list', $data);
    }
     public function add_task_detail()
     {
        $id = $this->loginUser->id;
        // $data['updated_by']=$id;
  
        $datas['small_title'] = 'Add';
        $path['formaction_path'] ='save_task';
        $data=array(
             'title' => $this->input->post('title'),
             'category_id'=> $this->input->post('task_category'),
             'description' => $this->input->post('description'), 
             'status' => $this->input->post('status'),       
             'created_on' => date('Y-m-d H:i:s'),
             // 'assign_to' => $this->input->post('assign_to'),      
             'created_by' => $this->loginUser->id,
             'status' => "pending",
        );
        $result = $this->jobcard_model->insert_task($data);  
        if (!empty($result)) {           
                        $this->session->set_flashdata('msg', 'Task Added Successfully');
                        $redict_users_list = "success";
                        echo json_encode(array('msg'=>$redict_users_list,'mid'=>'Task Added Successfully'));
                        exit();
                        }       
     }
//method for show the form data in data base
    public function add_task()
    {
        $id = 6;
        $data = array();
        $data['user_id'] = $this->loginUser->id;
        $data['small_title'] ='Add';
        $data['current_page_task'] = 'current-page';
        $data['task_info'] = array();
        $data['tasker_list'] = $this->jobcard_model->tasker_list($id);
        $data['task_category'] = $this->db->query('select * from task_category where status= "active"')->result_array();
        // print_r($data['tasker_list']);
        $data['task_list'] = array();
        $data['task_detail'] = array();
        $data['formaction_path'] ='add_task_detail';
        $this->template->load_admin('jobcard/task_form', $data);
    }
    //Delete Single Row
    public function delete( $id = NULL )
    {
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        $result = $this->jobcard_model->delete_select_row($id,$table);
        //do not change below code
         if ($result) {
            echo $return = '{"type":"success","msg":"success"}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }
 
    //Delete Multiple Rows
    public function delete_bulk()
    {
        // $ids_array =  rtrim($_REQUEST['id'], ",");
        $ids_array = $this->input->post('id');
        $table = $this->input->post("obj");
        $result = $this->db->query('delete from ' . $table . ' where id in (' . $ids_array . ') ');
        //do not change below code
        if ($result) {
            echo $return = '{"type":"success","msg":"Ok"}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }
    //select data against id and edit
     public function edit_task()
    {
        $task_id = $this->uri->segment(3);
        $data['small_title'] = 'Edit';
        $id = 6;
        $data['formaction_path'] = 'save_edit_task';
        $data['current_page_task'] = 'current-page';
        $data['tasker_list'] = $this->jobcard_model->tasker_list($id);
         $data['task_category'] = $this->db->query('select * from task_category')->result_array();
        $data['task_info'] = array();
        $data['task_detail'] = $this->jobcard_model->all_task_list($task_id);
        $this->template->load_admin('jobcard/task_form', $data);
    }

    // save the edit task against id
       public function save_edit_task()
    {
        $this->load->library('form_validation');
        // $data = $this->input->post();
        if ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'title',
                    'label' => 'title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'description',
                    'label' => 'description',
                    'rules' => 'trim|required'),
               );      
        }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            echo json_encode(array('msg' => validation_errors()));
        } else {

       $id = $this->input->post('task_id');
       $data['title'] = $this->input->post('title');
       $data['description'] = $this->input->post('description');
       $data['category_id'] = $this->input->post('task_category');
       // $data['assign_to'] = $this->input->post('assign_to');
       $data['updated_by'] = $this->loginUser->id;
        $data['updated_on'] = date('Y-m-d H:i:s');
        $data['status'] = "pending";
        unset($data['task_id']);
        unset($data['task_category_list']);

        // print_r($data);die('aaa');
         $check= $this->jobcard_model->update_edit_task($id, $data);
            if (!empty($check)) {
               $this->session->set_flashdata('msg', 'task update Successfully');
                        $redict_users_list = "success";
                        echo json_encode(array('msg'=>$redict_users_list,'mid'=>'task update Successfully'));
                        exit();
            }
        }
    }    
     public function change_status()
    {
        $status_id = array('status' => $_GET['status_id']);
        $user_id = $_GET['user_id'];
        $id = 6;
        $return = $this->jobcard_model->change_status($status_id,$user_id);
        $data['tasker_list'] = $this->jobcard_model->tasker_list($id);
        if($data['tasker_list'][0]['status'] == 1){
        $this->session->set_flashdata('msg', 'Tasker Active Successfully');
        }
        if($data['tasker_list'][0]['status'] == 0){
            $this->session->set_flashdata('msg', 'Tasker InActive Successfully');
        }
        redirect(base_url().'jobcard');

    }
    public function tasker_tasks_list()
    {
        $id = $this->loginUser->id;
        $data['small_title'] = "Assign Tasks"; 
        $data['tasker_tasks_list'] = $this->jobcard_model->tasker_tasks_list($id);
        $data['taskers_ids'] = $this->db->query('select * from assigned_task ' )->result_array(); 
        if(!empty($data['taskers_ids']))
        {
            foreach ($data['taskers_ids'] as $key => $value) {
                $tasker_ids_from_array = json_decode($value['assign_to_ids']);
                foreach ($tasker_ids_from_array as $key => $val) {
                    $a = $val;
                    $id = $this->loginUser->id;
                    if($id == $a)
                    {   
                    
                        $b =json_decode($value['assigned_task_ids']);
                          $task_detail_from_assigned_detail = $this->db->query('select * from assigned_tasks_detail where assigned_task_id = "'.$value['id'].'" order by id desc')->result_array();
                             $data['task_detail'][] = $task_detail_from_assigned_detail;
                        foreach ($task_detail_from_assigned_detail as $task_key => $task_value) {
                           $d = $this->db->get_where('task', ['id' => $task_value['task_id'] ])->result_array();
                           $data['task_list'][] = $d;

                        }
                    } 
                }

            }
        }
            $data['id'] = $id;
        $this->template->load_admin('jobcard/tasker_tasks_list', $data);
    }
public function complete_task()
{
     $task_id =$_GET['task_id'];
     $user_id = $_GET['user_id'];
     $data['status'] = "complete";
     $data['complete_date'] = date('Y-m-d H:i:s');
     // print_r($data);die('aaaaaaaa');
     $result = $this->jobcard_model->update_task_status($task_id,$user_id,$data );
     if($result)
     {
         redirect(base_url().'jobcard/tasker_tasks_list');
     }
}
    public function get_assigned_tasks()
    {
        $data = array();
        $data['small_title'] = "Assigned Tasks";
        $data['tasker_id'] = $this->input->post('id');
        $data['tasker_tasks_list'] = $this->jobcard_model->tasker_tasks_list($data['tasker_id']);
        $data_view = $this->load->view('jobcard/task_list_for_opdept', $data, true);
        // print_r($data_view);

        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    }

     public function filter_report()
    {
        $data = array();
        if($this->input->post())
        {
            $posted_data = $this->input->post();
        if (isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {
            $start_date = $posted_data['datefrom'];
            $end_date = $posted_data['dateto'];           
            $sql[] = " (DATE(task.created_on) between '$start_date' and '$end_date') ";

        }
            $query = "";
        if (!empty($sql)) {
            $query .= ' ' . implode(' OR ', $sql);

        }
            $data['task_list'] = $this->jobcard_model->tasks_listing($query);
            $data_view = $this->load->view('jobcard/filter_data', $data, true);
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found'));
        }
    }

       public function filter_report_assigned_task()
    {
        $data = array();
        if($this->input->post())
        {
            $posted_data = $this->input->post();
        if (isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {
            $start_date = $posted_data['datefrom'];
            $end_date = $posted_data['dateto'];           
            $sql[] = " (DATE(assigned_task.assigned_date) between '$start_date' and '$end_date') ";

        }
            $query = "";
        if (!empty($sql)) {
            $query .= ' ' . implode(' OR ', $sql);

        }
            $data['assigned_task_list'] = $this->jobcard_model->assigned_task_filter($query);
            $data_view = $this->load->view('jobcard/assigned_task_to_user/assigned_task_filter', $data, true);
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found'));
        }
    }

      public function filter_report_users()
    {
        $data = array();
        if($this->input->post())
        {
            $posted_data = $this->input->post();

        if (isset($posted_data['username']) && !empty($posted_data['username'][0])) {

            $username = "";
            $username .= ''. implode("%'OR users.id LIKE'%",$posted_data['username']);
            $sql[] = " users.id LIKE '%$username%' ";
        }
        if (isset($posted_data['username_for_report']) && !empty($posted_data['username_for_report'][0])) {

            $username = "";
            $username .= ''. implode("%'OR assigned_tasks_detail.user_id LIKE'%",$posted_data['username_for_report']);
            $sql[] = "( assigned_tasks_detail.user_id LIKE '%$username%') ";
        }
         if (isset($posted_data['email_for_report']) && !empty($posted_data['email_for_report'][0])) {

            $email_for_report = "";
            $email_for_report .= ''. implode("%'OR assigned_tasks_detail.user_id LIKE'%",$posted_data['email_for_report']);
            $sql[] = "assigned_tasks_detail.user_id  LIKE '%$email_for_report%' ";
        }
         if (isset($posted_data['mobile_for_report']) && !empty($posted_data['mobile_for_report'][0])) {

            $mobile_for_report = "";
            $mobile_for_report .= ''. implode("%'OR assigned_tasks_detail.user_id LIKE'%",$posted_data['mobile_for_report']);
            $sql[] = " assigned_tasks_detail.user_id  LIKE '%$mobile_for_report%' ";
        }
        if (isset($posted_data['code']) && !empty($posted_data['code'][0])) {
            $users = "";
            $users .= ''. implode("%'OR users.id LIKE'%",$posted_data['code']);
            $sql[] = " users.id LIKE '%$users%' ";
        }

        if (isset($posted_data['email']) && !empty($posted_data['email'][0])) {
            $email = "";
            $email .= ''. implode("%'OR users.id LIKE'%",$posted_data['email']);
            $sql[] = " users.id LIKE '%$email%' ";
        }
        if (isset($posted_data['mobile']) && !empty($posted_data['mobile'][0])) {
            $mobile = "";
            $mobile .= ''. implode("%'OR users.id LIKE'%",$posted_data['mobile']);
            $sql[] = " (users.id LIKE '%$mobile%') ";
        }
           
    

            // print_r($sql);echo "++++++++++++++++++++++++++++++++++++++++++";


        if (isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {


            $start_date = $posted_data['datefrom'];
            $end_date = $posted_data['dateto'];
            
            $sql[] = " (DATE(users.created_on) between '$start_date' and '$end_date') ";


        }
            $query = "";
        if (!empty($sql)) {
            $query .= ' ' . implode(' AND ', $sql);  
        } 

            if (isset($posted_data['category_id']) && !empty($posted_data['category_id'])) {
                $category_id = $posted_data['category_id'];
                $sql[] = " assigned_tasks_detail.category_id LIKE '%$category_id%' ";
                
            }
            if (isset($posted_data['datefrom_for_report']) && !empty($posted_data['dateto_for_report'])) {
                $start_date = $posted_data['datefrom_for_report'];
                $end_date = $posted_data['dateto_for_report'];
                 $sql[] = " (DATE(assigned_tasks_detail.created_on) between '$start_date' and '$end_date') ";
            }

            if (isset($posted_data['status']) && !empty($posted_data['status'])) {
                $status = $posted_data['status'];
                 $sql[] = "( assigned_tasks_detail.status LIKE '%$status%' )";
            }
            // print_r($sql);die('asas');
            if(isset($sql[0])){
                 if (!empty($sql)) {
                        $query = ' ' . implode(' AND ', $sql);  
                           } 
                    // print_r($query);die('aaaaaaaaa');
                $this->db->select('*');
                $this->db->from('assigned_tasks_detail');
                $this->db->where($query);
                 $data['user_monthly_report'][] = $this->db->get()->result_array();
            }
            else
            {
                $data['users_list'] = $this->jobcard_model->jobcard_user_filter_list($query);
            }
  
            if(!empty($data['users_list'])){
                foreach ($data['users_list'] as $val ) {
                    $ids[] = $val['id'];
                }
            }


            if($this->input->post('monthly_report_user'))
            {   if(!empty($data['users_list'])){
                foreach ($ids   as $key)  {
                    $user_id = $key;
                    $data['user_monthly_report'][] = $this->db->get_where('assigned_tasks_detail', ['user_id' => $user_id])->result_array();
                   
                }
            }
                  // echo $this->db->last_query();
               
                $data['small_title'] = "User Report";
                $data_view = $this->load->view('jobcard/task_report_detail', $data,true);

            }
            else{
            $data_view = $this->load->view('jobcard/filter_jobcard_taskers', $data, true);
            }
            $response = array('msg' => 'success','data' => $data_view);

            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found'));
        }
    }


        public function jobCardUsersList()
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
                 // Custom search filter 
         $username = (isset($posted_data['username'])) ? $posted_data['username'] : '';
         $email = (isset($posted_data['email'])) ? $posted_data['email'] : '';
         $code = (isset($posted_data['code']))? $posted_data['code'] : '';
         $assigned_to = (isset($posted_data['assigned_to']))? $posted_data['assigned_to']: ''; 
         $mobile = (isset($posted_data['mobile']))? $posted_data['mobile']: ''; 

         ## Search 
         $search_arr = array();
         $searchQuery = "";
         if($searchValue != ''){
            $search_arr[] = " (fname like '%".$searchValue."%' ) ";
            $search_arr[] = " (lname like '%".$searchValue."%' ) ";
         }
         if($username != ''){
            $usernames = "'".implode("','",$username)."'";
            $search_arr[] = " users.id IN (".$usernames.") ";
         }
         if($code != ''){
            $codes = "'".implode("','",$code)."'";
            $search_arr[] = " users.id IN (".$codes.") ";
         }
         if($assigned_to != ''){
            $assigned_to_id = "'".implode("','",$assigned_to)."'";
            $search_arr[] = " users.id IN (".$assigned_to_id.") ";
         }
         if($mobile != ''){
            $mobiles = "'".implode("','",$mobile)."'";
            $search_arr[] = " users.id IN (".$mobiles.") ";
         }
         if($email != ''){
            $emails = "'".implode("','",$email)."'";
            $search_arr[] = " users.id IN (".$emails.") ";
         }
        if(isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {
            $start_date = $posted_data['datefrom'];
            $end_date = $posted_data['dateto'];
            $search_arr[] = " (DATE(users.created_on) between '".$start_date."' and '".$end_date."') ";
        }
         if(count($search_arr) > 0){
            // $otherSQL = ' AND role = 6 ';
            $searchQuery = ' ('.implode(" OR ",$search_arr).') ';
         }

        $total = $this->items_model->get_items();

                ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->where('role', '6');
        $records = $this->db->get('users')->result();
        $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->where('role', '6');
        $records = $this->db->get('users')->result();
        $totalRecordwithFilter = $records[0]->allcount;

         ## Fetch records
        $this->db->select('*');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->where('role', '6');
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('users')->result();
        $data = array();
        $have_documents = false;
            // $records = $this->db->get('item',$rowperpage, $start)->result();

     foreach($records as $record ){
        $user_id = $record->id;
        if($record->status == 0){
            $user_status = '<a class="btn btn-success  btn-xs" href="'.base_url().'jobcard/change_status?user_id='.$user_id.'&status_id=1">Active</a>';
        }elseif($record->status == 1){
            $user_status = '<a class="btn btn-warning  btn-xs" href="'.base_url().'jobcard/change_status?user_id='.$user_id.'&status_id=0">InActive</a>';
        }
        $action = '';
        $action .= '<a href="'.base_url().'jobcard/update_tasker/'.$record->id.'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
        $action .= ' <button onclick="deleteRecord(this)" type="button" data-obj="users" data-token-name="'.$this->security->get_csrf_token_name().'" data-token-value="'.$this->security->get_csrf_hash().'" data-id="'.$record->id.'" data-url="'.base_url().'users/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>';

       $data[] = array( 
        "id" => $record->id, 
         "username"=> $record->username,
         "code"=> $record->code,
         // "role"=> $role_val,
         "mobile"=> $record->mobile,
         "email"=> $record->email,
         "created_on"=>$record->created_on,
         "updated_on"=> $record->updated_on,
         "user_status"=>$user_status,
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
    
    public function assigned_task()
    {
        $data = array();
        $data['small_title'] = 'Assigned Task';
        $data['current_page_assigned_task'] = 'current-page';
        $data['formaction_path'] = 'filter_report_assigned_task';
        $data['assigned_task_list'] = $this->jobcard_model->assigned_task_list_admin();
         // $data['user_id'] = $this->loginUser->id;
        $this->template->load_admin('jobcard/assigned_task_to_user/list', $data);

    }
    public function task_category_list()
    {
        $data = array();
        $data['small_title'] = 'Task Category List';
        $data['current_task_category'] = 'current-page';
        $data['formaction_path'] = 'add_task_category';
        // $data['formaction_path'] = 'filter_report_';
        $data['task_category_list'] = $this->db->query('select * from task_category order by id desc')->result_array();
         // $data['user_id'] = $this->loginUser->id;
        $this->template->load_admin('jobcard/task_category_list', $data);
    }
    public function add_task_category()
    {
        $data['small_title'] = 'Add';
        $data['current_task_category'] = 'current-page';
        if($this->input->post())
        {
            $rules  = array(array(
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'trim|required'
             ),
        );

            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) 
            {
                echo validation_errors();
                exit();
            }
             $data = $this->input->post();
             $data['created_on'] = date('Y-m-d H:i:s');
             $data['created_by'] = $this->loginUser->id;
             $result = $this->jobcard_model->insert_task_category($data);
             if($result)
             {
                  $this->session->set_flashdata('success', 'Successfully Add Category');
                    $msg = 'success';
                    echo json_encode(array('msg' => $msg,'mid' => 'Successfully'));
                    exit();
             }


        }
        $data['formaction_path'] = 'add_task_category';
        $this->template->load_admin('jobcard/task_category/form',$data);
    }
    public function edit_task_category()
    {
        $data['small_title'] = "Edit";
        $data['formaction_path'] = "update_task_category";
        $data['current_task_category'] = 'current-page';
        $id = $this->uri->segment(3);
        $data['task_category_list'] = $this->db->get_where('task_category',['id'=> $id])->row_array();
        $this->template->load_admin('jobcard/task_category/form',$data);

    }
    public function update_task_category()
    {
         if($this->input->post())
        {
            $rules  = array(array(
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'trim|required'
             ),
        );

            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) 
            {
                echo validation_errors();
                exit();
            }

            $category_id = $this->input->post('category_id');
            $data  = array(
                'name' =>$this->input->post('name'),
                'status' => $this->input->post('status'),
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by' => $this->loginUser->id
                  );
            // print_r($data);die('aaaaa');
            $result = $this->jobcard_model->update_task_category($category_id,$data);
            if($result)
            {
                $this->session->set_flashdata('success', 'Successfully Updated Category');
                    $msg = 'success';
                    echo json_encode(array('msg' => $msg,'mid' => 'Successfully'));
                    exit();
            }
    }
}
    public function assign_task_to_user()
    {
        $id = $this->loginUser->id;
        $data['small_title'] = "Assign Task";
        $data['formaction_path'] = "save_assigned_task";
        $data['current_page_assigned_task'] = 'current-page';
        // $data['current_page_makes'] = 'current-page';
        $data['tasker_list'] = $this->jobcard_model->tasker_list();
        $data['task_category'] = $this->db->query('select * from task_category where status = "active"')->result_array();
        $this->template->load_admin('jobcard/assigned_task_to_user/form',$data);
    }

    public function save_assigned_task()
    {
        // $data['small_title'] = 'Add';
        if($this->input->post())
        {
            $rules  = array(array(
                'field' => 'task_category_id',
                'label' => 'Category',
                'rules' => 'trim|required'
             ),
        );

            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) 
            {
                echo validation_errors();
                exit();
            }
             $data = $this->input->post();
             foreach ($data['assigned_task_ids'] as $key ) {
                    $this->db->query('update task set status = "working" where id = "'.$key.'" ');
                 }
             $count = count($data['assigned_task_ids']);
             $data['assigned_task_ids'] = json_encode($data['assigned_task_ids']);
             $data['assign_to_ids'] = json_encode($data['assign_to_ids']);
             $data['assigned_date'] = date('Y-m-d H:i:s');  
             $data['status'] = "pending";
             $data['assign_by'] = $this->loginUser->id;
             // print_r($data);die('aaaaaa');
             $result = $this->jobcard_model->insert_assigned_task($data);
              $data['assign_to_ids'] = json_decode($data['assign_to_ids']);
              $assign_to_ids = $data['assign_to_ids'];

            unset($data['status']);
            unset($data['tasker_id']);

             $data['category_id'] = $data['task_category_id'];
             unset($data['assign_to_ids']);
             unset($data['task_category_id']);
             unset($data['assigned_date']);
             unset($data['assign_by']);
             unset($data['description']);
             $data['assigned_task_ids'] = json_decode($data['assigned_task_ids']);
             // unset($data['assigned_task_ids']);
             foreach ($data['assigned_task_ids'] as $key) {  
            unset($data['assigned_task_ids']);
            $data['task_id'] = $key;
             $data['assigned_task_id'] = $result;
             $data['created_on'] = date('y-m-d H:i:s');
             $data['assign_by'] = $this->loginUser->id;
             // print_r($data);die('aaaaaa');
             $this->jobcard_model->insert_to_asigned_tasks_detail_table($data);
                 
             }
             unset($data['category_id']);
             unset($data['task_id']);
             unset($data['assigned_task_id']);
             foreach ($assign_to_ids as $v) {
                $data['status'] = "unseen";
                $data['tasker_id'] = $v;
                $data['assign_by'] = $this->loginUser->id;
                if($count == 1){
                    $data['description'] = "You are assigned ".$count." task";
                }else{
                    $data['description'] = "You are assigned ".$count." tasks";
                }
                $data['assigned_task_id'] = $result;
                $data['created_on'] = date('y-m-d H:i:s');
                // $data['']
                $this->jobcard_model->insert_to_notify_me($data);
                 
             }


             if($result)
             {
                  $this->session->set_flashdata('success', 'Task has been assigned Successfully!');
             }else
             {
                  $this->session->set_flashdata('error', 'Task has been failed to assign.');
             }
             redirect(base_url('jobcard/assigned_task'));
    }
}
    public function get_category_tasks()
    {
        $id = $this->uri->segment(3);
        $data = array();
        $data = $this->db->query('select * from task where category_id ="'.$id.'"')->result_array();
        echo json_encode($data);

    }
    public function get_assigned_tasks_detail()
    {
        $assigned_task_id = $this->input->post('id');
        $data['assigned_tasks_detail'] = $this->db->query('select * from assigned_tasks_detail where assigned_task_id ="'.$assigned_task_id.'" ')->result_array();
        $data_view = $this->load->view('jobcard/assigned_tasks_status', $data, true);    
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);

    }
    public function edit_assigned_task()
    {
        $assigned_task_id = $this->uri->segment(3);
        $data['small_title'] = 'Edit';  
        $data['current_page_edit_assign_task'] = 'current-page';
        $data['current_page_assigned_task'] = 'current-page';
        $data['edit'] =  $this->db->get_where('assigned_task',['id' => $assigned_task_id])->row_array();
        $data['selected_task_ids'] = json_decode($data['edit']['assigned_task_ids']);
        $data['formaction_path'] = 'update_assigned_task';
        $id = 6;
        $data['tasker_list'] = $this->jobcard_model->tasker_list($id);
        $data['task_category'] = $this->db->query('select * from task_category')->result_array();
        $data['task_info'] = array();
        $data['tasks_list'] = $this->db->query('select * from task')->result_array();
        // $data['task_detail'] = $this->jobcard_model->all_task_list($task_id);
        $this->template->load_admin('jobcard/assigned_task_to_user/form', $data);
    }
    public function update_assigned_task()
    {

         if($this->input->post())
        {
            $rules  = array(array(
                'field' => 'task_category_id',
                'label' => 'Category',
                'rules' => 'trim|required'
             ),
        );

            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) 
            {
                echo validation_errors();
                exit();
            }
             $data = $this->input->post();
             $id = $this->input->post('id');
             $data['assigned_task_ids'] = json_encode($data['assigned_task_ids']);
             $data['assign_to_ids'] = json_encode($data['assign_to_ids']);
             $data['assigned_date'] = date('Y-m-d H:i:s');  
             $data['status'] = "pending";
             $data['assign_by'] = $this->loginUser->id;
             // print_r($data);die('aaaaaa');
             $result = $this->jobcard_model->update_assigned_task($id,$data);
             $data['category_id'] = $data['task_category_id'];
             unset($data['assign_to_ids']);
             unset($data['task_category_id']);
             unset($data['assigned_date']);
             unset($data['assign_by']);
             unset($data['description']);
             unset($data['id']);
             $data['assigned_task_ids'] = json_decode($data['assigned_task_ids']);
             if(!empty($data['assigned_task_ids']))
             {
                $this->db->query('delete from assigned_tasks_detail where assigned_task_id= "'.$id.'"');
             }
             // print_r($data);die('aaaaaaaa');
             foreach ($data['assigned_task_ids'] as $key) {  
             unset($data['assigned_task_ids']);
             $data['task_id'] = $key;
             $data['assigned_task_id'] = $id;
             $data['assign_by'] = $this->loginUser->id;
             $this->jobcard_model->insert_to_asigned_tasks_detail_table($data);          
             }
             if($result)
             {
                  $this->session->set_flashdata('success', 'Successfully Updated');
             }
             else
             {
                $this->session->set_flashdata('error', 'Not Updated');
             }
             redirect(base_url('jobcard/assigned_task'));
    }
    }
    public function atempt_to_work()
    {
    $task_detail_id =$_GET['task_detail_id'];
     // $user_id = $_GET['user_id'];
     $data['status'] = "in_process";
     $data['atempt_date'] = date('Y-m-d H:i:s');
     $data['user_id'] = $this->loginUser->id;
     // print_r($data);die('aaaaaaaa');
     $result = $this->jobcard_model->atempt_to_work($task_detail_id,$data );
     if($result)
     {
         redirect(base_url().'jobcard/tasker_tasks_list');
     }
    }
    public function task_complete()
    {
         $task_detail_id =$_GET['task_detail_id'];
         $task_id =$_GET['task_id'];
         $this->db->query('update task set status = "not_working" where id = "'.$task_id.'"');
         // $user_id = $_GET['user_id'];
         $data['status'] = "complete";
         $data['complete_date'] = date('Y-m-d H:i:s');
         $data['user_id'] = $this->loginUser->id;
         

         $result = $this->jobcard_model->complete_task($task_detail_id,$data );
          $get_assigned_task_id = $this->db->query('select assigned_task_id from assigned_tasks_detail where id = "'.$task_detail_id.'"')->row_array();
         $get_all_task_status = $this->db->query('select status from assigned_tasks_detail where assigned_task_id= "'.$get_assigned_task_id['assigned_task_id'].'"')->result_array();

         $flag = false;

          foreach ($get_all_task_status as $key ) {
            if($key['status'] == "pending" || $key['status'] == "in_process" ) 
            {
              $flag = false;
              break;
            }else{  $flag = true;  }
            }
            // print_r($flag);die('aa');

            if($flag == true)
            {
             $date = date('y-m-d H:i:s');
            $this->db->query('update assigned_task set completed_date= "'.$date.'" where id = "'.$get_assigned_task_id['assigned_task_id'].'" ');
            }
         if($result)
         {
             redirect(base_url().'jobcard/tasker_tasks_list');
         }
    } 
    public function tasker_report()
    {
        $data['formaction_path'] = "filter_report_users";
        $data['task_category'] = $this->db->query('select * from task_category')->result_array();
        $data['tasker_list'] = $this->jobcard_model->get_users_for_report();
        $data['small_title'] = " Tasker Reports";
        $this->template->load_admin('jobcard/tasker_report',$data);
    }
    public function assigned_task_username()
    {
        // make dynamicn query 
        unset($sql); 
        // print_r($this->input->post());die('aa');
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " username like '%".$_POST['search']."%' ";
                // $sql[] = " lname like '%".$_POST['search']."%' ";
            }
        }
       

        $query = "";
        $otherSQL = ' AND role = 6 AND status = 1';
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') '.$otherSQL;
        }
        $columns = "id, CONCAT(`username`) AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );
        // print_r($select2_array);die('aaaa');
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    }
   public function email_api()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " email like '%".$_POST['search']."%' ";
                // $sql[] = " lname like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        $otherSQL = ' AND role = 6 AND status = 1';
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') '.$otherSQL;
        }
        $columns = "id, CONCAT(`email`) AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );
        // print_r($select2_array);die('aaaa');
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    }
    public function taskeremail_api()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            /*if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " email like '%".$_POST['search']."%' ";
                // $sql[] = " lname like '%".$_POST['search']."%' ";
            }*/
             $sql[] = " email like '%".$_POST['search']."%' ";
        }
        $query = "";
        $otherSQL = ' AND role = 6';
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') '.$otherSQL;
        }
        $columns = "id, CONCAT(`email`) AS Name";
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


    public function username_api()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " username like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        $otherSQL = ' AND role = 6 AND status = 1';
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') '.$otherSQL;
        }
        $columns = "id, CONCAT(`username`) AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );
        // print_r($select2_array);die('aaaa');
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    }

    public function taskerlist_api()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " username like '%".$_POST['search']."%' ";
                // $sql[] = " lname like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        $otherSQL = ' AND role = 6';
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') '.$otherSQL;
        }
        $columns = "id, CONCAT(`username`) AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );
        // print_r($select2_array);die('aaaa');
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    }
    public function number_api()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " mobile like '%".$_POST['search']."%' ";
                // $sql[] = " lname like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        $otherSQL = ' AND role = 6 AND status = 1';
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') '.$otherSQL;
        }
        $columns = "id, CONCAT(`mobile`) AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );
        // print_r($select2_array);die('aaaa');
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    }
    public function taskernumber_api()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " mobile like '%".$_POST['search']."%' ";
                // $sql[] = " lname like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        $otherSQL = ' AND role = 6';
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') '.$otherSQL;
        }
        $columns = "id, CONCAT(`mobile`) AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );
        // print_r($select2_array);die('aaaa');
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    }
 public function code_api()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " code like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        $otherSQL = ' AND role = 6 AND status = 1';
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') '.$otherSQL;
        }
        $columns = "id, CONCAT(`code`) AS Name";
        $values = $_POST['values'];
        // set option array for SELECT2 JQuery response result 
        $select2_array = array(
            'page' => $_POST['page'],
            'columns' => $columns,
            'table' => $_POST['table'],
            'where' => $query,
            'values' => $values,
        );
        // print_r($select2_array);die('aaaa');
        $this->common_model->filterSelect2($select2_array); // get response for select2 JQuery
    }

     public function taskercode_api()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " code like '%".$_POST['search']."%' ";
                // $sql[] = " lname like '%".$_POST['search']."%' ";
            }
        }
        $query = "";
        $otherSQL = ' AND role = 6';
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') '.$otherSQL;
        }
        $columns = "id, CONCAT(`code`) AS Name";
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

    public function jobCardUsersTask()
    {
        $posted_data = $this->input->post();

        ## Read value
         $draw = $posted_data['draw'];
         $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
         $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 10; // Rows display per page
         $columnIndex = $posted_data['order'][0]['column']; // Column index
         $columnName = 'id';
         $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
         $searchValue = $posted_data['search']['value']; // Search value
                 // Custom search filter 
         $task_title = (isset($posted_data['title'])) ? $posted_data['title'] : '';
         ## Search 
         $search_arr = array();
         $searchQuery = "";
         if($searchValue != ''){
            $search_arr[] = " (title like '%".$searchValue."%' ) ";
            // $search_arr[] = " (lname like '%".$searchValue."%' ) ";
         }
         if($task_title != ''){
            $task_title = "'".implode("','",$task_title)."'";
            $task_title[] = " task.id IN (".$task_title.") ";
         }
        
        if(isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {
            $start_date = $posted_data['datefrom'];
            $end_date = $posted_data['dateto'];
            $search_arr[] = " (DATE(task.created_on) between '".$start_date."' and '".$end_date."') ";
        }
         if(count($search_arr) > 0){
            // $otherSQL = ' AND role = 6 ';
            $searchQuery = ' ('.implode(" OR ",$search_arr).') ';
         }

        $total = $this->items_model->get_items();

                ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('task')->result();
        $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('task')->result();
        $totalRecordwithFilter = $records[0]->allcount;

         ## Fetch records
        $this->db->select('*');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('task')->result();
        $data = array();
        $have_documents = false;
            // $records = $this->db->get('item',$rowperpage, $start)->result();

        foreach ($records as $record) {
      
        $action = '';
        
        $action .= '<a href="'.base_url().'jobcard/edit_task/'.$record->id.'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
        $action .= ' <button onclick="deleteRecord(this)" type="button" data-obj="task" data-id="'.$record->id.'" data-url="'.base_url().'users/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>';
        // print_r($record);die('njhjh');
        $category_name = $this->db->query('select name from task_category where id = "'.$record->category_id.'"')->row_array();
        // print_r($category_name);die('');
       $data[] = array( 
        "id" => $record->id, 
         "title"=> $record->title,
         "description"=> $record->description,
         "category_id"=> $category_name['name'],
         "created_on"=> date('Y-m-d',strtotime($record->created_on)),
         "updated_on"=> date('Y-m-d',strtotime($record->updated_on)),
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

     public function jobCardUsersAssignedTask()
    {
        $posted_data = $this->input->post();
        if(isset($_GET['user_id'])){
            $user_id = $_GET['user_id'];
            $assigned_task_ids = $this->db->query('select id,assign_to_ids from assigned_task')->result_array();
            foreach ($assigned_task_ids as $value) {
                $ids = json_decode($value['assign_to_ids']);
                foreach ($ids as $id) {
                    if($user_id == $id){
                        $assigned_task_table_id[] = $value['id'];
                    }
                }
            }
        }

        // print_r($posted_data);die('asasas');
        $data = array();
        ## Read value
        $draw = $posted_data['draw'];
        $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
        $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 5; // Rows display per page
        $columnIndex = $posted_data['order'][0]['column']; // Column index
        $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
        $columnName = 'id';
        $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
        $searchValue = $posted_data['search']['value']; // Search value
        // Custom search filter 
        $assigned_task_title = (isset($posted_data['title'])) ? $posted_data['title'] : '';
        ## Search 
        $search_arr = array();
        $searchQuery = "";
        if($searchValue != ''){
            // $search_arr[] = " (title like '%".$searchValue."%' ) ";
        }
        if(isset($posted_data['username_for_report']) && !empty($posted_data['username_for_report'])){
            if($posted_data['username_for_report'] != ''){
                $username_for_report = "".implode(",",$posted_data['username_for_report'])."";

            // print_r($username_for_report);die();
                $assign_to_ids_arr[] = "assigned_task.assign_to_ids IN (".$username_for_report.") ";
            }
        }

        if(isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {
            $start_date = $posted_data['datefrom'];
            $end_date = $posted_data['dateto'];
            $search_arr[] = " (DATE(assigned_task.assigned_date) between '".$start_date."' and '".$end_date."') ";
        }
        if(count($search_arr) > 0){
            $searchQuery = ' ('.implode(" OR ",$search_arr).') ';
        }
        // $total = $this->items_model->get_items();

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('assigned_task')->result();
        $totalRecords = $records[0]->allcount;

        # Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('assigned_task')->result();
        $totalRecordwithFilter = $records[0]->allcount;
        // print_r($totalRecordwithFilter);die();
        // print_r($this->db->last_query());die();


        ## Fetch records
        $this->db->select('*');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('assigned_task')->result_array();

        if(isset($_GET['user_id']) && empty($searchQuery) && $_GET['user_id'] != 0)
        {
            $records = array();
        }
        $have_documents = false;
        $data = array();
        foreach ($records as $records_key => $record) {
            $tasker_name = array();
            $tasker_email = array();
            // print_r($record);
            $action = '';
            if($record['status'] == "completed" ){
                $action .= '<a style="display: none;"  href="'.base_url().'jobcard/edit_assigned_task/'.$record['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            } else{
                 $action .= '<a href="'.base_url().'jobcard/edit_assigned_task/'.$record['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }
            $action .= '<button type="button" id="'.$record['id'].'" data-id="<?php echo $record->id; ?>" data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="false" onclick="get_tasks_detail(this)" class="get_tasks_detail"><i class="fa fa-tasks"></i>&nbsp;Task Detail</button>';
            if($record['status'] == "completed" ){
                $action .= ' <button onclick="deleteRecord(this)" style="display: none;" type="button" data-obj="assigned_tasks_detail" data-id="'.$record['id'].'" data-url="'.base_url().'users/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>';
            }
            else{
                $action .= ' <button onclick="deleteRecord(this)" type="button" data-obj="assigned_tasks_detail" data-token-name="'.$this->security->get_csrf_token_name().'" data-token-value="'.$this->security->get_csrf_hash().'" data-id="'.$record['id'].'" data-url="'.base_url().'users/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>';
            }
            $id = $record['id'];
            $assign_to_ids_array = $record['assign_to_ids']; 
            $assigned_task_ids_array = $record['assigned_task_ids']; 
            $assign_to_ids_array = json_decode($assign_to_ids_array);
            $assigned_task_ids_array = json_decode($assigned_task_ids_array);

            foreach ($assign_to_ids_array as $assign_to_ids_values ) {
                $assign_to_name = $this->db->query('select * from users where id ="'.$assign_to_ids_values.'"' )->row_array();
                if ($assign_to_name) {
                    $tasker_name[] =  $assign_to_name['username'];
                    $tasker_email[] =  $assign_to_name['email'];
                }
            }
            foreach ($assigned_task_ids_array as $assigned_task_ids_values ) {
                $assigned_task_name = $this->db->query('select * from task where id ="'.$assigned_task_ids_values.'"' )->row_array();
                $tasks_name[] =  $assigned_task_name['title'];
            }
            $category_name = $this->db->query('select name from task_category where id = "'.$record['task_category_id'].'"')->row_array();
            $category_name = $category_name['name'];
            
            $query_task_detail = $this->db->query('select status from assigned_tasks_detail where assigned_task_id = "'.$record['id'].'"')->result_array(); 

            $flag = false;
            foreach ($query_task_detail as $key ) {
                if($key['status'] == "pending" || $key['status'] == "in_process" ){
                    $flag = false;
                    break;
                }else{
                    $flag = true;  
                }
            }

            if($flag == true){ 
                $status = "<p style='color: green; font-size: 15px'> Completed </p>";
                $this->db->query('update assigned_task set status= "completed" where id = "'.$record['id'].'" ');
            }else 
            { 
                $status = "<p style='color: red; font-size: 15px'> Pending </p>";
            }
            $data[] = array( 
                "id" => $id, 
                "tasker_name"=> implode("<br>",$tasker_name),
                "tasker_email"=> implode("<br>",$tasker_email),
                "tasks_name"=> " - ".implode("<br> - ",$tasks_name),
                "category_name"=> $category_name,
                "assigned_date"=> $record['assigned_date'],
                "status"=> $status,
                "completed_date"=> (!empty($record['completed_date'])) ? date('Y-m-d',strtotime($record['completed_date'])) : '',
                "action"=> $action
            ); 

           $tasker_name = array();
           $tasker_email = array();
           $tasks_name = array();
            // print_r($query_task_detail);
            // print_r($data);
        }
        $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
        );
        echo json_encode($response); 
       
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
            $number = "+".$this->input->post('mobile_code').$this->input->post('mobile');
            if($user_mobile['mobile'] == $number)
            {
                $msg = 'not_duplicate';
                echo json_encode(array('msg' => $msg));
                exit();
            }

        }
        
          $number = "+".$this->input->post('mobile_code').$this->input->post('mobile');
                $check_number = $this->users_model->check_user_number($number);
                if($check_number == true)
                {   
                // $this->session->set_flashdata('error', 'Number already exist');
                $msg = 'duplicate';
                echo json_encode(array('msg' => $msg));
                }
                  if($check_number == false)
                {   
                // $this->session->set_flashdata('error', 'Number already exist');
                $msg = 'not_duplicate';
                echo json_encode(array('msg' => $msg));
                }
                 }
        public function seen_notification()
        {
            $id = $this->loginUser->id;
            $result = $this->jobcard_model->seen_notification($id);
            echo $result;
        }

        public function get_notify()
        {
            $user_id = $this->loginUser->id;
            $query = $this->db->query('select * from notify_me where status = "unseen" AND tasker_id= "'.$user_id.'"')->result_array();
            if(!empty($query)){

            $query_id = $query[0]['id'];
            $session_id = $this->session->userdata('query_id');
            $this->session->unset_userdata('query_id');
            $this->session->set_userdata('query_id',$query_id);
            // print_r($query);
            if($user_id == $query[0]['tasker_id']){
                $count = count($query);
                $notify_span = array();
                if($session_id < $query_id) {
                    foreach ($query as $k) {
                    $assign_by =$this->db->query('select username from users where id = "'.$k['assign_by'].'"')->row_array();
                    $description = $k['description'];
                    $time1 = $k['created_on'];
                    
                    $time2 = date('Y-m-d H:i:s');

                    $date1 = strtotime($time1);  
                    $date2 = strtotime($time2); 
                    // $remain_time = $time2 - $time1;
                    $diff = abs($date2 - $date1);  
                    $remaining_time = gmdate("H:i:s", $diff);
                    $notify_[] = "<li><a><span><span id='assign_by_name'>".$assign_by['username']."</span></span><span id='description' class='message'>".$description."</span></a></li>";
                    // print_r($notify_);die('as');
                }
                }
                }
            }
            else
            { 
                $count = 0; 
            } 
            if(empty($notify_[0]))
            {
                $notify_ = "";
            }

            echo json_encode(array('count' => $count,'data' => $notify_,'responce' =>'false'));

        }


        }   
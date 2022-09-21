<?php defined('BASEPATH') or exit('No direct script access allowed');
class Cars extends Loggedin_Controller
{
    
    function __construct()
    {
        parent::__construct();
        // $this->load->model('Common_Model', 'common_model');
        $this->load->model('Cars_Model', 'cars_model');
        $this->load->model('Users_Model', 'users_model');
        $this->load->model('admin/Common_model','common_model');
    }

    public function index()
    {
        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Cars';
        $data['current_page_cars'] = 'current-page';
        $data['car_list'] = $this->cars_model->car_list();
        if ($this->uri->segment(3)) {
            $id = $this->uri->segment(3);
            $data['car_list'] = $this->cars_model->car_list($id);
            $this->template->load_admin('cars/view_car', $data);
        } else {
            $this->template->load_admin('cars/car_list', $data);
        }
    }

    public function makes()
    {

        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Makes';
         $data['current_page_makes'] = 'current-page';
        $data['makes_list'] = $this->cars_model->makes_list();
        $this->template->load_admin('cars/make_list', $data);
    }

    public function new_makes()
    {
        $data = array();
        // $this->template->load_admin('profiles/profile_form', $data);
        $id = $this->loginUser->id;
        $data['status'] = array();
        $data['title'] = 'Add Make';
        $data['formaction_path'] = 'save_makes';
        $data['current_page_makes'] = 'current-page';
        $data['makes_list'] = array();
        //$data['all_users'] = $this->users_model->get_user_by_id($id);
        $this->template->load_admin('cars/new_make', $data);
    }
    public function update_makes()
    {    
    if ($this->input->post()) {
        $this->load->library('form_validation');
        if ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'slug',
                    'label' => 'Slug',
                    'rules' => 'trim|required'));
              
        }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            echo json_encode(array('msg' => validation_errors()));
        } 
        else 
        {
            $makes_id = $this->input->post('id');
            $data = $this->input->post();
            $data['updated_by'] = $this->loginUser->id;
             // $data['current_page_makes'] = 'current-page';

            $check_slug = $this->cars_model->makes_list($makes_id);
            // print_r($data['slug']);echo "+++++++";
            // print_r($check_slug);die('aaaa');
            if($data['slug'] != $check_slug[0]['slug'])
            {
            $duplicate = $this->cars_model->checkRecordDuplication('valuation_make', 'slug', $data['slug'],$makes_id);
            }
            else
            {
                $duplicate = '';
            }
            if (!empty($duplicate))
            { 

            $msg = 'duplicate';
            echo json_encode(array('msg' => $msg, 'response' => 'This make already exists please try with another title!!')); 
            // exit();           
            }
            else
            {
                $title = [
                    'english' => $data['title'], 
                    'arabic' => $data['title_arabic'], 
                ];
                    unset($data['title']); 
                    unset($data['title_arabic']); 
                $data['title'] = json_encode($title);
                
                $result = $this->cars_model->update_makes($makes_id, $data);
                if (!empty($result)) 
                {
                    $this->session->set_flashdata('msg', 'Makes Updated Successfully');
                    $msg = 'success';
                    echo json_encode(array('msg' => $msg, 'mid' => $result));
                }
            }

            
            }
        }

    }
    public function edit_makes()
        {
            $makes_id = $this->uri->segment(3);
            $data['small_title'] = 'Edit';
            $data['current_page_makes'] = 'current-page';
            $data['formaction_path'] = 'update_makes';
            $data['title'] = 'Edit Make';
            $data['makes_list'] = $this->cars_model->makes_list($makes_id);
            $this->template->load_admin('cars/new_make', $data);
        }
        
    public function delete_makes()
    {
        $data = rtrim($_REQUEST['ids'], ",");
        $result = $this->db->query('delete from valuation_make where id in (' . $data .
            ') ');
        if (!empty($result)) {
            $this->session->set_flashdata('msg', 'Data Deleted Successfully');
            $msg = 'success';
            echo json_encode(array('msg' => $msg, 'mid' => $result));
        }
        //$data = explode(",",$data);

    }
    function make_prices($make_id = "")
    {
        if ($make_id == "") {
            redirect(base_url().'cars/makes');
        }
        $data = array();
        $id = $this->loginUser->id;
        $getTitle = $this->cars_model->makes_title($make_id);
        $data['small_title'] = $getTitle;
        $data['get_models'] = $this->cars_model->models_list_Bymake($make_id);
        $data['make_id'] = $make_id;
        $data['get_engin_size'] = $this->cars_model->eng_size_list();
        $data['current_page_makes'] = 'current-page';
        $data['valuate_option'] = $this->cars_model->get_valuate_Option();
        $this->template->load_admin('cars/makes_price', $data);
    }
    function makesPrices()
    {
        foreach ($_REQUEST['prices'] as $models) {
            foreach ($models['prices'] as $price_for_make) {
                if ($price_for_make['price'] == "" || $price_for_make['year_to'] == "" || $price_for_make['year_from'] ==
                    "") {
                    continue;
                }
                $data_push = $price_for_make;
                $data_push['valuation_make_id'] = $models['valuation_make_id'];
                $data_push['valuation_model_id'] = $models['valuation_model_id'];
                $this->new_makes_price($data_push);
            }
        }
        $this->session->set_flashdata('msg', 'Data Updated Successfully');
        redirect(base_url().'cars/make_prices/' . $_REQUEST['prices'][0]['valuation_make_id']);
    }

    public function new_makes_price($data)
    {


        $data['created_by'] = $this->loginUser->id;
        $update_flag = false;
        $data['make_title'] = $this->cars_model->makes_title($data['valuation_make_id']);
        $data['current_page_makes'] = 'current-page';
        // get model title by id
        $data['model_title'] = $this->cars_model->models_title($data['valuation_model_id']);
        $data['status'] = 1;
        // check edit id
        if (isset($data['id'])) {
            $update_flag = true;
            $price_id = $data['id'];
            $result = $this->cars_model->update_price($price_id, $data);
        } else {
            $data['created_on'] = date('Y-m-d H:i:s');
            $result = $this->cars_model->save_prices($data);
        }


    }
    public function ajax_makes_list()
    {
            $posted_data = $this->input->post();
            ## Read value
             $draw = $posted_data['draw'];
             $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
             $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 5; // Rows display per page
             $columnIndex = $posted_data['order'][0]['column']; // Column index
             $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
             $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
            $searchValue = $posted_data['search']['value']; // Search value
             $search_arr = array();
             $searchQuery = "";
             if($searchValue != ''){
                $search_arr[] = " (title like '%".$searchValue."%' ) ";
                // $search_arr[] = " (lname like '%".$searchValue."%' ) ";
             }
             if(count($search_arr) > 0){
            // $otherSQL = ' AND role = 6 ';
            $searchQuery = ' ('.implode(" OR ",$search_arr).') ';
             }
            ## Total number of records without filtering
            $this->db->select('count(*) as allcount');
            $records = $this->db->get('valuation_make')->result();
            $totalRecords = $records[0]->allcount;
             ## Total number of record with filtering
            $this->db->select('count(*) as allcount');
             if($searchQuery != '')
            $this->db->where($searchQuery);
            $records = $this->db->get('valuation_make')->result();
            $totalRecordwithFilter = $records[0]->allcount;

             ## Fetch records
        $this->db->select('*');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('valuation_make')->result();
        $data = array();
        $have_documents = false;

        foreach($records as $record ){
            $user_id = $record->id;
            $action="";
            
            $action .= '<a class="btn btn-xs btn-warning"   href="'.base_url().'cars/edit_makes/'.$record->id.'"><i class="fa fa-pencil"></i> Edit</a> ';
            $action .= ' <button onclick="deleteRecord(this)" type="button" data-obj="valuation_make" data-token-name="'. $this->security->get_csrf_token_name().'" data-token-value="'.$this->security->get_csrf_hash().'" data-id="'.$record->id.'" data-url="'.base_url().'cars/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>';
            $status="";
            if ($record->status==1 ){
                $status =  '<span class="tag tag-success tag-sm">  yes</span>';
            }
               if($record->status!=1){
             $status ='<span class="tag tag-success tag-sm"> no </span>'; 
                }       
                // print_r($record);die('aaaaaa');
            $title  = json_decode($record->title);
           $data[] = array( 
            "id" => $record->id, 
             "title"=> @$title->english,
             "status"=>$status,
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
    public function ajax_model_list()
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
        $search_arr = array();
        $searchQuery = "";
        if($searchValue != ''){
            $search_arr[] = " (title like '%".$searchValue."%' ) ";
            // $search_arr[] = " (lname like '%".$searchValue."%' ) ";
        }
        if(count($search_arr) > 0){
            // $otherSQL = ' AND role = 6 ';
            $searchQuery = ' ('.implode(" OR ",$search_arr).') ';
        }
        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
       
        $records = $this->db->get('valuation_model')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('valuation_model')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('valuation_model.*,valuation_make.title as make');
        $this->db->join('valuation_make', 'valuation_model.valuation_make_id = valuation_make.id', 'LEFT');
           if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('valuation_model')->result();
        $data = array();
        $have_documents = false;
        foreach($records as $record ){
            $user_id = $record->id;
            $action="";
            
            $action .= '<a class="btn btn-xs btn-warning"   href="'.base_url().'cars/edit_models/'.$record->id.'"> <i class="fa fa-pencil"></i> Edit</a>';
            $action .= ' <button onclick="deleteRecord(this)" type="button" data-obj="valuation_model" data-token-name="'. $this->security->get_csrf_token_name().'" data-token-value="'.$this->security->get_csrf_hash().'" data-id="'.$record->id.'" data-url="'.base_url().'cars/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>';

            $action.='<button  onclick="get_year_depeciation_detail(this)" type="button"  data-id="'.$record->id.'" data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="true" class="get_year_depeciation_detail btn btn-info btn-xs"><i class="fa fa-percent "></i>Year Depreciation Detail</button>';
            $status="";
            if ($record->status==1 ){
                $status =  '<span class="tag tag-success tag-sm">  Active</span>';
            }
               if($record->status!=1){
             $status ='<span class="tag tag-success tag-sm"> Inactive </span>'; 
                }       
                // print_r($record);die('aaaaaa');
            $title = json_decode($record->title);
            $make_title = json_decode($record->make);
            // print_r($title_make->english);
            $data[] = array( 
                "id" => $record->id, 
                "title"=> @$title->english,
                "make_title"=> @$make_title->english,
                 // "engine_size_title"=> $record->engine_size_title,
                "created_on"=>$record->created_on,
                "status"=>$status,
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
    


    public function ajax_price_list()
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
            $search_arr = array();
             $searchQuery = "";
             if($searchValue != ''){
                $search_arr[] = " (make_title like '%".$searchValue."%' ) ";
                $search_arr[] = " (year like '%".$searchValue."%' ) ";
                $search_arr[] = " (model_title like '%".$searchValue."%' ) ";
                $search_arr[] = " (engine_size_title like '%".$searchValue."%' ) ";
                $search_arr[] = " (created_on like '%".$searchValue."%' ) ";
                // $search_arr[] = " (lname like '%".$searchValue."%' ) ";
             }
             if(count($search_arr) > 0){
            // $otherSQL = ' AND role = 6 ';
            $searchQuery = ' ('.implode(" OR ",$search_arr).') ';
             }
                         ## Total number of records without filtering
            $this->db->select('count(*) as allcount');
           
            $records = $this->db->get('valuation_price')->result();
            $totalRecords = $records[0]->allcount;

             ## Total number of record with filtering
            $this->db->select('count(*) as allcount');
             if($searchQuery != '')
        $this->db->where($searchQuery);
            $records = $this->db->get('valuation_price')->result();
            $totalRecordwithFilter = $records[0]->allcount;

             ## Fetch records
          $this->db->select('*');
           if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('valuation_price')->result();
        $data = array();
        $have_documents = false;
        // print_r($records);
         foreach($records as $record ){
            $user_id = $record->id;
            $action="";
            
            $action .= '<a class="btn btn-xs btn-warning"   href="'.base_url().'cars/new_make_price/'.$record->id.'"><i class="fa fa-pencil"></i> Edit</a>';
            $action .= ' <button onclick="deleteRecord(this)" type="button" data-obj="valuation_price" data-token-name="'. $this->security->get_csrf_token_name().'" data-token-value="'.$this->security->get_csrf_hash().'" data-id="'.$record->id.'" data-url="'.base_url().'cars/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>';

            // $action.='<button type="button" id="$record->id " data-id="$record->id" data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="false" class="get_year_depeciation_detail btn btn-info btn-xs" onclick="get_year_depeciation_detail()"><i class="fa fa-percent "></i>Year Depreciation Detail</button>';  
                // print_r($record);die('aaaaaa');
            $make_title_english = json_decode($record->make_title);
            $model_title_english = json_decode($record->model_title);
           $data[] = array( 
            "id" => $record->id,    
             "make_title"=> @$make_title_english->english,
             "model_title"=> @$model_title_english->english,
             "engine_size_title"=> $record->engine_size_title,
             "price"=> $record->price,
             "year"=> $record->year,
             "created_on"=>$record->created_on,
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
    

    function validation_form($field, $validate, $custom_message = "")
    {
        $this->load->library('form_validation');
        //$this->form_validation->set_rules('save[]', 'Something', 'required|xss_clean');
        echo $field;
        if ($custom_message != "") {

            $this->form_validation->set_rules('save[' . $field . ']', $field, $validate, $custom_message);
        } else {
            $this->form_validation->set_rules($field, $field, $validate);
        }
        //trim|required|xss_clean|valid_email|htmlspecialchars|min_length[4]|max_length[40]


        if ($this->form_validation->run() == false) {
            return $this->form_validation->error_array();
        } else {

            return "true";
        }
    }


    public function saveSimpleData()
    {
        $data = $this->input->post();

        if (!isset($data['table_name'])) {

            $this->session->set_flashdata('msg',
                'Please set table name in which you want save data.<br> <strong>Note: </strong> you just need to add this code 
             <blockquote><code><pre><input type="hidden" value="set table name here" name="table_name"></pre></code></blockquote>
            in your form');
            if (isset($_SERVER['HTTP_REFERER'])) {
                //$redirect_to = str_replace(base_url(), '', $_SERVER['HTTP_REFERER']);
                redirect($_SERVER['HTTP_REFERER']);
            }


        }
        $table = $data['table_name'];


        // data for save
        if (!isset($data['save'])) {

            $this->session->set_flashdata('msg',
                '<strong>Note: </strong> you just need to set fields formate that you need to save in database like this 
             <code><input type="text"  name="save[field name]"></code>
            in your form');
            if (isset($_SERVER['HTTP_REFERER'])) {
                redirect($_SERVER['HTTP_REFERER']);
            }


        }
        $save_data = $data['save'];
        $validation_fields = $data['validation'];
        foreach ($validation_fields as $valid_fields => $value) {
            $return_res = $this->validation_form($valid_fields, $value);
            print_r($return_res);
            if ($return_res != "true") {
                $this->session->set_flashdata($table, $save_data);
                $this->session->set_flashdata("valid_error", $return_res);
                //redirect($_SERVER['HTTP_REFERER']);
            }
        }
        die();

        // check validation

        /*$empty_message = "";
        foreach ($save_data as $key => $val) {
        if (strpos($key, '_required') !== false) {
        if ($val == "") {
        $empty_message .= $key . " is required field<br>";
        }

        $newkey = str_replace('_required', "", $key);
        unset($save_data[$key]);
        $save_data[$newkey] = $val;

        }

        }

        if ($empty_message != "") {
        $this->session->set_flashdata('msg', $empty_message);
        $this->session->set_flashdata($table, $save_data);
        if (isset($_SERVER['HTTP_REFERER'])) {
        redirect($_SERVER['HTTP_REFERER']);
        }
        }*/


        //check duplicate record
        if (isset($data['duplicate'])) {
            $colunmDuplicate = $data['duplicate'];
            $valueDuplicate = $save_data[$colunmDuplicate];
            if ($this->cars_model->checkRecordDuplication($table, $colunmDuplicate, $valueDuplicate)) {


                $this->session->set_flashdata('msg', 'This ' . $colunmDuplicate .
                    ' value already exists please try with another value!!');

                if (isset($_SERVER['HTTP_REFERER'])) {
                    redirect($_SERVER['HTTP_REFERER']);
                }


            }
        }

        // save data to database
        $this->db->insert($table, $save_data);
        if ($this->db->insert_id() != "") {
            $this->session->set_flashdata('msg', 'data has been saved');
            if (isset($_SERVER['HTTP_REFERER'])) {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->session->set_flashdata('msg', 'there is some problem with data saving..');
            if (isset($_SERVER['HTTP_REFERER'])) {
                redirect($_SERVER['HTTP_REFERER']);
            }
        }


    }

    public function save_makes()
    {    
       $data = $this->input->post();
            // print_r($data);die();
      if ($this->input->post()) {

        $this->load->library('form_validation');
        if ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required'),
                 array(
                    'field' => 'title_arabic',
                    'label' => 'Arabic Title',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'slug',
                    'label' => 'Slug',
                    'rules' => 'trim|required'));
              
        }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            $msg = 'error';
            echo json_encode(array('msg' => $msg, 'response' => validation_errors()));
        } else {
            $data = $this->input->post();
            $data['created_by'] = $this->loginUser->id;
            $duplicate = $this->cars_model->checkRecordDuplication('valuation_make', 'slug', $data['slug']);
            if (!empty($duplicate))
            {
                $msg = 'duplicate';
                echo json_encode(array('msg' => $msg, 'response' => 'This make already exists please try with another title!'));
                exit();
            }
            $data['created_on'] = date('Y-m-d H:i:s');
            $title = [
                'english' => $data['title'], 
                'arabic' => $data['title_arabic'], 
            ];
            unset($data['title']); 
            unset($data['title_arabic']); 
            $data['title'] = json_encode($title);

            $result = $this->cars_model->save_makes($data);

            if (!empty($result)) 
            {
                $this->session->set_flashdata('msg', 'Makes created Successfully');
                $msg = 'success';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
               
            } 
            else
            {
                $msg = 'error';
                echo json_encode(array('msg' => $msg, 'response' => 'This make already exists please try with another title!'));
            }
        }
        
    }
}

    public function edit($id)
    {

        $this->load->model('cars_model');
        $user = $this->cars_model->getuser($id);
        $data = array();
        $data['user'] = $user;
        $this->form_validation->set_rules('valuation_make_tittle', 'tittle', 'required');
        $this->form_validation->set_rules('valuation_make_status', 'status', 'required');
        if ($this->form_validation->run() == false) {
            $this->template->load_admin('edit_make');
        } else {
            return false;
        }

    }


    public function new_car()
    {
        $data = array();
        $id = $this->loginUser->id;
        $data['status'] = array();
        $data['price_list'] = array();
        $data['car_list'] = array();
        $id = $this->uri->segment(3);
        $data['title'] = 'add car';
        $data['makes_list'] = $this->cars_model->makes_list(); //select make list
        $data['valuation_milage'] = $this->cars_model->valuation_milage($id);
        $data['option_list'] = $this->cars_model->get_valuate_Option();
        $data['milleage_list'] = $this->cars_model->milleage_list();
        //$data['option_list'] = $this->cars_model->option_list();

        if ($id != "") {
            $data['title'] = 'Edit';
            $data['car_list'] = $this->cars_model->car_list($id);
        }
        $this->template->load_admin('cars/new_car', $data);

    }
    public function save_car()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $data['created_by'] = $this->loginUser->id;

            $config = array(
                'upload_path' => './uploads/',
                'allowed_types' => "gif|jpg|png|jpeg",
                'overwrite' => true,
                'max_size' => "2048000",
                'max_height' => "768",
                'max_width' => "1024");
            $this->load->library('upload', $config);
            $post = $this->upload->do_upload('image');
            $post = array('image_path' => $this->upload->data(''));

            $data['image_path'] = $post['image_path']['full_path'];
            //get make title by id
            if ($this->input->post('valuation_make_id')) {
                $data['make_title'] = $this->cars_model->makes_title($_REQUEST['valuation_make_id']);
                // get model title by id
                $data['model_title'] = $this->cars_model->models_title($_REQUEST['valuation_model_id']);
            }
            $data['created_on'] = date('Y-m-d H:i:s');
            $result = $this->cars_model->save_car($data);
            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Cars created Successfully');
                redirect(base_url().'cars/car_list');
            } else {
                $this->session->set_flashdata('msg', 'Cars not created');
                redirect(base_url().'car/car_list');
            }
        } else {
            $data['small_title'] = 'Cars';
            $this->template->load_admin('cars/car_list', $data);
        }
    }


    // public function do_upload()
    //    {

    //            $config['upload_path']          = './uploads/';
    //            $config['allowed_types']        = 'gif|jpg|png';
    //            $config['max_size']             = 100;
    //            $config['max_width']            = 1024;
    //            $config['max_height']           = 768;

    //            $this->load->library('upload', $config);

    //            if ( ! $this->upload->do_upload('userfile'))
    //            {
    //                    $error = array('error' => $this->upload->display_errors());
    //                    echo "<pre>";
    //                    print_r($error);
    //            }
    //            else
    //            {
    //                    $data = array('upload_data' => $this->upload->data());
    //                    $this->load->view('upload_success', $data);
    //            }
    //    }


    public function edit_car()
    {

        $data = $this->input->post();
        $id = $data['id'];
        $data['created_by'] = $this->loginUser->id;
        $updated_car = $this->cars_model->update_car($id, $data);
        if (!empty($updated_car)) {
            $this->session->set_flashdata('msg', 'Cars Updated Successfully');
            redirect(base_url().'cars/car_list');
        }
    }

    public function get_engineBymodel($model_id = 0)
    {

        $data = array();
        $engine_sizes = $this->cars_model->get_engineBymodel($model_id);
        $years = $this->db->get_where('valuation_years',['model_id'=>$model_id])->result_array();
        echo json_encode(array('engine_sizes' =>$engine_sizes , 'years' => $years));

    }

    public function engine_sizes()
    {
        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Engine Sizes';
          $data['current_page_engine_size'] = 'current-page';
        $data['eng_size_list'] = $this->cars_model->eng_size_list();
        $this->template->load_admin('cars/engine_sizes', $data);
    }
    public function add_engine_sizes()
    {
        $data = array();
        // $this->template->load_admin('profiles/profile_form', $data);
        $data = array();
        $id = $this->loginUser->id;
        $data['status'] = array();
        $data['title_'] = 'Add Engine Sizes';
          $data['current_page_engine_size'] = 'current-page';
        $data['eng_size_list'] = array();
        $data['formaction_path'] = 'save_engine_sizes';
        //$data['all_users'] = $this->users_model->get_user_by_id($id);
        $this->template->load_admin('cars/add_engine_sizes', $data);
    }
    public function save_engine_sizes()
    {
        if ($this->input->post()) {
                $this->load->library('form_validation');
        if  ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required')
              
            );            
        }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            echo json_encode(array('msg' => validation_errors()));
        } else{
            $data = $this->input->post();
            $data['created_by'] = $this->loginUser->id;
            $data['created_on'] = date('Y-m-d H:i:s');
              // $data['current_page_auction'] = 'current-page';
            $result = $this->cars_model->save_engine_size($data);

            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Engine Size created Successfully');
                $msg = 'success';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
                
            }
        } 
    }
}

    public function update_engine_size()
    {
        if ($this->input->post()) {
        $this->load->library('form_validation');
        $location_id = $this->input->post('id');
        if ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required'),
               );
          }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            echo json_encode(array('msg' => validation_errors()));
        } else {
            $engine_size_id = $this->input->post('id');
            $data = $this->input->post();
            $result = $this->cars_model->update_engine_size($engine_size_id, $data);
            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Engine Size Updated Successfully');
                 $msg = 'success';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
                
            }
        }
    }
}
    public function edit_engine_sizes()
    {
        $engine_size_id = $this->uri->segment(3);
        $data['small_title'] = 'Edit';
        $data['title_'] = 'Edit Engine Size';
        $data['formaction_path'] = 'update_engine_size';
           $data['current_page_engine_size'] = 'current-page';
        $data['eng_size_list'] = $this->cars_model->eng_size_list($engine_size_id);
        $this->template->load_admin('cars/add_engine_sizes', $data);
    }
    public function delete_engine_sizes()
    {
        $data = rtrim($_REQUEST['ids'], ",");
        $result = $this->db->query('delete from valuation_enginesize where id in (' . $data .
            ') ');
        if (!empty($result)) {
            $this->session->set_flashdata('msg', 'Data Deleted Successfully');
            $msg = 'success';
            echo json_encode(array('msg' => $msg, 'mid' => $result));
        }
    }

    public function evaluation_config()
    {
        $data = array();
        // $this->template->load_admin('profiles/profile_form', $data);
        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Evaluation configure';
        $data['formaction_path'] = 'save_evaluation_configure';
        $data['current_page_evaluation'] = 'current-page';
        $data['valuation_config'] = $this->cars_model->valuation_config($id);
        $data['valuation_year'] = $this->cars_model->valuation_year($id);
        // $data['valuation_year_luxury'] = $this->cars_model->valuation_year_luxury($id);
        // $data['valuation_year_economy'] = $this->cars_model->valuation_year_economy($id);
        $data['mileage_list'] = $this->cars_model->milleage_list();
        $data['valuation_milage'] = $this->cars_model->valuation_milage($id);
        $data['option'] = $this->cars_model->get_valuate_Option();
        $this->template->load_admin('cars/evaluation_configure', $data);

    }
    public function save_evaluation_configure()
    {
        $this->load->library('form_validation');
        $id = $this->loginUser->id;

        // delete old settings by user id
        // $this->cars_model->delete_valuation_years_byuserId($id);
        $this->cars_model->delete_valuation_millage_byuserId($id);
        $this->cars_model->delete_valuation_config_setting_byuserId($id);

        // if (isset($_REQUEST['year_from_luxury']) && isset($_REQUEST['year_to_luxury']) ) {

        //     $counter = 0;
        //     foreach ($_REQUEST['year_from_luxury'] as $years) {
        //         $years_to = "";
        //         if (isset($_REQUEST['year_depreciation_luxury'][$counter]) && $_REQUEST['year_depreciation_luxury'][$counter] !=
        //             "") {
        //             $years_to = $_REQUEST['year_to_luxury'][$counter];
        //             $data = array(
        //                 'year_from' => $years,
        //                 "year_to" => $years_to,
        //                 "year_depreciation" => $_REQUEST['year_depreciation_luxury'][$counter],
        //                 "created_by" => $id,
        //                 "category" => 1,
        //                 'created_on' => date('Y-m-d H:i:s'));

        //             // $this->cars_model->insert_valuation_years($data);
        //             $counter++;
        //         }
        //     }
        // }

        //  if (isset($_REQUEST['year_from_economy']) && isset($_REQUEST['year_to_economy']) ) {

        //     $counter = 0;
        //     foreach ($_REQUEST['year_from_economy'] as $years) {
        //         $years_to = "";
        //         if (isset($_REQUEST['year_depreciation_economy'][$counter]) && $_REQUEST['year_depreciation_economy'][$counter] !=
        //             "") {
        //             $years_to = $_REQUEST['year_to_economy'][$counter];
        //             $data = array(
        //                 'year_from' => $years,
        //                 "year_to" => $years_to,
        //                 "year_depreciation" => $_REQUEST['year_depreciation_economy'][$counter],
        //                 "created_by" => $id,
        //                 "category" => 2,
        //                 'created_on' => date('Y-m-d H:i:s'));
        //             // $this->cars_model->insert_valuation_years($data);
        //             $counter++;
        //         }
        //     }
        // }

       
        // add milage
        if (isset($_REQUEST['mileage_from'])) {
            $counter = 0;
            foreach ($_REQUEST['mileage_from'] as $years) {
                $mileage_to = "";
                if (isset($_REQUEST['mileage_depreciation'][$counter]) && $_REQUEST['mileage_depreciation'][$counter] !=
                    "") {
                    // $mileage_to = $_REQUEST['mileage_to'][$counter];
                    $data = array(
                        'mileage_id' => $years,
                        // "millage_to" => $mileage_to,
                        "millage_depreciation" => $_REQUEST['mileage_depreciation'][$counter],
                        "created_by" => $id,
                        'created_on' => date('Y-m-d H:i:s'));
                    $this->cars_model->insert_valuation_millage($data);
                    $counter++;
                }
            }
        }

        $data = array(
            'config_setting_paint' => json_encode($_REQUEST['paint']),
            'config_setting_specs' => json_encode($_REQUEST['specs']),
            'config_option' => json_encode($_REQUEST['option']),
            'litre' => json_encode($_REQUEST['litre']),
            "created_by" => $id,
            'created_on' => date('Y-m-d H:i:s'));
          // $data['current_page_evaluation'] = 'current-page';
        $result = $this->cars_model->insert_valuation_config_setting($data);
        if (!empty($result)) {
            $this->session->set_flashdata('msg', 'Configuration Updated Successfully');
            echo "success";
        }

    }
    public function models()
    {
        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Models';
        $data['current_page_models'] = 'current-page';
        $data['models_list'] = $this->cars_model->models_list();
        // print_r($data);
        $this->template->load_admin('cars/models', $data);

    }

    public function get_year_depeciation_detail()
    {
        $model_id = $this->input->post('id');
        $data['valuation_year'] =$this->db->get_where('valuation_years',['model_id' => $model_id])->result_array();
        // $data['year_depreciation_detail'] = $this->db->query('select * from valuation_years where model_id ="'.$model_id.'" ')->result_array();
    
        $data_view = $this->load->view('cars/year_depreciation_detail', $data, true);    
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);

    }
    public function new_models()
    {
        // echo "helllo";die();
        $data = array();
        // $this->template->load_admin('profiles/profile_form', $data);
        $id = $this->loginUser->id;
        $data['status'] = array();
        $data['title'] = 'Add New Models';
        $data['current_page_models'] = 'current-page';
        $data['valuation_year'] = array();
        $data['formaction_path'] = 'save_models';
        // $data['models_list'] = array();
        $data['models_list'] = array();
        $data['makes_list'] = $this->cars_model->makes_list();
        $data['eng_size_list'] = $this->cars_model->eng_size_list();
        //$data['all_users'] = $this->users_model->get_user_by_id($id);
        $this->template->load_admin('cars/new_models', $data);
    }

    public function save_models()
    {
        $get_data = $this->input->post();
        if ($get_data) {
                $this->load->library('form_validation');
            if ($get_data) {
                $rules = array(
                    array(
                        'field' => 'title',
                        'label' => 'Title',
                        'rules' => 'trim|required'),
                    array(
                        'field' => 'title_arabic',
                        'label' => 'Arabic Title',
                        'rules' => 'trim|required'),
                    array(
                        'field' => 'title_arabic',
                        'label' => 'Arabic Title',
                        'rules' => 'trim|required'),
                    array(
                        'field' => 'valuation_make_id',
                        'label' => 'Make',
                        'rules' => 'trim|required')
                );
                  
            }
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('msg' => validation_errors()));
            } else {

                $data['title'] = $this->input->post('title');
                $data['valuation_make_id'] = $this->input->post('valuation_make_id');
                $exist = $this->cars_model->get_existed_model($data['title'],$data['valuation_make_id']);
                if (!empty($exist)) {
                    $this->session->set_flashdata('msg', 'Model already exists.');
                    redirect(base_url('cars/new_models'));
                    exit();
                }else{
                    $data['status'] = $this->input->post('status');
                    // $data['titile'] = $this->input->post('title');
                    $make_id = $this->input->post('valuation_make_id');
                    $engine_size_id = $this->input->post('valuation_engine_size_id');
                    $engine_size_id = implode(", ", $engine_size_id);
                    $data['valuation_engine_size_id'] = $engine_size_id;
                    // print_r($make_id); die();
                    $data['created_by'] = $this->loginUser->id;
                    $data['make_title'] = $this->cars_model->makes_title($make_id);
                    $data['engine_size_title'] = $this->cars_model->engine_size_title($engine_size_id);
                    // print_r($data); die();
                    $data['created_on'] = date('Y-m-d H:i:s');
                    $data['model_category'] = $this->input->post('model_category');

                    $title = [
                        'english' => $get_data['title'], 
                        'arabic' => $get_data['title_arabic'], 
                    ];
                    unset($get_data['title']); 
                    unset($get_data['title_arabic']); 
                    $data['title'] = json_encode($title);

                    $result = $this->cars_model->save_models($data);
                    unset($data['title']);
                    unset($data['valuation_make_id']);
                    unset($data['status']);
                    unset($data['valuation_engine_size_id']);
                    unset($data['make_title']);
                    unset($data['engine_size_title']);
                    unset($data['model_category']);

                     if (isset($_REQUEST['year']) ) {

                        $counter = 0;
                        foreach ($_REQUEST['year'] as $years) {
                            $years_to = "";
                            if (isset($_REQUEST['year_depreciation'][$counter]) && $_REQUEST['year_depreciation'][$counter] !=
                                "") {
                                $years = $_REQUEST['year'][$counter];
                                $data = array(
                                    'year' => $years,
                                    "year_depreciation" => $_REQUEST['year_depreciation'][$counter],
                                    "created_by" => $this->loginUser->id,
                                    "make_id" => $make_id,
                                    "model_id" => $result,
                                    'created_on' => date('Y-m-d H:i:s'));
                                $this->cars_model->insert_valuation_years($data);
                                $counter++;
                            }
                        }
                    }



                    if (!empty($result)) {
                        $this->session->set_flashdata('success', 'Model has been created Successfully!');
                    } else {
                        $this->session->set_flashdata('error', 'Model has been failed to create.');
                    }

                    redirect(base_url('cars/models'));
                    
                }
            }
        }
    }

    public function update_models()
        {        
            $get_data = $this->input->post();
            if ($get_data) 
            {
                $this->load->library('form_validation');
                if ($this->input->post()) {
                    $rules = array(
                    array(
                        'field' => 'title',
                        'label' => 'Title',
                        'rules' => 'trim|required'),
                    array(
                        'field' => 'valuation_make_id',
                        'label' => 'Make',
                        'rules' => 'trim|required')
                    );
              
                }
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    echo json_encode(array('msg' => validation_errors()));
                }
                else{

                    $models_id = $this->input->post('id');
                    $data['title'] = $this->input->post('title');
                    $data['valuation_make_id'] = $this->input->post('valuation_make_id');
                    $data['status'] = $this->input->post('status');
                    // $data['titile'] = $this->input->post('title');
                    $make_id = $this->input->post('valuation_make_id');
                   // $model_id = $this->

                    $engine_size_id = $this->input->post('valuation_engine_size_id');
                    $engine_size_id = implode(", ", $engine_size_id);
                    $data['valuation_engine_size_id'] = $engine_size_id;
                    // print_r($make_id); die();
                    $data['created_by'] = $this->loginUser->id;
                    $data['make_title'] = $this->cars_model->makes_title($make_id);
                    $data['model_category'] = $this->input->post('model_category');
                    $data['engine_size_title'] = $this->cars_model->engine_size_title($engine_size_id);

                    $title = [
                        'english' => $get_data['title'], 
                        'arabic' => $get_data['title_arabic'], 
                    ];
                    unset($get_data['title']); 
                    unset($get_data['title_arabic']); 
                    $data['title'] = json_encode($title);

                    $result = $this->cars_model->update_models($models_id, $data);
                    $this->db->where('model_id',$models_id);
                    $this->db->delete('valuation_years');
                    unset($data['title']);
                    unset($data['valuation_make_id']);
                    unset($data['status']);
                    unset($data['valuation_engine_size_id']);
                    unset($data['make_title']);
                    unset($data['engine_size_title']);
                    unset($data['model_category']);

                     if (isset($_REQUEST['year']) ) {
                        $models_id = $this->input->post('id');
                        $counter = 0;
                        foreach ($_REQUEST['year'] as $years) {
                            $years_to = "";
                            if (isset($_REQUEST['year_depreciation'][$counter]) && $_REQUEST['year_depreciation'][$counter] !=
                                "") {
                                $years = $_REQUEST['year'][$counter];
                                $data = array(
                                    'year' => $years,
                                    "year_depreciation" => $_REQUEST['year_depreciation'][$counter],
                                    "created_by" => $this->loginUser->id,
                                    "make_id" => $make_id,
                                    "model_id" => $models_id,
                                    'created_on' => date('Y-m-d H:i:s'));
                                $this->cars_model->insert_valuation_years($data);
                                $counter++;
                            }
                        }
                    }
                    // print_r($_REQUEST['year']);die('AAAA');
                    


                if (!empty($result)) 
                {
                    $this->session->set_flashdata('msg', 'Model updated Successfully!');
                    redirect(base_url('cars/models'));
                    exit();
                    // $msg = 'success';
                    // echo json_encode(array('msg' => $msg, 'mid' => $result));
                }
            }
        }
    }
    

        public function edit_models()
        {
            $models_id = $this->uri->segment(3);
            $data['small_title'] = 'Edit';
            $data['formaction_path'] = 'update_models';
            $data['current_page_models'] = 'current-page';
            $data['title'] = 'Edit Models';
            $data['makes_list'] = $this->cars_model->makes_list();
            $data['eng_size_list'] = $this->cars_model->eng_size_list();
            $data['models_list'] = $this->cars_model->models_list($models_id);
            // print_r($data['models_list']);die();
            $data['valuation_year'] =$this->db->order_by('year', 'DESC')->get_where('valuation_years',['model_id' => $models_id])->result_array();

            $this->template->load_admin('cars/new_models', $data);


        }


    public function delete_models()
    {
        $data = rtrim($_REQUEST['ids'], ",");
        $result = $this->db->query('delete from valuation_model where id in (' . $data .
            ') ');
        if (!empty($result)) {
            $this->session->set_flashdata('msg', 'Data Deleted Successfully');
            $msg = 'success';
            echo json_encode(array('msg' => $msg, 'mid' => $result));
        }
        //$data = explode(",",$data);

    }
    public function prices($make_price = '')
    {
        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'New Price';
          $data['current_page_prices'] = 'current-page';
        if ($make_price != "") {
            $data['price_list'] = $this->cars_model->price_list_bymake($make_price);
        } else {
            $data['price_list'] = $this->cars_model->price_list();
        }

        $this->template->load_admin('cars/prices', $data);
    }
    public function new_price()
    {
        // $this->template->load_admin('profiles/profile_form', $data);
        $data = array();
        $id = $this->loginUser->id;
        $data['title'] = 'Add Price';
        $data['formaction_path'] = 'new_price';
          $data['current_page_prices'] = 'current-page';
        $price_id = $this->uri->segment(3);
        $data['price_list'] = array();
        $data['eng_size_list'] = $this->cars_model->eng_size_list();
        if ($this->input->post()) {
            $this->load->library('form_validation');
             if ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'valuation_make_id',
                    'label' => 'Make',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'valuation_model_id',
                    'label' => 'Model',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'year',
                    'label' => 'Year From',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'price',
                    'label' => 'Price',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'engine_size_id',
                    'label' => 'Engine Size',
                    'rules' => 'trim|required')
            );
        }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
           echo json_encode(array('msg' => validation_errors()));
        } else {            
            $data = $this->input->post();
            $data['created_by'] = $id;
            $update_flag = false;
            //get make title by id
            if($this->input->post('valuation_make_id')){
            $data['make_title'] = $this->cars_model->makes_title($_REQUEST['valuation_make_id']);
            }
            // get model title by id
            if($this->input->post('valuation_model_id')){
            $data['model_title'] = $this->cars_model->models_title($_REQUEST['valuation_model_id']);
            }
             // get Engine title by id
            if($this->input->post('engine_size_id')){
            $data['engine_size_title'] = $this->cars_model->engine_size_title($_REQUEST['engine_size_id']); 
            }
            
            // check edit id
            if (isset($_REQUEST['id'])) {
                $price_id = $this->input->post('id');
                // print_r($data);die('aaaaaaa');
                $result = $this->cars_model->update_price($price_id, $data);
                if (!empty($result)) {
                    $this->session->set_flashdata('msg', 'Blog Updated Successfully');
                    $msg = 'success';
                    echo json_encode(array('msg' => $msg, 'mid' => $result));
                    exit();
                    
                    }
                } 
                else {
                $data['created_on'] = date('Y-m-d H:i:s');
                $result = $this->cars_model->save_prices($data);
                if (!empty($result)) {
                    $this->session->set_flashdata('msg', 'Price Add Successfully');
                    $msg = 'success';
                    echo json_encode(array('msg' => $msg, 'mid' => $result));
                    exit();
                    
                    }       
            }
        }
    }

        if ($price_id != "") {

            $data['small_title'] = 'Edit';
            $data['title'] = 'Edit Price';
            $data['formaction_path'] = 'new_price';
            // $data['price_list'] = $this->cars_model->models_list($price_id);
            $data['price_list'] = $this->cars_model->price_list($price_id);
            }


        $data['makes_list'] = $this->cars_model->active_makes_list();
        $data['option_list'] = $this->cars_model->get_valuate_Option();
        $this->template->load_admin('cars/new_price', $data);       
    }


    public function new_make_price($make_id = '',$price_id='')
    {
        // $this->template->load_admin('profiles/profile_form', $data);
        $data = array();
        $id = $this->loginUser->id;
        $data['title'] = 'Add Price';
        $data['formaction_path'] = 'new_make_price';
          $data['current_page_prices'] = 'current-page';

        if($this->uri->segment(3)){
            $price_id = $this->uri->segment(3);
        }
       
        if(!empty($price_id))
        {
        $data['price_id'] = $this->uri->segment(3);
            
        }
        else
        {
            $data['price_id'] = '';
        }
        $data['price_list'] = array();
        if ($make_id != "") {
            // $data['price_list'] = $this->cars_model->price_list_bymake($make_id);
            $data['makes_list'] = $this->cars_model->makes_list($make_id);
        } else {
            $data['price_list'] = $this->cars_model->price_list();
            $data['makes_list'] = $this->cars_model->makes_list();
        }

        if ($this->input->post()) {
            $this->load->library('form_validation');
             if ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'valuation_make_id',
                    'label' => 'Make',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'valuation_model_id',
                    'label' => 'Model',
                    'rules' => 'trim|required'),
             
                array(
                    'field' => 'year',
                    'label' => 'Year ',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'price',
                    'label' => 'Price',
                    'rules' => 'trim|required'),
                // array(
                //     'field' => 'option_id',
                //     'label' => 'Option',
                //     'rules' => 'trim|required'),
                array(
                    'field' => 'engine_size_id',
                    'label' => 'Engine Size',
                    'rules' => 'trim|required')
            );
        }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
           echo json_encode(array('msg' => validation_errors()));
        } else {            
            $data = $this->input->post();
            $data['created_by'] = $id;
            $update_flag = false;
            //get make title by id
            if($this->input->post('valuation_make_id')){
            $data['make_title'] = $this->cars_model->makes_title($_REQUEST['valuation_make_id']);
            }
            // get model title by id
            if($this->input->post('valuation_model_id')){
            $data['model_title'] = $this->cars_model->models_title($_REQUEST['valuation_model_id']);
            }
            // get Engine title by id
            if($this->input->post('engine_size_id')){
            $data['engine_size_title'] = $this->cars_model->engine_size_title($_REQUEST['engine_size_id']); 
            } 
            // check edit id
            if (isset($_REQUEST['id'])) {

                $update_flag = true;
                // $price_id = $this->input->post('id');
                unset($data['id']);
                $result = $this->cars_model->update_price($price_id, $data);
            } else {
                $data['created_on'] = date('Y-m-d H:i:s');
                $result = $this->cars_model->save_prices($data);       
            }
            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Make Price Updated Successfully');
                $msg = 'success';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
                
            }
        }
    }
    else
    {
        $data['make_id'] = $this->uri->segment(3);
        if ($price_id != "") 
        {

            $data['small_title'] = 'Edit';
            $data['title'] = 'Edit Price';
            $data['formaction_path'] = 'new_make_price';
            // $data['price_list'] = $this->cars_model->models_list($price_id);
            $data['price_list'] = $this->cars_model->price_list($price_id);
            // print_r($data['price_list']);
            $data['model_list'] = $this->cars_model->get_makes_data($data['make_id']);
             $data['makes_list'] = $this->cars_model->makes_list();
             $data['model_list'] = $this->cars_model->model_list();
            $data['option_list'] = $this->cars_model->get_valuate_Option();
            $data['engin_list'] = $this->cars_model->get_engineBymodel($data['price_list'][0]['valuation_model_id']);
        }
        else
        {

            $data['option_list'] = $this->cars_model->get_valuate_Option();
            $data['model_list'] = $this->cars_model->get_makes_data($data['make_id']);
            $data['engin_list'] = array();
        }
        
        $data['eng_size_list'] = $this->cars_model->eng_size_list();
        // print_r($data['eng_size_list']);
        $data['language'] = $this->language;
        $this->template->load_admin('cars/makes_price_form', $data);       
    }

        
    }

    public function get_model_data($model_id)
    {

        $data = array();
        $data = $this->cars_model->get_model_data($model_id);
        echo json_encode($data);


    }

    public function remove_existed_years()
    {
        $year_id = $this->input->post('year_id');

            $this->db->where('id', $year_id);
            $result = $this->db->delete('valuation_years');

            if($result){
                echo json_encode(array('error'=>false,'response'=>'Year has been removed successfully.'));
            }else{
                echo json_encode(array('error'=>true,'response'=>'Year has not been removed.'));
            }
    }

    public function get_make_data()
    {
        // $id=$_GET('make_id');
        $id = $this->input->post('make_id');
        $data = array();
        $data = $this->cars_model->get_makes_data($id);
        echo json_encode($data);

        // echo "data";
    }


    public function save_price()
    {
        if ($this->input->post()) {
            $this->load->library('form_validation');
             if ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'valuation_make_id',
                    'label' => 'Make',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'valuation_model_id',
                    'label' => 'Model',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'year_from',
                    'label' => 'Year From',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'year_to',
                    'label' => 'Year To',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'price',
                    'label' => 'Price',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'engine_size_id',
                    'label' => 'Engine Size',
                    'rules' => 'trim|required')
            );
        }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
           echo json_encode(array('msg' => validation_errors()));
        } else {            
            $data = $this->input->post();
            $make_id = $data['valuation_make_id'];
            $model_id = $data['valuation_model_id'];
            $engine_sizes_id = $data['engine_size_id'];
            $data['created_by'] = $this->loginUser->id;
            // get make title by id
            $make_title['make_title'] = $this->cars_model->makes_title($make_id);
            // get model title by id
            $data['model_title'] = $this->cars_model->models_title($model_id);
            $data['engine_size_title'] = $this->cars_model->engine_size_title($engine_size_id); 
            $data['created_on'] = date('Y-m-d H:i:s');
            $result = $this->cars_model->save_prices($data);
            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Price created Successfully');
                $msg = 'success';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
                
            } else {
                $this->session->set_flashdata('msg', 'Price not created');
               $msg = 'error';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
            }
        } 

        }
            // $data['small_title'] = 'Price';
            // $this->template->load_admin('cars/Prices', $data);
    }
    public function delete_prices()
    {
        $data = rtrim($_REQUEST['ids'], ",");
        $result = $this->db->query('delete from valuation_price where id in (' . $data .
            ') ');
        if (!empty($result)) {
            $this->session->set_flashdata('msg', 'Data Deleted Successfully');
            $msg = 'success';
            echo json_encode(array('msg' => $msg, 'mid' => $result));
        }
    }

    public function vehicles()
    {

        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'vehicles Detail';
          $data['current_page_auction'] = 'current-page';
        $data['car_datail_list'] = $this->cars_model->car_datail_list();
        $this->template->load_admin('cars/vehicle_list', $data);
    }
    public function add_vehicle()
    {
        $data = array();
        $id = $this->loginUser->id;
        $data['status'] = array();
        $data['cars_list'] = array();
        $data['cars_data'] = $this->cars_model->get_car();
        $data['eng_list'] = $this->cars_model->eng_list();
        $data['title'] = 'vehicles Detail';
        $this->template->load_admin('cars/vehicle_detail', $data);
    }
    public function save_detail()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $car_id = $data['car_id'];
            $engine_id = $data['engine_id'];
            $data['car_title'] = $this->cars_model->car_title($car_id);
            $data['engine_title'] = $this->cars_model->engine_size_title($engine_id);
            $data['created_by'] = $this->loginUser->id;
            // print_r($data);
            // die();
            $data['created_on'] = date('Y-m-d H:i:s');
            $result = $this->cars_model->detail_save($data);

            if (!empty($result)) {
                $this->session->set_flashdata('msg', ' created Successfully');
                redirect(base_url().'cars/vehicles');
            } else {
                $this->session->set_flashdata('msg', 'e not created');
                redirect(base_url().'cars/vehicles');
            }
        } else {
            $data['small_title'] = 'Engine Sizes';
            $data['doctor_type'] = array();
            $this->template->load_admin('cars/vehicle_list', $data);
        }
    }
    public function vehicle_specs()
    {

        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Vehicles Specification';
        $data['car_datail_list'] = $this->cars_model->car_datail_list();
        $this->template->load_admin('cars/vehicle_specs', $data);
    }
    public function add_vehicle_specs()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $data['created_by'] = $this->loginUser->id;
            $data['created_on'] = date('Y-m-d H:i:s');
            $result = $this->cars_model->save_car_specs($data);
            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Cars Specification Add  Successfully');
                redirect(base_url().'cars/vehicle_specs');
            } else {
                $this->session->set_flashdata('msg', 'Cars not created');
                redirect(base_url().'car/vehicle_specs');
            }
        } else {
            $data['small_title'] = 'Vehicle Specification';
            $data['title'] = 'Add Vehicle Specification';
            $data['car_datail_list'] = array();
            $this->template->load_admin('cars/add_vehicle_specs', $data);

        }
    }


    public function valuation_mileage()
    {
        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Mileage List';
        $data['current_page_valuation_mileage'] = 'current-page';

        $data['mileage_list'] = $this->cars_model->valuation_millage_list();
        $this->template->load_admin('mileage_deprciation/mileage_list', $data);
    }


    public function add_mileage()
    {
        $data = array();
        $data['small_title'] = 'Add';
        $data['current_page_valuation_mileage_depreciation'] = 'current-page';

        $data['formaction_path'] = 'save_mileage';
        $data['mileage_list_from'] = $this->cars_model->milleage_list_from();
        foreach ($data['mileage_list_from'] as  $value) {
            $data['mileage_list_from_sorted'][] = $value['milleage'];
        }
        asort($data['mileage_list_from_sorted']);
        $data['mileage_list_to'] = $this->cars_model->milleage_list_to();
         foreach ($data['mileage_list_to'] as  $value) {
            $data['mileage_list_to_sorted'][] = $value['milleage'];
        }
        asort($data['mileage_list_to_sorted']);
        $this->template->load_admin('mileage_deprciation/mileage_form', $data);
    }


    public function edit_mileage()
    {
        $mileage_id = $this->uri->segment(3);
        $data['small_title'] = 'Edit';
        $data['formaction_path'] = 'update_mileage';
        $data['current_page_valuation_mileage_depreciation'] = 'current-page';
        $data['mileage_info'] = array();
        $data['mileage_info'] = $this->cars_model->valuation_millage_list($mileage_id);
         $data['mileage_list_from'] = $this->cars_model->milleage_list_from();
        foreach ($data['mileage_list_from'] as  $value) {
            $data['mileage_list_from_sorted'][] = $value['milleage'];
        }
        asort($data['mileage_list_from_sorted']);
        $data['mileage_list_to'] = $this->cars_model->milleage_list_to();
         foreach ($data['mileage_list_to'] as  $value) {
            $data['mileage_list_to_sorted'][] = $value['milleage'];
        }
        asort($data['mileage_list_to_sorted']);
        // $data['mileage_list'] = $this->cars_model->milleage_list();
        $this->template->load_admin('mileage_deprciation/mileage_form', $data);
    }

    public function save_mileage()
    {
        $this->load->library('form_validation');
        $data = $this->input->post();
        if ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'millage_to',
                    'label' => 'Mileage To',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'millage_from',
                    'label' => 'Mileage From',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'millage_depreciation',
                    'label' => 'Depreciation',
                    'rules' => 'trim|required'),
                array(
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
            // $data['current_page_valuation_mileage'] = 'current-page';

            $data['created_on'] = date('Y-m-d H:i:s');
            // $data['millage_to']=$this->cars_model->get_millage_to_name($millage_to_id);
            // $data['millage_from']=$this->cars_model->get_millage_from_name($millage_from_id);
            $result = $this->cars_model->insert_valuation_millage($data);
            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Mileage Added Successfully');

                $msg = 'success';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
            }
        }
    }

    public function update_mileage()
    {
        $this->load->library('form_validation');
        $mileage_id = $this->input->post('id');
        if ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'millage_to',
                    'label' => 'Mileage To',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'millage_from',
                    'label' => 'Mileage From',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'millage_depreciation',
                    'label' => 'Depreciation',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'));
        }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            echo json_encode(array('msg' => validation_errors()));
        } else {
            $data = array(
                'millage_to' => $this->input->post('millage_to'),
                'millage_from' => $this->input->post('millage_from'),
                'millage_depreciation' => $this->input->post('millage_depreciation'),
                'status' => $this->input->post('status'),
                'updated_by' => $this->loginUser->id);
            // $data['current_page_valuation_mileage'] = 'current-page';

            $result = $this->cars_model->update_valuation_millage($mileage_id, $data);
            $this->session->set_flashdata('msg', 'Mileage Updated Successfully');
            $msg = 'success';
            echo json_encode(array('msg' => $msg, 'mid' => $result));

        }
    }

    public function location()
    {
        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Locations';
        $data['location_list'] = $this->cars_model->location_list();
        $this->template->load_admin('location/location_list', $data);
    }
    public function new_loaction()
    {
        $data = array();
        $data['small_title'] = 'Add';
        $data['formaction_path'] = 'save_location';
        $data['location_list'] = array();
        $this->template->load_admin('location/location_form', $data);
    }

    public function save_location()
    {
        $this->load->library('form_validation');
        $data = $this->input->post();
        if ($this->cars_model->checkRecordDuplication('valuation_location', 'slug', $data['slug'])) {
            $this->session->set_flashdata('msg',
                'This make already exists please try with another title!!');
            redirect(base_url().'cars/new_loaction');
        }

        if ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'name',
                    'label' => 'Name',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'slug',
                    'label' => 'Slug',
                    'rules' => 'trim|required'),
                array(
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
            $result = $this->cars_model->insert_valuation_location($data);
            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Location Added Successfully');
                $msg = 'success';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
            }
        }
    }

    public function edit_location()
    {
        $location_id = $this->uri->segment(3);
        $data['small_title'] = 'Edit';
        $data['formaction_path'] = 'update_location';
        $data['location_list'] = $this->cars_model->location_list($location_id);
        $this->template->load_admin('location/location_form', $data);
    }

    public function update_location()
    {
        $this->load->library('form_validation');
        $location_id = $this->input->post('id');
        if ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'name',
                    'label' => 'Name',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'slug',
                    'label' => 'Slug',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'description',
                    'label' => 'Description',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'));
        }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            echo json_encode(array('msg' => validation_errors()));
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'slug' => $this->input->post('slug'),
                'description' => $this->input->post('description'),
                'status' => $this->input->post('status'),
                'updated_by' => $this->loginUser->id);

            $flag_update = true;
            if ($this->cars_model->checkRecordDuplication('valuation_make', 'slug', $data['slug'],
                $location_id)) {
                $flag_update = false;
            }
            if ($flag_update == true) {
                if ($this->cars_model->checkRecordDuplication('valuation_make', 'slug', $data['slug'])) {
                    $this->session->set_flashdata('msg',
                        'This location already exists please try with another title!!');
                    //redirect('cars/update_location/'.$location_id);
                }
            }


            $result = $this->cars_model->update_valuation_location($location_id, $data);
            $this->session->set_flashdata('msg', 'Location Updated Successfully');
            $msg = 'success';
            echo json_encode(array('msg' => $msg, 'mid' => $result));

        }
    }
    public function delete_location()
    {
        $data = rtrim($_REQUEST['ids'], ",");
        $result = $this->db->query('delete from valuation_location where id in (' . $data .
            ') ');
        if (!empty($result)) {
            $this->session->set_flashdata('msg', 'Data Deleted Successfully');
            $msg = 'success';
            echo json_encode(array('msg' => $msg, 'mid' => $result));
        }
        //$data = explode(",",$data);

    }

    public function dates()
    {
        $data = array();
        $id = $this->loginUser->id;
        $data['small_title'] = 'Dates';
        $data['dates_list'] = $this->cars_model->dates_list();
        $this->template->load_admin('dates/dates_list', $data);
    }
    public function add_dates()
    {
        $data = array();
        $data['small_title'] = 'Add';
        $data['formaction_path'] = 'save_dates';
        $data['dates_list'] = array();
        $data['location_list'] = $this->cars_model->location_list();
        $this->template->load_admin('dates/dates_form', $data);
    }
    public function save_dates()
    {
        $this->load->library('form_validation');
        $data = $this->input->post();
        if ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'location_id',
                    'label' => 'Location',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'from_date',
                    'label' => 'From Date',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'to_date',
                    'label' => 'To Date',
                    'rules' => 'trim|required'));
        }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            echo json_encode(array('msg' => validation_errors()));
        } else {
            $dates = $this->input->post('times');
            $data['times'] = json_encode($dates);
            $location_id = $this->input->post('location_id');
            $data['location_name'] = $this->cars_model->get_location_name($location_id);
            $id = $this->loginUser->id;
            $data['created_by'] = $id;
            $data['created_on'] = date('Y-m-d H:i:s');
            $result = $this->cars_model->insert_dates($data);
            if (!empty($result)) {
                $this->session->set_flashdata('msg', 'Dates Added Successfully');

                $msg = 'success';
                echo json_encode(array('msg' => $msg, 'mid' => $result));
            }
        }
    }
    public function edit_dates()
    {
        $dates_id = $this->uri->segment(3);
        $data['small_title'] = 'Edit';
        $data['formaction_path'] = 'update_dates';
        $data['location_list'] = $this->cars_model->location_list();
        $data['dates_list'] = $this->cars_model->dates_list($dates_id);
        $this->template->load_admin('dates/dates_form', $data);
    }

    public function update_dates()
    {
        $this->load->library('form_validation');
        $dates_id = $this->input->post('id');

        if ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'location_id',
                    'label' => 'Location',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'from_date',
                    'label' => 'From Date',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'to_date',
                    'label' => 'To Date',
                    'rules' => 'trim|required'));
        }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            echo json_encode(array('msg' => validation_errors()));
        } else {
            $data = $this->input->post();
            $dates = $this->input->post('times');
            $data['times'] = json_encode($dates);


            $result = $this->cars_model->update_valuation_dates($dates_id, $data);
            $this->session->set_flashdata('msg', 'Dates Updated Successfully');
            $msg = 'success';
            echo json_encode(array('msg' => $msg, 'mid' => $result));

        }
    }
    public function delete_dates()
    {
        $data = rtrim($_REQUEST['ids'], ",");
        $result = $this->db->query('delete from valuation_dates where id in (' . $data .
            ') ');
        if (!empty($result)) {
            $this->session->set_flashdata('msg', 'Data Deleted Successfully');
            $msg = 'success';
            echo json_encode(array('msg' => $msg, 'mid' => $result));
        }
    }

    public function car_valuation()
    {
        $data['makes_list'] = $this->cars_model->makes_list();
        $data['milleage_list'] = $this->cars_model->milleage_list();
        $data['option_list'] = $this->cars_model->get_valuate_Option();
        $data['current_page_cars'] = 'current-page';

        $this->template->load_admin('car_valuation/car_valuation', $data);
    }
    
    public function car_valuation_results()
    {
        $data = array();

       
        $valuation_make_id = $this->input->post('valuation_make_id');
        $valuation_model_id = $this->input->post('valuation_model_id');
        $cat = $this->db->query('select model_category from valuation_model where id ='.$valuation_model_id)->row_array();
        // print_r($cat);die('aaa');
        $category = $cat['model_category'];
        $year_to = $this->input->post('year_to');
        $valuation_milleage_id = $this->input->post('valuation_milleage_id');
        $engine_size_id = $this->input->post('engine_size_id');
        $valuate_option = $this->input->post('valuate_option');
        $valuate_paint = $this->input->post('valuate_paint');
       // $valuate_gc = $this->input->post('valuate_gc');
        $valuate_gc = $this->input->post('valuate_gc');
        $email = $this->input->post('email');
        // get price
        
        $result = $this->db->query("SELECT * FROM `valuation_price` WHERE 
        (`valuation_make_id` = '" . $valuation_make_id .
            "' AND `valuation_model_id` = '" . $valuation_model_id . "') and ($year_to BETWEEN `year_from` AND `year_to`)
        AND (`engine_size_id` = '" . $engine_size_id . "' AND `option_id` = '" .
            $valuate_option . "') || (year_from='".$year_to."' || year_to='".$year_to."' )");

        $result = $result->result_array();
       

        if (isset($result) && !empty($result) && count($result) > 0) {
            $orig_price = $result[0]['price'];

            // check year depreciation
            $get_year_depre = $this->db->query("select year_depreciation from valuation_years where ($year_to BETWEEN `year_from` AND `year_to`) && (model_id='". $valuation_model_id ."') && (make_id='". $valuation_make_id."')
            || (year_from='".$year_to."' || year_to='".$year_to."' )");
            $get_year_depre = $get_year_depre->row_array();
            if (count($get_year_depre) > 0) {
                $get_year_depre['year_depreciation'] = (float)$get_year_depre['year_depreciation'];
                if ($get_year_depre['year_depreciation'] > 0) {
                    $year_valuate_price = $orig_price * ($get_year_depre['year_depreciation'] / 100);
                    $orig_price = $orig_price - $year_valuate_price;
                }
            }
            // check mileage depreciation
            $get_mileage_depre = $this->db->query("select millage_depreciation from valuation_millage where ('".$valuation_milleage_id."' BETWEEN `millage_from` AND `millage_to`)
            || (millage_from='".$valuation_milleage_id."' || millage_to='".$valuation_milleage_id."' )");
            $get_mileage_depre = $get_mileage_depre->row_array();
            if (count($get_mileage_depre) > 0) {
                if ($get_mileage_depre['millage_depreciation'] > 0) {
                    $mileage_valuate_price = $orig_price * ($get_mileage_depre['millage_depreciation'] /
                        100);
                    $orig_price = $orig_price - $mileage_valuate_price;
                }
            }
            $id = $this->loginUser->id;
            // check global config depreciation
            $get_global_depre = $this->db->query("select * from valuation_config_setting where created_by='" .
                $id . "'");
            $get_global_depre = $get_global_depre->row_array();    
            if (count($get_global_depre) > 0) {
                // check paint depreciation
                if ($get_global_depre['config_setting_paint'] != "" && $valuate_paint!="") {
                    $config_setting_paint = json_decode($get_global_depre['config_setting_paint'], 1);
                    //print_r($config_setting_paint);
                    $config_setting_paint = $config_setting_paint[$valuate_paint];
                    if ($config_setting_paint > 0) {
                        $config_setting_paint = (float)$config_setting_paint;
                        $config_setting_paint = $orig_price * ($config_setting_paint / 100);
                        $orig_price = $orig_price - $config_setting_paint;
                    }

                }
                
                // check paint depreciation
                if ($get_global_depre['config_setting_specs'] != "" && $valuate_gc!="") {
                    $config_setting_specs = json_decode($get_global_depre['config_setting_specs'], 1);
                    $config_setting_specs = $config_setting_specs[$valuate_gc];
                    if ($config_setting_specs > 0) {
                        $config_setting_specs = $orig_price * ($config_setting_specs / 100);
                        $orig_price = $orig_price - $config_setting_specs;
                    }

                }
            }
            $data =  number_format($orig_price,2);
             $msg = 'success';
            echo json_encode(array('msg' => $msg, 'mid' => $data));
            
        }
        else{

                // $this->session->set_flashdata('msg', 'Not Aailable Record');
                // redirect(base_url().'cars/car_valuation');
              $msg = 'not_available';
            echo json_encode(array('msg' => $msg, 'mid' => 'This model car price is not available'));
             
        }



    }

    public function milleage()
    {
        $data = array();
        $data['small_title'] = 'Mileage';
        $data['milleage_list'] = $this->cars_model->milleage_list();
        $this->template->load_admin('milleage/milleage_list', $data);
    }

    public function mileage()
    {
        $data = array();
        $data['small_title'] = 'Mileage List';
        $data['current_page_mileage'] = 'current-page';
        $data['milleage_list'] = $this->cars_model->milleage_list();
        $this->template->load_admin('milleage/milleage_list', $data);
    }
    public function new_mileage()
    {
        $data = array();
        $data['small_title'] = 'Add Mileage';
        $data['formaction_path'] = 'save_milleage';
          $data['current_page_mileage'] = 'current-page';
        $data['milleage_list'] = array();
        $this->template->load_admin('milleage/milleage_form', $data);
    }
    public function save_milleage()
    {
        $this->load->library('form_validation');
        $data = $this->input->post();
        if ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'milleage',
                    'label' => 'Milleage',
                    'rules' => 'trim|required'
                ),array(
                    'field' => 'mileage_label',
                    'label' => 'Milleage Label',
                    'rules' => 'trim|required'
                )
        );
        }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            echo json_encode(array('msg' => 'error', 'response' => validation_errors()));
        } else {

            $data['created_by'] = $this->loginUser->id;
            $duplicate = $this->cars_model->checkRecordDuplication('milleage', 'milleage', $data['milleage']);
            if (!empty($duplicate))
            {
                $msg = 'duplicate';
                echo json_encode(array('msg' => $msg, 'response' => 'This Mileage already exists please try with another!'));
                exit();
            }
            else
            {
                $data['created_on'] = date('Y-m-d H:i:s');
                $result = $this->cars_model->insert_milleage($data);
                if (!empty($result)) 
                {
                    $this->session->set_flashdata('msg', 'Milleage Added Successfully');
                    $msg = 'success';
                    echo json_encode(array('msg' => $msg, 'mid' => $result));
                }
            }
            
        }
    }


    public function edit_milleage()
    {
        $milleage_id = $this->uri->segment(3);
        $data['small_title'] = 'Edit Mileage';
        $data['formaction_path'] = 'update_milleage';
          $data['current_page_mileage'] = 'current-page';
        $data['milleage_list'] = $this->cars_model->milleage_list($milleage_id);
        $this->template->load_admin('milleage/milleage_form', $data);
    }

    public function update_milleage()
    {
        $this->load->library('form_validation');
        if ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'milleage',
                    'label' => 'Milleage',
                    'rules' => 'trim|required'
                ),array(
                    'field' => 'mileage_label',
                    'label' => 'Milleage Label',
                    'rules' => 'trim|required'
                )
        );
        }

        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            echo json_encode(array('msg' => 'error','response' => validation_errors()));
        } 
        else 
        {
            $data = $this->input->post();
            $milleage_id = $this->input->post('id');
            $duplicate = '';
            $check_mileage = $this->cars_model->milleage_list($milleage_id);

            if($data['milleage'] != $check_mileage[0]['milleage'])
            {
            $duplicate = $this->cars_model->checkRecordDuplication('milleage', 'milleage', $data['milleage']);
            }

            if (!empty($duplicate))
            { 

            $msg = 'duplicate';
            echo json_encode(array('msg' => $msg, 'response' => 'This Mileage already exists please try with another!!')); 
            }
            else
            {
                $data = $this->input->post();
                $result = $this->cars_model->update_milleage($milleage_id, $data);
                if(!empty($result))
                {
                    $this->session->set_flashdata('msg', 'Mileage Updated Successfully');
                    $msg = 'success';
                        echo json_encode(array('msg' => $msg, 'mid' => $result));
                }        
            }
        }
    }
    public function delete_milleage()
    {
        $data = rtrim($_REQUEST['ids'], ",");
        $result = $this->db->query('delete from milleage where id in (' . $data . ') ');
        if (!empty($result)) {
            $this->session->set_flashdata('msg', 'Data Deleted Successfully');
            $msg = 'success';
            echo json_encode(array('msg' => $msg, 'mid' => $result));
        }
    }

     public function delete()
    {
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        $res = false;
        // print_r($table);

        if ($table == "valuation_make") {

            $this->db->delete('valuation_price', ['valuation_make_id' =>$id]);
            $this->cars_model->delete_valuation_model_row_by_make($id);
            $res = $this->cars_model->delete_valuation_make_row($id);
        }
        if ($table == "valuation_model") {
            $this->db->delete('valuation_price', ['valuation_model_id' =>$id]);
            $res = $this->cars_model->delete_valuation_model_row($id);
            $this->db->where('model_id',$id);
            $this->db->delete('valuation_years');
        }
        if ($table == "milleage") {
            $res = $this->cars_model->delete_milleage_row($id);
        }
        if ($table == "valuation_location") {
            $res = $this->cars_model->delete_valuation_location_row($id);
        }
        if ($table == "valuation_dates") {
            $res = $this->cars_model->delete_valuation_dates_row($id);
        }

        if ($table == "valuation_enginesize") {
            $res = $this->cars_model->delete_engine_size_row($id);
        }
        if ($table == "valuation_price") {
            $res = $this->cars_model->delete_valuation_price_row($id);
         }
          if ($table == "valuation_enginesize") {
            $result = $this->cars_model->delete_valuation_price_rows($id);
         }
          if ($table == "valuation_millage") {
            $res = $this->cars_model->delete_valuation_millage_depriciation_row($id);
         }

        //do not change below code
        if ($res) {
            echo $return = '{"type":"success","msg":"Ok"}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }

    public function delete_bulk()
    {

        $ids_array =  $this->input->post("id");
        $table = $this->input->post("obj");
         if ($table == "valuation_make") {
            $result = $this->db->query('delete from ' . $table . ' where id in (' . $ids_array . ') ');
        } 
        if ($table == "valuation_model") {
            $result = $this->db->query('delete from ' . $table . ' where id in (' . $ids_array . ') ');
        } 
        if ($table == "milleage") {
            $result = $this->db->query('delete from ' . $table . ' where id in (' . $ids_array . ') ');
        }
        if ($table == "valuation_location") {
            $result = $this->db->query('delete from ' . $table . ' where id in (' . $ids_array . ') ');
        } 
        if ($table == "valuation_dates") {
            $result = $this->db->query('delete from ' . $table . ' where id in (' . $ids_array . ') ');
        }
        if ($table == "valuation_enginesize") {
            $result = $this->db->query('delete from ' . $table . ' where id in (' . $ids_array . ') ');
        }
        if ($table == "valuation_price") {
            $result = $this->db->query('delete from ' . $table . ' where id in (' . $ids_array . ') ');
        }
        if ($table == "valuation_millage") {
                    $result = $this->db->query('delete from ' . $table . ' where id in (' . $ids_array . ') ');
                }
      
        //do not change below code
        if ($result) {
            echo $return = '{"type":"success","msg":"Ok"}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }
    public function get_years()
    {

        // $id=$_GET('make_id');
        $make_id = $this->input->post('make_id');
        $model_id = $this->input->post('model_id');
        $data = array();
        $year_from = $this->db->query('select year_from from valuation_years where model_id= "'.$model_id.'" and make_id= "'.$make_id.'" ')->result_array() ;
        $year_to = $this->db->query('select year_to from valuation_years where model_id= "'.$model_id.'" and make_id= "'.$make_id.'" ')->result_array();
        $yf = 0;
        $yt = 0;
        foreach ($year_from as $key) {
           for ($year_from[$yf]['year_from']; $year_from[$yf]['year_from']<= $year_to[$yt]['year_to']  ; $year_from[$yf]['year_from']++ ) {
            $years[] =  $year_from[$yf]['year_from'];         
           }
           $yf++;
           $yt++;
        }
        // print_r($years);die();
        if(!empty($years))
        {
        asort($years);
        $msg = "success";
        echo json_encode(array('result' => $years));
        }
        else{
            echo json_encode(array('msg' => "No Years Found"));
        }

    }

    public function make_list_api()
    {
        // make dynamicn query 
        unset($sql); 
        if (isset($_POST['search']) && !empty($_POST['search'])){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search'])){
                $sql[] = '';
            }else{
                $sql[] = " title like '%".$_POST['search']."%' ";
                // $sql[] = " lname like '%".$_POST['search']."%' ";
            }
        }

        $query = "";
        // $otherSQL = ' AND role = 6';
        if (!empty($sql)){
             $query .= ' ( ' . implode(' OR ', $sql).') ';
        }
        $columns = "id, CONCAT(`title`) AS Name";
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

}

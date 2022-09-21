<?php defined('BASEPATH') or exit('No direct script access allowed');
class Auction extends Loggedin_Controller
{
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('Auction_model', 'auction_model');
        $this->load->model('items/Items_model', 'items_model');
        $this->load->model('users/Users_model','users_model');
        $this->load->model('files/Files_model','files_model');
        $this->load->model('crm/Crm_model','crm_model');
        $this->load->model('customer/Customer_model', 'customer_model');
        $this->load->helper('general_helper');
        $this->load->model('email/Email_model','email_model');
        $this->load->model('search/Search_model', 'sm');
    }

    public function index()
    {   
        $this->output->enable_profiler(TRUE);
        $data = array();
        $data['small_title'] = 'Online Auction List';
        $data['current_page_auction'] = 'current-page';
        $data['formaction_path'] = 'filter_auction_items';
        $data['auction_list'] = $this->auction_model->get_auction_list();
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['customer_type_list'] = $this->crm_model->customer_type_list_active();
        $data['seller_list'] = $this->users_model->get_all_sales_user();
        $this->template->load_admin('auction/auction_list', $data);
    }

    public function printCatalog($auctionId='')
    {
        if(empty($auctionId)){
            show_404();
        }

        $language = "english";

        $auction = $this->db->get_where('auctions', ['id' => $auctionId])->row_array();

        $select = $this->sm->getItemCatalog();

        $this->db->select($select);
        $this->db->from('auction_items ai');
        $this->db->join('item i', 'i.id = ai.item_id');
        //$this->db->join('auctions a', 'a.id = ai.auction_id');
        $this->db->join('item_makes imake', 'imake.id = i.make', 'LEFT');
        $this->db->join('item_models imodel', 'imodel.id = i.model', 'LEFT');

        $this->db->where('ai.auction_id', $auctionId);

        $this->db->order_by('ai.order_lot_no', 'ASC');

        $catalog = $this->db->get()->result_array();

        //print_r($catalog);
        $this->load->view('search/catalog', [
            'catalog' => $catalog, 
            'auction' => $auction, 
            'language' => $language
        ]);
    }

    // public function get_banner_details()
    // {
    //     $data = array();
    //     $data['auction_id'] = $this->input->post('id');
    //     print_r($data);die();
    //     $data['item_row'] = $this->items_model->get_item_details_by_id($data['item_id']); 
    //     if(isset($data['item_row'][0]['item_images']) && !empty($data['item_row'][0]['item_images']))
    //     {
    //         $images_ids = explode(",",$data['item_row'][0]['item_images']);
    //        $data['files_array'] = $this->files_model->get_multiple_files_by_ids($images_ids);
    //     }
    //     else
    //     {
    //         $data['files_array'] = array();
    //     }

    //     $data_view = $this->load->view('items/items_details_content', $data, true);
    //     echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    // }

    public function direct_sale($b64_uid) {
        $data = array();
        // print_r('expression');
        // $id = $this->loginUser->id;
        $data['small_title'] = 'Direct Sale';

        $auction_id = base64_decode(urldecode($b64_uid));
        $data['current_page_live_auction'] = 'current-page';
        
        $auction_data_array = $this->auction_model->get_auctions($auction_id);
        $data['category_id'] = $auction_data_array[0]['category_id'];

        $item_ids_list_multi_array = $this->auction_model->get_auction_item_ids($auction_id);
        if(isset($item_ids_list_multi_array) && !empty($item_ids_list_multi_array))
        {
            $item_ids_list = array_column($item_ids_list_multi_array,"item_id");
            $onlyNotSold = true;
            $data['items_list'] = $this->items_model->get_active_item_list($item_ids_list,$onlyNotSold, $auction_id);
            // print_r($data['items_list']);die();
            foreach ($data['items_list'] as $key => $value) {
                $user = $this->db->get_where('users', ['id' => $value['seller_id']])->row_array();
                $data[$key]['seller_name'] = $user['username'];
            }
        }
        else
        {
            $data['items_list'] = array();
        }
        $data['formaction_path'] = 'filter_items';
        $data['auction_id'] = $auction_id;
        $this->template->load_admin('auction/offline_auction', $data);
    }

    public function sales()
    {
        $data = array();
        $data['small_title'] = 'Auction Sales List';
        $data['current_page_auction_sales'] = 'current-page';
        $data['formaction_path'] = 'filter_auction_items';
        $data['auction_list'] = $this->auction_model->get_auction_list();
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['customer_type_list'] = $this->crm_model->customer_type_list_active();
        $data['seller_list'] = $this->users_model->get_all_sales_user();
        $this->template->load_admin('auction/auction_list', $data);
    }


    public function filter_auction_items()
    {
        $data = array();
        if($this->input->post())
        {
            $posted_data = $this->input->post();
            unset($sql); 
        $sql_cat = '';
        if (isset($posted_data['category_id']) && !empty($posted_data['category_id'])) 
        {
            $sql_cat = "  auctions.category_id = ".$posted_data['category_id']."  ";
            
        }
        if (isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {
            $start_date = $posted_data['datefrom'];
            $end_date = $posted_data['dateto'];
            $sql[] = " (DATE(auctions.created_on) between '$start_date' and '$end_date') ";
        }
            $query = "";
            if (!empty($sql)) {
                $query .= ' (' . implode(' OR ', $sql).' ) AND '.$sql_cat;
            }
            else
            {
                 $query .= $sql_cat;
            }

            $data['auction_list'] = $this->auction_model->auction_filter_list($query);
            
            $data_view = $this->load->view('auction/ajax_auction_content', $data, true);
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found'));
        }
    }


    public function live()
    {
        $this->output->enable_profiler(TRUE);
        $data = array();
        $data['small_title'] = 'Live Auction List';
        $data['current_page_live_auction'] = 'current-page';
        $data['formaction_path'] = 'filter_live_auction_items';
        $data['user'] = (array)$this->loginUser;
        $data['live_auction_list'] = $this->auction_model->get_live_auction_list();
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['customer_type_list'] = $this->crm_model->customer_type_list_active();
        $data['seller_list'] = $this->users_model->get_all_sales_user();
        $this->template->load_admin('auction/live_auction_list', $data);
    }

    public function auctionDeposit()
    {
        $this->output->enable_profiler(TRUE);
        $data = array();
        $data['small_title'] = 'Live Deposit List';
        $data['current_page_live_auction'] = 'current-page';
        $data['formaction_path'] = 'filter_live_auction_items';
        $data['auction_id'] = $this->uri->segment(3);
        // print_r($data['auction_id']);
        $data['users_list'] = $this->db->get_where('users',['role' => 4])->result_array();
        $this->template->load_admin('auction/auction_deposit', $data);
    }

    //deposit users
    public function deposit_users_list()
    {
        $posted_data = $this->input->post();
        $data = array();
        // return ;
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
            $search_arr[] = " (users.fname like '%".$searchValue."%' or users.mobile like '%".$searchValue."%'  or users.email like '%".$searchValue."%') ";
        }
        

        if(count($search_arr) > 0){
            $searchQuery = implode(" AND ",$search_arr);
        }
        $auction_id = $this->uri->segment(3);

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('auction_deposit');
        $this->db->join('users','auction_deposit.user_id = users.id', 'LEFT');
        $this->db->where('auction_deposit.auction_id', $auction_id);
        $this->db->where('users.role', 4);
        $this->db->where('auction_deposit.status', 'approved');
        $this->db->where('auction_deposit.deposit_type', 'temporary');
        $records = $this->db->get()->result();
        $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
   
        $this->db->from('auction_deposit');
        $this->db->join('users','auction_deposit.user_id = users.id', 'LEFT');
        $this->db->where('auction_deposit.auction_id', $auction_id);
        $this->db->where('users.role', 4);
        $this->db->where('auction_deposit.status', 'approved');
        $this->db->where('auction_deposit.deposit_type', 'temporary');
        if($searchQuery != ''){
            $this->db->where($searchQuery);
        }
        $records = $this->db->get()->result();
        $totalRecordwithFilter = $records[0]->allcount;

        $this->db->select('auction_deposit.*,users.fname,users.mobile,users.email');
        
        
        $this->db->from('auction_deposit');
        $this->db->join('users','auction_deposit.user_id = users.id', 'LEFT');
        $this->db->where('auction_deposit.auction_id', $auction_id);
        $this->db->where('users.role', 4);
        $this->db->where('auction_deposit.status', 'approved');
        $this->db->where('auction_deposit.account', 'DR');
        $this->db->where('auction_deposit.deposit_type', 'temporary');
        $this->db->order_by('id', 'desc');
        if($searchQuery != ''){
            $this->db->where($searchQuery);
        }
        $this->db->limit($rowperpage, $start);
        // $this->db->order_by($columnName, $columnSortOrder);
        $users_list = $this->db->get()->result_array();
        // $this->db->last_query();die();

        $action = '';
        // $users_list = $this->db->get_where('users', ['role' => 4])->result_array();

        foreach ($users_list as $key => $value) {
            $action = '<a href="#" onclick="myfunc(this)" data-id="'.$value['id'].'" data-amount="'.$value['amount'].'" data-description="'.$value['description'].'" data-payment_type="'.$value['payment_type'].'" data-card="'.$value['card_number'].'" data-user_id="'.$value['user_id'].'" data-name="'.$value['fname'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a> 
            
                <button onclick="permanent(this)" type="button" data-obj="auction_deposit" data-field="permanent" data-id="'.$value['id'].'" data-amount="'.$value['amount'].'" data-url="'.base_url().'auction/change_to_permanent" class="btn btn-primary btn-xs" title="Permanent"><i class="fa fa-money"></i> Convert to Deposit </button>

                <button onclick="Refund(this)" type="button" data-token-name="'.$this->security->get_csrf_token_name().'" data-token-value="'.$this->security->get_csrf_hash().'" data-obj="auction_deposit" data-field="refund" data-id="'.$value['id'].'" data-amount="'.$value['amount'].'" data-url="'.base_url().'auction/refund" class="btn btn-primary btn-xs" title="Refund"><i class="fa fa-money"></i> Refund </button>

                <a href="'.base_url().'auction/user_payment_receipt/'.$value['id'].'"  class="btn btn-info btn-xs" target="_blank"><i class="fa fa-print"></i>Receipt</a>
                ';

            $data[] = array( 
             "id"=>$value['id'],
             "card_number"=>$value['card_number'],
             "fname"=>$value['fname'],
             "mobile"=>$value['mobile'],
             "email"=>$value['email'],
             "amount"=>$value['amount'],
             "created_on"=>$value['created_on'],
            "action"=> $action
            ); 
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        echo json_encode($response); 

    }

    public function refund()
    {
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        $column = $this->input->post("column");
        $res = false;

        if ($table == "auction_deposit") {
            $deposit_data = $this->db->get_where('auction_deposit', ['id' => $id])->row_array();
            $res = $this->db->update('auction_deposit', ['status' => $column], ['id' => $id]);
            $deposit_data['account'] = 'CR';
            $deposit_data['created_by'] = $this->session->userdata('logged_in')->id;
            $deposit_data['created_on'] = date('Y-m-d H:i:s');
            unset($deposit_data['id']);
            $this->db->insert('auction_deposit', $deposit_data);
        }
        if ($table == "auction_item_deposits") {
            $item_deposits = $this->db->get_where('auction_item_deposits', ['id' => $id])->row_array();
            $res = $this->db->update('auction_item_deposits', ['status' => $column], ['id' => $id]);
            $item_deposits['account'] = 'CR';
            $item_deposits['created_by'] = $this->session->userdata('logged_in')->id;
            $item_deposits['created_on'] = date('Y-m-d H:i:s');
            unset($item_deposits['id']);
            $this->db->insert('auction_item_deposits', $item_deposits);
        }

        // do not change below code
        if ($res) {
            echo $return = '{"type":"success","msg":"Action succeded."}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }

    public function change_to_permanent()
    {
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        $column = $this->input->post("column");
        $res = false;

        if ($table == "auction_deposit") {
            $res = $this->db->update('auction_deposit', ['deposit_type' => $column], ['id' => $id]);
        }
        // do not change below code
        if ($res) {
            echo $return = '{"type":"success","msg":"Action succeded."}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }


    public function find_user()
    {
        $data = array();
        $search = $this->input->post('search');
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("fname", $search);
        $this->db->or_where("mobile", $search);
        $query = $this->db->get();
        $user = $query->row_array();
        if ($user) {
            $response = array(
               "status" => 'success',
               "user" => $user
            );
        }else{
            $response = array(
               "status" => 'error',
               "msg" => 'User not exist'
            );
        }

        echo json_encode($response); 
    }

    // Add a new User
    public function save_user()
    {
        $data['small_title'] = 'Add';
        $data['formaction_path'] = 'save_user';
        $data['current_page_live_auction'] = 'current-page';
        $data['all_users'] = array();
        $role_id = $this->loginUser->id; 
        $this->load->library('form_validation');
        if ($this->input->post()) {
            $items_documents_data = $this->session->userdata('items_documents');

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
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
                exit();
            } 
            // $documents = $this->uploadFiles($_FILES);
            if($this->input->post())
            {
                $number = "+".$this->input->post('mobile_code').$this->input->post('mobile');
                $check_number = $this->users_model->check_user_number($number);
                if($check_number == true)
                {   
                $this->session->set_flashdata('error', 'Number already exist.');
                $msg = 'duplicate';
                echo json_encode(array('msg' => $msg,'mid' => 'Number already exist.'));
                exit();
                }
                $email = $this->input->post('email');
                $result = $this->users_model->check_email($email);
                if($result == true)
                {   
                    $this->session->set_flashdata('error', 'Email already exist.');
                    $msg = 'duplicate';
                    echo json_encode(array('msg' => $msg,'mid' => 'Email already exist.'));
                    exit();
                }
                else{
                    $data = array(
                        'fname' => $this->input->post('fname'),
                        'lname' => $this->input->post('lname'),
                        'username' =>$this->input->post('fname').' '.$this->input->post('lname'),
                        'email' => $this->input->post('email'),
                        'role' => '4',
                        'password' => hash("sha256", $this->input->post('password')),
                    );
                    if(!empty($items_documents_data))
                    {
                        $data['documents'] = $items_documents_data;
                    }
                     if($this->input->post('phone')){
                        $data['phone'] = $this->input->post('phone');
                    }

                    if($this->input->post('description')){
                        $data['description'] =$this->input->post('description');     
                    }
                    if($this->input->post('prefered_language')){
                        $data['prefered_language'] =$this->input->post('prefered_language');     
                    }
                    if($this->input->post('mobile')){
                        $data['mobile'] =$this->input->post('mobile');  
                        $data['mobile'] = "+".$this->input->post('mobile_code').$this->input->post('mobile');
                    }
                    if($this->input->post('reg_type')){
                        $data['reg_type'] =$this->input->post('reg_type');     
                    }  

                    
                    $data['created_on'] = date('Y-m-d H:i:s');
                    // $data['updated_on'] = '0000-00-00 00:00:00';
                    if($this->input->post('email'))
                    {

                        $data['email_verification_code'] = getNumber(4);
                        $this->load->library('email');
                     
                        $email = $this->input->post('email');  
                        $password = $this->input->post('password');         
                        $to = $email;
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

                    }
                    $mobile_verification_code = getNumber(4);
                    //SMS verification process start
                    $this->load->library('SendSmart');
                    $sms = $this->lang->line('pioneer_verification_code_is') . $mobile_verification_code;
                    // $number = '971561387755';
                    $number2 = '971561387755';
                    $sms_response = $this->sendsmart->sms($number, $sms);
                    // print_r($data);die();
                    $previous_id = $this->users_model->get_max_id();
                    // $data['code'] = sprintf('%08d', $previous_id[0]['max(id)']);
                    $data['code'] = $mobile_verification_code;
                    $result = $this->users_model->insert_user_details($data);
                    if (!empty($result)) {

                        $auctionCont = $this->input->post('sold');
                        if ($auctionCont) {
                        $this->session->set_flashdata('msg', 'User Add Successfully.');
                        // redirect(base_url().'livecontroller/sold/items');
                        $redict_users_list = "user_listing";
                        echo json_encode(array('msg'=>$redict_users_list,'mid'=>'User add Successfully.'));
                        exit();
                        }
                        else
                        {
                        $this->session->set_flashdata('msg', 'User Add Successfully.');
                        // redirect(base_url().'auction/auctionDeposit');
                        echo json_encode(array('msg'=>'user_listing','mid'=>'User add Successfully.'));
                        exit();
                        }     
                    }
                }
            }
        }
        else{
            // $this->session->set_flashdata('msg', 'User Add Successfully');
            $this->template->load_admin('auction/users_form',$data);
        }
    }

    public function update_deposit()
    {
        $data = array();
        $posted_data = $this->input->post();
        $posted_data['payment_type'] = $posted_data['payment_type'];
        $posted_data['description'] = $posted_data['description'];
        $posted_data['card_number'] = $posted_data['card_number'];
        $posted_data['status'] = 'approved';
        if (isset($posted_data['id']) && !empty($posted_data['id'])) {
            $id = $posted_data['id'];
            unset($posted_data['id']);
            unset($posted_data['user_name']);
            $posted_data['created_by'] = $this->loginUser->id;
            $result = $this->db->update('auction_deposit', $posted_data, ['id' => $id]);
            $message = 'updated successfully.';
        }else{
            $posted_data['deposit_type'] = 'temporary';
            $posted_data['created_on'] = date('Y-m-d H:i:s');
            $posted_data['created_by'] = $this->loginUser->id;
            $result = $this->db->insert('auction_deposit', $posted_data);
            $message = 'Deposit successfully.';
        }

        if ($result) {
            $response = array(
               "status" => 'success',
               "result" => $result,
               "msg" => 'Success! '.$message
            );
        }else{
            $response = array(
               "status" => 'error',
               "msg" => 'Error! deposit faild please try again.'
            );
        }

        echo json_encode($response);
    }

    public function user_payment_receipt($id){
        $data['receipt'] = $this->db->get_where('auction_deposit',['id' =>$id])->row_array();
        $data['receipt_no'] = 'N/A';
        $data['ref-no'] = 'N/A';
        $data['lot_no'] = 'N/A';
        $data['auction_id'] =  $data['receipt']['auction_id'];
        $user_data = $this->db->get_where('users',['id' =>$data['receipt']['user_id']])->row_array();
        $data['customer_name'] =$user_data['username'];
        $data['purpose'] ='Deposit';
        $data['payable_amount'] ='N/A';
        // $rand_str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // $data['randomKey'] = $this->generate_string($rand_str);
        $receipt = date('y-M', strtotime($data['receipt']['created_on']));
        $data['randomKey'] = $receipt.'-01-'.$data['receipt']['id'];
        $data['payment_mode'] = $data['receipt']['payment_type'];
        $data['description'] = $data['receipt']['description'];
        $data['card_number'] = $data['receipt']['card_number'];
        $data['amount'] = $data['receipt']['amount'];
        $this->load->view('receipts/cash_receipt_user',$data);
    }
    private function generate_string($input, $strength = 6) {
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) 
        {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
            return $random_string;
    }

    public function filter_live_auction_items()
    {
        $data = array();
        if($this->input->post())
        {
            $posted_data = $this->input->post();
            unset($sql); 
            $sql_cat = '';
        if (isset($posted_data['category_id']) && !empty($posted_data['category_id'])) 
        {
            $sql_cat = "  auctions.category_id = ".$posted_data['category_id']."  ";
            
        }
        if (isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {
            $start_date = date('Y-m-d',strtotime($posted_data['datefrom']));
            $end_date = date('Y-m-d',strtotime($posted_data['dateto']));
            $sql[] = " (DATE(auctions.created_on) between '$start_date' and '$end_date') ";
        }
            $query = "";
            if (!empty($sql) && !empty($sql_cat)) {
                $query .= ' (' . implode(' OR ', $sql).' ) AND '.$sql_cat;
            }elseif (!empty($sql) && empty($sql_cat)) {
                $query .= ' (' . implode(' OR ', $sql).' ) ';
            }
            else
            {
                 $query .= $sql_cat;
            }
            $data['user'] = (array)$this->loginUser;
            $data['auction_list'] = $this->auction_model->live_auction_filter_list($query);
            
            $data_view = $this->load->view('auction/ajax_live_auction_content', $data, true);
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found.'));
        }
    }


    public function validate_check_registration_no()
    {
        $check_number = $this->auction_model->check_registration_no($this->uri->segment(3));
        if(isset($check_number) && !empty($check_number))
        {
            print_r($check_number);
        }
        else
        {
            echo '200';
        }
    }

 
    public function get_stock_list()
    {
        $auction_id = $this->input->post('id');
        $data = array();
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['auction_id'] = $auction_id;
        // $auction_id = base64_decode(urldecode($auction_id));
        $auction_data_array = $this->auction_model->get_auctions($auction_id);
        $data['category_id'] = $auction_data_array[0]['category_id'];

        // $item_ids_list_multi_array = $this->auction_model->get_auction_item_ids($auction_id);
        // $data['item_ids_list'] = array_column($item_ids_list_multi_array,"item_id");
        
        if(isset($auction_data_array[0]['category_id']) && !empty($auction_data_array[0]['category_id']))
        {
            $sql[] = " item.category_id = ".$auction_data_array[0]['category_id']." ";
            // $sql[] = " item.in_auction = 'no' ";
            $sql[] = " item.item_status = 'completed' ";
            $sql[] = " item.status = 'active' ";
            $sql[] = " item.sold = 'no' ";
        }

        $query = "";
        if (!empty($sql)) {
            $query .= ' ' . implode(' AND ', $sql);
        }
        $data_items_list = $this->items_model->items_filter_limit_list_active($query);
        // print_r($data_items_list);die();
        if(isset($data_items_list) && !empty($data_items_list)){
            $items_ids = array();
            $auction_ids = array();
            $auction_item_id = array();
            $sold_items_id = array();
            $validItemsList = array();
            // get active item ids 
            foreach ($data_items_list as $activeitems_listvalue) {
                $items_ids[] = $activeitems_listvalue['id'];
            }
            // print_r($items_ids);die();
            // check if item is in some active auciton or not
            $active_auctions = $this->auction_model->getActiveAuctions();
            foreach ($active_auctions as $activeAuctionvalue) {
                $auction_ids[] = $activeAuctionvalue['id'];
            }
            $data['auction_id_arr'] = $auction_ids;
            if (!empty($auction_ids)) {
                $active_auction_item = $this->auction_model->getActiveAuctionItems($auction_ids);
                foreach ($active_auction_item as $activeAuctionItemvalue) {
                    $auction_item_id[] = $activeAuctionItemvalue['item_id'];
                }
            }
            $sold_item = $this->auction_model->getsoldItems();
            foreach ($sold_item as $solditemvalue) {
                $sold_items_id[] = $solditemvalue['item_id'];
            }

            $validItemsList = array_diff($items_ids, $auction_item_id);
            $validItemsList = array_diff($validItemsList, $sold_items_id);
            if ($validItemsList != array()) {
                $data['items_list'] = $this->items_model->active_items_filter_list($validItemsList);
            }else{
                $data['items_list'] = array();
            }
        }else{
            $data['items_list'] = array();
        }

        $data['auction_id'] =  $auction_id;
        $data['formaction_path'] = 'filter_items_by_category';
        $data['formaction_path2'] = 'search_items';
        $data_view = $this->load->view('auction/auction_items/auction_items_list', $data, true);
            // print_r($data['items_list']);
            // die();
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));

    }



    function removeItemString($str, $item) {
        $parts = explode(',', $str);
        while(($i = array_search($item, $parts)) !== false) {
        unset($parts[$i]);
        }
        return implode(',', $parts);
    }

    public function get_stock_list_inner()
    {
        $auction_id = $this->input->post('id');
        $auction_id = base64_decode(urldecode($auction_id));
        $data = array();
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['auction_id'] = $auction_id;
        $auction_data_array = $this->auction_model->get_auctions($auction_id);
        $data['category_id'] = $auction_data_array[0]['category_id'];

        $item_ids_list_multi_array = $this->auction_model->get_auction_item_ids_by_auction_category($auction_id,$data['category_id']);
        // print_r($item_ids_list_multi_array);
        $data['item_ids_list'] = array_column($item_ids_list_multi_array,"item_id");
        // $already_ids = implode( ",", $data['item_ids_list']);
        // if(isset($auction_data_array[0]['category_id']) && !empty($auction_data_array[0]['category_id'])){

        $sql = array();
        $sql[] = " item.category_id = '".$auction_data_array[0]['category_id']."' ";
        // $sql[] = " item.in_auction = 'no' ";
        $sql[] = " item.item_status = 'completed' ";
        $sql[] = " item.status = 'active' ";
        $sql[] = " item.sold = 'no' ";
        // }

         $query = "";
        if (!empty($sql)) {
            $query .= ' ' . implode(' AND ', $sql);
        }
        // $data_items_list = $this->items_model->items_filter_limit_list_active($query);
        $data_items_list = $this->items_model->items_filter_limit_list_active($query);
        if(isset($data_items_list) && !empty($data_items_list)){
            $items_ids = array();
            $auction_ids = array();
            $auction_item_id = array();
            $sold_items_id = array();
            $validItemsList = array();
            // get active item ids 
            foreach ($data_items_list as $activeitems_listvalue) {
                $items_ids[] = $activeitems_listvalue['id'];
            }
            // check if item is in some active auciton or not
            $active_auctions = $this->auction_model->getActiveAuctions();
            foreach ($active_auctions as $activeAuctionvalue) {
                $auction_ids[] = $activeAuctionvalue['id'];
            }
            $data['auction_id_arr'] = $auction_ids;
                if (!empty($auction_ids)) {
                    $active_auction_item = $this->auction_model->getActiveAuctionItems($auction_ids);
                foreach ($active_auction_item as $activeAuctionItemvalue) {
                    $auction_item_id[] = $activeAuctionItemvalue['item_id'];
                }
            }
            $sold_item = $this->auction_model->getsoldItems();
            foreach ($sold_item as $solditemvalue) {
                $sold_items_id[] = $solditemvalue['item_id'];
            }
            $validItemsList = array_diff($items_ids, $auction_item_id);
            $validItemsList = array_diff($validItemsList, $sold_items_id);
            // print_r($validItemsList);die();
            if ($validItemsList != array()) {
                $data['items_list'] = $this->items_model->active_items_filter_list($validItemsList);
            }else{
                $data['items_list'] = array();
            }
        }else{
            $data['items_list'] = array();
        }
        // $where = " expiry_time >= NOW() and access_type = 'live' ";
        // $auction_active = $this->db->get_where('auctions',[$where])->result_array();
      

        $data['auction_id'] =  $auction_id;
        $data['formaction_path'] = 'filter_items_by_category';
        $data['formaction_path2'] = 'search_items';
        $data_view = $this->load->view('auction/auction_items/auction_items_list_inner', $data, true);
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));

    }


    public function filter_items()
    {
        $data = array();
        if($this->input->post())
        {
            $posted_data = $this->input->post();
            unset($sql); 

        if (isset($posted_data['sale_person_id']) && !empty($posted_data['sale_person_id'])) {
 
            $sales_person_id = implode(",",$posted_data['sale_person_id']);
            $sql[] = " item.created_by IN ($sales_person_id) ";
        }

        if (isset($posted_data['keyword']) && !empty($posted_data['keyword'])) {
 
            $sql[] = " item.keyword like '%".$posted_data['keyword']."%' ";
        }

        if (isset($posted_data['item_status']) && !empty($posted_data['item_status'])) {
 
            $sql[] = " item.item_status = '".$posted_data['item_status']."' ";
        }

        if (isset($posted_data['registration_no']) && !empty($posted_data['registration_no'])) {
 
            $sql[] = " item.registration_no = '".$posted_data['registration_no']."' ";
        }

        // if (isset($posted_data['category_id']) && !empty($posted_data['category_id'])) {
 
        //     $sql[] = " item.category_id = ".$posted_data['category_id']." ";
        // }
        
        if (isset($posted_data['category_id']) && !empty($posted_data['category_id'])) 
        {
            $sql_cat = "  item.category_id = ".$posted_data['category_id']."  ";
            // $sql_cat .= " AND item.in_auction = 'no' ";
            $sql_cat .= " AND item.item_status = 'completed' ";
            $sql_cat .= " AND item.status = 'active' ";
        }


        if (isset($posted_data['seller_id']) && !empty($posted_data['seller_id'])) {
            $seller_id = implode(",",$posted_data['seller_id']);
            $sql[] = " item.seller_id IN ($seller_id) ";
        }

        if (isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {
            $start_date = $posted_data['datefrom'];
            $end_date = $posted_data['dateto'];
            $sql[] = " (DATE(item.created_on) between '$start_date' and '$end_date') ";
        }

        
        if (isset($posted_data['days_filter']) && !empty($posted_data['days_filter'])) {
          
            $interval = (($posted_data['days_filter'] == 'today')) ? 0 : $posted_data['days_filter'];

            $sql[] = " (DATE(item.created_on)  >= DATE(NOW()) + INTERVAL ".$interval." DAY  AND DATE(item.created_on) <  NOW() + INTERVAL  0 DAY) ";
             
        }
            $query = "";
            if (!empty($sql)) {
                $query .= ' (' . implode(' OR ', $sql).' ) AND '.$sql_cat;
            }
            else
            {
                 $query .= $sql_cat;
            }

            $auction_id = $this->input->post('auction_id');
            $item_ids_list_multi_array = $this->auction_model->get_auction_item_ids($auction_id);
            if(isset($item_ids_list_multi_array) && !empty($item_ids_list_multi_array))
            {
            $item_ids_list = array_column($item_ids_list_multi_array,"item_id");
            }
            else
            {
                $item_ids_list = array();
            }

            $data['items_list'] = $this->items_model->auction_items_filter_list($item_ids_list,$query);
            // print_r($this->db->last_query());die();
            // print_r($this->db->last_query());die();
            $data['auction_id'] = $auction_id;
            $data_view = $this->load->view('auction/auction_items/ajax_items_content', $data, true);
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found.'));
        }
    }


    // Update a auction Item
    //This method was extra we use item form for edit which will call directly fron here
    // public function update_auction_item()
    // {
    //     $data = array();
    //     $data['small_title'] = 'Update Item';
    //     $data['formaction_path'] = 'update_auction_item';
    //     $data['current_page_auction'] = 'current-page';
    //     $data['item_id'] = $this->uri->segment(3);
    //     $item_id = $this->uri->segment(3);
     
    //     $data['item_info'] = $this->items_model->get_item_byid($item_id);
    //     $item_dynamic_fields_info = $this->items_model->get_itemfields_byItemid($item_id);
    //     // $data['seller_list'] = $this->users_model->users_list(3);
    //     $data['seller_list'] = $this->users_model->get_all_sales_user();
    //     // print_r($item_dynamic_fields_info);
    //     if($item_dynamic_fields_info)
    //     {
    //       foreach ($item_dynamic_fields_info as $value) 
    //       {
    //         $multiple_info = $this->items_model->fields_multiple_info($value['fields_id']);
    //         $field_ids[] = $value['fields_id'];
    //         $field_values[] = $value['value'];
    //         $field_ids_list[$value['fields_id']] = array('multiple' => $multiple_info[0]['multiple'], 'value' => $value['value']);
    //         // print_r($value['fields_id'].'  - '.$multiple_info[0]['multiple'].' ');
    //       }
    //         $data['field_ids'] = $field_ids;
    //         $data['field_values'] = $field_ids_list;
    //     }

    //      // print_r($field_ids_list);

    //     $data['category_list'] = $this->items_model->get_item_category_active();
    //     if ($this->input->post()) {

    //     $item_dynamic_data = $this->input->post();
         
    //     $item_data = $this->input->post('item'); // get basic information 
    //     unset($item_dynamic_data['item']);  // remove basic information form data
   

    //     $this->load->library('form_validation');
    //       $rules = array(
    //             array(
    //                 'field' => 'item[name]',
    //                 'label' => 'Name',
    //                 'rules' => 'trim|required'),
    //             // array(
    //             //     'field' => 'item[keyword]',
    //             //     'label' => 'Keyword',
    //             //     'rules' => 'trim|required'),
    //             array(
    //                 'field' => 'item[status]',
    //                 'label' => 'Status',
    //                 'rules' => 'trim|required'),
    //             array(
    //                 'field' => 'item[item_status]',
    //                 'label' => 'Item Status',
    //                 'rules' => 'trim|required')
    //         );
    //          $this->form_validation->set_rules($rules);
    //         if ($this->form_validation->run() == false) {

    //             echo json_encode(array('msg' => validation_errors()));
    //         } 
    //         else
    //         {   
    //             $id = $item_data['id'];
    //              if(!empty($item_data['seller_id']))
    //             {
    //                 $seller_id = $item_data['seller_id'];
    //             }
    //             else
    //             {
    //              $seller_id = $this->loginUser->id;   
    //             }

    //             $posted_data = array(
    //             'name' => $item_data['name'],
    //             'location' => $item_data['location'],
    //             'year' => $item_data['year'],
    //             'detail' => $item_data['detail'],
    //             'status' => $item_data['status'],
    //             'item_status' => $item_data['item_status'],
    //             'price' => $item_data['price'],
    //             // 'keyword' => $item_data['keyword'],
    //             'category_id' => $item_data['category_id'],
    //             'seller_id' => $seller_id,
    //             // 'auction_type' => implode(",", $item_data['auction_type']),
    //             'updated_by' => $this->loginUser->id
    //             );

    //             if(isset($item_data['lat']) && !empty($item_data['lat']))
    //             {
    //                 $posted_data['lat'] = $item_data['lat'];
    //             }
    //             if(isset($item_data['lng']) && !empty($item_data['lng']))
    //             {
    //                 $posted_data['lng'] = $item_data['lng'];
    //             }

    //             if(isset($item_data['subcategory_id']) && !empty($item_data['subcategory_id']))
    //             {
    //                 $posted_data['subcategory_id'] = $item_data['subcategory_id'];
    //             }
                
    //             if(isset($item_data['keyword']) && !empty($item_data['keyword']))
    //             {
    //                 $posted_data['keyword'] = $item_data['keyword'];
    //             }
                
    //             if(isset($item_data['vin_no']) && !empty($item_data['vin_no']))
    //             {
    //                 $posted_data['vin_number'] = $item_data['vin_no'];
    //             }

    //             if(isset($item_data['price']) && !empty($item_data['price']))
    //             {
    //                 $posted_data['price'] = $item_data['price'];
    //             }

    //             if(isset($item_data['registration_no']) && !empty($item_data['registration_no']))
    //             {
    //                 $posted_data['registration_no'] = $item_data['registration_no'];
    //             }

    //             if(isset($item_data['make']))
    //             {
    //                 $posted_data['make'] = $item_data['make'];
    //                 $posted_data['model'] = $item_data['model'];  
    //                 $posted_data['mileage'] = $item_data['mileage'];    
    //                 $posted_data['mileage_type'] = $item_data['mileage_type'];    
    //             }
                

    //             $result = $this->items_model->update_item($id,$posted_data);

    //             $item_row_array = $this->items_model->get_item_byid($id);

    //             if(empty($item_row_array[0]['barcode']))
    //             {
    //                 $path = "uploads/items_documents/".$id."/qrcode/";

    //                 // make path
    //                 if ( !is_dir($path)) {
    //                     mkdir($path, 0777, TRUE);
    //                 } 

    //                 $qrcode_name = $this->generate_code($id,$path, ['id'=>$id]);
    //                 if(!empty($qrcode_name))
    //                 {
    //                     $barcode_array = array(
    //                     'barcode' => $qrcode_name
    //                     );
    //                     $this->items_model->update_item($id,$barcode_array);
    //                 }
    //             }
               



    //             $result_attachments = array();
  

    //             foreach ($item_dynamic_data as $dynamic_keys => $dynamic_values) {
    //             $ids_arr = explode("-", $dynamic_keys);
    //             if(is_array($dynamic_values))
    //                 {
    //                     $dynamic_values_new = "[".implode(",",$dynamic_values)."]";
    //                 }
    //                 else
    //                 {
    //                     $dynamic_values_new = $dynamic_values;
    //                 }
    //                 $category_id = $item_data['category_id'];
    //                 $fields_id = $ids_arr[0];
    //                 $check_if = $this->items_model->check_items_field_data($id,$item_data['category_id'],$ids_arr[0]);
    //                 // check if fields already exist or not then insert or update accordingly 
    //                 if($check_if)
    //                 {
    //                     $dynaic_information2 = array(
    //                     'category_id' => $category_id,
    //                     'item_id' => $id,
    //                     'fields_id' => $fields_id,
    //                     'value' => $dynamic_values_new,
    //                     'updated_by' => $this->loginUser->id
    //                     );

    //                 $result_info = $this->items_model->update_item_fields_data($id,$category_id,$fields_id,$dynaic_information2);
    //                 }
    //                 else
    //                 {
    //                     $dynaic_information = array(
    //                     'category_id' => $item_data['category_id'],
    //                     'item_id' => $id,
    //                     'fields_id' => $ids_arr[0],
    //                     'value' => $dynamic_values_new,
    //                     'created_on' => date('Y-m-d H:i:s'),
    //                     'created_by' => $this->loginUser->id
    //                     );

    //                 $result_info = $this->items_model->insert_item_fields_data($dynaic_information);
    //                 }
                    
    //             }

    //             if (!empty($result)) {
    //               $this->session->set_flashdata('msg', 'Item Updated Successfully');
    //               $msg = 'success';
    //               echo json_encode(array('msg' => $msg, 'in_id' => $result, 'attach' => $result_attachments));
                  
    //             }
    //             else
    //             {
    //                 $msg = 'DB Error found.';
    //                 echo json_encode(array('msg' => $msg, 'error' => $result));
    //             }
    //         }
    //     }else{

    //         $this->template->load_admin('auction/auction_items/auction_item_form', $data);
    //     }

    // }

    public function sale_out_email($item_id='', $buyer_id='')
    {
        if(!empty($item_id) && !empty($buyer_id)){
            //echo "//////////////";
            $buyer = $this->db->get_where('users', ['id' => $buyer_id])->row_array();
            $item = $this->db->get_where('item', ['id' => $item_id])->row_array();

            $msg = $item['name'].' has been sold to '. $buyer['fname'].' - '.$buyer['email'];
            $to = $buyer['email'];
            
            $subject='Sale Out Notification';
            $notification = array(
                'title_english' => 'Sale Out Notification',
                'description_english' => $msg,
            );
            $data = [
                'btn' => 'true',
                'notification' => $notification
            ];
            $vars = [
                '{btn_link}' => base_url(),
                '{btn_text}' => 'Visit'
            ];
            
            $email_message = $this->load->view('email_templates/common_template', $data, true);
            if($vars){
                foreach ($vars as $key => $value) {
                    $email_message = str_replace($key, $value, $email_message);
                }
            }
            //print_r($to);
            //print_r($email_message);
            //die();
            $send = $this->email->to($to)->subject($subject)->message($email_message)->send();
            return $send;
            //return $this->email->print_debugger();
        }
    }

    // Update a auction Item
    public function sale_item()
    {
        $data = array();
        $data['current_page_auction'] = 'current-page';
        $data['item_id'] = $this->uri->segment(3);
        $item_id = $this->uri->segment(3);
        $auction_id = $this->uri->segment(4);
        // $auction_id = base64_decode(urldecode($base64_auction_id));
        $base64_auction_id = urlencode(base64_encode($auction_id));
        $sold_status = $this->uri->segment(5);
        if ($sold_status == 'sold') {
            $seller = $this->db->select('seller_id,sold')->where('id', $item_id)->get('item')->row_array();
            if ($seller['sold'] == 'yes') {
                $this->session->set_flashdata('msg', 'Item already sold out.');
                redirect(base_url().'auction/items/'.$base64_auction_id); 
            } else {
                $query = $this->db->query('Select bid.buyer_id,bid.id,bid.bid_amount, bid.bid_status from bid inner join  ( Select max(bid_time) as LatestDate, item_id  from bid Group by bid.item_id  ) SubMax on bid.bid_time = SubMax.LatestDate and bid.item_id = SubMax.item_id WHERE bid.item_id = '.$item_id.' AND bid.auction_id = '.$auction_id.';');
                $buyer = $query->row_array();
                $this->db->update('bid', ['bid_status' => 'won'], ['id' => $buyer['id']]);
                $auction_item = $this->db->get_where('auction_items', ['item_id' => $item_id, 'auction_id' => $auction_id])->row_array();
                $sold_item = array(
                    'item_id' => $item_id,
                    'auction_id' => $auction_id,
                    'auction_item_id' => $auction_item['id'],
                    'buyer_id' => $buyer['buyer_id'],
                    'seller_id' => $seller['seller_id'],
                    'price' => $buyer['bid_amount'],
                    'payable_amount' => $buyer['bid_amount'],
                    'created_by' => $this->loginUser->id,
                    'updated_by' => $this->loginUser->id,
                    'created_on' => date('Y-m-d H:i'),
                    'sale_type' => 'online'
                );
                $insert = $this->db->insert('sold_items', $sold_item);
                $result = $this->db->update('item', ['sold' => 'yes','updated_by' => $this->loginUser->id,'updated_on' => date('Y-m-d H:i')], ['id' => $item_id]);
                $this->db->update('auction_items', ['sold_status' => 'sold','updated_by' => $this->loginUser->id,'updated_on' => date('Y-m-d H:i')], ['item_id' => $item_id, 'auction_id' => $auction_id]);
                if ($insert) {
                    $this->load->model('email/Email_model', 'email_model');
                    $buyer_data = $this->db->get_where('users', ['id' => $buyer['buyer_id']])->row_array();
                    $item_data = $this->db->get_where('item', ['id' => $item_id])->row_array();
                    $item_link = base_url('auction/online-auction/details/').$auction_id.'/'.$item_id;
                    $email = $buyer_data['email'];
                    $itm_name = json_decode($item_data['name']);
                    $lan = $this->language;
                    $vars = array(
                        '{username}' => $buyer_data['fname'],
                        '{item_name}' => '<a href="'.$item_link.'">'.$itm_name->$lan.'</a>',
                        '{year}' => $item_data['year'],
                        '{bid_price}' => $buyer['bid_amount'],
                        '{lot_number}' => $auction_item['order_lot_no'],
                        '{registration_number}' => $item_data['registration_no'],
                        '{login_link}' => 'Please login:<a href="'.base_url("user-login").'" > Go to My Account </a>'
                    );
                    $this->email_model->email_template($email, 'item-win-email', $vars, false);
                   // push notification 
                    $p_noti = ['lot_no'=> $auction_item['order_lot_no'], 'item_name' =>  $itm_name->$lan];
                    $this->getAuctionWinnnerPushNotification($buyer_data['fcm_token'], $p_noti);
                     // send email to seller
                    $seller_data = $this->db->get_where('users',['id' => $seller['seller_id']])->row_array();
                    $vars['{username}'] = $seller_data['fname'];
                    $seller_email = $seller_data['email'];
                    $this->email_model->email_template($seller_email, 'item-sold-email', $vars, false);
                    /// sold sms ////
                    $buyer_data = $this->db->get_where('users',['id' => $buyer['buyer_id']])->row_array();
                    $item_detail = $this->db->get_where('item',['id' => $item_id])->row_array();
                    $sms_buyer = $buyer_data['fname'].', you purchased '.json_decode($item_detail['name'])->english;
                    $sms_seller = $seller_data['fname'].', your '.json_decode($item_detail['name'])->english.' is sold.';
                    $this->load->library('SendSmart');
                    //$number = '971561387755';
                    $sms_response = $this->sendsmart->sms($buyer_data['mobile'], $sms_buyer);
                    $sms_response = $this->sendsmart->sms($seller_data['mobile'], $sms_seller);
                    /// sold sms End ////
                    // $this->sale_out_email($sold_item['item_id'], $sold_item['buyer_id']);
                    $this->session->set_flashdata('msg', 'Item has been sold successfully.');
                    redirect(base_url().'auction/items/'.$base64_auction_id); 
                    // echo json_encode(array('msg'=>'Item has been sold successfully.', 'item_id' => $item_id ));
                } else {
                    $this->session->set_flashdata('msg', 'Item has been failed to sold.');
                    redirect(base_url().'auction/items/'.$base64_auction_id);

                    // echo json_encode(array('msg'=>'Item has been faild to sold.'));
                }
            }
        } else {
            // if ($sold_status == 'approval') {
            //     $this->db->update('auction_items', ['sold_status' => $sold_status,'updated_by' => $this->loginUser->id,'updated_on' => date('Y-m-d H:i')], ['item_id' => $item_id, 'auction_id' => $auction_id]);
            //     $this->session->set_flashdata('msg', 'Item status updated successfully.');
            //         redirect(base_url().'auction/items/'.$base64_auction_id); 
            // }
            if ($sold_status == 'not_sold') {
                $this->db->update('auction_items', ['sold_status' => $sold_status,'updated_by' => $this->loginUser->id,'updated_on' => date('Y-m-d H:i')], ['item_id' => $item_id, 'auction_id' => $auction_id]);

                $query = $this->db->query('Select bid.buyer_id,bid.id,bid.bid_amount,item.name as item_name,users.email as buyer_email,users.fcm_token,users.fname as buyer_fname from bid inner join  ( Select max(bid_time) as LatestDate, item_id  from bid Group by bid.item_id  ) SubMax on bid.bid_time = SubMax.LatestDate and bid.item_id = SubMax.item_id LEFT JOIN item ON item.id = bid.item_id LEFT JOIN users ON users.id = bid.user_id WHERE bid.item_id = '.$item_id.' AND bid.auction_id = '.$auction_id.';');
                $last_bid = $query->row_array();
                $buyer_data = $this->db->get_where('users', ['id' => $last_bid['buyer_id']])->row_array();
                $item_data = $this->db->get_where('item', ['id' => $item_id])->row_array();
                $auction_item = $this->db->get_where('auction_items', ['item_id' => $item_id, 'auction_id' => $auction_id])->row_array();
                $item_link = base_url('auction/online-auction/details/').$auction_id.'/'.$item_id;
                $category_data = $this->db->get_where('item_category', ['id' => $item_data['category_id']])->row_array();
                
                //send email to heighest bidder on rejection from seller 
                $this->load->model('email/Email_model', 'email_model');
                $body = array(
                    'title_english' => 'Rejected',
                    'description_english' => 'Seller did not approve to sale {item_name}.',
                );
                $itm_name = json_decode($item_data['name']);
                $cate_name = json_decode($category_data['title']);
                $lan = $this->language;

                $vars = array(
                    '{username}' => $last_bid['buyer_fname'],
                    '{item_name}' => '<a href="'.$item_link.'">'.$itm_name->$lan.'</a>',
                    '{category_name}' => $cate_name->$lan,
                    '{year}' => $item_data['year'],
                    '{bid_price}' => $last_bid['bid_amount'],
                    '{lot_number}' => $auction_item['order_lot_no'],
                    '{registration_number}' => $item_data['registration_no'],
                    '{login_link}' => 'Please login:<a href="'.base_url().'/user-login" > Go to My Account </a>'
                );
                $email = $last_bid['buyer_email'];
                $this->email_model->email_template($email, 'rejected-item-email', $vars, false);

                // push notification 
                 $p_noti = ['item_detail'=> $auction_item['order_lot_no'].' - '. $itm_name->$lan];
                $this->getItemRejectPushNotification($last_bid['fcm_token'], $p_noti);

                // $this->email_model->common_template($email, 'rejected-item-email', $vars, false);
                //end email

                $this->session->set_flashdata('msg', 'Item status updated successfully.');
                redirect(base_url().'auction/items/'.$base64_auction_id); 

                // $result = $this->db->update('item', ['status' => 'inactive','updated_by' => $this->loginUser->id,'updated_on' => date('Y-m-d H:i')], ['id' => $item_id]);
            }
        }
    }

    // Update a live auction Item
    public function sale_live_item()
    {
        $data = array();
        $data['current_page_auction'] = 'current-page';
        $data['item_id'] = $this->uri->segment(3);
        $item_id = $this->uri->segment(3);
        $base64_auction_id = $this->uri->segment(4);
        $auction_id = base64_decode(urldecode($base64_auction_id));
        // $base64_auction_id = urlencode(base64_encode($auction_id));
        $sold_status = $this->uri->segment(5);
        if ($sold_status == 'sold') {
            $seller = $this->db->select('seller_id,sold')->where('id', $item_id)->get('item')->row_array();
            if ($seller['sold'] == 'yes') {
                $this->session->set_flashdata('msg', 'Item already sold out.');
                redirect(base_url().'auction/items/'.$base64_auction_id); 
            } else {
                $query = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log',['item_id' => $item_id,'auction_id' => $auction_id]);
                $bid_date = $query->row_array();
                // print_r($bid_date);die();
                $this->db->update('bid', ['bid_status' => 'win'], ['id' => $bid_date['id']]);
                $auction_item = $this->db->get_where('auction_items', ['item_id' => $item_id, 'auction_id' => $auction_id])->row_array();
                $sold_item = array(
                    'item_id' => $item_id,
                    'auction_id' => $auction_id,
                    'auction_item_id' => $auction_item['id'],
                    'buyer_id' => $auction_item['buyer_id'],
                    'seller_id' => $seller['seller_id'],
                    'price' => $bid_date['bid_amount'],
                    'payable_amount' => $bid_date['bid_amount'],
                    'created_by' => $this->loginUser->id,
                    'updated_by' => $this->loginUser->id,
                    'created_on' => date('Y-m-d H:i'),
                    'sale_type' => 'online'
                );
                $insert = $this->db->insert('sold_items', $sold_item);
                $result = $this->db->update('item', ['sold' => 'yes','updated_by' => $this->loginUser->id,'updated_on' => date('Y-m-d H:i')], ['id' => $item_id]);
                $this->db->update('auction_items', ['sold_status' => 'sold','updated_by' => $this->loginUser->id,'updated_on' => date('Y-m-d H:i')], ['item_id' => $item_id, 'auction_id' => $auction_id]);
                if ($insert) {
                    $this->load->model('email/Email_model', 'email_model');
                    if (isset($auction_item['buyer_id']) && !empty($auction_item['buyer_id'])) {
                        $buyer_data = $this->db->get_where('users', ['id' => $auction_item['buyer_id']])->row_array();
                    }
                    $item_data = $this->db->get_where('item', ['id' => $item_id])->row_array();
                    $item_link = base_url('auction/online-auction/details/').$auction_id.'/'.$item_id;
                    $itm_name = json_decode($item_data['name']);
                    $lan = $this->language;
                    $vars = array(
                        '{username}' => isset($buyer_data) ? $buyer_data['fname'] : '',
                        '{item_name}' => '<a href="'.$item_link.'">'.$itm_name->$lan.'</a>',
                        '{year}' => $item_data['year'],
                        '{bid_price}' => $bid_date['bid_amount'],
                        '{lot_number}' => $auction_item['order_lot_no'],
                        '{registration_number}' => $item_data['registration_no'],
                        '{login_link}' => 'Please login:<a href="'.base_url("user-login").'" > Go to My Account </a>'
                    );
                    if (isset($buyer_data) && !empty($buyer_data['email'])) {
                        $email = $buyer_data['email'];
                        $this->email_model->email_template($email, 'item-win-email', $vars, false);
                    // push notification 
                    $p_noti = ['lot_no'=> $auction_item['order_lot_no'], 'item_name' =>  $itm_name->$lan];
                    $this->getAuctionWinnnerPushNotification($buyer_data['fcm_token'], $p_noti);
                }
                    // send email to seller
                    $seller_data = $this->db->get_where('users',['id' => $seller['seller_id']])->row_array();
                    $vars['{username}'] = $seller_data['fname'];
                    $seller_email = $seller_data['email'];
                    $this->email_model->email_template($seller_email, 'item-sold-email', $vars, false);
                    /// sold sms ////
                    // $buyer_data = $this->db->get_where('users',['id' => $buyer['buyer_id']])->row_array();
                    $item_detail = $this->db->get_where('item',['id' => $item_id])->row_array();
                    $sms_seller = $seller_data['fname'].', your '.json_decode($item_detail['name'])->english.' is sold.';
                    $this->load->library('SendSmart');
                    //$number = '971561387755';
                    if (isset($buyer_data) && !empty($buyer_data['mobile'])) {
                        $sms_buyer = $buyer_data['fname'].', you purchased '.json_decode($item_detail['name'])->english;
                        $sms_response = $this->sendsmart->sms($buyer_data['mobile'], $sms_buyer);
                    }
                    $sms_response = $this->sendsmart->sms($seller_data['mobile'], $sms_seller);
                    /// sold sms End ////
                    // $this->sale_out_email($sold_item['item_id'], $sold_item['buyer_id']);
                    $this->session->set_flashdata('msg', 'Item has been sold successfully.');
                    redirect(base_url().'auction/liveitems/'.$base64_auction_id); 
                    // echo json_encode(array('msg'=>'Item has been sold successfully.', 'item_id' => $item_id ));
                } else {
                    $this->session->set_flashdata('msg', 'Item has been failed to sold.');
                    redirect(base_url().'auction/liveitems/'.$base64_auction_id);

                    // echo json_encode(array('msg'=>'Item has been faild to sold.'));
                }
            }
        } else {
            // if ($sold_status == 'approval') {
            //     $this->db->update('auction_items', ['sold_status' => $sold_status,'updated_by' => $this->loginUser->id,'updated_on' => date('Y-m-d H:i')], ['item_id' => $item_id, 'auction_id' => $auction_id]);
            //     $this->session->set_flashdata('msg', 'Item status updated successfully.');
            //         redirect(base_url().'auction/items/'.$base64_auction_id); 
            // }
            if ($sold_status == 'not_sold') {
                $this->db->update('auction_items', ['sold_status' => $sold_status,'updated_by' => $this->loginUser->id,'updated_on' => date('Y-m-d H:i')], ['item_id' => $item_id, 'auction_id' => $auction_id]);

                $query = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log',['item_id' => $item_id,'auction_id' => $auction_id]);
                $bid_date = $query->row_array();
                $last_bid = $query->row_array();
                $item_data = $this->db->get_where('item', ['id' => $item_id])->row_array();
                $auction_item = $this->db->get_where('auction_items', ['item_id' => $item_id, 'auction_id' => $auction_id])->row_array();
                $buyer_data = $this->db->get_where('users', ['id' => $auction_item['buyer_id']])->row_array();
                $item_link = base_url('auction/online-auction/details/').$auction_id.'/'.$item_id;
                $category_data = $this->db->get_where('item_category', ['id' => $item_data['category_id']])->row_array();
                
                //send email to heighest bidder on rejection from seller 
                $this->load->model('email/Email_model', 'email_model');
                $body = array(
                    'title_english' => 'Rejected',
                    'description_english' => 'Seller did not approve to sale {item_name}.',
                );
                $itm_name = json_decode($item_data['name']);
                $cate_name = json_decode($category_data['title']);
                $lan = $this->language;

                $vars = array(
                    '{username}' => $last_bid['buyer_fname'],
                    '{item_name}' => '<a href="'.$item_link.'">'.$itm_name->$lan.'</a>',
                    '{category_name}' => $cate_name->$lan,
                    '{year}' => $item_data['year'],
                    '{bid_price}' => $last_bid['bid_amount'],
                    '{lot_number}' => $auction_item['order_lot_no'],
                    '{registration_number}' => $item_data['registration_no'],
                    '{login_link}' => 'Please login:<a href="'.base_url().'/user-login" > Go to My Account </a>'
                );
                $email = $last_bid['buyer_email'];
                $this->email_model->email_template($email, 'rejected-item-email', $vars, false);

                 // push notification 
                 $p_noti = ['item_detail'=> $auction_item['order_lot_no'].' - '. $itm_name->$lan];
                $this->getItemRejectPushNotification($buyer_data['fcm_token'], $p_noti);

                // $this->email_model->common_template($email, 'rejected-item-email', $vars, false);
                //end email

                $this->session->set_flashdata('msg', 'Item status updated successfully.');
                redirect(base_url().'auction/liveitems/'.$base64_auction_id); 

                // $result = $this->db->update('item', ['status' => 'inactive','updated_by' => $this->loginUser->id,'updated_on' => date('Y-m-d H:i')], ['id' => $item_id]);
            }
        }
    }

    public function unsold()
    {
        $data['item_id'] = $this->uri->segment(3);
        $item_id = $this->uri->segment(3);
        $auction_id = $this->uri->segment(4);
        // $auction_id = base64_decode(urldecode($base64_auction_id));
        $base64_auction_id = urlencode(base64_encode($auction_id));
        $sold_status = $this->uri->segment(5);
        if ($sold_status == 'not_sold') {
            $this->db->update('auction_items', ['sold_status' => $sold_status,'buyer_id' => NULL,'updated_by' => $this->loginUser->id,'updated_on' => date('Y-m-d H:i')], ['item_id' => $item_id, 'auction_id' => $auction_id]);
            $this->db->delete('sold_items', ['item_id' => $item_id, 'auction_id' => $auction_id]);
            $this->db->update('item', ['sold' => 'no'], ['id' => $item_id]);

            $query = $this->db->query('Select bid.buyer_id,bid.id,bid.bid_amount,item.name as item_name,users.fcm_token,users.email as buyer_email,users.fname as buyer_fname from bid inner join  ( Select max(bid_time) as LatestDate, item_id  from bid Group by bid.item_id  ) SubMax on bid.bid_time = SubMax.LatestDate and bid.item_id = SubMax.item_id LEFT JOIN item ON item.id = bid.item_id LEFT JOIN users ON users.id = bid.user_id WHERE bid.item_id = '.$item_id.' AND bid.auction_id = '.$auction_id.';');
            $last_bid = $query->row_array();
            $this->db->update('bid', ['bid_status' => 'pending'], ['id' => $last_bid['id']]);
            $buyer_data = $this->db->get_where('users', ['id' => $last_bid['buyer_id']])->row_array();
            $item_data = $this->db->get_where('item', ['id' => $item_id])->row_array();
            $auction_item = $this->db->get_where('auction_items', ['item_id' => $item_id, 'auction_id' => $auction_id])->row_array();
            $item_link = base_url('auction/online-auction/details/').$auction_id.'/'.$item_id;
            $category_data = $this->db->get_where('item_category', ['id' => $item_data['category_id']])->row_array();
            
            //send email to heighest bidder on rejection from seller 
            $this->load->model('email/Email_model', 'email_model');
            $body = array(
                'title_english' => 'Rejected',
                'description_english' => 'Seller did not approve to sale {item_name}.',
            );
            $itm_name = json_decode($item_data['name']);
            $cate_name = json_decode($category_data['title']);
            $lan = $this->language;

            $vars = array(
                '{username}' => $last_bid['buyer_fname'],
                '{item_name}' => '<a href="'.$item_link.'">'.$itm_name->$lan.'</a>',
                '{category_name}' => $cate_name->$lan,
                '{year}' => $item_data['year'],
                '{bid_price}' => $last_bid['bid_amount'],
                '{lot_number}' => $auction_item['order_lot_no'],
                '{registration_number}' => $item_data['registration_no'],
                '{login_link}' => 'Please login:<a href="'.base_url().'/user-login" > Go to My Account </a>'
            );
            $email = $last_bid['buyer_email'];
            $this->email_model->email_template($email, 'rejected-item-email', $vars, false);

             // push notification 
            $p_noti = ['item_detail'=> $auction_item['order_lot_no'].' - '. $itm_name->$lan];
            $this->getItemRejectPushNotification($last_bid['fcm_token'], $p_noti);

            // $this->email_model->common_template($email, 'rejected-item-email', $vars, false);
            //end email

            $this->session->set_flashdata('msg', 'Item status updated successfully.');
            redirect(base_url().'auction/items/'.$base64_auction_id); 

            // $result = $this->db->update('item', ['status' => 'inactive','updated_by' => $this->loginUser->id,'updated_on' => date('Y-m-d H:i')], ['id' => $item_id]);
        }
    }

    public function unsold_live()
    {
        $data['item_id'] = $this->uri->segment(3);
        $item_id = $this->uri->segment(3);
        $base64_auction_id = $this->uri->segment(4);
        $auction_id = base64_decode(urldecode($base64_auction_id));
        // $base64_auction_id = urlencode(base64_encode($auction_id));
        $sold_status = $this->uri->segment(5);
        if ($sold_status == 'not_sold') {
            $this->db->update('auction_items', ['sold_status' => $sold_status,'buyer_id' => NULL,'updated_by' => $this->loginUser->id,'updated_on' => date('Y-m-d H:i')], ['item_id' => $item_id, 'auction_id' => $auction_id]);
            $this->db->delete('sold_items', ['item_id' => $item_id, 'auction_id' => $auction_id]);
            $this->db->update('item', ['sold' => 'no'], ['id' => $item_id]);

            $query = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log',['item_id' => $item_id,'auction_id' => $auction_id]);
            $last_bid = $query->row_array();
            $this->db->update('bid', ['bid_status' => 'bid'], ['id' => $last_bid['id']]);
            $buyer_data = $this->db->get_where('users', ['id' => $last_bid['buyer_id']])->row_array();
            $item_data = $this->db->get_where('item', ['id' => $item_id])->row_array();
            $auction_item = $this->db->get_where('auction_items', ['item_id' => $item_id, 'auction_id' => $auction_id])->row_array();
            $item_link = base_url('auction/online-auction/details/').$auction_id.'/'.$item_id;
            $category_data = $this->db->get_where('item_category', ['id' => $item_data['category_id']])->row_array();
            
            //send email to heighest bidder on rejection from seller 
            $this->load->model('email/Email_model', 'email_model');
            $body = array(
                'title_english' => 'Rejected',
                'description_english' => 'Seller did not approve to sale {item_name}.',
            );
            $itm_name = json_decode($item_data['name']);
            $cate_name = json_decode($category_data['title']);
            $lan = $this->language;

            $vars = array(
                '{username}' => $last_bid['buyer_fname'],
                '{item_name}' => '<a href="'.$item_link.'">'.$itm_name->$lan.'</a>',
                '{category_name}' => $cate_name->$lan,
                '{year}' => $item_data['year'],
                '{bid_price}' => $last_bid['bid_amount'],
                '{lot_number}' => $auction_item['order_lot_no'],
                '{registration_number}' => $item_data['registration_no'],
                '{login_link}' => 'Please login:<a href="'.base_url().'/user-login" > Go to My Account </a>'
            );
            $email = $last_bid['buyer_email'];
            $this->email_model->email_template($email, 'rejected-item-email', $vars, false);

            // push notification 
            $p_noti = ['item_detail'=> $auction_item['order_lot_no'].' - '. $itm_name->$lan];
            $this->getItemRejectPushNotification($last_bid['fcm_token'], $p_noti);

            // $this->email_model->common_template($email, 'rejected-item-email', $vars, false);
            //end email

            $this->session->set_flashdata('msg', 'Item status updated successfully.');
            redirect(base_url().'auction/liveitems/'.$base64_auction_id); 

            // $result = $this->db->update('item', ['status' => 'inactive','updated_by' => $this->loginUser->id,'updated_on' => date('Y-m-d H:i')], ['id' => $item_id]);
        }
    }

    public function documents()
    {
        $data = array();
        $data['small_title'] = 'Manage Documents';
        // $data['current_page_auction'] = 'current-page';
        $data['formaction_path'] = 'update_item';
        $data['item_id'] = $this->uri->segment(3);
        $item_id = $this->uri->segment(3);  
        if($this->uri->segment(5)) {
            $base64_auction_id = $this->uri->segment(4); 
            $data['back_url'] = 'auction/liveitems/'.$base64_auction_id; 
            $data['current_page_live_auction'] = 'current-page';
        }else{
            $base64_auction_id = urlencode(base64_encode($this->uri->segment(4)));
            $data['current_page_auction'] = 'current-page';
            // $data['back_url'] = 'auction/items/'; 
            $data['back_url'] = 'auction/items/'.$base64_auction_id; 
        }   

        $data['item_info'] = $this->items_model->get_item_byid($item_id);
        if(isset($data['item_info']) && !empty($data['item_info']))
        {
            $doc_ids = explode(",",$data['item_info'][0]['item_attachments']);
            $test_report_ids = explode(",",$data['item_info'][0]['item_test_report']);
            $item_documents = $this->items_model->get_item_documents_byid($doc_ids);
            $img_ids = explode(",",$data['item_info'][0]['item_images']);
            $threed_imaages = explode(",",$data['item_info'][0]['threed_images']);
            $item_images = $this->items_model->get_item_images_byid($img_ids);
            $item_test_report_documents = $this->items_model->get_item_documents_byid($test_report_ids);
            $three_d = $this->items_model->get_item_three_d_images($threed_imaages);
            // print_r($item_images);

            foreach ($three_d as $value)
            {
                $data['three_d'][] = array(
                    'name' => $value['name'],
                    'size' => $value['size'],
                    'url' => $value['name'],
                    'type' => $value['type']
                );
            }

            foreach ($item_images as $value) 
            {
                $data['item_images'][] = array(
                    'name' => $value['name'],
                    'size' => $value['size'],
                    'url' => $value['name'],
                    'type' => $value['type']
                );
            }

            foreach ($item_documents as $value) 
            {
                $data['item_documents'][] = array(
                    'name' => $value['name'],
                    'size' => $value['size'],
                    'url' => $value['name'],
                    'type' => $value['type']
                );
            }
            foreach ($item_test_report_documents as $value) {
                $data['item_test_documents'][] = array(
                    'name' => $value['name'],
                    'size' => $value['size'],
                    'url' => $value['name'],
                    'type' => $value['type']
                );
            }
        }

        if ($this->input->post()) 
        {

        }
        else
        {
              $this->template->load_admin('auction/auction_items/documents_form', $data);
        }
    }

    public function save_3d_file_images()
    {

        if( ! empty($_FILES['threed_images']['name'])){
            // print_r($_FILES);
            $item_id = $_POST['item_id'];
            $itemsIds_array = array();
            $ids_concate = '';

            $result_array = $this->items_model->get_item_byid($item_id);
            if(isset($result_array) && !empty($result_array))
            {
                $itemsIds_array = explode(',' ,$result_array[0]['threed_images']);

                if(!empty($itemsIds_array) && !empty($result_array[0]['threed_images']))
                {
                    $ids_concate = $result_array[0]['threed_images'].",";
                }
            }

            $path = "uploads/items_threed/".$item_id."/qrcode/";
            // make path
            if ( !is_dir($path)) {
                mkdir($path, 0777, TRUE);
            }

            // make path
            $path = './uploads/items_threed/';
            if ( ! is_dir($path.$item_id)){
                mkdir($path.$item_id, 0777, TRUE);
            }
            // $config['upload_path'] = $path;
            $config['allowed_types'] = 'ico|png|jpg|jpeg';
            $sizes = [
                // ['width' => 1184, 'height' => 661],
                // ['width' => 191, 'height' => 120], // for details page carusal icons
                // ['width' => 305, 'height' => 180], // for gallery page
                // ['width' => 294, 'height' => 204], // for auction page
                // ['width' => 349, 'height' => 207], // for auction page
                ['width' => 37, 'height' => 36] // for table listing
            ];
            // if ( ! is_dir($path.$item_id)) {
            //     mkdir($path.$item_id, 0777, TRUE);
            // }
            $path = $path.$item_id.'/';
            $config['upload_path'] = $path;
            // $config['allowed_types'] = 'ico|png|jpg|pdf|ai|psd|eps|indd|doc|docx|ppt|pptx|xlsx|xls';
            // $config['allowed_types'] = 'ico|png|jpg|jpeg';
            $uploaded_file_ids = $this->files_model->multiUpload2('threed_images', $config, $sizes,$item_id); // upload and save to database
            if (isset($uploaded_file_ids['error'])) {
                $error = $uploaded_file_ids['error'];
                echo json_encode(array('error' => true,'message'=>$uploaded_file_ids['error']));
            } else {
                // $uploaded_file_ids = $this->files->upload('images', $config, $sizes);
                // $uploaded_file_ids = implode(',', $uploaded_file_ids);
                $uploaded_file_ids = implode(',', $uploaded_file_ids);
                $update = [
                    'threed_images' => $ids_concate.$uploaded_file_ids
                ];
                $result = ($this->items_model->update_item($item_id,$update)) ? 'true' : 'false';

                if($result == 'true')
                {
                    $this->session->set_flashdata('msg', 'Item 3D Added Successfully');
                    echo json_encode(array("error"=> false,'message' => "Item 3D Added Successfully"));
                }
                else
                {
                    echo json_encode(array("error"=> true,'message' => "Uploading process has been failed."));
                }
            }
        }
        else
        {
            echo json_encode(array("error"=> true,'message' => "No item found to upload."));
        }
    }

    public function delete_3d_image($itemId)
    {
        $attach_name = $this->input->post('file_to_be_deleted');
        $file_array = $this->files_model->get_file_byName($attach_name);
        if(isset($file_array) && !empty($file_array))
        {

            $result_array = $this->items_model->get_item_byid($itemId);
            if(isset($result_array) && !empty($result_array))
            {
                $itemsIds_array = explode(',' ,$result_array[0]['threed_images']);

                if(!empty($itemsIds_array))
                {
                    $str = $result_array[0]['threed_images'];
                }
            }
            $updated_str = $this->removeItemString($str, $file_array[0]['id']);
            $update = [
                'threed_images' => $updated_str,
                'updated_by' => $this->loginUser->id
            ];
            $result_update = ($this->items_model->update_item($itemId,$update)) ? 'true' : 'false';
        }
        $path = FCPATH .  "uploads/items_threed/".$itemId."/";
        $result = $this->files_model->delete_by_name($attach_name,$path);
        if($result)
        {
            echo 'success';
        }

    }

    public function save_item_test_report()
    {

        if( ! empty($_FILES['test_documents']['name'])){
 
            $item_id = $_POST['item_id'];
            
            $itemsIds_array = array();
            $ids_concate = '';

            $result_array = $this->items_model->get_item_byid($item_id);
            if(isset($result_array) && !empty($result_array))
            {
                $itemsIds_array = explode(',' ,$result_array[0]['item_test_report']);

                if(!empty($itemsIds_array) && !empty($result_array[0]['item_test_report']))
                {
                    $ids_concate = $result_array[0]['item_test_report'].",";
                }
            }

            // make path
            $path = './uploads/items_documents/';
            if ( ! is_dir($path.$item_id)) {
                mkdir($path.$item_id, 0777, TRUE);
            }
            $path = $path.$item_id.'/'; 
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'pdf|doc|docx|xlsx|xls';
            $uploaded_file_ids = $this->files_model->multiUpload('test_documents', $config); // upload and save to database
            if (isset($uploaded_file_ids['error'])) {
                $error = $uploaded_file_ids['error'];
                echo json_encode(array('error' => true,'message'=>$uploaded_file_ids['error']));
            } else {

                $uploaded_file_ids = implode(',', $uploaded_file_ids);
                if (isset($uploaded_file_ids['error'])) {
                    $error = $uploaded_file_ids['error'];
                    echo json_encode(array('error' => true,'data'=>$uploaded_file_ids['error']));
                } else {
                    $update = [
                        'item_test_report' => $ids_concate.$uploaded_file_ids,
                        // 'item_status' => 'completed',
                    ];
                    $result = ($this->items_model->update_item($item_id,$update)) ? 'true' : 'false';
                    if($result == 'true')
                    {
                        echo json_encode(array('error' => false, 'msg'=>'Item Documents has been updated Successfully.'));
                        $this->session->set_flashdata('msg', 'Item Test Report Added Successfully');
                    }
                }
            }
        }
    }

    public function view_documents()
    {
        $data = array();
        $data['small_title'] = 'Documents ';
        $data['formaction_path'] = 'update_item';
        $data['item_id'] = $this->uri->segment(3);
        $item_id = $this->uri->segment(3);     
        if ($this->uri->segment(5)) {
            $base64_auction_id = $this->uri->segment(4);
            $data['back_url'] = 'auction/liveitems/'.$base64_auction_id; 
            $data['current_page_live_auction'] = 'current-page';
        }else{
            $base64_auction_id = urlencode(base64_encode($this->uri->segment(4)));
            $data['current_page_auction'] = 'current-page';
            // $data['back_url'] = 'auction/items/'; 
            $data['back_url'] = 'auction/items/'.$base64_auction_id; 
        }

        $data['item_info'] = $this->items_model->get_item_byid($item_id);
        if(isset($data['item_info']) && !empty($data['item_info']))
        {
            $doc_ids = explode(",",$data['item_info'][0]['item_attachments']);
            $item_documents = $this->items_model->get_item_documents_byid($doc_ids);
            $img_ids = explode(",",$data['item_info'][0]['item_images']);
            $test_report_ids = explode(",",$data['item_info'][0]['item_test_report']);
            $item_images = $this->items_model->get_item_images_byid($img_ids);
            $item_test_report_documents = $this->items_model->get_item_documents_byid($test_report_ids);
            // print_r($item_images);
            foreach ($item_images as $value) 
            {
                $data['item_images'][] = array(
                    'id' => $value['id'],
                    'name' => $value['name'],
                    'size' => $value['size'],
                    'file_order' => $value['file_order'],
                    'status' => $value['status']
                );
            }
            foreach ($item_documents as $value) 
            {
                $data['item_documents'][] = array(
                    'id' => $value['id'],
                    'name' => $value['name'],
                    'orignal_name' => $value['orignal_name'],
                    'size' => $value['size'],
                    'file_order' => $value['file_order'],
                    'status' => $value['status']
                );
            }
            foreach ($item_test_report_documents as $value) {
                $data['item_test_documents'][] = array(
                    'id' => $value['id'],
                    'name' => $value['name'],
                    'orignal_name' => $value['orignal_name'],
                    'size' => $value['size'],
                    'url' => $value['name'],
                    'type' => $value['type'],
                    'file_order' => $value['file_order'],
                    'status' => $value['status']
                );
            }
        }

        if ($this->input->post()) 
        {

        }
        else
        {
              $this->template->load_admin('auction/auction_items/documents_list', $data);
        }
    }

    public function delete_item_test_documents($itemId)
    {
        $attach_name = $this->input->post('file_to_be_deleted');

        $file_array = $this->files_model->get_file_byName($attach_name);
        if(isset($file_array) && !empty($file_array))
        {
            $result_array = $this->items_model->get_item_byid($itemId);
            if(isset($result_array) && !empty($result_array))
            {
                $itemsIds_array = explode(',' ,$result_array[0]['item_test_report']);

                if(!empty($itemsIds_array))
                {
                    $str = $result_array[0]['item_test_report'];
                }
            }
            $updated_str = $this->removeItemString($str, $file_array[0]['id']);
            $update = [
                    'item_test_report' => $updated_str,
                    'updated_by' => $this->loginUser->id
                ];
            $update_item_row = ($this->items_model->update_item($itemId,$update)) ? 'true' : 'false';
        }
       

        $path = FCPATH .  "uploads/items_documents/".$itemId."/";
        $result = $this->files_model->delete_by_name($attach_name,$path);
        if($result)
        {
            echo 'success';    
        }
        
    }


    public function save_item_file_images()
    {

        if( ! empty($_FILES['images']['name'])){

            $item_id = $_POST['item_id'];
            $itemsIds_array = array();
            $ids_concate = '';

            $result_array = $this->items_model->get_item_byid($item_id);
            if(isset($result_array) && !empty($result_array))
            {
                $itemsIds_array = explode(',' ,$result_array[0]['item_images']);

                if(!empty($itemsIds_array) && !empty($result_array[0]['item_images']))
                {
                    $ids_concate = $result_array[0]['item_images'].",";
                }
            }

            // make path
            $path = './uploads/items_documents/';
            if ( ! is_dir($path.$item_id)) {
                mkdir($path.$item_id, 0777, TRUE);
            }
            $path = $path.$item_id.'/'; 
            $config['upload_path'] = $path;
            $sizes = [
            // ['width' => 1184, 'height' => 661],
            // ['width' => 191, 'height' => 120], // for details page carusal icons 
            // ['width' => 305, 'height' => 180], // for gallery page 
            // ['width' => 294, 'height' => 204], // for auction page
            // ['width' => 349, 'height' => 207], // for auction page
            ['width' => 37, 'height' => 36] // for table listing
            ];
            // $config['allowed_types'] = 'ico|png|jpg|pdf|ai|psd|eps|indd|doc|docx|ppt|pptx|xlsx|xls';
            $config['allowed_types'] = 'ico|png|jpg|jpeg';
            $uploaded_file_ids = $this->files_model->multiUpload('images', $config, $sizes);
            if (isset($uploaded_file_ids['error'])) {
                $error = $uploaded_file_ids['error'];
                echo json_encode(array('error' => true,'message'=>$uploaded_file_ids['error']));
            } else {
                // upload and save to database
                $uploaded_file_ids = implode(',', $uploaded_file_ids);
                $update = [
                    'item_images' => $ids_concate.$uploaded_file_ids
                ];
                $result = ($this->items_model->update_item($item_id,$update)) ? 'true' : 'false';

                 if($result == 'true')
                {
                    echo json_encode(array('error' => false,'data'=>'Item Documents has been updated Successfully.'));
                    $this->session->set_flashdata('msg', 'Item Documents Added Successfully');
                }
            }
        }
    }

    public function save_item_file_documents()
    {

        if( ! empty($_FILES['documents']['name'])){

            $item_id = $_POST['item_id'];
            
            $itemsIds_array = array();
            $ids_concate = '';

            $result_array = $this->items_model->get_item_byid($item_id);
            if(isset($result_array) && !empty($result_array))
            {
                $itemsIds_array = explode(',' ,$result_array[0]['item_attachments']);

                if(!empty($itemsIds_array) && !empty($result_array[0]['item_attachments']))
                {
                    $ids_concate = $result_array[0]['item_attachments'].",";
                }
            }

            // make path
            $path = './uploads/items_documents/';
            if ( ! is_dir($path.$item_id)) {
                mkdir($path.$item_id, 0777, TRUE);
            }
            $path = $path.$item_id.'/'; 
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'pdf|doc|docx|xlsx|xls';
            $uploaded_file_ids = $this->files_model->multiUpload('documents', $config); // upload and save to database

            $uploaded_file_ids = implode(',', $uploaded_file_ids);
            if (isset($uploaded_file_ids['error'])) {
                $error = $uploaded_file_ids['error'];
                echo json_encode(array('error' => true,'message'=>$uploaded_file_ids['error']));
            } else {
                $update = [
                    'item_attachments' => $ids_concate.$uploaded_file_ids,
                    'item_status' => 'completed',
                ];
                $result = ($this->items_model->update_item($item_id,$update)) ? 'true' : 'false';
                if($result == 'true')
                {
                    echo json_encode(array('error' => false,'data'=>'Item Documents has been updated Successfully.'));
                    $this->session->set_flashdata('msg', 'Item Documents Added Successfully');
                }
            }
        }
    }

    public function delete_item_image($itemId)
    {
        $attach_name = $this->input->post('file_to_be_deleted');
        $file_array = $this->files_model->get_file_byName($attach_name);
        if(isset($file_array) && !empty($file_array))
        {

        $result_array = $this->items_model->get_item_byid($itemId);
            if(isset($result_array) && !empty($result_array))
            {
                $itemsIds_array = explode(',' ,$result_array[0]['item_images']);

                if(!empty($itemsIds_array))
                {
                    $str = $result_array[0]['item_images'];
                }
            }
            $updated_str = $this->removeItemString($str, $file_array[0]['id']);
            $update = [
                'item_images' => $updated_str,
                'updated_by' => $this->loginUser->id
            ];
            $result_update = ($this->items_model->update_item($itemId,$update)) ? 'true' : 'false';
        }
        $path = FCPATH .  "uploads/items_documents/".$itemId."/";
        $result = $this->files_model->delete_by_name($attach_name,$path);
        if($result)
        {
            echo 'success';    
        }
        
    }
    public function delete_item_documents($itemId)
    {
        $attach_name = $this->input->post('file_to_be_deleted');

        $file_array = $this->files_model->get_file_byName($attach_name);
        if(isset($file_array) && !empty($file_array))
        {
            $result_array = $this->items_model->get_item_byid($itemId);
            if(isset($result_array) && !empty($result_array))
            {
                $itemsIds_array = explode(',' ,$result_array[0]['item_attachments']);

                if(!empty($itemsIds_array))
                {
                    $str = $result_array[0]['item_attachments'];
                }
            }
            $updated_str = $this->removeItemString($str, $file_array[0]['id']);
            $update = [
                    'item_attachments' => $updated_str,
                    'updated_by' => $this->loginUser->id
                ];
            $update_item_row = ($this->items_model->update_item($itemId,$update)) ? 'true' : 'false';
        }
       

        $path = FCPATH .  "uploads/items_documents/".$itemId."/";
        $result = $this->files_model->delete_by_name($attach_name,$path);
        if($result)
        {
            echo 'success';    
        }
        
    }

    public function filter_items_by_category()
    {
        $data = array();
        if($this->input->post())
        {
            $posted_data = $this->input->post();
            unset($sql);  

        if (isset($posted_data['category_id']) && !empty($posted_data['category_id'])) {
 
            $sql[] = " item.category_id = ".$posted_data['category_id']." ";
            $sql[] = " item.in_auction = 'no' ";
        }
  
            $query = "";
        if (!empty($sql)) {
            $query .= ' ' . implode(' AND ', $sql);
        }

            $data['items_list'] = $this->items_model->items_filter_limit_list($query);
            $data['auction_id'] =  $this->input->post('auction_id');
            $data_view = $this->load->view('auction/auction_items/items_content', $data, true);
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found'));
        }
    }


    public function get_auction_banner_details()
    {
        $data = array();
        $data['auction_id'] = $this->input->post('id');
        $data['item_id'] = $this->input->post('item_id');
        // print_r($data['item_id']);die();
        $item_ids = explode(',', $data['item_id']);
        $j=-1;
        foreach ($item_ids as $key => $item_id) {
            $j++;
            $data['item_rows'][] = $this->items_model->get_item_details_by_id($item_id); 
            // print_r($data['item_rows'][0][$j]['item_images']);die('dsd');
            $data['auction_item_rows'][] = $this->auction_model->auction_item_bidding_rule_list($data['auction_id'],$item_id); 
            if(isset($data['item_rows'][0][$j]['item_images']) && !empty($data['item_rows'][0][$j]['item_images']))
            {
                $images_ids = explode(",",$data['item_rows'][0][$j]['item_images']);
               $data['files_array'][] = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
            }
            else
            {
                $data['files_array'][] = array();
            }
        }
            // print_r($data['files_array']);die();

        $data_view = $this->load->view('auction/items_details_content', $data, true);
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view , 'auction_id' => $data['auction_id'] ,  'item_id' => urlencode($data['item_id'])));
    }

    public function get_auction_bannerPrintDetail()
    {
        $data = array();
        $data['item_ids'] = $this->uri->segment(4);
        $data['auction_id'] = $this->uri->segment(3);
        $auction_id = $this->uri->segment(3);
        $data['item_id'] = urldecode($data['item_ids']);
        $item_ids = explode(',', $data['item_id']);
        $j=-1;
        foreach ($item_ids as $key => $item_id) {
            $j++;
            $data['item_rows'][] = $this->items_model->get_item_details_by_id($item_id); 
            // print_r($data['item_rows'][0][$j]['item_images']);die('dsd');
            $data['auction_item_rows'][$key] = $this->db->get_where('auction_items', ['item_id' => $item_id,'auction_id' => $auction_id])->row_array();
        } 

       $data_view = $this->load->view('auction/item_detail_printTab', $data);
        // echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    }

    public function get_auction_lot_listing()
    {
        $data = array();
        $data['auction_id'] = $this->input->post('id');
        $data['item_id'] = $this->input->post('item_id');
        if ($data['item_id']) {
            // print_r($data['item_id']);die();
            $item_ids = explode(',', $data['item_id']);
            $j=-1;
            foreach ($item_ids as $key => $item_id) {
                $j++;
                // print_r($data['item_rows'][0][$j]['item_images']);die('dsd');
                $this->db->select('auction_items.*, item.name, item.item_images');
                $this->db->from('auction_items');
                $this->db->join('item', 'auction_items.item_id = item.id', 'LEFT');
                $this->db->where('auction_items.item_id',$item_id);
                $this->db->where('auction_items.auction_id',$data['auction_id']);
                $query = $this->db->get();
                $data['auction_item_rows'][] = $query->result_array();

                if(isset($data['auction_item_rows'][$j][0]['item_images']) && !empty($data['auction_item_rows'][$j][0]['item_images']))
                {
                    $images_ids = explode(",",$data['auction_item_rows'][$j][0]['item_images']);
                   $data['auction_item_rows'][$j][0]['item_files'] = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
                }
                else
                {
                    $data['auction_item_rows'][$j][0]['item_files'] = array();
                }
            }
        }
        usort($data['auction_item_rows'], function($a, $b) {
            return $a[0]['order_lot_no'] <=> $b[0]['order_lot_no'];
        });
            // print_r($data['auction_item_rows']);die();

        $data_view = $this->load->view('auction/auction_items_lot_listing', $data, true);
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    }


    public function get_banner_details()
    {
        $data = array();
        $data['item_id'] = $this->input->post('id');
        // var_dump($data['item_id']);
        $data['auction_id'] = $this->input->post('auction_id');
        $data['item_row'] = $this->items_model->get_item_details_by_id($data['item_id']); 
        
        $data['auction_item_row'] = $this->auction_model->auction_item_bidding_rule_list($data['auction_id'],$data['item_id']); 
        if(isset($data['item_row'][0]['item_images']) && !empty($data['item_row'][0]['item_images']))
        {
            $images_ids = explode(",",$data['item_row'][0]['item_images']);
           $data['files_array'] = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
        }
        else
        {
            $data['files_array'] = array();
        }
        $data_view = $this->load->view('auction/auction_items/items_details_content', $data, true);

        echo json_encode(array( 'msg'=>'success', 'data' => $data_view , 'item_id' => $data['item_id'], 'auction_id' => $data['auction_id']));
    }

    public function get_print_tab()
    {
        $data = array();
        $data['auction_id'] =$this->uri->segment(3);
        // print_r($data);die();
        $data['item_id'] = $this->uri->segment(4);
        $data['item_row'] = $this->items_model->get_item_details_by_id($data['item_id']); 
        
        $data['auction_item_row'] = $this->auction_model->auction_item_bidding_rule_list($data['auction_id'],$data['item_id']); 
        if(isset($data['item_row'][0]['item_images']) && !empty($data['item_row'][0]['item_images']))
        {
            $images_ids = explode(",",$data['item_row'][0]['item_images']);
           $data['files_array'] = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
        }
        else
        {
            $data['files_array'] = array();
        }
        $data_view = $this->load->view('auction/auction_items/print_tab_content', $data);
        // echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
        // $data_view = $this->load->view('auction/auction_items/print_tab_content');
    }


    public function get_lotting()
    {
        $data = array();
        $data['item_id'] = $this->input->post('id');
        $data['auction_id'] = $this->input->post('auction_id');
        $data['bidding_info'] = $this->auction_model->auction_item_bidding_rule_list($data['auction_id'],$data['item_id']); 
        $auction_bid_list_array = $this->auction_model->get_auctions($data['auction_id']); 
        $data['auction_bid_list'] = explode(",",$auction_bid_list_array[0]['bid_options']); 
         $data['auction_start_time'] = $auction_bid_list_array[0]['start_time'];
         $data['auction_expiry_time'] = $auction_bid_list_array[0]['expiry_time'];
        $data_view = $this->load->view('auction/auction_items/lotting', $data, true);
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    }

    public function get_blinking()
    {
        $data = array();
        $data['item_id'] = $this->input->post('id');
        $data['auction_id'] = $this->input->post('auction_id');
        $data['bidding_info'] = $this->auction_model->auction_item_bidding_rule_list($data['auction_id'],$data['item_id']); 
        $auction_bid_list_array = $this->auction_model->get_auctions($data['auction_id']); 
        $data['auction_bid_list'] = explode(",",$auction_bid_list_array[0]['bid_options']); 
         $data['auction_start_time'] = $auction_bid_list_array[0]['start_time'];
         $data['auction_expiry_time'] = $auction_bid_list_array[0]['expiry_time'];
        $data_view = $this->load->view('auction/auction_items/blinking', $data, true);
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    }

    // Update an Item in popup model
    public function edit_item_detail_view()
    {
        $data = array();
        $data['small_title'] = 'Update Item';
        $data['formaction_path'] = 'update_item_detail_view';
        $data['current_page_list'] = 'current-page';
        $data['item_id'] = $this->input->post('id');
        $item_id = $this->input->post('id');
     
        $data['item_info'] = $this->items_model->get_item_byid($item_id);
        $item_dynamic_fields_info = $this->items_model->get_itemfields_byItemid($item_id);
        // $data['seller_list'] = $this->users_model->users_list(3);
        $data['seller_list'] = $this->users_model->get_all_sales_user();
        // print_r($item_dynamic_fields_info);
        if($item_dynamic_fields_info)
        {
          foreach ($item_dynamic_fields_info as $value) 
          {
            $multiple_info = $this->items_model->fields_multiple_info($value['fields_id']);
            $field_ids[] = $value['fields_id'];
            $field_values[] = $value['value'];
            $field_ids_list[$value['fields_id']] = array('multiple' => $multiple_info[0]['multiple'], 'value' => $value['value']);
            // print_r($value['fields_id'].'  - '.$multiple_info[0]['multiple'].' ');
          }
            $data['field_ids'] = $field_ids;
            $data['field_values'] = $field_ids_list;
        }

            $data['category_list'] = $this->items_model->get_item_category_active();


            $data_view = $this->load->view('auction/auction_items/item_detail_form', $data, true);
            echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
            // $response = array('msg' => 'success','data' => $data_view);
    }

    public function item_detail()
    {
        $data = array();
        $item_id = $this->uri->segment(3);
        $data['item_id'] = $item_id;
        $auction_id = $this->uri->segment(4);
        $auction = $this->db->get_where('auctions', ['id' => $auction_id])->row_array();
        $data['auction_expiry_time'] = $auction['expiry_time'];
        if ($auction['access_type'] == 'live') {
            $data['back_navigate'] = 'liveitems';
        } else {
            $data['back_navigate'] = 'items';
        }
        $data['item_row'] = $this->items_model->get_item_details_by_id($data['item_id']); 
        if(isset($data['item_row'][0]['item_images']) && !empty($data['item_row'][0]['item_images']))
        {
            $images_ids = explode(",",$data['item_row'][0]['item_images']);
           $data['files_array'] = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
        }
        else
        {
            $data['files_array'] = array();
        }

        if(isset($data['item_row'][0]['item_attachments']) && !empty($data['item_row'][0]['item_attachments']))
        {
            $documents_ids = explode(",",$data['item_row'][0]['item_attachments']);
           $data['documents_array'] = $this->files_model->get_multiple_files_by_ids_orders($documents_ids);
        }
        else
        {
            $data['documents_array'] = array();
        }


        if(isset($data['item_row'][0]['item_test_report']) && !empty($data['item_row'][0]['item_test_report']))
        {
            $test_documents_ids = explode(",",$data['item_row'][0]['item_test_report']);
            $data['test_documents_array'] = $this->files_model->get_multiple_files_by_ids_orders($test_documents_ids);
        }
        else
        {
            $data['test_documents_array'] = array();
        }


        $this->template->load_admin('auction/auction_items/items_detail', $data);
    }

    public function item_detail_ajax()
    {
        $data = array();
        $item_id = $this->uri->segment(3);
        $data['item_id'] = $item_id;
        $data['auction_id'] = $this->input->post('auction_id');
        $data['back_navigate'] = 'items';
        $data['item_row'] = $this->items_model->get_item_details_by_id($data['item_id']); 
        // print_r($data['auction_id']);die();
        // print_r($data['item_row']);die();
        $data['lot_no'] = $this->db->select('lot_no, order_lot_no')->get_where('auction_items', ['item_id' => $data['item_id'],'auction_id' => $data['auction_id']])->row_array();
        $user_deposit = $this->db->get_where('auction_deposit', ['user_id' => $data['item_row'][0]['seller_id'], 'deposit_type' => 'permanent'])->result_array();
        //seller charges details
        $data['seller_charges'] = $this->db->get_where('seller_charges',['user_type' => 'seller','type' => 'percent'])->row_array();
        $data['seller_per_charges'] = $this->db->select('SUM(commission) as commission')->get_where('seller_charges',['user_type' => 'seller','type' => 'amount'])->row_array();

        $tr='';
        if ($user_deposit != '') {
            foreach ($user_deposit as $key => $value) {
                $tr .= '<tr>
                <td>'.date('Y-m-d', strtotime($value['created_on'])).'</td>
                <td>'.$value['transaction_id'].'</td>
                <td>'.$value['amount'].'</td>
                <td>0.00</td>
                <td>'.$value['payment_type'].'</td>
                </tr>';
            }
        }
        echo json_encode(array('msg' => 'success', 'data' => $data, 'tr' => $tr));
    }

    public function buyer_details()
    {
        $data = array();
        $data['user_id'] = $this->input->post('user_id');
        $data['user_row'] = $this->users_model->get_user_byid($data['user_id']); 
        // print_r($data['item_row']);die();
        //buyer comission details
        $data['buyer_per_charges'] = $this->db->get_where('seller_charges',['user_type' => 'buyer','type' => 'percent'])->row_array();
        $data['buyer_charges'] = $this->db->select('SUM(commission) as commission')->get_where('seller_charges',['user_type' => 'buyer','type' => 'amount'])->row_array();

        $user_deposit = $this->db->get_where('auction_deposit', ['user_id' => $data['user_id'], 'deposit_type' => 'permanent'])->result_array();
        $data['balance']=$this->customer_model->user_balance($data['user_id']);
        $tr='';
        if ($user_deposit != '') {
            foreach ($user_deposit as $key => $value) {
                $tr .= '<tr>
                <td>'.date('Y-m-d', strtotime($value['created_on'])).'</td>
                <td>'.$value['transaction_id'].'</td>
                <td>'.$value['amount'].'</td>
                <td>0.00</td>
                <td>'.$value['payment_type'].'</td>
                </tr>';
            }
        }
        echo json_encode(array('msg' => 'success', 'data' => $data, 'tr' => $tr));
    }

    public function deposit_direct_sale()
    {
        $data = array();
        $posted_data = $this->input->post();
        $auction_items = $this->db->get_where('auction_items', ['auction_id' => $posted_data['auction_id'], 'item_id' => $posted_data['item_id']])->row_array();
        $posted_data['auction_item_id'] = $auction_items['id'];
        $posted_data['buyer_charges'] = 0;
        $posted_data['seller_charges'] = 0;
        $posted_data['adjusted_security'] = 0;
        $posted_data['adjusted_deposit'] = 0;
        $posted_data['payable_amount'] = $this->input->post('price');
        $posted_data['payment_status'] = 0;
        $posted_data['seller_payment_status'] = 0;
        $posted_data['created_by'] = $this->session->userdata('logged_in')->id;
        $posted_data['updated_by'] = $this->session->userdata('logged_in')->id;
        $posted_data['sale_type'] = 'direct';
        // print_r($posted_data);die();
        $insert = $this->db->insert('sold_items', $posted_data);
        $update = $this->db->update('item', ['sold' => 'yes'], ['id' => $posted_data['item_id']]);
        $this->db->update('auction_items',['sold_status' => 'sold'], ['id' => $auction_items['id']]);
        // $data['balance']=$this->customer_model->user_balance($data['user_id']);
        if ($insert != '') {   
            $this->session->set_flashdata('success', 'Item has been successfully sold out.');
            echo json_encode(array('msg' => 'success', 'data' => $data));
        }else{
            echo json_encode(array('msg' => 'Data base error occured.'));
        }
    }

    public function live_item_detail()
    {
        $data = array();
        $item_id = $this->uri->segment(3);
        $data['item_id'] = $item_id;
        $data['back_navigate'] = 'liveitems';
        $data['item_row'] = $this->items_model->get_item_details_by_id($data['item_id']); 
        if(isset($data['item_row'][0]['item_images']) && !empty($data['item_row'][0]['item_images']))
        {
            $images_ids = explode(",",$data['item_row'][0]['item_images']);
           $data['files_array'] = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
        }
        else
        {
            $data['files_array'] = array();
        }

        if(isset($data['item_row'][0]['item_attachments']) && !empty($data['item_row'][0]['item_attachments']))
        {
            $documents_ids = explode(",",$data['item_row'][0]['item_attachments']);
           $data['documents_array'] = $this->files_model->get_multiple_files_by_ids_orders($documents_ids);
        }
        else
        {
            $data['documents_array'] = array();
        }


        if(isset($data['item_row'][0]['item_test_report']) && !empty($data['item_row'][0]['item_test_report']))
        {
            $test_documents_ids = explode(",",$data['item_row'][0]['item_test_report']);
            $data['test_documents_array'] = $this->files_model->get_multiple_files_by_ids_orders($test_documents_ids);
        }
        else
        {
            $data['test_documents_array'] = array();
        }
        
        $this->template->load_admin('auction/auction_items/items_detail', $data);
    }


    public function item_details_documents()
    {
        $data = array();
        $data['small_title'] = 'Manage Documents';
        $data['current_page_list'] = 'current-page';
        $data['formaction_path'] = 'update_item';
        $data['item_id'] = $this->input->post('id');
        $item_id = $this->input->post('id');     

        $data['item_info'] = $this->items_model->get_item_byid($item_id);
        if(isset($data['item_info']) && !empty($data['item_info']))
        {
            $doc_ids = explode(",",$data['item_info'][0]['item_attachments']);
            $item_documents = $this->items_model->get_item_documents_byid($doc_ids);
            $img_ids = explode(",",$data['item_info'][0]['item_images']);
            $item_images = $this->items_model->get_item_images_byid($img_ids);
            // print_r($item_images);

            foreach ($item_images as $value) 
            {
                $data['item_images'][] = array(
                    'name' => $value['name'],
                    'size' => $value['size'],
                    'type' => $value['type']
                );
            }

            foreach ($item_documents as $value) 
            {
                $data['item_documents'][] = array(
                    'name' => $value['name'],
                    'size' => $value['size'],
                    'type' => $value['type']
                );
            }
        }
              // $this->template->load_admin('items/documents_form', $data);
            $data_view = $this->load->view('auction/auction_items/item_detail_documents_form', $data, true);
            echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
        
    }

    public function update_item_detail_view()
    {
    
        if ($this->input->post()) {

        $item_dynamic_data = $this->input->post();
         
        $item_data = $this->input->post('item'); // get basic information 
        unset($item_dynamic_data['item']);  // remove basic information form data
   

        $this->load->library('form_validation');
          $rules = array(
                array(
                    'field' => 'item[name]',
                    'label' => 'Name',
                    'rules' => 'trim|required'), 
                array(
                    'field' => 'item[status]',
                    'label' => 'Status',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'item[item_status]',
                    'label' => 'Item Status',
                    'rules' => 'trim|required')
            );
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                echo json_encode(array('msg' => validation_errors(),'response'=>validation_errors()));
            } 
            else
            {   
                $id = $item_data['id'];
                 if(!empty($item_data['seller_id']))
                {
                    $seller_id = $item_data['seller_id'];
                }
                else
                {
                 $seller_id = $this->loginUser->id;   
                }

                $posted_data = array(
                'name' => $item_data['name'],
                'location' => $item_data['location'],
                'year' => $item_data['year'],
                'detail' => $item_data['detail'],
                'status' => $item_data['status'],
                'item_status' => $item_data['item_status'],
                'price' => $item_data['price'],
                'category_id' => $item_data['category_id'],
                'seller_id' => $seller_id,
                // 'auction_type' => implode(",", $item_data['auction_type']),
                'updated_by' => $this->loginUser->id
                );

                if(isset($item_data['subcategory_id']) && !empty($item_data['subcategory_id']))
                {
                    $posted_data['subcategory_id'] = $item_data['subcategory_id'];
                }

                if(isset($item_data['keyword']) && !empty($item_data['keyword']))
                {
                    $posted_data['keyword'] = $item_data['keyword'];
                }
                
                if(isset($item_data['price']) && !empty($item_data['price']))
                {
                    $posted_data['price'] = $item_data['price'];
                }

                if(isset($item_data['registration_no']) && !empty($item_data['registration_no']))
                {
                    $posted_data['registration_no'] = $item_data['registration_no'];
                }

                if(isset($item_data['make']))
                {
                    $posted_data['make'] = $item_data['make'];
                    $posted_data['model'] = $item_data['model']; 
                    $posted_data['mileage'] = $item_data['mileage'];    
                    $posted_data['mileage_type'] = $item_data['mileage_type'];     
                }
                

                $result = $this->items_model->update_item($id,$posted_data);

                $item_row_array = $this->items_model->get_item_byid($id);

                    $path = "uploads/items_documents/".$id."/qrcode/";

                    // make path
                    if ( !is_dir($path)) {
                        mkdir($path, 0777, TRUE);
                    } 

                    $qrcode_name = $this->generate_code($id,$path, ['id'=>$id]);
                    if(!empty($qrcode_name))
                    {
                        $barcode_array = array(
                        'barcode' => $qrcode_name
                        );
                        $this->items_model->update_item($id,$barcode_array);
                    }
                if(empty($item_row_array[0]['barcode']))
                {
                    $this->files_model->delete_by_id($item_row_array[0]['barcode'], $path);
                }
               



                $result_attachments = array();
  

                foreach ($item_dynamic_data as $dynamic_keys => $dynamic_values) {
                $ids_arr = explode("-", $dynamic_keys);
                if(is_array($dynamic_values))
                    {
                        $dynamic_values_new = "[".implode(",",$dynamic_values)."]";
                    }
                    else
                    {
                        $dynamic_values_new = $dynamic_values;
                    }
                    $category_id = $item_data['category_id'];
                    $fields_id = $ids_arr[0];
                    $check_if = $this->items_model->check_items_field_data($id,$item_data['category_id'],$ids_arr[0]);
                    // check if fields already exist or not then insert or update accordingly 
                    if($check_if)
                    {
                        $dynaic_information2 = array(
                        'category_id' => $category_id,
                        'item_id' => $id,
                        'fields_id' => $fields_id,
                        'value' => $dynamic_values_new,
                        'updated_by' => $this->loginUser->id
                        );

                    $result_info = $this->items_model->update_item_fields_data($id,$category_id,$fields_id,$dynaic_information2);
                    }
                    else
                    {
                        $dynaic_information = array(
                        'category_id' => $item_data['category_id'],
                        'item_id' => $id,
                        'fields_id' => $ids_arr[0],
                        'value' => $dynamic_values_new,
                        'created_on' => date('Y-m-d H:i:s'),
                        'created_by' => $this->loginUser->id
                        );

                    $result_info = $this->items_model->insert_item_fields_data($dynaic_information);
                    }
                    
                }

                if (!empty($result)) {
                  $this->session->set_flashdata('msg', 'Item Updated Successfully');
                  $msg = 'success';
                  $response = 'Item Updated Successfully';
                  echo json_encode(array('msg' => $msg,'response'=>$response, 'in_id' => $result, 'attach' => $result_attachments));
                  
                }
                else
                {
                    $msg = 'DB Error found.';
                    $response = 'DB Error found.';
                    echo json_encode(array('msg' => $msg,'response'=>$response, 'error' => $result));
                }
            }
        }else{
            $msg = 'DB Error found.';
                    $response = 'DB Error found.';
                    echo json_encode(array('msg' => $msg,'response'=>$response));
        }
    }

    public function update_lotting()
    {
        $data = array();
        $id = '';
        if ($this->input->post()) 
        {
            $this->load->library('form_validation');
            $rules = array(
                array(
                    'field' => 'item_id',
                    'label' => 'Valid Item',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'auction_id',
                    'label' => 'Valid Auction',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'order_lot_no',
                    'label' => 'Lot No',
                    'rules' => 'trim|required'));
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array( 'msg'=>'error', 'data' => validation_errors()));
            } 
            else
            { 
                $item_id = $this->input->post('item_id');
                $auction_id = $this->input->post('auction_id');
                $order_lot_no = $this->input->post('order_lot_no');
                $item_with_same_lot = $this->db->get_where('auction_items', ['order_lot_no' => $order_lot_no, 'auction_id' => $auction_id, 'item_id !=' => $item_id]);
                if ($item_with_same_lot->num_rows() > 0) {
                    $order_lot_array = array();
                    $all_items_with_greater_lot_no = $this->db->order_by('order_lot_no', 'ASC')->get_where('auction_items', ['order_lot_no >=' => $order_lot_no, 'auction_id' => $auction_id])->result_array();
                    if (!empty($all_items_with_greater_lot_no)) {
                        // print_r($all_items_with_greater_lot_no);
                        foreach ($all_items_with_greater_lot_no as $key => $value) {
                            $order_lot_array[] = $value['order_lot_no'];
                        }
                        // print_r($order_lot_array);die();
                        $j = 0;
                        $order_lot_array_count = count($order_lot_array);
                        for ($i = $order_lot_array_count; $i >=1 ; $i--) {
                            $j++;
                            $a = $all_items_with_greater_lot_no[$i-1]['order_lot_no'];
                            $itm_id = $all_items_with_greater_lot_no[$i-1]['item_id'];
                            if ($j == 1) {
                                $new = $a+1;
                                $posted_data = array(
                                    'order_lot_no' => $new
                                );
                                $this->auction_model->update_auction_item_bidding_rules($itm_id,$auction_id,$posted_data);
                            } else {
                                $posted_data = array(
                                    'order_lot_no' => $b
                                );
                                $this->auction_model->update_auction_item_bidding_rules($itm_id,$auction_id,$posted_data);
                            }
                            $b = $a;
                        }
                    } else {
                        // echo json_encode(array( 'msg'=>'error', 'data' => 'Please chage lot number.'));
                        // exit();
                    }
                }
                
                if(!empty($item_id))
                {
                   $posted_data = array(
                           'order_lot_no' => $this->input->post('order_lot_no'),
                            'updated_by' => $this->loginUser->id
                            );
                    $result = $this->auction_model->update_auction_item_bidding_rules($item_id,$auction_id,$posted_data);
                    $msg_ = 'Item Lotting Number Updated Successfully';
                }
              
                if($result)
                {
                    // $this->session->set_flashdata('msg', $msg_);
                    echo json_encode(array( 'msg'=>'success', 'data' => $msg_));
                }
                else
                {
                    echo json_encode(array( 'msg'=>'error', 'data' => 'Error Found'));
                }
            }
        }
    
    }

    public function update_blinking(){
        $data = array();
        $id = '';
        if ($this->input->post()) 
        {
            $this->load->library('form_validation');
            $rules = array(
                array(
                    'field' => 'item_id',
                    'label' => 'Valid Item',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'auction_id',
                    'label' => 'Valid Auction',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'blink_text',
                    'label' => 'Blinking Text',
                    'rules' => 'trim|required'));
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                 echo json_encode(array( 'msg'=>'error', 'data' => validation_errors()));
            } 
            else
            { 

            
            $item_id = $this->input->post('item_id');
            $auction_id = $this->input->post('auction_id');
            
            if(!empty($item_id))
            {
               $posted_data = array(
                       'blink_text' => $this->input->post('blink_text'),
                        'updated_by' => $this->loginUser->id
                        );
                $result = $this->auction_model->update_auction_item_bidding_rules($item_id,$auction_id,$posted_data);
                $msg_ = 'Item Screen 1 Blinking text Updated Successfully';
            }
              
            if($result)
            {
                echo json_encode(array( 'msg'=>'success', 'data' => $msg_));
                }
                else
                {
                    echo json_encode(array( 'msg'=>'error', 'data' => 'Error Found'));
                }
            }
        }
    
    }

    public function view_auction_items_with_users($b64_aid)
    {
        $data = array();
        $auction_id = base64_decode(urldecode($b64_aid));
        $data['current_page_auction'] = 'current-page';
        
        $auction_data_array = $this->auction_model->get_auctions($auction_id);
        $data['category_id'] = $auction_data_array[0]['category_id'];
        $auction_title = json_decode($auction_data_array[0]['title']);
        $data['small_title'] = $auction_title->english.' Items List';
        $item_ids_list_multi_array = $this->auction_model->get_auction_item_ids($auction_id);
        if(isset($item_ids_list_multi_array) && !empty($item_ids_list_multi_array))
        {
            $item_ids_list = array_column($item_ids_list_multi_array,"item_id");
            $data['items_list'] = $this->items_model->get_active_item_list($item_ids_list);
            foreach ($data['items_list'] as $key => $value) {
                $query = $this->db->query('Select bid.bid_time,bid.user_id,bid.item_id,bid.auction_id,bid.bid_amount, bid.bid_status ,item.category_id,users.username  from bid  
           inner join  ( Select max(bid_time) as LatestDate, item_id  from bid  Group by bid.item_id  ) SubMax on bid.bid_time = SubMax.LatestDate and bid.item_id = SubMax.item_id
               LEFT JOIN item ON item.id = bid.item_id LEFT JOIN users ON users.id = bid.user_id  WHERE bid.item_id = '.$value['id'].' AND bid.auction_id = '.$auction_id.';');
            $data['items_list'][$key]['bid_data'] =  $query->row_array();
            $auction_item = $this->db->select('sold_status,bid_start_time,bid_end_time,order_lot_no')->where('item_id', $value['id'])->where('auction_id', $auction_id)->get('auction_items')->row_array();
            $data['items_list'][$key]['sold_status'] =  $auction_item['sold_status'];
            $data['items_list'][$key]['bid_start_time'] =  $auction_item['bid_start_time'];
            $data['items_list'][$key]['bid_end_time'] =  $auction_item['bid_end_time'];
            $data['items_list'][$key]['order_lot_no'] =  $auction_item['order_lot_no'];
            }
            // print_r($data['items_list']);die();
        }
        else
        {
            $data['items_list'] = array();
        }
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['formaction_path'] = 'filter_items';
        $data['auction_id'] = $auction_id;
        $data['auction_expiry_time'] = $auction_data_array[0]['expiry_time'];
        $data['customer_type_list'] = $this->crm_model->customer_type_list_active();
        $data['seller_list'] = $this->users_model->get_all_sales_user();
        $data['application_user_list'] = $this->users_model->get_application_users();
        $this->template->load_admin('auction/auction_items/items_list', $data);
    }


    // Add a new Auction
    public function save_live_auction()
    {
        $data = array();
        $dynaic_information = array();
        $data['small_title'] = 'Add Auction';
        $data['current_page_live_auction'] = 'current-page';
        $data['formaction_path'] = 'save_live_auction';
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['customer_list'] = $this->users_model->get_all_customer_user();
        if ($this->input->post()) {
            $auction_data = $this->input->post();
            
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
                    'field' => 'registration_no',
                    'label' => 'Registration No',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'start_time',
                    'label' => 'Start Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'expiry_time',
                    'label' => 'Expiry Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'access_type',
                    'label' => 'Access Type',
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
            else
            {   
                

                $posted_data = array(
                // 'title' => $auction_data['title'],
                'access_type' => $auction_data['access_type'], 
                'status' => $auction_data['status'],
                'registration_no' => $auction_data['registration_no'], 
                'start_time' => date('Y-m-d H:i',strtotime($auction_data['start_time'])),
                'expiry_time' => date('Y-m-d H:i',strtotime($auction_data['expiry_time'])),
                'created_on' => date('Y-m-d H:i'),
                'created_by' => $this->loginUser->id
                ); 

                $title = [
                'english' => $auction_data['title_english'], 
                'arabic' => $auction_data['title_arabic'], 
                ];
                unset($auction_data['title_english']); 
                unset($auction_data['title_arabic']); 
                $posted_data['title'] = json_encode($title);

                $detail = [
                'english' => $auction_data['detail_english'], 
                'arabic' => $auction_data['detail_arabic'], 
                ];
                unset($auction_data['detail_english']); 
                unset($auction_data['detail_arabic']); 
                $posted_data['detail'] = json_encode($detail);

                if(isset($auction_data['detail']) && !empty($auction_data['detail'])){
                    $posted_data['detail'] = $auction_data['detail'];
                }

                $popup_message = [
                'english' => $auction_data['popup_message_english'], 
                'arabic' => $auction_data['popup_message_arabic'], 
                ];
                unset($auction_data['popup_message_english']); 
                unset($auction_data['popup_message_arabic']); 
                $posted_data['popup_message'] = json_encode($popup_message);

                if(isset($auction_data['popup_message']) && !empty($auction_data['popup_message'])){
                    $posted_data['popup_message'] = $auction_data['popup_message'];
                }

                // if(isset($auction_data['security']) && !empty($auction_data['security'])){
                //     $posted_data['security'] = $auction_data['security'];
                // }

                if(isset($auction_data['category_id']) && !empty($auction_data['category_id'])){
                    $posted_data['category_id'] = $auction_data['category_id'];
                    
                }

                if(isset($auction_data['subcategory_id']) && !empty($auction_data['subcategory_id'])){
                    $posted_data['subcategory_id'] = $auction_data['subcategory_id'];

                }
                
                $result = $this->auction_model->insert_auction($posted_data);
                if (!empty($result))
                {
                  $this->session->set_flashdata('msg', 'Auction Added Successfully');
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
            $this->template->load_admin('auction/live_auction_form', $data);
        }

    }

    public function live_auction_controller(){
        
        $data = array();
        $data['small_title'] = 'Live Auction Setting';
        $data['current_page_live_setting_auction'] = 'current-page';
        $data['formaction_path'] = 'live_auction_controller';
        $data['auction_live_setting'] = $this->db->get('auction_live_settings')->row_array();
        $data['button_status'] = (isset($data['auction_live_setting']) && !empty($data['auction_live_setting']))? 'Update' : 'Save';
        if($this->input->post()){       
            $result = array();
            $live_auction_setting_data = $this->input->post();  
            $this->load->library('form_validation');
             $rules = array( 
                array(
                    'field' => 'buttons',
                    'label' => 'Buttons',
                    'rules' => 'trim|required'), 
                // array(
                //     'field' => 'allow_online',
                //     'label' => 'Allow Online',
                //     'rules' => 'trim|required')

            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false){
                echo json_encode(array('msg' => validation_errors()));

            }else{


                $posted_data = array( 
                    'buttons' => $live_auction_setting_data['buttons'],
                    // 'allow_online' => $live_auction_setting_data['allow_online']
                );

                $posted_data['description'] = $live_auction_setting_data['description'];
                $posted_data['min_increment'] = $live_auction_setting_data['min_increment'];
                $posted_data['live_video_link'] = ( isset($live_auction_setting_data['live_video_link']) && !empty($live_auction_setting_data['live_video_link']) ) ? $live_auction_setting_data['live_video_link'] : NULL;

                if(isset($live_auction_setting_data['id']) && !empty($live_auction_setting_data['id'])){

                    // update record
                    $id = $live_auction_setting_data['id'];
                    $posted_data['updated_by'] =  $this->loginUser->id;
                    $result = $this->db->update('auction_live_settings', $posted_data,['id'=>$id]);

                }else{
                    // save new record 
                    $posted_data['created_by'] =  $this->loginUser->id;
                    $posted_data['created_on'] =  date('Y-m-d');
                    $result = $this->db->insert('auction_live_settings', $posted_data);
                }

                if($result){
                    $this->session->set_flashdata('success', 'Auction setting has been updated successfully');
                    echo json_encode(array('error'=>false, 'message'=>'Setting has been updated'));
                }else{
                    echo json_encode(array('error'=>true, 'message'=>'record has not been updated'));
                }
            }
        }else{
            
            $this->template->load_admin('auction/live_auction_settings', $data);
        }
    }

    public function controller_view()
    {
        $data = array();

        // $this->load->view('auction/auction_controller/auction_controller', $data);
        $this->template->loadControllerView('auction/auction_controller/auction_controller', $data);
    }
    // Update a Auction
    public function update_live_auction()
    {
        $data = array();
        $data['small_title'] = 'Update Live Auction';
        $data['current_page_live_auction'] = 'current-page';
        $data['formaction_path'] = 'update_live_auction';
        $auction_id = $this->uri->segment(3);
        $data['auction_info'] = $this->auction_model->get_auctions($auction_id);
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['customer_list'] = $this->users_model->get_all_customer_user();

        if ($this->input->post()) {         
            $auction_data = $this->input->post();  
            
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
                    'field' => 'registration_no',
                    'label' => 'Registration No',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'start_time',
                    'label' => 'Start Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'expiry_time',
                    'label' => 'Expiry Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'access_type',
                    'label' => 'Access Type',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required')

            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) 
            {
                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                if(isset($auction_data['close_auction_users']) && !empty($auction_data['close_auction_users']))
                {
                $close_auction_users = implode(",", $auction_data['close_auction_users']);
                }
                else
                {
                $close_auction_users = '';
                }

                $id = $auction_data['id'];
                $posted_data = array( 
                // 'title' => $auction_data['title'],
                // 'title' => $auction_data['title'],
                'access_type' => $auction_data['access_type'],
                'status' => $auction_data['status'],
                'registration_no' => $auction_data['registration_no'],
                'close_auction_users' => $close_auction_users,
                'start_time' => date('Y-m-d H:i',strtotime($auction_data['start_time'])),
                'expiry_time' => date('Y-m-d H:i',strtotime($auction_data['expiry_time'])),
                'updated_by' => $this->loginUser->id
                );

                 $title = [
                'english' => $auction_data['title_english'], 
                'arabic' => $auction_data['title_arabic'], 
                ];
                unset($auction_data['title_english']); 
                unset($auction_data['title_arabic']); 
                $posted_data['title'] = json_encode($title);

                $detail = [
                'english' => $auction_data['detail_english'], 
                'arabic' => $auction_data['detail_arabic'], 
                ];
                unset($auction_data['detail_english']); 
                unset($auction_data['detail_arabic']); 
                $posted_data['detail'] = json_encode($detail);


                if(isset($auction_data['detail']) && !empty($auction_data['detail'])){
                    $posted_data['detail'] = $auction_data['detail'];
                }

                $popup_message = [
                'english' => $auction_data['popup_message_english'], 
                'arabic' => $auction_data['popup_message_arabic'], 
                ];
                unset($auction_data['popup_message_english']); 
                unset($auction_data['popup_message_arabic']); 
                $posted_data['popup_message'] = json_encode($popup_message);                

                if(isset($auction_data['popup_message']) && !empty($auction_data['popup_message'])){
                    $posted_data['popup_message'] = $auction_data['popup_message'];
                }
                
                // if(isset($auction_data['security']) && !empty($auction_data['security']))
                // {
                //     $posted_data['security'] = $auction_data['security'];
                // }
                if(isset($auction_data['category_id']) && !empty($auction_data['category_id']))
                {
                    $posted_data['category_id'] = $auction_data['category_id'];
                }

                if(isset($auction_data['subcategory_id']) && !empty($auction_data['subcategory_id']))
                {
                    $posted_data['subcategory_id'] = $auction_data['subcategory_id'];
                }

                $result = $this->auction_model->update_auction($id,$posted_data);

                if (!empty($result)) 
                {
                  $this->session->set_flashdata('msg', 'Auction Updated Successfully');
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

            $this->template->load_admin('auction/live_auction_form', $data);
        }

    }
 

    public function get_bidding_rules()
    {
        $data = array();
        $data['item_id'] = $this->input->post('id');
        $data['auction_id'] = $this->input->post('auction_id');
        $data['bidding_info'] = $this->auction_model->auction_item_bidding_rule_list($data['auction_id'],$data['item_id']); 
        $auction_bid_list_array = $this->auction_model->get_auctions($data['auction_id']); 
        $data['auction_bid_list'] = explode(",",$auction_bid_list_array[0]['bid_options']); 
         $data['auction_start_time'] = $auction_bid_list_array[0]['start_time'];
         $data['auction_expiry_time'] = $auction_bid_list_array[0]['expiry_time'];
        $data_view = $this->load->view('auction/auction_items/bidding_rules', $data, true);
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    }

    public function update_bidding_rules()
    {
        $data = array();
        $id = '';
        if ($this->input->post()) 
        {
            $this->load->library('form_validation');
                $rules = array(
                    array(
                        'field' => 'auction_id',
                        'label' => 'Valid Auction',
                        'rules' => 'trim|required'),
                    array(
                        'field' => 'bid_start_price',
                        'label' => 'Bid Start Price',
                        'rules' => 'trim|required'),
                    array(
                        'field' => 'minimum_bid_price',
                        'label' => 'Minimum Bid Price',
                        'rules' => 'trim|required'),
                    array(
                        'field' => 'bid_start_time',
                        'label' => 'Start End Time',
                        'rules' => 'trim|required'),
                    array(
                        'field' => 'bid_end_time',
                        'label' => 'Bidding End Time',
                        'rules' => 'trim|required')
                );
                if ($this->input->post('security')) {
                    $security = $this->input->post('security');
                    if($security == 'yes'){
                        $deposit = [
                            'field' => 'deposit',
                            'label' => 'Deposit',
                            'rules' => 'trim|required'
                        ];
                        array_push($rules, $deposit);
                    }
                }

                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                     echo json_encode(array( 'msg'=>'error', 'data' => validation_errors()));
                }else{
                    $auction_id = $this->input->post('auction_id');
                    if(!empty($auction_id)){
                            // print_r($this->input->post('auction_item_ids_list'));die();

                       $posted_data = array(
                        // 'allowed_bids' => implode(",", $this->input->post('allowed_bids')),
                        'allowed_bids' => $this->input->post('allowed_bids'),
                        'bid_start_price' => $this->input->post('bid_start_price'),
                        'minimum_bid_price' => $this->input->post('minimum_bid_price'),
                        'bid_start_time' => date('Y-m-d h:i:s',strtotime($this->input->post('bid_start_time'))),
                        'bid_end_time' => date('Y-m-d H:i:s',strtotime($this->input->post('bid_end_time'))),
                        'security' => $this->input->post('security'),
                        'deposit' => $this->input->post('deposit'),
                        'status' => $this->input->post('status'),
                        'updated_by' => $this->loginUser->id
                        );
                        if ($this->input->post('auction_item_ids_list')) {
                            $auction_item_ids_list = $this->input->post('auction_item_ids_list');
                            $auction_item_ids_list = explode(',', $auction_item_ids_list);
                            // print_r($auction_item_ids_list);die();
                            $this->db->where_in('item_id', $auction_item_ids_list);
                            $this->db->where('auction_id', $auction_id);
                            $result = $this->db->update('auction_items', $posted_data);
                        }else {
                            $item_id = $this->input->post('item_id');
                            $result = $this->auction_model->update_auction_item_bidding_rules($item_id,$auction_id,$posted_data);
                        }
                    $msg_ = 'Item Bidding Rules Updated Successfully';
                }
              
              if($result)
              {
                // $this->session->set_flashdata('msg', $msg_);
                echo json_encode(array( 'msg'=>'success', 'data' => $msg_));
              }
              else
              {
                echo json_encode(array( 'msg'=>'error', 'data' => 'Error Found'));
              }
            }
        }
    
    }

    public function search_items()
    {
        $data = array();
        if($this->input->post())
        {
            $posted_data = $this->input->post();
            unset($sql); 
        
        if (isset($posted_data['category_id']) && !empty($posted_data['category_id'])) {
 
            $sql_cat = "  item.category_id = ".$posted_data['category_id']."  ";

            // $sql_cat .= " AND item.in_auction = 'no' ";
            $sql_cat .= " AND item.item_status = 'completed' ";
            $sql_cat .= " AND item.status = 'active' ";
            $sql_cat .= "AND item.sold = 'no' ";
        }
        
        if (isset($posted_data['days_filter']) && !empty($posted_data['days_filter'])) {
          
            $interval = (($posted_data['days_filter'] == 'today')) ? 0 : $posted_data['days_filter'];

            $sql[] = " (DATE(item.created_on)  >= DATE(NOW()) + INTERVAL ".$interval." DAY  AND DATE(item.created_on) <  NOW() + INTERVAL  0 DAY) ";
             
        }

        if (isset($posted_data['search']) && !empty($posted_data['search'])) {
 
            $sql[] = " item.lot_id like '%".$posted_data['search']."%' ";
            
            $sql[] = " item.registration_no like '%".$posted_data['search']."%' ";
            
            $sql[] = " item.name like '%".$posted_data['search']."%' ";

            $sql[] = " item.keyword like '%".$posted_data['search']."%' "; 
        }
        
        $query = "";
        if (!empty($sql)) {
            $query .= ' (' . implode(' OR ', $sql).' ) AND '.$sql_cat;
        }
        else
        {
             $query .= $sql_cat;
        }


            $data_items_list = $this->items_model->items_filter_list_rules($query);
            if(isset($data_items_list) && !empty($data_items_list)){
                $items_ids = array();
                $auction_ids = array();
                $auction_item_id = array();
                $sild_items_id = array();
                $validItemsList = array();
                // get active item ids 
                foreach ($data_items_list as $activeitems_listvalue) {
                    $items_ids[] = $activeitems_listvalue['id'];
                }
                // check if item is in some active auciton or not
                $active_auctions = $this->auction_model->getActiveAuctions();
                foreach ($active_auctions as $activeAuctionvalue) {
                    $auction_ids[] = $activeAuctionvalue['id'];
                }

                $active_auction_item = $this->auction_model->getActiveAuctionItems($auction_ids);
                foreach ($active_auction_item as $activeAuctionItemvalue) {
                    $auction_item_id[] = $activeAuctionItemvalue['item_id'];
                }
                $sold_item = $this->auction_model->getsoldItems();
                foreach ($sold_item as $solditemvalue) {
                    $sild_items_id[] = $solditemvalue['item_id'];
                }
                $validItemsList = array_diff($items_ids, $auction_item_id);
                $validItemsList = array_diff($validItemsList, $sild_items_id);
                if ($validItemsList != array()) {
                    $data['items_list'] = $this->items_model->active_items_filter_list($validItemsList);
                }else{
                    $data['items_list'] = array();
                }
            }else{
                $data['items_list'] = array();
            }

            $data['auction_id'] =  $this->input->post('auction_id');
            $data_view = $this->load->view('auction/auction_items/items_content', $data, true);
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found'));
        }
    }

    public function view_auction_items($b64_aid)
    {
        $data = array();
        $auction_id = base64_decode(urldecode($b64_aid));
        $data['current_page_auction'] = 'current-page';
        
        $auction_data_array = $this->auction_model->get_auctions($auction_id);
        $data['category_id'] = $auction_data_array[0]['category_id'];
        $auction_title = json_decode($auction_data_array[0]['title']);
        $data['small_title'] = $auction_title->english.' Items List';
        $item_ids_list_multi_array = $this->auction_model->get_auction_item_ids($auction_id);
        if(isset($item_ids_list_multi_array) && !empty($item_ids_list_multi_array))
        {
            $item_ids_list = array_column($item_ids_list_multi_array,"item_id");
            $data['items_list'] = $this->items_model->get_active_item_list($item_ids_list);
            foreach ($data['items_list'] as $key => $value) {
                $query = $this->db->query('Select bid.bid_time,bid.user_id,bid.item_id,bid.auction_id,bid.bid_amount, bid.bid_status ,item.category_id,users.username  from bid  
                inner join  ( Select max(bid.id) as LatestBidId, item_id  from bid  Group by bid.item_id  ) SubMax on bid.id = SubMax.LatestBidId and bid.item_id = SubMax.item_id
               LEFT JOIN item ON item.id = bid.item_id LEFT JOIN users ON users.id = bid.user_id  WHERE bid.item_id = '.$value['id'].' AND bid.auction_id = '.$auction_id.';');
                $data['items_list'][$key]['bid_data'] =  $query->row_array();
                $auction_item = $this->db->select('sold_status,bid_start_time,bid_end_time,order_lot_no')->where('item_id', $value['id'])->where('auction_id', $auction_id)->get('auction_items')->row_array();
                $data['items_list'][$key]['sold_status'] =  $auction_item['sold_status'];
                $data['items_list'][$key]['bid_start_time'] =  $auction_item['bid_start_time'];
                $data['items_list'][$key]['bid_end_time'] =  $auction_item['bid_end_time'];
                $data['items_list'][$key]['order_lot_no'] =  $auction_item['order_lot_no'];
            }
            // print_r($data['items_list']);die();
        }
        else
        {
            $data['items_list'] = array();
        }
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['formaction_path'] = 'filter_items';
        $data['auction_id'] = $auction_id;
        $data['auction_expiry_time'] = $auction_data_array[0]['expiry_time'];
        $data['customer_type_list'] = $this->crm_model->customer_type_list_active();
        $data['seller_list'] = $this->users_model->get_all_sales_user();
        $data['application_user_list'] = $this->users_model->get_application_users();
        $this->template->load_admin('auction/auction_items/items_list', $data);
    }


    public function auctionItemList($encodedAuctionId)
    {
        $posted_data = $this->input->post();
        $item_ids_list = array();
        $auction_id = base64_decode(urldecode($encodedAuctionId));
        $data['small_title'] = 'Items List';
        $data['current_page_auction'] = 'current-page';
        

        $item_ids_list_multi_array = $this->auction_model->get_auction_item_ids($auction_id);
        if(isset($item_ids_list_multi_array) && !empty($item_ids_list_multi_array))
        {
            $item_ids_list = array_column($item_ids_list_multi_array,"item_id");
        }
        ## Read value
        $draw = $posted_data['draw'];
        $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
        $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 10; // Rows display per page
        $columnIndex = $posted_data['order'][0]['column']; // Column index
        $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
        $searchValue = $posted_data['search']['value']; // Search value
        // Custom search filter 
        $category_id = (isset($posted_data['category_id'])) ? $posted_data['category_id'] : '';
        $seller_id = (isset($posted_data['seller_id']))? $posted_data['seller_id'] : '';
        $sale_person_id = (isset($posted_data['sale_person_id']))? $posted_data['sale_person_id']: '';
        $item_status = (isset($posted_data['item_status']))? $posted_data['item_status'] : '';
        $keyword = (isset($posted_data['keyword']))? $posted_data['keyword'] : '';
        $vin_number = (isset($posted_data['vin_no']))? $posted_data['vin_no'] : '';
        $registration_no = (isset($posted_data['registration_no'])) ? $posted_data['registration_no'] : '';
        $days_filter = (isset($posted_data['days_filter'])) ? $posted_data['days_filter'] : '';

        $make_id = (isset($posted_data['make_id']))? $posted_data['make_id'] : '';
        $model_id = (isset($posted_data['model_id']))? $posted_data['model_id'] : '';
        ## Search 
        $search_arr = array();
        $searchQuery = "";
        if($searchValue != ''){
            $search_arr[] = " (name like '%".$searchValue."%' ) ";
        }
        if($category_id != ''){
            $search_arr[] = " category_id='".$category_id."' ";
        }
        if($seller_id != ''){
            $seller_ids = implode(",",$seller_id);
            $search_arr[] = " item.seller_id IN (".$seller_ids.") ";
        }
        if($make_id != ''){
            $make_ids = implode(",",$make_id);
            $search_arr[] = " item.make IN (".$make_ids.") ";
        }
        if($model_id != ''){
            $model_ids = implode(",",$model_id);
            $search_arr[] = " item.model IN (".$model_ids.") ";
        }
        if($sale_person_id != ''){
            $sales_person_id = implode(",",$sale_person_id);
            $search_arr[] = " item.created_by IN (".$sales_person_id.") ";
        }
        if($item_status != ''){
            $search_arr[] = " item_status like '%".$item_status."%' ";
        }
        if($registration_no != ''){
            $search_arr[] = " registration_no like '%".$registration_no."%' ";
        }
        if($vin_number != ''){
            $search_arr[] = " vin_number like '%".$vin_number."%' ";
        }
        if($keyword != ''){
            $search_arr[] = " keyword like '%".$keyword."%' ";
        }
        if(isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) {
            $start_date = $posted_data['datefrom'];
            $end_date = $posted_data['dateto'];
            $search_arr[] = " (DATE(item.created_on) between '".$start_date."' and '".$end_date."') ";
        }
        if($days_filter != ''){
            $interval = (($posted_data['days_filter'] == 'today')) ? 0 : $posted_data['days_filter'];
            $search_arr[] = " (DATE(item.created_on)  >= DATE(NOW()) + INTERVAL ".$interval." DAY  AND DATE(item.created_on) <  NOW() + INTERVAL  0 DAY) ";
        }
        if(count($search_arr) > 0){
            $searchQuery = implode(" AND ",$search_arr);
        }

        $total = $this->items_model->get_items();

                ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->where_in('item.id', $item_ids_list);
        $records = $this->db->get('item')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->where_in('item.id', $item_ids_list);
        $records = $this->db->get('item')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('item.*,auction_items.id as auctionItemId,auction_items.sold_status, auction_items.order_lot_no, auction_items.buyer_id');
        $this->db->join('auction_items', 'auction_items.item_id = item.id', 'LEFT');
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->where_in('item.id', $item_ids_list);
        $this->db->where_in('auction_items.auction_id', $auction_id);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('item')->result();


        $data = array();
        $have_documents = false;
            // $records = $this->db->get('item',$rowperpage, $start)->result();

        foreach($records as $record ){
            $bid_amount = 0;
            $images_ids = explode(",",$record->item_images);
            $item_id = $record->id;
            $document_form_url =  base_url('items/documents/').$item_id;
            $view_document_url =  base_url('items/view_documents/').$item_id;
            $item_detail_url =  base_url('auction/details/').$item_id.'/'.$auction_id;

            $image = '';
            $category = $this->db->get_where('item_category',['id'=>$record->category_id])->row_array();
            // $auction_item = $this->db->get_where('auction_items',['auction_id'=>$auction_id, 'item_id'=>$item_id])->row_array();
            $seller = $this->db->get_where('users',['id'=>$record->seller_id])->row_array();
            $seller_row = $this->db->get_where('users',['id'=>$record->seller_id])->row();
            $seller_name = $seller_row->fname.' '.$seller_row->lname;
            $make = $this->db->get_where('item_makes',['id'=>$record->make])->row_array();
            $model = $this->db->get_where('item_models',['id'=>$record->model])->row_array();
            $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
            $bid_query = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $auction_id, 'item_id' => $item_id]);
            if ($bid_query->num_rows() > 0) {
                // print_r($bid_query->num_rows());die();
                $bid_data = $bid_query->row_array();
                $bid_amount = $bid_data['bid_amount'];
            }
            if(!empty($record->item_images) || !empty($record->item_attachments)){
                 $documents_class = 'btn-success';
                 $have_documents = true;
                if(empty($record->item_attachments)){
                    $documents_class = 'btn-info';
                }

            }else{
                $documents_class = 'btn-warning';
            }
            $item_name = json_decode($record->name);
            if(isset($files_array[0]['name']) && !empty($files_array[0]['name'])){
                $file_name = $files_array[0]['name'];
                // $base_url_img = base_url('uploads/items_documents/').$item_id.'/37x36_'.$file_name;
                $urlImg = base_url('uploads/items_documents/').$item_id.'/37x36_'.$file_name;
                if(file_exists($urlImg)){
                    $base_url_img = $urlImg;
                }else{
                    $base_url_img = base_url('uploads/items_documents/').$item_id.'/'.$file_name;
                }
            
                $image = '<a class="text-success" href="'.$item_detail_url.'"><img style="max-width: 50px" class="img-responsive avatar-view" src="'.$base_url_img.'" alt="'.$item_name->english.'"></a>';
            }else{
                 $base_url_img =base_url('assets_admin/images/product-default.jpg');
                 $image = '<a class="text-success" href="'.$item_detail_url.'"><img style="max-width: 50px" class="img-responsive avatar-view" src="'.$base_url_img.'" alt="'.$item_name->english.'"></a>';
            }
            $action = '';

            $b64_auction_id = urlencode(base64_encode($auction_id));

            if($this->loginUser->role == 1 && $record->sold == 'no'){
            $action .= '<a href="'.base_url().'auction/update_live_auction_item/'.$item_id.'/'.$b64_auction_id.'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }
            $action .= '<a href="'.base_url().'auction/documents/'.$item_id.'/'.$b64_auction_id.'/live" class="btn '.$documents_class.' btn-xs"><i class="fa fa-pencil"></i> Documents</a> ';
            if($have_documents){
             $action .= '<a href="'.base_url().'auction/view_documents/'.$item_id.'/'.$b64_auction_id.'/live" class="btn '.$documents_class.' btn-xs"><i class="fa fa-pencil"></i> View Documents</a>';
            }
            $action .= '<button type="button" id="'.$item_id.'" data-toggle="modal" data-target=".bs-example-modal-print" onclick="getQRcode(this);"  data-backdrop="static" data-keyboard="false" class="btn btn-info btn-xs oz_print_qr"><i class="fa fa-qrcode"></i> QR Code</button> ';
            $action .= '<button type="button" id="'.$item_id.'" onclick="get_lotting('.$auction_id.','.$item_id.')"  data-toggle="modal" data-target=".bs-example-modal-sm_lot" data-backdrop="static" data-keyboard="false" class="btn btn-success btn-xs oz_lotting_model"><i class="fa fa-pencil-square-o"></i>Lotting</button>

            <button type="button" id="'.$item_id.'" onclick="getBlinking('.$auction_id.','.$item_id.')"  data-toggle="modal" data-target=".bs-example-modal-sm_blink" data-backdrop="static" data-keyboard="false" class="btn btn-info btn-xs oz_blinking_model"><i class="fa fa-pencil-square-o"></i>Blinking text</button>

            <button type="button" id="'.$item_id.'" data-toggle="modal" onclick="getBanner(this);" data-target=".bs-example-modal-banner"  data-backdrop="static" data-keyboard="false" class="btn btn-info btn-xs oz_banner_pr"><i class="fa fa-file"></i> Banner</button>
            <!-- <a href="'.base_url().'auction/other_charges/'.$item_id.'/'.$b64_auction_id.'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Other Charges</a>-->';

            if($record->sold == 'return'){                  
                $action .= '<a href="javascript:void(0)" class="btn btn-info btn-xs"><i class="fa fa-money"></i> Returned</a>';
            }elseif($record->sold_status == 'not_sold'){ 
            }elseif($record->sold_status == 'sold'){ 
            } else { 
                if($bid_query->num_rows() > 0){
                    // $itms_name = json_decode($value['name']);
                    $action .= '<button type="button" data-id="'.$record->id.'" onclick="saleitem(this)" name="'.$item_name->english.'" reserve_price="'.$record->price.'" bid_price="'.$bid_data['bid_amount'].'" data-toggle="modal" data-target=".sale-model"  data-backdrop="static" data-keyboard="true" class="btn btn-info btn-xs sale_item"><i class="fa fa-file"></i> Sale</button>';
                }
            }

            if($this->loginUser->role == 1  && $bid_query->num_rows() < 1 && $record->sold == 'no'){
                $action .= ' <button onclick="deleteRecord(this)" type="button" data-obj="auction_items" data-id="'.$item_id.'-'.$auction_id.'" data-url="'.base_url().'auction/delete" data-token-name="'.$this->security->get_csrf_token_name().'" data-token-value="'.$this->security->get_csrf_hash().'" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>';
            }

            $title = '';
            $buyer = 'N/A';
            $single_auction=$this->db->get_where('auctions', ['id' => $auction_id])->row_array();
            if ($record->sold_status == 'sold') {
                $sold_items = $this->db->get_where('sold_items',['auction_id'=>$auction_id, 'item_id'=>$item_id])->row_array();
                $days_to_sale_item = $this->config->item("sold_button_days_limit"); 
                if(!empty($sold_items)){ 
                    if ($sold_items["payment_status"] == 0 && date("Y-m-d H:i:s",strtotime("+$days_to_sale_item days",strtotime($single_auction['expiry_time']))) >= date("Y-m-d H:i:s")) {
                        $action .= '<a href="'.base_url('auction/unsold_live/').$item_id.'/'.$b64_auction_id .'/not_sold" class="btn btn-info btn-xs"><i class="fa fa-money"></i> Unsold</a>';
                    }
                }
                if (isset($sold_items['buyer_id']) && !empty($sold_items['buyer_id'])) {
                    $buyer_data = $this->db->get_where('users',['id'=>$sold_items['buyer_id']])->row();
                    $buyer = $buyer_data->username;
                } else {
                    $buyer = '<button class="btn btn-info btn-xs" auctionitem_id="'.$record->auctionItemId.'" onclick="selectbuyer(this)" >Select Buyer</button>';
                }
            }
            $sold_ststus = $record->sold_status;
            switch ($sold_ststus) {
                case 'not':
                $status = "Available";
                break;

                case 'not_sold':
                $status = "Unsold";
                break;

                case 'sold':
                $status = "Sold Out";
                break;

                case 'approval':
                $status = "Need Approval From Customer";
                if (!empty($record->buyer_id)) {
                    $buyer_data = $this->db->get_where('users',['id'=>$record->buyer_id])->row();
                    $buyer = $buyer_data->username;
                }
                break;

                case 'return':
                $status = "Item Returned";
                break;

                default:
                $status = "";
                break;
            }
            $title = json_decode($category['title']);
            if ($make) {
                $make_title = json_decode($make['title'])->english;
                $model_title = json_decode($model['title'])->english;
            } else {
                $make_title = 'N/A';
                $model_title = 'N/A';
            }
            $data[] = array( 
                "id" => $record->id,
                "image"=>$image,
                "name"=> '<a class="text-success" href="'.$item_detail_url.'">'.ucwords($item_name->english).'</a>',
                "category_id"=>$title->english,
                "registration_no"=>$record->registration_no,
                "order_lot_no"=>$record->order_lot_no,
                "make"=>$make_title,
                "model"=>$model_title,
                "vin_number"=>$record->vin_number,
                "price"=>$record->price,
                "bid_amount"=>$bid_amount,
                "created_on"=> date('Y-m-d',strtotime($record->created_on)),
                "sold"=> $status,
                "buyer"=> $buyer,
                "seller"=> $seller_name,
                "item_status"=>ucfirst($record->item_status),
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


    public function view_live_auction_items($b64_uid)
    {
        $this->output->enable_profiler(TRUE);
        $data = array();
        $data['small_title'] = 'Items List';
        $auction_id = base64_decode(urldecode($b64_uid));
        $data['b64_auction_id'] = $b64_uid;
        $data['small_title'] = 'Items List';
        $data['current_page_live_auction'] = 'current-page';
        
        $auction_data_array = $this->auction_model->get_auctions($auction_id);
        $data['category_id'] = $auction_data_array[0]['category_id'];
        $data['auction_expiry_time'] = $auction_data_array[0]['expiry_time'];

        $item_ids_list_multi_array = $this->auction_model->get_auction_item_ids($auction_id);
        if(isset($item_ids_list_multi_array) && !empty($item_ids_list_multi_array))
        {
        $item_ids_list = array_column($item_ids_list_multi_array,"item_id");
        $data['items_list'] = $this->items_model->get_active_item_list($item_ids_list);
        }
        else
        {
            $data['items_list'] = array();
        }
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['formaction_path'] = 'filter_items';
        $data['auction_id'] = $auction_id;
        $data['customer_type_list'] = $this->crm_model->customer_type_list_active();
        $data['seller_list'] = $this->users_model->get_all_sales_user();
        $data['application_user_list'] = $this->users_model->get_application_users();
        $this->template->load_admin('auction/auction_items/live_items_list', $data);
    }

    // Update a auction Item
    public function update_live_auction_item()
    {
        $data = array();
        $data['small_title'] = 'Update Item';
        $data['formaction_path'] = 'update_live_auction_item';
        $data['current_page_live_auction'] = 'current-page';
        $data['item_id'] = $this->uri->segment(3);
        $item_id = $this->uri->segment(3);
     
        $data['item_info'] = $this->items_model->get_item_byid($item_id);
        $item_dynamic_fields_info = $this->items_model->get_itemfields_byItemid($item_id);
        // $data['seller_list'] = $this->users_model->users_list(3);
        $data['seller_list'] = $this->users_model->get_all_sales_user();
        // print_r($item_dynamic_fields_info);
        if($item_dynamic_fields_info)
        {
            foreach ($item_dynamic_fields_info as $value) 
            {
                $multiple_info = $this->items_model->fields_multiple_info($value['fields_id']);
                $field_ids[] = $value['fields_id'];
                $field_values[] = $value['value'];
                $field_ids_list[$value['fields_id']] = array('multiple' => $multiple_info[0]['multiple'], 'value' => $value['value']);
                // print_r($value['fields_id'].'  - '.$multiple_info[0]['multiple'].' ');
            }
            $data['field_ids'] = $field_ids;
            $data['field_values'] = $field_ids_list;
        }

         // print_r($field_ids_list);

        $data['category_list'] = $this->items_model->get_item_category_active();
        if ($this->input->post()) {

        $item_dynamic_data = $this->input->post();
         
        $item_data = $this->input->post('item'); // get basic information 
        unset($item_dynamic_data['item']);  // remove basic information form data
        

        $this->load->library('form_validation');
          $rules = array(
                array(
                    'field' => 'item[name_english]',
                    'label' => 'Name',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'item[name_arabic]',
                    'label' => 'Name',
                    'rules' => 'trim|required'),
                // array(
                //     'field' => 'item[keyword]',
                //     'label' => 'Keyword',
                //     'rules' => 'trim|required'),
                array(
                    'field' => 'item[status]',
                    'label' => 'Status',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'item[item_status]',
                    'label' => 'Item Status',
                    'rules' => 'trim|required')
            );
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                $id = $item_data['id'];
                 if(!empty($item_data['seller_id']))
                {
                    $seller_id = $item_data['seller_id'];
                }
                else
                {
                 $seller_id = $this->loginUser->id;   
                }
                $name = [
                    'english' => $item_data['name_english'],
                    'arabic' => $item_data['name_arabic'],
                ];
                $detail = [
                    'english' => $item_data['detail_english'],
                    'arabic' => $item_data['detail_arabic'],
                ];
                $terms = [
                    'english' => $item_data['terms_english'],
                    'arabic' => $item_data['terms_arabic'],
                ];

                $posted_data = array(
                'name' => json_encode($name),
                'detail' => json_encode($detail),
                'terms' => json_encode($terms),
                'location' => $item_data['location'],
                'year' => $item_data['year'],
                'status' => $item_data['status'],
                'item_status' => $item_data['item_status'],
                'price' => $item_data['price'],
                // 'keyword' => $item_data['keyword'],
                'category_id' => $item_data['category_id'],
                'seller_id' => $seller_id,
                // 'auction_type' => implode(",", $item_data['auction_type']),
                'updated_by' => $this->loginUser->id
                );

                if(isset($item_data['lat']) && !empty($item_data['lat']))
                {
                    $posted_data['lat'] = $item_data['lat'];
                }
                if(isset($item_data['lng']) && !empty($item_data['lng']))
                {
                    $posted_data['lng'] = $item_data['lng'];
                }

                if(isset($item_data['subcategory_id']) && !empty($item_data['subcategory_id']))
                {
                    $posted_data['subcategory_id'] = $item_data['subcategory_id'];
                }

                if(isset($item_data['keyword']) && !empty($item_data['keyword']))
                {
                    $posted_data['keyword'] = $item_data['keyword'];
                }

                if(isset($item_data['vin_number']) && !empty($item_data['vin_number']))
                {
                    $posted_data['vin_number'] = $item_data['vin_number'];
                }

                if(isset($item_data['price']) && !empty($item_data['price']))
                {
                    $posted_data['price'] = $item_data['price'];
                }

                if(isset($item_data['registration_no']) && !empty($item_data['registration_no']))
                {
                    $posted_data['registration_no'] = $item_data['registration_no'];
                }

                if(isset($item_data['make']))
                {
                    $posted_data['make'] = $item_data['make'];
                    $posted_data['model'] = $item_data['model'];  
                    $posted_data['mileage'] = $item_data['mileage'];    
                    $posted_data['mileage_type'] = $item_data['mileage_type'];    
                }
                

                $result = $this->items_model->update_item($id,$posted_data);

                $item_row_array = $this->items_model->get_item_byid($id);

                    $path = "uploads/items_documents/".$id."/qrcode/";

                    // make path
                    if ( !is_dir($path)) {
                        mkdir($path, 0777, TRUE);
                    } 

                    $qrcode_name = $this->generate_code($id,$path, ['id'=>$id]);
                    if(!empty($qrcode_name))
                    {
                        $barcode_array = array(
                        'barcode' => $qrcode_name
                        );
                        $this->items_model->update_item($id,$barcode_array);
                    }
                if(empty($item_row_array[0]['barcode']))
                {
                    $this->files_model->delete_by_id($item_row_array[0]['barcode'], $path);
                }
               



                $result_attachments = array();
  

                foreach ($item_dynamic_data as $dynamic_keys => $dynamic_values) {
                $ids_arr = explode("-", $dynamic_keys);
                if(is_array($dynamic_values))
                    {
                        $dynamic_values_new = "[".implode(",",$dynamic_values)."]";
                    }
                    else
                    {
                        $dynamic_values_new = $dynamic_values;
                    }
                    $category_id = $item_data['category_id'];
                    $fields_id = $ids_arr[0];
                    $check_if = $this->items_model->check_items_field_data($id,$item_data['category_id'],$ids_arr[0]);
                    // check if fields already exist or not then insert or update accordingly 
                    if($check_if)
                    {
                        $dynaic_information2 = array(
                        'category_id' => $category_id,
                        'item_id' => $id,
                        'fields_id' => $fields_id,
                        'value' => $dynamic_values_new,
                        'updated_by' => $this->loginUser->id
                        );

                    $result_info = $this->items_model->update_item_fields_data($id,$category_id,$fields_id,$dynaic_information2);
                    }
                    else
                    {
                        $dynaic_information = array(
                        'category_id' => $item_data['category_id'],
                        'item_id' => $id,
                        'fields_id' => $ids_arr[0],
                        'value' => $dynamic_values_new,
                        'created_on' => date('Y-m-d H:i:s'),
                        'created_by' => $this->loginUser->id
                        );

                    $result_info = $this->items_model->insert_item_fields_data($dynaic_information);
                    }
                    
                }

                if (!empty($result)) {
                  $this->session->set_flashdata('msg', 'Item Updated Successfully');
                  $msg = 'success';
                  echo json_encode(array('msg' => $msg, 'in_id' => $result, 'attach' => $result_attachments));
                  
                }
                else
                {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                }
            }
        }else{

            $this->template->load_admin('auction/auction_items/live_auction_item_form', $data);
        }

    }


    // Add a new Auction
    public function save_auction()
    {
        // $this->output->enable_profiler(TRUE);
        $data = array();
        $dynaic_information = array();
        $data['small_title'] = 'Add Auction';
        $data['current_page_auction'] = 'current-page';
        $data['formaction_path'] = 'save_auction';
        // $auction_id = $this->uri->segment(3);
        // $data['auction_info'] = $this->auction_model->get_auctions($auction_id);
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['customer_list'] = $this->users_model->get_all_customer_user();

        if ($this->input->post()) {
        $auction_data = $this->input->post();
        
        if(isset($auction_data['access_type']) && $auction_data['access_type'] == 'closed')
        {
            $close_auction_users_rule =  array(
                'field' => 'close_auction_users[]',
                'label' => 'Buyer List',
                'rules' => 'trim|required'
            );         
        }
        else
        {

            $close_auction_users_rule = '';
        }

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
                    'field' => 'registration_no',
                    'label' => 'Registration No',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'category_id',
                    'label' => 'category Id',
                    'rules' => 'trim|required'),
                // array(
                //     'field' => 'security',
                //     'label' => 'Security',
                //     'rules' => 'trim|required'),
                array(
                    'field' => 'start_time',
                    'label' => 'Start Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'expiry_time',
                    'label' => 'Expiry Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'access_type',
                    'label' => 'Access Type',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'),
                $close_auction_users_rule

            );
             $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                if(isset($auction_data['close_auction_users']) && !empty($auction_data['close_auction_users']))
                {
                    $close_auction_users = implode(",", $auction_data['close_auction_users']);
                }
                else
                {
                    $close_auction_users = '';
                }

                $posted_data = array(
                // 'title' => $auction_data['title'],
                'access_type' => $auction_data['access_type'],
                // 'access_type' => implode(",", $auction_data['access_type']),
                'status' => $auction_data['status'],
                'registration_no' => $auction_data['registration_no'],
                // 'security' => $auction_data['security'],
                // 'view_only' => $auction_data['view_only'],
                'close_auction_users' => $close_auction_users,
                'start_time' => date('Y-m-d H:i',strtotime($auction_data['start_time'])),
                'expiry_time' => date('Y-m-d H:i',strtotime($auction_data['expiry_time'])),
                'created_on' => date('Y-m-d H:i'),
                'created_by' => $this->loginUser->id
                );

                $title = [
                'english' => $auction_data['title_english'], 
                'arabic' => $auction_data['title_arabic'], 
                ];
                unset($auction_data['title_english']); 
                unset($auction_data['title_arabic']); 
                $posted_data['title'] = json_encode($title);


                // if(isset($auction_data['detail']) && !empty($auction_data['detail'])){
                //     $posted_data['detail'] = $auction_data['detail'];
                // }
                $detail = [
                'english' => $auction_data['detail_english'], 
                'arabic' => $auction_data['detail_arabic'], 
                ];
                unset($auction_data['detail_english']); 
                unset($auction_data['detail_arabic']); 
                $posted_data['detail'] = json_encode($detail);

                if(isset($auction_data['security']) && !empty($auction_data['security'])){
                    $posted_data['security'] = $auction_data['security'];
                }

                if(isset($auction_data['category_id']) && !empty($auction_data['category_id'])){
                    $posted_data['category_id'] = $auction_data['category_id'];
                    
                }

                if(isset($auction_data['subcategory_id']) && !empty($auction_data['subcategory_id'])){
                    $posted_data['subcategory_id'] = $auction_data['subcategory_id'];

                }

                $result = $this->auction_model->insert_auction($posted_data);
                if (!empty($result))
                {
                  $this->session->set_flashdata('msg', 'Auction Added Successfully');
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
            $this->template->load_admin('auction/auction_form', $data);
        }

    }
    // Add a new items to Auction
    public function add_auction_items()
    {
        $data = array();
        // $LottingNumberRand  = getNumber(4); 
        $dynaic_information = array();
        if ($this->input->post()) {
            $auction_data = $this->input->post();
            // print_r($auction_data);die();
            
            $items_array = explode(",",$auction_data['ids_list']);
            foreach ($items_array as $value) {
                //lot number
                $topAuctionItem = $this->db->order_by('id','desc')->get('auction_items', 1)->row_array();
                $LottingNumberRand = $topAuctionItem['id'] +  1;

                //order lot number
                $auction_item = $this->db->select_max('order_lot_no')->where('auction_id', $auction_data['auction_id'])->get('auction_items');
                if ($auction_item->num_rows() > 0) {
                    $auction_item = $auction_item->row_array();
                    $order_lot_no = $auction_item['order_lot_no'] + 1;
                } else {
                    $order_lot_no = 1;
                }
                
                $posted_data = array(
                'auction_id' => $auction_data['auction_id'],
                'category_id' => $auction_data['category_id'],
                'item_id' => $value,
                'lot_no' => $LottingNumberRand,
                'order_lot_no' => $order_lot_no,
                'created_on' => date('Y-m-d h:i:s'),
                'created_by' => $this->loginUser->id
                );
                $result = $this->auction_model->insert_auction_items($posted_data);
                
                $in_auction_array = array(
                    'in_auction' => 'yes',
                    'updated_by' => $this->loginUser->id
                );
                
                $result_item_in_auction = $this->items_model->update_item($value,$in_auction_array);

            }
            
            if (!empty($result))
            {

              $msg = 'success';
              echo json_encode(array('msg' => $msg, 'data' => 'Items added to auction successfully'));
            }
            else
            {
                $msg = 'DB Error found.';
                echo json_encode(array('msg' => $msg, 'data' => $result));
            }
        }
        else
        {
            $this->template->load_admin('auction/auction_form', $data);
        }

    } 


    public function get_qrcode()
    {
        $data = array();
        $data['item_id'] = $this->input->post('id');
        $item_row = $this->items_model->get_item_byid($data['item_id']); 
        $image = base_url()."uploads/items_documents/".$data['item_id']."/qrcode/".$item_row[0]['barcode'];
        $data_view = '<div class="row" style="display: flex;"><img src="'.$image.'" width="75px" height="75px" alt="" title="QR Code"/><div style="display: flex; flex-wrap: wrap; align-items: stretch;"><h3 class="" style=" font-size: 9px; margin-top: 11px; width: 100px; height: 30px; word-wrap: break-word;">'.json_decode($item_row[0]['name'])->english.'</h3><h2 class="" style="display: flex; align-items: flex-end; font-size: 9px !important; width: 100%; margin-bottom: 9px;"> '.$item_row[0]['registration_no'].' </h2></div></div>';
        // $data_view .= '<div class="product_price" style="margin-left:10px; display: flex; align-items: flex-end; transform: translate(90px, 29px); position: absolute; background: none; border: none;><h2 class="" style="font-size: 9px !important;"> '.$item_row[0]['registration_no'].' </h2></div>';
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    }

    public function get_log()
    {
        $data = array();
        $item_id = $this->input->post('id');
        $auction_id = $this->input->post('auction_id');
        $data['log'] = $this->db->select('bid.*,users.username as username,users.email as email,users.mobile as mobile')
        ->from('bid')
        ->join('users', 'users.id = bid.user_id', 'LEFT')
        ->where('item_id', $item_id)
        ->where('auction_id', $auction_id)
        ->order_by('bid.bid_amount', 'DESC')
        ->order_by('bid.bid_time', 'DESC')
        ->get()
        ->result_array();
        $data_view = $this->load->view('auction/auction_items/bid_log',  $data, true);
        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
    }

    public function update_files_order()
    {
        $data = array();
        if($this->input->post('images_orders'))
        {
        $data_array = $this->input->post('images_orders');
        foreach ($data_array as $file_id_key => $file_order_value) 
        {
            
            $update_data = array(
                'file_order' => $file_order_value
            );
            $result = $this->files_model->update_file($file_id_key,$update_data);
            
        }
        }
        if($this->input->post('documents_order'))
        {
        $data_array = $this->input->post('documents_order');
        foreach ($data_array as $file_id_key => $file_order_value) 
        {
            
            $update_data = array(
                'file_order' => $file_order_value
            );
            $result = $this->files_model->update_file($file_id_key,$update_data);
            
        }
        }
        if($this->input->post('test_documents_order'))
        {
        $data_array = $this->input->post('test_documents_order');
        foreach ($data_array as $file_id_key => $file_order_value) 
        {
            
            $update_data = array(
                'file_order' => $file_order_value
            );
            $result = $this->files_model->update_file($file_id_key,$update_data);
            
        }
        }
        if(isset($result))
        {
        $data_message = "Files Order has been updated";
        echo json_encode(array( 'msg'=>'success', 'response' => $data_message));
        }
        else
        {
        $data_message = "Something went wrong.";
        echo json_encode(array( 'msg'=>'error', 'response' => $data_message));   
        }
    }

    // Update a Auction
    public function update_auction()
    {
        $data = array();
        $data['small_title'] = 'Update Auction';
        $data['current_page_auction'] = 'current-page';
        $data['formaction_path'] = 'update_auction';
        $auction_id = $this->uri->segment(3);
        $data['auction_info'] = $this->auction_model->get_auctions($auction_id);  
        // print_r( $data['auction_info']);die(); 
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['customer_list'] = $this->users_model->get_all_customer_user();

        if ($this->input->post()) {         
            $auction_data = $this->input->post();  
            // print_r($auction_data);die('kkkkk');
            if(isset($auction_data['access_type']) && $auction_data['access_type'] == 'closed')
            {
                $close_auction_users_rule =  array(
                                'field' => 'close_auction_users[]',
                                'label' => 'Buyer List',
                                'rules' => 'trim|required');         
            }
            else
            {

                $close_auction_users_rule = '';
            }
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
                    'field' => 'category_id',
                    'label' => 'category Id',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'registration_no',
                    'label' => 'Registration No',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'start_time',
                    'label' => 'Start Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'expiry_time',
                    'label' => 'Expiry Time',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'access_type',
                    'label' => 'Access Type',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'),
                $close_auction_users_rule

            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) 
            {
                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                if(isset($auction_data['close_auction_users']) && !empty($auction_data['close_auction_users']))
                {
                $close_auction_users = implode(",", $auction_data['close_auction_users']);
                }
                else
                {
                $close_auction_users = '';
                }

                $id = $auction_data['id'];
                $posted_data = array( 
                // 'title' => $auction_data['title'],
                'access_type' => $auction_data['access_type'],
                'status' => $auction_data['status'],
                'registration_no' => $auction_data['registration_no'],
                'close_auction_users' => $close_auction_users,
                'start_time' => date('Y-m-d H:i',strtotime($auction_data['start_time'])),
                'expiry_time' => date('Y-m-d H:i',strtotime($auction_data['expiry_time'])),
                'updated_by' => $this->loginUser->id
                );

                $title = [
                'english' => $auction_data['title_english'], 
                'arabic' => $auction_data['title_arabic'], 
                ];
                unset($auction_data['title_english']); 
                unset($auction_data['title_arabic']); 
                $posted_data['title'] = json_encode($title);


                // if(isset($auction_data['detail']) && !empty($auction_data['detail'])){
                //     $posted_data['detail'] = $auction_data['detail'];
                // }
                $detail = [
                'english' => $auction_data['detail_english'], 
                'arabic' => $auction_data['detail_arabic'], 
                ];
                unset($auction_data['detail_english']); 
                unset($auction_data['detail_arabic']); 
                $posted_data['detail'] = json_encode($detail);

                if(isset($auction_data['security']) && !empty($auction_data['security']))
                {
                    $posted_data['security'] = $auction_data['security'];
                }
                if(isset($auction_data['category_id']) && !empty($auction_data['category_id']))
                {
                    $posted_data['category_id'] = $auction_data['category_id'];
                }

                if(isset($auction_data['subcategory_id']) && !empty($auction_data['subcategory_id']))
                {
                    $posted_data['subcategory_id'] = $auction_data['subcategory_id'];
                }

                $result = $this->auction_model->update_auction($id,$posted_data);

                if ($result) 
                {
                    // echo($result);die();
                  $this->session->set_flashdata('msg', 'Auction Updated Successfully');
                  $msg = 'success';
                  echo json_encode(array('msg' => $msg, 'in_id' => $result));
                  exit();
                  
                }
                else
                {
                    $msg = 'DB Error found.';
                    echo json_encode(array('msg' => $msg, 'error' => $result));
                    exit();
                }
            }
        }
        else
        {

            $this->template->load_admin('auction/auction_form', $data);
        }

    }
 

    // other_charges list
    public function other_charges($id)
    {
        $data = array();
        $data['small_title'] = 'Other Charges';
        $data['current_page_live_auction'] = 'current-page';
        // $data['formaction_path'] = 'update_auction';
        $auction_id = $this->uri->segment(3);
        $data['auction_id'] = $this->db->select('auction_id,item_id')->from('auction_items')->where('item_id', $id)->get()->row_array(); 
        // print_r($data);die();
        $data['list'] = $this->db->get_where('item_other_charges',['item_id' => $id])->result_array(); 

        $this->template->load_admin('auction/auction_items/other_charges', $data);

    }
    // add list
    public function add_other_details()
    {
        $data = array();
        if ($this->input->post()) {
            $posted_data = $this->input->post();
            $this->load->library('form_validation');
            $rules = array( 
                array(
                    'field' => 'description',
                    'label' => 'Detail',
                    'rules' => 'trim|required'),
                array(
                    'field' => 'amount',
                    'label' => 'Amount',
                    'rules' => 'trim|required'),

            );  
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) 
            {
                echo json_encode(array('msg' => validation_errors()));
            } 
            else
            {   
                if (isset($posted_data['id']) && !empty($posted_data['id'])) {
                    $id = $posted_data['id'];
                    unset($posted_data['id']);
                    $result = $this->db->update('item_other_charges',$posted_data, ['id' => $id]);
                    if ($result) {
                        echo json_encode(array('msg'=>'success'));
                    }else{
                        echo json_encode(array('msg'=>'Error! fail to update please try again.'));
                    }
                }else{   
                    unset($posted_data['id']);
                    $posted_data['created_on'] = date('Y-m-d H:i:s');
                    $result = $this->db->insert('item_other_charges',$posted_data);
                    if ($result) {
                        echo json_encode(array('msg'=>'success', 'data' => ''));
                    }else{
                        echo json_encode(array('msg'=>'Error! fail to add please try again.', 'data' => ''));
                    }
                }
            }   

        }

        // $this->template->load_admin('auction/auction_items/other_charges', $data);

    }
    // add list
    public function get_other_details()
    {
        $id = $this->input->post('id');
        $result = $this->db->get_where('item_other_charges',['id' => $id])->row_array();
        if ($result) {
            echo json_encode(array('msg'=>'success', 'data' => $result));
        }else{
            echo json_encode(array('msg'=>'Error!', 'data' => ''));
        }

        // $this->template->load_admin('auction/auction_items/other_charges', $data);

    }
 
    //Delete Single Row
    public function delete( $id = NULL )
    {
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        $res = false;

        if ($table == "auctions") {
            $res = $this->auction_model->delete_auction_row($id);
            $res2 = $this->auction_model->delete_auction_item_rows($id);
        }

        if ($table == "auction_items") {
            $id_arr = explode("-",$id);
            $res = $this->auction_model->delete_item_row_from_auctionitems($id_arr[1],$id_arr[0]);
            $in_auction_array = array(
                    'in_auction' => 'no',
                    'updated_by' => $this->loginUser->id
                );
            $result_item_in_auction = $this->items_model->update_item($id_arr[0],$in_auction_array);
        } 

        //do not change below code
        if ($res) {
            echo $return = '{"type":"success","msg":"Ok"}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }

    //Delete Single Row
    public function delete_bulk( $ids_array = array() )
    {
        $ids_array = $this->input->post("id");
        $table = $this->input->post("obj");
        $res = false;

        if ($table == "auctions") {
            $res = $this->auction_model->delete_auction_MultipleRow($ids_array);
            $res2 = $this->auction_model->delete_auction_items_multiple_row($ids_array);
        }

        //do not change below code
        if ($res) {
            echo $return = '{"type":"success","msg":"Ok"}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }
 

      public function load_user_popup()
    {
        // echo "kdjcnskdnc";
        $posted_data = $this->input->post();
        $users_list['deposite_list'] = $this->db->get_where('users', ['role' => 4])->result_array();
        $opup_data = $this->load->view('deposite/deposite_list',  $users_list, true);
        // print_r($opup_data);die();
        if ($opup_data) {
            $output = json_encode([
                'success' => true,
                'opup_data' => $opup_data,
                'msg' => 'User data get successfully.' ]);
            return print_r($output);
        }
        else
        {
            echo "sdvk";
        }

    }
 
    public function load_buyer_popup()
    {
        // echo "kdjcnskdnc";
        $posted_data = $this->input->post();
        $users_list['users_list'] = $this->db->get_where('users', ['role' => 4])->result_array();
        // print_r($users_list['deposite_list']);die()
        $opup_data = $this->load->view('deposite/buyer_list',  $users_list, true);
        // print_r($opup_data);die();
        if ($opup_data) {
            $output = json_encode([
                'success' => true,
                'opup_data' => $opup_data,
                'msg' => 'User data get successfully.' ]);
            return print_r($output);
        }
        else
        {
            echo "sdvk";
        }

    }

     //users
    public function load_user()
    {
        $posted_data = $this->input->post();
        $data = array();
        // return ;
        ## Read value
        $draw = $posted_data['draw'];
        $start = (isset($posted_data['start']) && !empty($posted_data['start'])) ? $posted_data['start'] : 0;
        $rowperpage = (isset($posted_data['length']) && !empty($posted_data['length'])) ? $posted_data['length'] : 10; // Rows display per page
        $columnIndex = $posted_data['order'][0]['column']; // Column index
        $columnName = $posted_data['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $posted_data['order'][0]['dir']; // asc or desc
        $searchValue = $posted_data['search']['value']; // Search value

        $keyword = (isset($posted_data['searchby1']))? $posted_data['searchby1'] : '';
        $search_arr = array();
         $searchQuery = "";
        if($keyword != ''){
            $search_arr[] = " username like '%".$keyword."%' or mobile like '%".$keyword."%' ";
        }
        // print_r($rowperpage);die();
        if(count($search_arr) > 0){
            $searchQuery = implode(" AND ",$search_arr);
        }

         ## Total number of records without filtering
         $this->db->select('count(*) as allcount');
        $this->db->from('users');
         $this->db->where('role', 4);
         $records = $this->db->get()->result();
         $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('users');
        $this->db->where('role', 4);
        if($searchQuery != ''){
            $this->db->where($searchQuery);
        }
        $records = $this->db->get()->result();
        $totalRecordwithFilter = $records[0]->allcount;

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('role',4);
        if($searchQuery != ''){
            $this->db->where($searchQuery);
        }
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $users_list = $this->db->get()->result_array();
        foreach ($users_list as $key => $value) {
 
        $data[] = array( 
         "id"=>$value['id'],
         "fname"=>$value['username'],
         "mobile"=>$value['mobile']
       ); 
        }

        $response = array(
           "draw" => intval($draw),
           "iTotalRecords" => $totalRecords,
           "iTotalDisplayRecords" => $totalRecordwithFilter,
           "aaData" => $data
        );

        echo json_encode($response); 
    }

    // inspection report
    public function inspection_report(){
        $item_id = $this->uri->segment(3);
        $item_data['data'] = $this->db->get_where('item',['id' => $item_id])->row_array();
        ///dynamic fields 
        $cat_id = 1;
        $datafields = $this->items_model->fields_data_new($cat_id);
        $fdata = array();
        foreach ($datafields as $key => $fields)
        { 
            $item_dynamic_fields_info = $this->items_model->get_itemfields_byItemid_new($item_id,$fields['id']);
            if (!empty($item_dynamic_fields_info)) {
                $fields['values'] = json_decode($fields['values'],true);  
                $fields['data-id'] = $fields['id'];
                if (!empty($fields['values'])) {
                    foreach ($fields['values'] as $key => $options) {
                        if ($options['value'] == $item_dynamic_fields_info['value']) {
                            $fields['data-value'] = $options['label'];
                        }
                    }
                }else{
                    $fields['data-value'] = $item_dynamic_fields_info['value'];
                }
                $fdata[] = $fields;

            }
        }
        $item_data['fields'] = $fdata;


        // print_r($item_data['cats_data']);die();
        $condition_report = $_SERVER['DOCUMENT_ROOT']."/uploads/items_documents/".$item_id."/";
            if (file_exists($condition_report.'condition.png')) {
                // unlink($output_file.'condition.png');
                // $img_base64 = base64_decode($condition_report.'condition.png');
                $item_data['condition_img'] = base_url()."/uploads/items_documents/".$item_id."/".'condition.png';
            }else{
                $item_data['condition_img_text'] = 'Inspection is pending.';
            }
        $this->load->view('items/inspection_report',$item_data);
    }

    public function generate_code($item_id,$path, $item_details_array= array())
   {
        $this->load->library('ciqrcode');

           // how to save PNG codes to server 
        $tempDir = FCPATH .  $path;
        $tempDir_url = base_url()."uploads/items_documents/";
         
        $codeContents = json_encode($item_details_array); 
         
            // we need to generate filename somehow,  
            // with md5 or with database ID used to obtains $codeContents... 
        $fileName = $item_id.'_'.md5($codeContents).'.png'; 
         
        $pngAbsoluteFilePath = $tempDir.$fileName; 
        $urlRelativeFilePath = $tempDir_url.$fileName; 
         
            // generating 
        if (!file_exists($pngAbsoluteFilePath)) { 
            QRcode::png($codeContents, $pngAbsoluteFilePath); 
            return $fileName;
        } else { 
            return $fileName;
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

    private function getItemRejectPushNotification($_id, $_data){        

        if($this->language=='arabic'){
          // $title = $arabic_l['push_item_reject_title'];
            $message = $this->lang->line('push_item_reject_message_2').$_data['item_detail'].$this->lang->line('push_item_reject_message_1');
        }else{
          //   $title = $english_l['push_item_reject_title'];
            $message = $this->lang->line('push_item_reject_message_1').$_data['item_detail'].$this->lang->line('push_item_reject_message_2');
            
        }
        $title = $this->lang->line('push_item_reject_title');
           $arr = [
            'title'=> $title,
           'message'=>$message,
           'link' => 'pioneerauctions://home'
       ];
        return $this->sendPushNotification($_id, $arr);
     }



     private function getAuctionWinnnerPushNotification($_id, $_data){

       if($this->language=='arabic'){
            //$title = $arabic_l['push_winner_title'];
            $message = $this->lang->line('push_winner_message_2').$_data['lot_no'].' - '.$_data['item_name'].$this->lang->line('push_winner_message_1');    
          
        }else{
            //$title = $english_l['push_winner_title'];
            $message = $this->lang->line('push_winner_message_1').$_data['lot_no'].' - '.$_data['item_name'].$this->lang->line('push_winner_message_2');
        }
        $title = $this->lang->line('push_winner_title');
           $arr = [
            'title'=> $title,
           'message'=>$message,
           'link' => 'pioneerauctions://home'
       ];

    
        return $this->sendPushNotification($_id, $arr);
     }

    public function sendPushNotification($to = "", $data=false)
    {

        $language = "english";
        $version = $this->config->item('appVersion');

        if(!empty($to)) {

            $title = $data['title'] ?? 'Test Title';
            $body = $data['message'] ?? 'test message body';
            $image = 'https://staging.pioneerauctions.ae/uploads/items_documents/2649/eNfVsQZlyo.jpg';
            $link = $data['link'] ?? 'pioneerauctions://home';

            $data = [
                "notification" => [
                    "body" => $body,
                    "title" => $title,
                    "image" => $image,
                    "deeplink" => $link 
                ],
                "priority" => "high",
                "data" => [
                    "deeplink" =>$link 
                ],
                "to" => $to
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_POST, 1);

            $headers = array();
            $headers[] = 'Content-Type: application/json';
           $headers[] = 'Authorization: key=AAAAASZ7Vwk:APA91bF6wZpCDm86aSx4HpBM6td3rvdytZqlViClzsqgy4OjdonZFlwpPxuZK0Ew1DLhwdGHPvcQ9ddZWI7hiaFfnTJWQIXkr6-VXR4p355fHnC1p-BPBo6j4GXj_Es3EJf4PP7Wyi84';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            curl_close($ch);

            $codee = http_response_code(200);
            $response = json_encode(array("status" => true, 'code' => $codee, "message" => 'Push Notification Send Successfully', 'language' => $this->language, 'appVersion' => $version));

        }
        return $response;

    }
}

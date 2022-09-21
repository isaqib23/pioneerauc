<?php defined('BASEPATH') or exit('No direct script access allowed');
class Transaction extends Loggedin_Controller
{
    
    function __construct()
    {
        parent::__construct();
        // $this->load->model('Sales_model', 'sales_model');
        $this->load->model('auction/Auction_model', 'auction_model');
        $this->load->model('items/Items_model', 'items_model');
        $this->load->model('users/Users_model','users_model');
        $this->load->model('files/Files_model','files_model');
        $this->load->model('crm/Crm_model','crm_model');
        $this->load->model('transaction/Transaction_model','transaction_model');

    }
    public function index()
    {
        $data = array();
        if($this->loginUser->role == 4 && $this->loginUser->type == 'buyer'){
            $id = $this->loginUser->id;
        }else{
            $id = $this->uri->segment(3);
        }
        $data['small_title'] = 'Deposit ';
        $data['current_page_deposite'] = 'current-page';
         $data['formaction_path'] = 'filter_for_deposite';
         if(!empty($id)){
        $data['deposit_list'] = $this->transaction_model->deposit_detail_list($id);
        }
        else{
            $data['deposit_list'] = $this->transaction_model->deposit_detail_list();

             }
 
        $data['transaction_detail'] = $this->transaction_model->get_transactiom_detail($id);

        // print_r($data['transaction_detail']); die('aaaaaaaaaa');
        $this->template->load_admin('transaction/deposite_acount_detail_list', $data);
    }

    public function bank_deposit_list()
    { 
        $data['small_title'] = 'Bank Transfer List';
        $data['current_page_deposit'] = 'current-page';
        $data['formaction_path'] = 'filter_report';
        $data['list'] = $this->db->select('bank_deposit_slip.*, users.username,users.email')
            ->join('users', 'users.id = bank_deposit_slip.user_id')
            ->order_by('bank_deposit_slip.deposit_date', 'DESC')
            ->get('bank_deposit_slip')->result_array();
        // print_r($data['list']);die();
        $this->template->load_admin('transaction/bank_deposit_list', $data); // get response for select2 JQuery
    }

    public function view_slip($id)
    { 
        $data['small_title'] = 'Bank Transfer List';
        $data['current_page_deposit'] = 'current-page';
        $data['formaction_path'] = 'filter_report';
        $data['row'] = $this->db->select('bank_deposit_slip.*,users.username,files.name,files.path')->from('bank_deposit_slip')->join('users', 'bank_deposit_slip.user_id = users.id', 'LEFT')->join('files', 'bank_deposit_slip.slip = files.id', 'LEFT')->where('bank_deposit_slip.id', $id)->get()->row_array();
        $this->template->load_admin('transaction/view_slip', $data); // get response for select2 JQuery
    }

    public function manage_deposite_acount()
    {
        $data = array();
        if($this->loginUser->role == 4 && $this->loginUser->type == 'buyer'){
            $id = $this->loginUser->id;
        }else{
            $id = $this->uri->segment(3);
        }
        $data['small_title'] = 'Deposit History';
        $data['current_page_deposite'] = 'current-page';
        $data['formaction_path'] = 'filter_for_deposite';
        if(!empty($id)){
            // $data['deposit_list'] = $this->transaction_model->deposit_detail_list($id);
            $data['deposit_list'] = $this->transaction_model->permanent_deposit($id);
        } else {
            // $data['deposit_list'] = $this->transaction_model->deposit_detail_list();
            $data['deposit_list'] = $this->transaction_model->permanent_deposit();
        }
        // print_r($data['deposit_list']);die();
 
        // $data['transaction_detail'] = $this->transaction_model->get_transactiom_detail($id);

        // print_r($data['transaction_detail']); die('aaaaaaaaaa');
        $this->template->load_admin('transaction/deposite_acount_detail_list', $data);
    }

    public function bid_history()
    {
        $data = array();
        if($this->loginUser->role == 4)
        {
            $id = $this->loginUser->id;
        }
        else
        {
            $id = $this->uri->segment(3);
        }
        $data['small_title'] = 'Bid History';
        $data['current_page_bid_history'] = 'current-page';
        $data['formaction_path'] = 'filter_bid_history';

        $data['bid_detail'] = $this->transaction_model->user_bid_detail($id);
        $this->template->load_admin('transaction/bid_detail_list', $data);
    }

    public function filter_bid_history()
    {
        $data = array();
        if($this->input->post())
        {
            $posted_data = $this->input->post();
        if (isset($posted_data['datefrom']) && !empty($posted_data['dateto'])) 
        {
            $start_date = $posted_data['datefrom'];
            $end_date = $posted_data['dateto'];
            $sql[] = " (DATE(bid.date) between '$start_date' and '$end_date') ";
            $sql[] = " bid.bid_status = 'won' ";
        }
            $query = "";
        if (!empty($sql)) 
        {
            $query .= ' ' . implode(' AND ', $sql);
        }
            $data['bid_list'] = $this->transaction_model->filter_bid_history($query);
            $data_view = $this->load->view('transaction/filter_for_bid', $data, true);
            $response = array('msg' => 'success','data' => $data_view);
            echo json_encode($response);
        }
        else
        {
            echo json_encode(array('msg'=>'error','data'=>'No Record Found'));
        }
    }

    public function user_security_history()
        {
        $data = array();
        if($this->loginUser->role == 4 && $this->loginUser->type == 'buyer'){
            $id = $this->loginUser->id;
        }else{
            $id = $this->uri->segment(3);
        }
        $data['small_title'] = 'Security History';
        $data['current_page_security'] = 'current-page';
        $data['formaction_path'] = 'filter_for_deposite';
        if(!empty($id)){
        $data['security_hisotry'] = $this->transaction_model->get_security_detail($id);
        }
        else{
            $data['security_hisotry'] = $this->transaction_model->get_security_detail();

             }
 
        // $data['transaction_detail'] = $this->transaction_model->get_transactiom_detail($id);

        // print_r($data['transaction_detail']); die('aaaaaaaaaa');
        $this->template->load_admin('transaction/security_acount_detail_list', $data);
    }
      public function show_deposite_options()
    {
        // print_r($this->session->userdata('logged_in')->id); die;
        $data = array();
        $data['small_title'] = 'Deposit';
        // $data['category_list'] = $this->items_model->get_item_category();   
        $deposite_user_id = $this->uri->segment(3);

        $data['current_page_deposite'] = 'current-page';
        $dataa['total_deposit'] = $this->users_model->get_amount_sum($deposite_user_id);
        $data['total'] = $dataa['total_deposit']->amount;
        $data['auction_fee'] = $this->users_model->get_deposite_amount();
        $this->template->load_admin('transaction/deposite_user_options', $data);
    }

     public function place_order()
    {
        //  if ($this->input->post()) {
        //     $rules = array(
        //          array(
        //             'field' => 'category_id',
        //             'label' => 'Select Category',
        //             'rules' => 'trim|required',
        //         ),
        //     );
        //     $this->form_validation->set_rules($rules);
        //     if ($this->form_validation->run() == FALSE) {

        //         $this->session->set_flashdata('error', 'catogory field is required');
        //         redirect ('users/show_deposite_options');
        //     }
        // }


        $data=$this->input->post();
        $id=$this->loginUser->id;
        if(!empty($data['user_id_for_admin']))
        {
        $id = $data['user_id_for_admin'];  
        }  

        $q=$this->db->query('select fname,lname,mobile,email from users where id='.$id);
        $query=$q->result_array();
        $data['result']=$query[0];
        $this->session->set_userdata('id',$id);
            // 3. load PayTabs library
            $merchant_email='it@armsgroup.ae';
            $secret_key='UPFlCtGZ0kPkeD5J9SotYq6my2MdE58yUOUxhAxhUmC9ZIT9AhCzIJWgLJupOCYIqSgItN0o9Dx9lqiWIVvqErHqabQZOOHfHXXa';
            $merchant_id='10047347';

            $params = [
                'merchant_email'=>$merchant_email,
                'merchant_id'=>$merchant_id,
                'secret_key'=>$secret_key
            ];
                 
            $this->load->library('Paytabs',$params);



            // 4. make a payment component through SADAD account and credit card
            $invoice = [
                "site_url" => "http://pa.yourvteams.com",
                "return_url" => base_url('transaction/return_paytab'),
                "title" =>'shoaib',
                "cc_first_name" => $query[0]['fname'],
                "cc_last_name" => $query[0]['lname'],
                "cc_phone_number" => '12345',
                "phone_number" => $query[0]['mobile'],
                "email" => $query[0]['email'],
                //"products_per_title" => "MobilePhone || Charger || Camera",
                "products_per_title" => 'bundls', //$order['project_name'],
                //"unit_price" => "12.123 || 21.345 || 35.678 ",
                "unit_price" =>$data['category_amount'],
                //"quantity" => "2 || 3 || 1",
                "quantity" => "1",
                "other_charges" => "0.0",
                //"amount" => "13.082",
                "amount" =>$data['category_amount'],
                "discount" => "0.0",
                "currency" => "SAR",
                "reference_no" => '3',

                "billing_address" => 'model town',
                "city" =>'lahore',
                "state" => 'punjab',
                "postal_code" =>'54000',
                "country" => "SAU",
                "shipping_first_name" => 'M',
                "shipping_last_name" => 'Shoaib',
                "address_shipping" => 'model town lahore',
                "city_shipping" => 'Lahore',
                "state_shipping" => 'punjab',
                "postal_code_shipping" => '5400',
                "country_shipping" => "SAU",
                "msg_lang" => "English",
                "cms_with_version" => "bundldesigns"
            ];

            //  echo "<pre>";
            $response = $this->paytabs->create_pay_page($invoice);

            if($response->response_code == "4012"){

                $this->session->set_userdata('amount',$data['category_amount']); 
                $this->session->set_userdata('transaction_id',$response->p_id);
                // if()
                // $category_id = $data['category_id'];
                // $q = $this->db->query('select title from item_category where id='.$category_id );
                // $query = $q->result_array();
                // $category_name = $query[0]['title'];
                // $this->session->set_userdata('category_name',$category_name);

             // print_r($response-> );die('ghhghghgh');
                // $this->items_model->insert_transaction_data($data);
                // $dataa['transaction_id'] = $response->p_id;
                // $dataa['user_id'] = $id;
                // $dataa['amount'] = $data['category_amount'];
                // $dataa['transaction_time'] = date('Y-m-d H:i:s');
                // print_r($data['user_id']);
                // die();
                // $this->items_model->insert_transaction_data($dataa);
                redirect($response->payment_url);
            }
        // }
    }

    public function return_paytab()
    {
        // print_r($_REQUEST);die();
        if($_REQUEST['payment_reference']){
            //echo $_REQUEST['payment_reference'];
            $merchant_email='it@armsgroup.ae';
            $secret_key='   UPFlCtGZ0kPkeD5J9SotYq6my2MdE58yUOUxhAxhUmC9ZIT9AhCzIJWgLJupOCYIqSgItN0o9Dx9lqiWIVvqErHqabQZOOHfHXXa';
            $merchant_id='10047347';

            $params = [
                'merchant_email'=>$merchant_email,
                'merchant_id'=>$merchant_id,
                'secret_key'=>$secret_key
            ];
                 
            $this->load->library('Paytabs',$params);
            $response = $this->paytabs->verify_payment($_REQUEST['payment_reference']);
            //print_r($response);die();

            if($response->response_code == 100){
                $dataa['transaction_id'] = $this->session->userdata['transaction_id'];;
                $dataa['user_id'] = $this->session->userdata['id'];
                $dataa['amount'] = $this->session->userdata['amount'];
                $dataa['transaction_time'] =  date('Y-m-d H:i:s A');
                $dataa['created_on'] =  date('Y-m-d H:i:s A');
                $dataa['created_by'] = $this->loginUser->id;
                // $dataa['payment'] = "Paytabs";
                // $dataa['category_name'] = $this->session->userdata['category_name'];
                $this->items_model->insert_transaction_data($dataa);


                $this->items_model->update_user_deposite($data);



                $this->session->unset_userdata['transaction_id'];
                $this->session->unset_userdata['id'];
                $this->session->unset_userdata['amount'];
                // $this->session->unset_userdata['category_name'];
                if($this->loginUser->id == 1)
                {
                    $this->session->set_flashdata('msg', 'Deposite Added Successfully');
                    redirect (base_url('transaction/manage_deposite_acount/'.$dataa['user_id']));
                }

                redirect ('transaction/manage_deposite_acount');
                // print_r($data['user_id']);
                // die();


                //if payment successful
                $order = $this->db->get_where('orders', ['trans_id' => $_REQUEST['payment_reference']]);
                if($order->num_rows() > 0){
                    // new order payment
                    $order = $order->row_array();
                    if($order['trans_id'] == $_REQUEST['payment_reference']){
                        // update order payment status to successful
                        $this->db->update('orders', ['payment_status' => 1], ['id' => $order['id']]);
                        
                        // get all order items against new order
                        $order_items = $this->db->get_where('order_items', ['order_id' => $order['id']])->result_array(); 
                        if($order['branding'] == 1){
                            //branding order
                            foreach ($order_items as $key => $item) {
                                $item_status = ($item['item_type'] == 'logo') ? '0' : '5';
                                $this->db->update('order_items', ['status' => $item_status], ['id' => $item['id']]);
                            }
                        }else{
                            //non branding order (custom bundle)
                            foreach ($order_items as $key => $item) {
                                // check each design has questionnaire or not
                                $q_exist = $this->db->get_where('questions_design', ['design_id' => $item['item_id']]);
                                $item_status = ($q_exist->num_rows() > 0) ?  '0' : '1';
                                $this->db->update('order_items', ['status' => $item_status], ['id' => $item['id']]);
                            }
                        }

                        $this->cart->destroy();
                        $this->session->unset_userdata('cart_total');
                        $this->session->set_flashdata('success', $this->lang->line('pm_success'));
                        $this->session->set_flashdata('order_id', $order['id']);
                        // redirect(base_url('questionnaire'));
                        $this->load->view('checkout');
                    }else{
                        //if trans id not matched
                        $this->session->set_flashdata('error', $this->lang->line('pm_cancel'));
                        redirect(base_url().'users/show_deposite_options/'.$dataa['user_id']);
                    }
                }else{
                    // existing order payment
                    $payment = $this->db->get_where('payments', ['trans_id' => $_REQUEST['payment_reference']]);
                    if($payment->num_rows() > 0){
                        //if order is existed
                        $payment = $payment->row_array();
                        if($payment['trans_id'] == $_REQUEST['payment_reference']){
                            $this->db->update('payments', ['payment_status' => 1], ['id' => $payment['id']]);

                            // check requested item is adjustment or not
                            $is_adjustment = $this->db->get_where('adjustment_items', ['item_id' => $payment['item_id']]);
                            if($is_adjustment->num_rows() > 0){
                                // adjustment handling
                                $this->db->update('adjustment_items', ['status' => 1], ['item_id' => $payment['item_id']]);
                                $this->db->update('order_items', ['status' => 4], ['id' => $payment['item_id']]);
                            }
                            
                            //check customize add-on item
                            $custom_addon = $this->db->get_where('order_items', ['order_id' => $payment['order_id'], 'item_type' => 'custom_addon']);
                            if($custom_addon->num_rows() > 0){
                                $custom_addon = $custom_addon->result_array();
                                // update only which are currently selected items
                                foreach ($this->cart->contents() as $key => $item) {

                                    if($item['type'] == 'custom_addon'){
                                        $design_id = explode('-', $item['id']);
                                        $design_id = $design_id[0];

                                        $existed_order = $this->db->get_where('orders', ['id' => $item['order_id']]);
                                        if($existed_order->num_rows() > 0){
                                            $existed_order = $existed_order->row_array();
                                            if($existed_order['branding'] == 1){
                                                $logo_is_approved = $this->db->get_where('order_items',[
                                                    'order_id' => $existed_order['id'],
                                                    'item_type' => 'logo',
                                                    'status' => 3
                                                ]);

                                                if($logo_is_approved->num_rows() > 0){
                                                    $q_exist = $this->db->get_where('questions_design', ['design_id' => $design_id]);
                                                    $item_status = ($q_exist->num_rows() > 0) ?  '0' : '1';
                                                }else{
                                                    $item_status = '5';
                                                }

                                            }else{
                                                $q_exist = $this->db->get_where('questions_design', ['design_id' => $design_id]);
                                                $item_status = ($q_exist->num_rows() > 0) ?  '0' : '1';
                                            }
                                            
                                            $this->db->update('order_items', ['status' => $item_status], [
                                                'order_id' => $existed_order['id'],
                                                'item_id' => $design_id,
                                                'item_type' => 'custom_addon'
                                            ]);
                                        }
                                    }
                                }
                            }
                            
                            $this->cart->destroy();
                            $this->session->unset_userdata('cart_total');
                            $this->session->set_flashdata('success', $this->lang->line('pm_success'));
                            // $this->load->view('my_view');
                            $this->session->set_flashdata('order_id', $payment['order_id']);
                            redirect(base_url('dashboard'));
                        
                        }
                    }
                }
            }else{
                $this->session->set_flashdata('error', $this->lang->line('pm_cancel'));
                // redirect(base_url('checkout'));
                $this->load->view('checkout');
            }

        }else{
            $this->session->set_flashdata('error', $this->lang->line('pm_failed'));
            redirect(base_url('checkout'));
        }
    }

     //Delete Single Row
    public function delete_deposit_detail( $id = NULL )
    {
        $id = $this->input->post("id");
        $table = $this->input->post("obj");
        $res = false;

        $res = $this->transaction_model->delete_transection($id, $table);

        //do not change below code
        if ($res) {
            echo $return = '{"type":"success","msg":"Ok"}';
        } else {
            echo $return = '{"type":"error","msg":"Something went wrong."}';
        }
    }


}

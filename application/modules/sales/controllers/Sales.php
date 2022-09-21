<?php defined('BASEPATH') or exit('No direct script access allowed');
class Sales extends Loggedin_Controller
{
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('Sales_model', 'sales_model');
        $this->load->model('auction/Auction_model', 'auction_model');
        $this->load->model('items/Items_model', 'items_model');
        $this->load->model('users/Users_model','users_model');
        $this->load->model('files/Files_model','files_model');
        $this->load->model('crm/Crm_model','crm_model');

    }

    public function index()
    {
        $data = array();
        $data['small_title'] = 'Auction List';
        $data['current_page_sales'] = 'current-page';
        $data['formaction_path'] = 'sales_items';
        $data['sales_list'] = $this->sales_model->get_auction_list(); 
        $this->template->load_admin('sales/sales_list', $data);
    }


    public function sales_items()
    {
        $data = array();
        $data['auction_id'] = $this->input->post('auction_id'); 
        $auction_bid_list_array = $this->sales_model->get_auctions($data['auction_id']);  
        if($auction_bid_list_array)
        {
            echo json_encode(array( 'msg'=>'success', 'response' => 'Successfully'));
        }else
        {
            echo json_encode(array( 'msg'=>'error', 'response' => 'No Item Found'));
        }
    }

    public function items()
    {
        
        $data = array();
        $user_id = $this->loginUser->id;
        $data['small_title'] = 'Items List';
        $data['total_bids'] = array();
        $data['current_page_sales'] = 'current-page';
        $data['formaction_path'] = 'filter_auction_items';
        $data['auction_id'] = $this->uri->segment(3);
        $data['total_deposite'] = $this->sales_model->get_total_deposite($user_id);
        $data['sales_list'] = $this->sales_model->get_auction_item_ids($data['auction_id']);

        // $data['total_bid'] = $this->sales_model->get_total_bids($data['auction_id']);
        $item_ids_list_multi_array = $this->sales_model->get_auction_item_ids($data['auction_id']);

        if(isset($item_ids_list_multi_array) && !empty($item_ids_list_multi_array))
        {
        $item_ids_list = array_column($item_ids_list_multi_array,"item_id");
        $data['items_list'] = $this->sales_model->get_active_item_list($item_ids_list);

        foreach ($data['items_list'] as $value) {
            $item_id = $value['id'];
           $data['total_bids'][] = $this->sales_model->get_total_bids($item_id);


        };
    
        if(isset($data['items_list']) && !empty($data['items_list']))
        {
            $doc_ids = explode(",",$data['items_list'][0]['item_attachments']);

            $item_documents = $this->items_model->get_item_documents_byid($doc_ids);
            $img_ids = explode(",",$data['items_list'][0]['item_images']);
            $item_images = $this->items_model->get_item_images_byid($img_ids);
            if(!empty($item_images)) {
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
        }
    } 
     
        }
        else
        {
            $data['items_list'] = array();
        }


        $this->template->load_admin('sales/sales_items_list', $data);
    }
 


    public function api()
    {
        
        $data = array();
        $user_id = $this->loginUser->id;
        $data['small_title'] = 'Items List';
        $data['auction_id'] = $this->uri->segment(3);
       
       try 
       {
            
            $sales_list = $this->sales_model->get_auction_item_ids($data['auction_id']);
           
            if(isset($sales_list) && !empty($sales_list))
            {
                $item_ids_list = array_column($sales_list,"item_id");
                $items_list = $this->sales_model->get_active_item_list($item_ids_list);
            }
            else
            {
                $items_list = array();
            }

            echo json_encode(array("success" => true, "message" => 'Successfully','response' => $items_list));
            // if(mysqli_connect_errno()) 
            // {
            //     throw new Exception("Can't connect to db.");
            // }
        }
        catch (Exception $e) 
        {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            return;
        }

        // echo json_encode(array( 'msg'=>'success', 'response' => 'Successfully'));
        // $this->template->load_admin('sales/sales_items_list', $data);
    }


    public function live()
    {
        $data = array();
        $data['small_title'] = 'Live Auction List';
        $data['current_page_live_auction'] = 'current-page';
        $data['formaction_path'] = 'filter_live_auction_items';
        $data['live_auction_list'] = $this->auction_model->get_live_auction_list();
        $data['category_list'] = $this->items_model->get_item_category_active();
        $data['customer_type_list'] = $this->crm_model->customer_type_list_active();
        $data['seller_list'] = $this->users_model->get_all_sales_user();
        $this->template->load_admin('auction/live_auction_list', $data);
    }

    public function bid_now()

    {
        $data = array();
        $q =  array();
        $data['current_date'] = date('Y-m-d');
        $item_id = $this->input->post('id');
        $user_id = $this->loginUser->id;
        $check_transaction = $this->sales_model->check_transaction($user_id);
        $chech_transaction_from_configuration = $this->sales_model->chech_transaction_from_configuration();
    if(!empty($check_transaction) && $check_transaction[0]['amount'] >= $chech_transaction_from_configuration[0]['deposite'])
    {


        $data['deposite'] = $check_transaction[0]['amount'];

        $check_item_amount = $this->sales_model->check_item_amount($item_id);
        
        
        $check_security_status = $this->sales_model->check_security($item_id); 

        if(!empty($check_security_status) && $check_security_status[0]['security'] == "yes")
        {

   
            $check_security_amount = $this->sales_model->check_security_amount($item_id);


            if(!empty($check_security_amount)){
            if($check_item_amount[0]['price'] <= $check_security_amount[0]['amount']*10)
            {

             
                    $data['item_amount'] =  $check_item_amount[0]['price'];
                    $data['item_detail'] = $this->sales_model->get_item_detail($item_id);
                    $data['bid_start_price'] = $this->sales_model->get_minimum_bidding_amount();
                    $data['item_detail'] = $this->sales_model->get_item_detail($item_id);
                    $data['transacton_detail'] = $this->users_model->transaction_detail_for_user($user_id);
                      
                    if(!empty($data['transacton_detail']))
                    {
                    $deposit_user =  $data['transacton_detail'][0]['user_id']; 
                    $data['admin_detail'] = $this->users_model->users_listing($user_id);
                     $q = $this->users_model->users_listing($deposit_user);
                    if($q)
                    {
                     $data['user_transaction'] = $q;
                    }
                    $data['sales_list'] = $this->sales_model->get_auction_list();

                    $data['time'] = $this->sales_model->remaining_time($item_id);

                      $data['item_info'] = $this->items_model->get_item_byid($item_id);
                    if(isset($data['item_info']) && !empty($data['item_info']))
                    {
                        $doc_ids = explode(",",$data['item_info'][0]['item_attachments']);

                        $item_documents = $this->items_model->get_item_documents_byid($doc_ids);
                        $img_ids = explode(",",$data['item_info'][0]['item_images']);
                        $item_images = $this->items_model->get_item_images_byid($img_ids);
                        // print_r($item_images);
                        if(!empty($item_images)) {
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
                    }
                }       
                        $data['item_images'] = array();
                        $data['sum_bid_price'] = $this->sales_model->sum_bid_price($item_id);
                        $data['total_bids'] = $this->sales_model->get_total_bids($item_id);
                        $data['last_bid_amount'] = $this->sales_model->last_bid_amount($item_id);
                     
                    $data_view = $this->load->view('sales/bidding_banner', $data, true);

                    echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
                    exit();
                        }

             }
             else
             {
             
                $this->session->set_flashdata('error', 'Please transfer required security');
                $mid = "security error" ;
                echo json_encode(array( 'msg'=>'error' , 'mid' => $mid));
                exit();
          

             }
         }
         else
         {
            $this->session->set_flashdata('error', 'Please transfer required security');
                $mid = "security error" ;
                echo json_encode(array( 'msg'=>'error' , 'mid' => $mid));
                exit();
         }
        }
        if(!empty($check_security) && $check_security[0]['security'] =="no")
        {
            $data['item_detail'] = $this->sales_model->get_item_detail($item_id);
            $data['bid_start_price'] = $this->sales_model->get_minimum_bidding_amount();
 
         }
    }
        else
        { 

            $this->session->set_flashdata('error', 'Please Deposite amount befor bid');
            echo json_encode(array( 'msg'=>'error', 'response' => 'Please Deposite amount befor bid'));
            exit();
              
        }
        $data['item_detail'] = $this->sales_model->get_item_detail($item_id);
        $data['transacton_detail'] = $this->users_model->transaction_detail_for_user($user_id);
    
        if(!empty($data['transacton_detail']))
        {
        $deposit_user =  $data['transacton_detail'][0]['user_id']; 
        $data['admin_detail'] = $this->users_model->users_listing($user_id);
         $q = $this->users_model->users_listing($deposit_user);
        if($q)
        {
         $data['user_transaction'] = $q;
        }
        $data['sales_list'] = $this->sales_model->get_auction_list();

        $data['time'] = $this->sales_model->remaining_time($item_id);

          $data['item_info'] = $this->items_model->get_item_byid($item_id);
        if(isset($data['item_info']) && !empty($data['item_info']))
        {
            $doc_ids = explode(",",$data['item_info'][0]['item_attachments']);

            $item_documents = $this->items_model->get_item_documents_byid($doc_ids);
            $img_ids = explode(",",$data['item_info'][0]['item_images']);
            $item_images = $this->items_model->get_item_images_byid($img_ids);
            // print_r($item_images);
            if(!empty($item_images)) {
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
        }
    }       
            $data['item_images'] = array();
            $data['sum_bid_price'] = $this->sales_model->sum_bid_price($item_id);
            $data['total_bids'] = $this->sales_model->get_total_bids($item_id);
            $data['last_bid_amount'] = $this->sales_model->last_bid_amount($item_id);

        $data_view = $this->load->view('sales/bidding_banner', $data, true);

        echo json_encode(array( 'msg'=>'success', 'data' => $data_view));
        }
         else
        {
             $this->session->set_flashdata('error', 'Please Deposite amount befor bid');
             echo json_encode(array( 'msg'=>'error', 'response' => 'Please Deposite amount befor bid'));
        }
    }



 public function check_bidding_requirments()
 {
    
    $item_id = $_GET['id'];
    $user_id = $this->loginUser->id;
    // print_r($user_id);
    $check_transaction = $this->sales_model->check_transaction($user_id);
    $chech_transaction_from_configuration = $this->sales_model->chech_transaction_from_configuration();
    if(!empty($check_transaction) && $check_transaction->amount >= $chech_transaction_from_configuration->deposite)
    {

        $check_item_amount = $this->sales_model->check_item_amount($item_id);
     
        
        $check_security = $this->sales_model->check_security($item_id); 

        if($check_security->security == "yes")
        {

        if($check_item_amount->price >= $check_security->amount*10)
        {
                $response = array('msg' => 'success','response' => 'Successfully Bid');
                 echo json_encode($response);       
         }
        }
        if($check_security->security =="no")
        {
            $data['item_detail'] = $this->sales_model->get_item_detail($item_id);
            $data['bid_start_price'] = $this->sales_model->get_minimum_bidding_amount();
           
            $data_view = $this->load->view('sales/bid_amount_form', $data, true);
              echo json_encode(array( 'msg'=>'bid_amount', 'data' => $data_view));

            // $response = array('msg' => 'success','response' => 'Successfully Bid');
            //      echo json_encode($response);  
        }
         else
         {

            $response = array('msg' => 'error','response' => 'please deposite securtiy');
                 echo json_encode($response);
          
             }
        
    }
    else
    {
                $response = array('msg' => 'success','response' => 'Please Deposite amount');
                 echo json_encode($response);
              

    }
}

 
    public function bid_amount()
     {
        $user_id = $this->loginUser->id;
        $data['item_id'] = $this->input->post('item_id');
        $data['bid_amount'] = $this->input->post('bid_amount');
        $data['user_id'] = $this->loginUser->id;
        $q = $this->db->query('select auction_id from auction_items where item_id='.$data['item_id']);
        $data['auction_id']=$q->result_array()[0]['auction_id'];
        $data['bid_time'] = date('Y-m-d H:i:s');

        $last_bid_amount = $this->sales_model->last_bid_amount($data['item_id']);
        if(!empty($last_bid_amount[0]['bid_amount']))
        {  
            $total_bid_amount = $last_bid_amount[0]['bid_amount'] + $data['bid_amount'];
            
        }

        if(empty($last_bid_amount[0]['bid_amount']))
        {
            $start_bid_amount = $this->sales_model->start_bid_amount($data['item_id']);

            $total_bid_amount = $start_bid_amount[0]['bid_start_price'] + $data['bid_amount'];
            if($start_bid_amount[0]['bid_start_price'] == 0)
            {
                $item_price = $this->sales_model->get_item_price($data['item_id']);
                $total_bid_amount = $item_price['price'] + $data['bid_amount'];
            }


        }

        $get_deposite = $this->sales_model->get_deposite($user_id);
        if(!empty($get_deposite))
        {  
            if($total_bid_amount/10 <= $get_deposite[0]['total_deposite'])
            {
                $data['bid_status'] = "pending";
                $result = $this->sales_model->insert_bid_data($data['item_id'],$data);
                $allow_bid_amount = $get_deposite[0]['total_deposite']*10;
                $remaining_amount_for_bid = $allow_bid_amount-$total_bid_amount;
                $remaining_user_deposite['total_deposite'] = $remaining_amount_for_bid/10;
                $check_security_status = $this->sales_model->check_security($data['item_id']); 
                $result = $this->sales_model->update_user_deposite($user_id,$remaining_user_deposite);
                if($result)
                {
                    $this->session->set_flashdata('success', 'successfully bid');
                    $response = array('msg' => 'bid','response' => 'successfully bid');
                    echo json_encode($response);
                    exit();
                }
            }
            else
            {
                    $response = array('msg' => 'deposite_error','mid' => 'You cannot bid because of less deposite');
                             echo json_encode($response);
            } 
                      
        }


}


    public function check_updated_bid()
    {
        $auction_id = $this->input->post('auction_id');
        $item_id = $this->uri->segment(3);

        // print_r($item_id);die('aaaaaaaaaa');
        $user_id = $this->loginUser->id;
        $max_bid_amount = $this->sales_model->max_bid_amount($item_id , $auction_id);
        $user_max_bid_amount = $this->sales_model->user_max_bid_amount($user_id,$item_id , $auction_id);
        $second_last_bid_record = $this->sales_model->second_last_bid_record();
        $last_bid_record = $this->sales_model->last_bid_record();
        if(!empty($second_last_bid_record) && !empty($last_bid_record))
        {
            if($second_last_bid_record[0]['user_id'] != $last_bid_record[0]['user_id']  )
            {

                if(!empty($user_max_bid_amount))
                {
                    if(!empty($max_bid_amount))
                    {
                        if($max_bid_amount[0]['bid_amount'] > $user_max_bid_amount[0]['bid_amount'])
                        {   
                            $this->session->set_flashdata('update_bid','Bid from another User');
                            echo json_encode(array('msg' =>'update_bid' ));
                            exit();
                        }
                    }
                }
            }
            else
            {
                $last_bid_record = $this->sales_model->last_bid_record();
                echo json_encode(array('msg' =>'same_bid','bid_amount'=> $last_bid_record[0]['bid_amount']));
                exit();
            }
        }
    }
    public function send_to_friend()
    {
        $friend_email = $this->input->post('email');
        $name = $this->input->post('name');
        $comment = $this->input->post('comment');
        $item_id = $_GET['item_id'];


                // if($this->input->post('email'))
                // {
                $friend_email = $this->input->post('email');      
                $to = $friend_email;
                $subject = "Intrested Item Suggested by your friend ";
                $email_message = base_url('sales/items/').$item_id;
                $to = $_GET['email'];
                $this->email->to($to);
                $this->email->subject($subject);
                $this->email->message($email_message);
            

                //print($this->email);die();
                $this->email->send();
            // }


    }

    public function sold_item()
    {
        $user_id = $this->loginUser->id;
        $user_email = $this->sales_model->get_user_email($user_id);
        $item_id = $_GET['item_id'];

        $item_detail = $this->sales_model->get_item_detail($item_id);
        $max_bid_amount = $this->sales_model->last_bid_amount($item_id);
        $sold_price = $max_bid_amount[0]['bid_amount'];
        $item_name = $item_detail[0]['name'];
        $item_lot_num = $item_detail[0]['lot_id'];
        $item_created_on = $item_detail[0]['created_on'];
        $item_auction_type =  $item_detail[0]['auction_type'];
        $item_status =  $item_detail[0]['item_status'];
     
      
                $to = $_GET['email'];
                $subject = "Item Sold Notification";
                $email_message = "Your Item $item_name , Lot N0 $item_lot_num has been sold  $sold_price AED. That is created  $item_created_on ";
                $to = $_GET['email'];
                $this->email->to($to);
                $this->email->subject($subject);
                $this->email->message($email_message);
            

                //print($this->email);die();
                $this->email->send();

    }

    public function cancel_item()
    {
        $user_id = $this->loginUser->id;
        $user_email = $this->sales_model->get_user_email($user_id);
        $item_id = $_GET['item_id'];

        $item_detail = $this->sales_model->get_item_detail($item_id);

        $this->sales_model->change_item_status($item_id);
        $item_name = $item_detail[0]['name'];
        $item_lot_num = $item_detail[0]['lot_id'];
        $item_created_on = $item_detail[0]['created_on'];
        $item_auction_type =  $item_detail[0]['auction_type'];
        $item_status =  $item_detail[0]['item_status'];
     
      
                $to = $_GET['email'];
                $subject = "Item Sold Notification";
                $email_message = "Your Item $item_name , Lot N0 $item_lot_num has been canceled . That is created  $item_created_on ";
                $to = $_GET['email'];
                $this->email->to($to);
                $this->email->subject($subject);
                $this->email->message($email_message);
            

                //print($this->email);die();
                $this->email->send();

    }


}



<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('vendor/autoload.php');
class Livecontroller extends Loggedin_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('Livecontroller_model', 'livecontroller_model');
		$this->load->model('auction/Live_auction_model', 'lam');
		$this->load->model('user/Users_model', 'users_model');
        $this->load->model('files/Files_model','files_model');
        $this->load->model('email/Email_model', 'email_model');
	}
	public function index() {
		$data = array();
		$id = $this->loginUser->id;
		$data['small_title'] = 'Live Controller';
		$data['current_page_livecontroller'] = 'current-page';
		$data['active_live_auctions'] = $this->livecontroller_model->getAuction();
		$data['auction_live_buttons'] = $this->livecontroller_model->getControllerButtons();
		$this->template->loadControllerView('live_controller', $data);
	}

	public function sold_items() {
		$data = array();
		$id = $this->loginUser->id;
		$data['auction'] = 'auction_controller';
		$data['small_title'] = 'Live Auction Sold Items';
		$data['current_page_livecontroller'] = 'current-page';
		$data['active_live_auctions'] = $this->livecontroller_model->getAuction();
		$data['auction_live_buttons'] = $this->livecontroller_model->getControllerButtons();
		$this->template->loadControllerView('live_auction_sold', $data);
	}

	public function auction(){
		$data = array();
		$id = $this->loginUser->id;
		$data['small_title'] = 'Live Controller';
		$this->template->loadControllerView('live_controller', $data);
	}
	public function getAuctionItems(){
		$auction_id = $this->input->post('auction_id');
		$search = $this->input->post('search');
		if(isset($auction_id) && !empty($auction_id)){
		$response = $this->livecontroller_model->getAuctionItemsActive($auction_id,$search);

		$getAuctionDetail = $this->livecontroller_model->getAuctionDetail($auction_id);
			$output = '';

		if(!empty($response)){
			$i=0;
			foreach ($response as $value) {

				$i++;
				// echo $value['item_id'];
				$item_row = $this->db->get_where('item',['id'=>$value['item_id']])->row_array();

				$user_seller = $this->db->get_where('users',['id'=>$item_row['seller_id'], 'role' => 4])->row_array();
				$item_name = json_decode($item_row['name']);
				$seller_name = $user_seller['fname'].' '.$user_seller['lname'];
				if ($item_row['sold'] == 'no') {
					
					$make = $this->db->select('title')->get_where('item_makes', ['id' => $item_row['make']])->row_array();
					if (!empty($make)) {
						$make_title = json_decode($make['title']);
					}


					$make = $this->db->select('title')->get_where('item_makes', ['id' => $item_row['make']])->row_array();
					if (!empty($make)) {
						$make_title = json_decode($make['title']);
					}


					$output.='<tr onclick="double_clk(this)" item_id="'.$value['item_id'].'" id="'.$value['id'].'" data-auctionid="'.$auction_id.'" class="itemRows">';
					$output.='<td> '.$value['order_lot_no'].'</td>';
					$output.='<td> '.@$item_name->english.'</td>';
					$output.='<td> '.@$seller_name.'</td>';
					$output.='<td> '.$item_row['registration_no'].'</td>';
					$output.='<td> '.@$make_title->english.'</td>';
					$output.='<td> '.$item_row['price'].'</td>';
					$output.='<td class="bolder"> Not Sold</td>';
					$output.='</tr>';
				}
			}
			echo json_encode(array('error'=>false,'response'=>$output,'startTime'=>date('l d/m/Y',strtotime($getAuctionDetail['start_time'])).' at '.date('H:i',strtotime($getAuctionDetail['start_time'])),'endTime'=>$getAuctionDetail['expiry_time'],'entitycount'=>'Ent: '.$i));	
		}else{
			$output.='<tr>';
			$output.='<td colspan="6" class="text-center">';
			$output.='No Item Found';
			$output.='</td>';
			$output.='</tr>';
			echo json_encode(array('error'=>false,'response'=>$output));	
		}
		
		}else{

		echo json_encode(array('error'=>true,'response'=>array()));	
		}
	}
	
	public function getAuctionSoldItems(){
		$auction_id = $this->input->post('auction_id');
		if(isset($auction_id) && !empty($auction_id)){
			$response = $this->livecontroller_model->getAuctionItemsSoldActive($auction_id);
			$getAuctionDetail = $this->livecontroller_model->getAuctionDetail($auction_id);
			$output = '';
			$count = 0;
			if(!empty($response)){
				$i=0;
				$count = count($response);
				foreach ($response as $value) {
					$i++;
					// echo $value['item_id'];
					$item_row = $this->db->get_where('item',['id'=>$value['item_id']])->row_array();
					// print_r($item_row['name']);die();
					$make = $this->db->select('title')->get_where('item_makes', ['id' => $item_row['make']])->row_array();
					$model = $this->db->select('title')->get_where('item_models', ['id' => $item_row['model']])->row_array();
					$sale_price = $this->db->select('bid_amount')->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $auction_id,'item_id' => $value['item_id']])->row_array();
					$output.='<tr id="'.$value['id'].'" data-auctionid="'.$auction_id.'" class="itemRows">';
					$output.='<td data-label="Entered"> '.date('Y-m-d',strtotime($item_row['created_on'])).'</td>';
					$user_row = $this->db->get_where('users', ['id'=>$item_row['seller_id']])->row_array();
					if ($value['buyer_id']) {
						$buyer_row = $this->db->get_where('users', ['id'=>$value['buyer_id']])->row_array();
					}
					// print_r($user_row);die();
					$user_name = (isset($user_row) && !empty($user_row)) ? $user_row['username'] : 'NA';
					$user_code = (isset($user_row['id']) && !empty($user_row['id'])) ? $user_row['id'] : 'NA';
					$buyer_name = ($value['buyer_id']) ? $buyer_row['username'].' '.'<button class="btn btn-info btn-xs" auctionitem_id="'.$value['id'].'" onclick="selectbuyer(this)" >Select Buyer</button>' : '<button class="btn btn-info btn-xs" auctionitem_id="'.$value['id'].'" onclick="selectbuyer(this)" >Select Buyer</button>';
					if(!empty($value['sold_status'])){
						$status = $value['sold_status'];
						if ($status == 'return') {
							$status_select = 'Returned';
						} else {
							$sold = ($status == "sold") ? "selected" : "";
							$not_sold = ($status == "not_sold") ? "selected" : "";
							$approval = ($status == "approval") ? "selected" : "";
							$status_select = '<select name="sold_status" class="ststus_dropdown" data-auctionids="'.$auction_id.'"  onchange="updateSoldStatus(this)" data-ids="'.$value['item_id'].'">
			                  <option '. $sold .' value="sold">Sold</option>
			                  <option '. $not_sold .' value="not_sold">Un-Sold</option>
			                  <option '. $approval .' value="approval">On approval </option>
			                </select>';
						}
					}
					$item_name = @json_decode($item_row['name']);
					$model_title = @json_decode($model['title']);
					$make_title = @json_decode($make['title']);

					$output.='<td data-label="Lot"> '.$value['order_lot_no'].'</td>';
					$output.='<td data-label="item_name"> '.@$item_name->english.'</td>';
					$output.='<td data-label="Reg No"> '.$item_row['registration_no'].'</td>';
					$output.='<td data-label="Make"> '.@$make_title->english.'</td>';
					$output.='<td data-label="Model"> '.@$model_title->english.'</td>';
					$output.='<td data-label="Saller code"> '. $user_code .'</td>';
					// $output.='<td data-label="Saller Name"> <a href="'.base_url().'user-payables/'.$item_row['seller_id'].'">'. $user_name . ' </td>';
					$output.='<td data-label="Saller Name">'. $user_name . '</td>';
					$output.='<td data-label="Reserve"> '.$item_row['price'].'</td>';
					$output.='<td data-label="Sale price"> '.$sale_price['bid_amount'].'</td>';
					// $output.='<td class="col-hide" data-label="Buyer"><a href="'.base_url().'user-payments/'.$value['buyer_id'].'"> '.$buyer_name.'</a> </td>';
					$output.='<td class="col-hide" data-label="Buyer">'.$buyer_name.'</td>';
					$output.='<td  data-label="B/No"> - </td>';
					$output.='<td data-label="Status"> 
				 		'.$status_select.'
					</td>';
					$output.='<td class="sold_hide" data-label="Action">
					<button class="btn btn-default reauctionBtn sm" data-auctionids="'.$auction_id.'" data-ids="'.$value['item_id'].'" onclick="reAuction(this)">Re Auction</button>
					</td>';
					$output.='</tr>';
				}
				echo json_encode(array('error'=>false,'response'=>$output,'count'=>$count));	
			}else{
				$output.='<tr>';
				$output.='<td colspan="13" class="text-center">';
				$output.='No Item Found';
				$output.='</td>';
				$output.='</tr>';
				echo json_encode(array('error'=>false,'response'=>$output,'count'=>$count));	
			}
		
		}else{

		echo json_encode(array('error'=>true,'response'=>array()));	
		}
	}

	public function updateitembuyer(){
		$id = $this->input->post('auction_item_id');
		$buyer_id = $this->input->post('item_buyer_id');
		$updated = $this->db->update('auction_items', ['buyer_id'=> $buyer_id],['id'=>$id]);
		$this->db->update('sold_items', ['buyer_id'=> $buyer_id],['auction_item_id'=>$id]);
		if($updated){
			$output = 'Buyer added successfully.';
			echo json_encode(array('error'=>false,'response'=>$output));	
		}else{
			$output = 'Buyer has been failed to add.';
			echo json_encode(array('error'=>true,'response'=>$output));	
		}
	}

	public function updateLiveAuctionStatus(){
		$auction_id = $this->input->post('auction_id');
		$status = $this->input->post('status');
		$updated = $this->db->update('auctions', ['start_status'=> $status],['id'=>$auction_id]);
		$pusher_data = array();
		// pusher data
				$options = array(
					'cluster' => 'ap1',
					'useTLS' => true
				);
				$pusher = new Pusher\Pusher(
					$this->config->item('pusher_app_key'),
                    $this->config->item('pusher_app_secret'),
                    $this->config->item('pusher_app_id'),
					$options
				);

		if($updated){
			if ($status == 'start') {
				$this->db->where('id !=',$auction_id)->update('auctions',['start_status' => 'stop']);
				$output = 'Auction has been started.';
				$pusher->trigger('ci_pusher', 'start-event', $pusher_data);
			}else{
				$output = 'Auction has been stoped.';
				

				
				$pusher->trigger('ci_pusher', 'stop-event', $pusher_data);
			}
			echo json_encode(array('error'=>false,'response'=>$output));	
		}else{
			$output = 'Auction has been failed to start.';
			echo json_encode(array('error'=>true,'response'=>$output));	
		}
	}

	public function initialAuctionBid(){
		// print_r('auction_id');die();
		$auction_id = $this->input->post('auction_id');
		$item_id = $this->input->post('itemid');
		$bidAmount = $this->input->post('bidAmount');
		if($this->input->post('initial')){
		$initial_type = $this->input->post('initial');
		}else{
		$initial_type = '';
		}
		// $new_amount = $bidAmount;
		$lot_no = $this->input->post('lot_no');
		$bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['item_id' => $item_id,'auction_id' => $auction_id])->row_array();


		if (isset($bid) && !empty($bid)) {
			$new_amount = $bid['bid_amount'] + $bidAmount;
		}else{
			$new_amount = $bidAmount;
		}

		if ($this->input->post('bid_type')) {
			$bid_type = $this->input->post('bid_type');
			$output = 'Bis has been placed successfully.';
		}else{
			$bid_type = 'initial';
			$output = 'Initial hall bis has been placed.';
		}
		// $updated = $this->db->update('auctions', ['start_status'=>'start'],['id'=>$auction_id]);
		$item = $this->db->get_where('item', ['id'=>$item_id])->row_array();
		$bid = array(
			"auction_id" => $auction_id,
			"item_id" => $item_id,
			// "bid_amount" => $new_amount,
			"bid_increment_amount" => $bidAmount,
			"user_id" => $this->loginUser->id,
			"lot_no" => $lot_no,
			"bid_type" => $bid_type,
			"seller_id" => $item['seller_id'],
			"created_on" => date('Y-m-d H:i:s')
		);
		if(!empty($initial_type)){
			$this->db->update('live_auction_bid_log', ['initial_priority_type'=>'no'],['item_id' => $item_id,'auction_id' => $auction_id]);
			$bid['bid_amount'] = $bidAmount;
			$bid['initial_priority_type'] = 'yes';
		}else{
			$bid['bid_amount'] = $new_amount;
		}
		// print_r($bid);die();
		$update = $this->db->insert('live_auction_bid_log', $bid);
		if($update){
			$last_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['item_id' => $item_id,'auction_id' => $auction_id])->row_array();

			//data for bradcasting pusher
			// $pusher_data = $this->broadcast_pusher($item_id, $auction_id);
			//return print_r($pusher_data);
            $pusher_data['item_id'] = $item_id;
			$pusher_data['auction_id'] =$auction_id;

			//broadcast pusher data
			$options = array(
				'cluster' => 'ap1',
				'useTLS' => true
			);
			$pusher = new Pusher\Pusher(
				$this->config->item('pusher_app_key'),
                $this->config->item('pusher_app_secret'),
                $this->config->item('pusher_app_id'),
				$options
			);
			$pusher->trigger('ci_pusher', 'live-event', $pusher_data);

			//auto bidder functionality
			$data['auction_id'] = $auction_id;
			$data['item_id'] = $item_id;
			//make auto_status to stop in bid_auto table for all those entries 
            //which has less limit than current bid amount.
            $this->db->update('bid_auto', ['auto_status' => 'stop'],
                                ['auction_id' => $data['auction_id'],
                                    'item_id' => $data['item_id'],
                                    'bid_limit <' => $new_amount
                                ]
                            );

            //auto bidders loop
            do {
                //get all auto bidders
                $auto_bidders = $this->db->get_where('bid_auto', ['item_id' => $data['item_id'], 'auction_id' => $data['auction_id'], 'auto_status' => 'start']);
                //get count of all auto bidders
                $count = $auto_bidders->num_rows();
                if($count > 0){
                    $auto_bidders = $auto_bidders->result_array();
                    //loop on all auto bidders
                    foreach ($auto_bidders as $key => $value) {
                        //get latest bid data
                        $bid_amount = $this->db->order_by('id', 'desc')->get_where('live_auction_bid_log', ['auction_id' => $data['auction_id'],'item_id' => $data['item_id']])->row_array();
                        $bid_price = $bid_amount['bid_amount'] + $value['bid_increment'];
                        //if current auto bidder limit is greater or equal than current bid price
                        if ($value['bid_limit'] >= $bid_price) {
                            //if last bid user not equal to current bid user
                            if ($value['user_id'] != $bid_amount['user_id']) {
                                
                                $auto_bid = [
                                    'auction_id' => $data['auction_id'],
                                    'item_id' => $data['item_id'],
                                    'lot_no' => $bid['lot_no'],
                                    'bid_type' => 'online',
                                    'initial_priority_type' => 'no',
                                    'user_id' => $value['user_id'],
                                    'bid_amount' => $bid_price,
                                    'bid_status' => 'bid',
                                    'bid_increment_amount' => $value['bid_increment'],
                                    'seller_id' => $bid['seller_id'],
                                    'created_on' => date('Y-m-d H:i:s')
                                ];

                                $res = $this->db->insert('live_auction_bid_log', $auto_bid);
                                if ($res) {
                                    $options = array(
                                        'cluster' => 'ap1',
                                        'useTLS' => true
                                    );
                                    $pusher = new Pusher\Pusher(
                                        $this->config->item('pusher_app_key'),
				                        $this->config->item('pusher_app_secret'),
				                        $this->config->item('pusher_app_id'),
                                        $options
                                    );

                                    // $pusher_data = $this->broadcast_pusher($auto_bid['item_id'], $auto_bid['auction_id']);
                                    $pusher_data['item_id'] = $auto_bid['item_id'];
        							$pusher_data['auction_id'] =$auto_bid['auction_id'];

                                    $userdata = $this->db->select('username')->get_where('users', ['id' => $value['user_id']])->row_array();
                                    $pusher_data['username'] = $userdata['username'];
                                    $pusher_data['user_id'] = $bid['user_id'];
                                    $pusher_data['buyer_id'] = $value['user_id'];
                                    $pusher->trigger('ci_pusher', 'live-event', $pusher_data);
                                }


                                /*$auto_bidder = $this->db->get_where('bid_auto', ['auction_id' => $data['auction_id'],'item_id' => $data['item_id'],'user_id' =>$bid_amount['user_id']])->row_array();
                                if (!$auto_bidder) {
                                    //Send eamil  
                                    $item_link = base_url('auction/online-auction/details/').$data['auction_id'].'/'.$data['item_id'];
                                    $out_user = $this->db->select('email,fname')->get_where('users', ['id' => $bid_amount['user_id']])->row_array();
                                    $vars = array(
                                        '{username}' => $out_user['fname'],
                                        '{item_name}' => '<a href="'.$item_link.'">'.ucwords($bid_data['name']).'</a>',
                                        '{year}' => $bid_data['year'],
                                        '{bid_price}' => $bid_amount['bid_amount'],
                                        '{lot_number}' => $auction_item_data['order_lot_no'],
                                        '{registration_number}' => $bid_data['registration_no'],
                                        '{login_link}' => 'Please login:<a href="'.base_url('user-login').'" > Go to My Account </a>'
                                    );
                                    $email = $out_user['email'];
                                    $this->email_model->email_template($email, 'out-bid-email', $vars, false);
                                }*/
                            }
                        }else{
                        	//update status of auto bidder to stop if bid cross the bidder limit.
                            $update = $this->db->update('bid_auto', ['auto_status' => 'stop'],['id' => $value['id']] );

                            //email to auto bidder of out bid
                            /*if ($update) {
                                //Send eamil  
                                $item_link = base_url('auction/online-auction/details/').$data['auction_id'].'/'.$data['item_id'];
                                $out_user = $this->db->select('email,fname')->get_where('users', ['id' => $value['user_id']])->row_array();
                                $vars = array(
                                    '{username}' => $out_user['fname'],
                                    '{item_name}' => '<a href="'.$item_link.'">'.ucwords($bid_data['name']).'</a>',
                                    '{year}' => $bid_data['year'],
                                    '{bid_price}' => $bid_amount['bid_amount'],
                                    '{lot_number}' => $auction_item_data['order_lot_no'],
                                    '{registration_number}' => $bid_data['registration_no'],
                                    '{login_link}' => 'Please login:<a href="'.base_url('user-login').'" > Go to My Account </a>'
                                );
                                $email = $out_user['email'];
                                
                                $email = $this->email_model->email_template($email, 'out-bid-email', $vars, false);
                            }*/

                            //pusher
                            /*$options = array(
                                'cluster' => 'ap1',
                                'useTLS' => true
                            );
                            $pusher = new Pusher\Pusher(
                                $this->config->item('pusher_app_key'),
		                        $this->config->item('pusher_app_secret'),
		                        $this->config->item('pusher_app_id'),
                                $options
                            );
                            $push['item_id'] = $data['item_id'];
                            $push['user_id'] = $value['user_id'];
                            $push['status'] = 'stop';
                            $pusher->trigger('ci_pusher', 'live-event', $push);*/
                        }
                    }
                }
            } while ($count > 1);


			echo json_encode(array('error'=>false,'response'=>$output));	
		}else{
			$output = 'Bid has not been placed.';
			echo json_encode(array('error'=>true,'response'=>$output));	
		}
	}

	public function retractAuctionBid(){
		$auction_id = $this->input->post('auction_id');
		$item_id = $this->input->post('item_id');
		$last_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['item_id' => $item_id,'auction_id' => $auction_id])->row_array();
		$result = $this->db->delete('live_auction_bid_log',['id'=>$last_bid['id']]);
		// $updated = $this->db->update('auctions', ['start_status'=>'start'],['id'=>$auction_id]);
		if($result){
		$last_amount = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['item_id' => $item_id,'auction_id' => $auction_id])->row_array();
		//pusher
		// $pusher_data = $this->broadcast_pusher($item_id, $auction_id);
		//return print_r($pusher_data);
		$pusher_data['item_id'] = $item_id;
        $pusher_data['auction_id'] =$auction_id;

		//broadcast pusher data
		$options = array(
			'cluster' => 'ap1',
			'useTLS' => true
		);
		$pusher = new Pusher\Pusher(
			$this->config->item('pusher_app_key'),
            $this->config->item('pusher_app_secret'),
            $this->config->item('pusher_app_id'),
			$options
		);  
		$pusher->trigger('ci_pusher', 'live-event', $pusher_data);

		$output = 'Auction has been retracted';
		if (!empty($last_amount)) {
			echo json_encode(array('error'=>false,'response'=>$output,'bidAmount'=>$last_amount['bid_amount'],'last_bid'=> number_format($last_amount['bid_increment_amount'], 0, ".", ",")));	
		} else {
			echo json_encode(array('error'=>false,'response'=>$output,'bidAmount'=> 0,'last_bid'=> 0));
		}
		}else{
		$output = 'Bid has not been retracted';
		echo json_encode(array('error'=>true,'response'=>$output,'bidAmount'=>'','last_bid'=>''));	
		}
	}

	public function retractAllAuctionBid(){
		$auction_id = $this->input->post('auction_id');
		$item_id = $this->input->post('item_id');
		$last_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['item_id' => $item_id,'auction_id' => $auction_id])->row_array();
		// $updated = $this->db->update('auctions', ['start_status'=>'start'],['id'=>$auction_id]);
		if($last_bid){ 
		$result = $this->db->delete('live_auction_bid_log',['auction_id'=>$auction_id,'item_id'=>$item_id]);
		//pusher
		// $pusher_data = $this->broadcast_pusher($item_id, $auction_id);
		//return print_r($pusher_data);
		$pusher_data['item_id'] = $item_id;
        $pusher_data['auction_id'] =$auction_id;

		//broadcast pusher data
		$options = array(
			'cluster' => 'ap1',
			'useTLS' => true
		);
		$pusher = new Pusher\Pusher(
			$this->config->item('pusher_app_key'),
            $this->config->item('pusher_app_secret'),
            $this->config->item('pusher_app_id'),
			$options
		);
		$pusher->trigger('ci_pusher', 'live-event', $pusher_data);
		
		$output = 'Auction Item all bids has been retracted';
		echo json_encode(array('error'=>false,'response'=>$output));	
		}else{
		$output = 'Bids has not been retracted';
		echo json_encode(array('error'=>true,'response'=>$output));	
		}
	}

	public function rollBackAuctionBid(){
		$auction_id = $this->input->post('auction_id');
		$item_id = $this->input->post('item_id');
		$last_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $auction_id])->row_array();
		// $updated = $this->db->update('auctions', ['start_status'=>'start'],['id'=>$auction_id]);
		if($last_bid){ 
			$updated = $this->db->update('auction_items', ['sold_status'=>'not','buyer_id'=>null],['auction_id'=>$auction_id]);
			$result = $this->db->delete('live_auction_bid_log',['auction_id'=>$auction_id]);
			$output = 'Auction Item all bids has been rollback';
			echo json_encode(array('error'=>false,'response'=>$output));	
		}else{
			$output = 'Bids has not been retracted';
			echo json_encode(array('error'=>true,'response'=>$output));	
		}
	}

	public function getAuctionUsersList(){
		$auction_id = $this->input->post('auction_id');
		$userArray = $this->db->group_by('user_id')->get_where('auction_user_list', ['auction_id' => $auction_id,'status' => 'in'])->result_array();
		$output = '';
		if($userArray){ 
			foreach ($userArray as $uservalue) {
				$user_id = $uservalue['user_id'];
				$output .= '<tr>';
				$user_detail = $this->livecontroller_model->get_all_customer_active($user_id);
				if(!empty($user_detail)){
					$output .= '<td>';
					$output .= $user_detail['fname'];
					$output .= '</td>';
					$output .= '<td>';
					$output .= $user_detail['lname'];
					$output .= '</td>';
					$output .= '<td>';
					$output .= $user_detail['code'];
					$output .= '</td>';
					$output .= '<td>';
					$output .= $user_detail['mobile'];
					$output .= '</td>';
				}
				$output .= '</tr>';
				echo json_encode(array('error'=>false,'response'=>$output));

			}
			
		}else{
		$output = '<tr class="empty"><td colspan="6">Online Conected use will<br> display here - we can block user<br> for bulding.</td></tr>';
		echo json_encode(array('error'=>true,'response'=>$output));	
		}
	}

	public function getSoldItemCount(){
		$auction_id = $this->input->post('auction_id');
		$item_id = $this->input->post('item_id');
		$sold_status_array = array('sold','approval');
		$this->db->where_in('sold_status', $sold_status_array);
		$soldArray = $this->db->get_where('auction_items', ['auction_id' => $auction_id])->result_array();
		if($soldArray){ 
			echo json_encode(array('error'=>false,'response'=>count($soldArray)));	
		}else{
			echo json_encode(array('error'=>true,'response'=>0));	
		}
	}

	public function approvalSoldAuctionBid(){
		$auction_id = $this->input->post('auction_id');
		$item_id = $this->input->post('item_id');
		$updated = $this->db->update('auction_items', ['sold_status' => 'approval'],['auction_id'=>$auction_id,'item_id'=>$item_id]);

		$auction_item = $this->db->get_where('auction_items',['auction_id'=>$auction_id,'item_id'=>$item_id])->row_array();

		$last_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['item_id' => $item_id,'auction_id' => $auction_id])->row_array();
		$log_update = $this->db->update('live_auction_bid_log',['bid_status'=>'win'],['id'=> $last_bid['id']]);
		if($last_bid['bid_type'] == 'online'){
			$this->db->update('auction_items',['buyer_id' => $last_bid['user_id']], ['auction_id'=>$auction_id,'item_id'=>$item_id]);
		}
			$pusher_data['item_id'] = $item_id;
	        $pusher_data['auction_id'] =$auction_id;

		if($updated){
			//broadcast pusher data
			$options = array(
				'cluster' => 'ap1',
				'useTLS' => true
			);
			$pusher = new Pusher\Pusher(
				$this->config->item('pusher_app_key'),
                $this->config->item('pusher_app_secret'),
                $this->config->item('pusher_app_id'),
				$options
			);
			$pusher->trigger('ci_pusher', 'sold-event', $pusher_data);
			if($last_bid['bid_type'] == 'online'){
				//send email to heighest bidder on approval 
	            $this->load->model('email/Email_model', 'email_model');
	            $buyer_data = $this->db->get_where('users', ['id' => $last_bid['user_id']])->row_array();
	            $item_data = $this->db->get_where('item', ['id' => $item_id])->row_array();
	            $item_link = base_url('auction/online-auction/details/').$auction_id.'/'.$item_id;
	            $email = $buyer_data['email'];
	            $itm_name_lan = json_decode($item_data['name']);
	            $lang = $this->language;
	            $vars = array(
	                '{username}' => $buyer_data['fname'],
	                '{item_name}' => '<a href="'.$item_link.'">'.$itm_name_lan->$lang.'</a>',
	                '{year}' => $item_data['year'],
	                '{bid_price}' => $last_bid['bid_amount'],
	                '{lot_number}' => $auction_item['order_lot_no'],
	                '{registration_number}' => $item_data['registration_no'],
	                '{login_link}' => 'Please login:<a href="'.base_url("user-login").'" > Go to My Account </a>'
	            );

	            $this->email_model->email_template($email, 'need-approval-from-seller-email', $vars, false);
	        }
			$output = 'Item has been sold on approval.';
			echo json_encode(array('error'=>false,'response'=>$output));	
		}else{
			$output = 'Auction item has not been sold.';
			echo json_encode(array('error'=>true,'response'=>$output));	
		}
	}

	public function updateSoldstatus(){
		$auction_id = $this->input->post('auction_id');
		$item_id = $this->input->post('item_id');
		$status = $this->input->post('status');
		// print_r($this->input->post());die();
		if ($status == 'sold') {
			$updated = $this->db->update('auction_items', ['sold_status' => $status],['auction_id'=>$auction_id,'item_id'=>$item_id]);
			$this->db->update('item', ['sold' => 'yes'],['id'=>$item_id]);

			$auction_item = $this->db->get_where('auction_items',['auction_id'=>$auction_id,'item_id'=>$item_id])->row_array();

			$last_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['item_id' => $item_id,'auction_id' => $auction_id])->row_array();

			$sold_data = [
				'item_id' => $item_id,
				'auction_id' => $auction_id,
				'auction_item_id' => $auction_item['id'],
				'seller_id' => $last_bid['seller_id'],
				'price' => $last_bid['bid_amount'],
				'payable_amount' => $last_bid['bid_amount'],
				'created_by' => $this->loginUser->id,
				'sale_type' => 'live'
			];
			//send email to seller
			$seller_data = $this->db->get_where('users', ['id' => $last_bid['seller_id']])->row_array();
            $item_data = $this->db->get_where('item', ['id' => $item_id])->row_array();
            $item_link = base_url('live-online-detail/').$auction_id.'/'.$item_id;
			$itm_name = json_decode($item_data['name']);
			$lan = $this->language;
			$seller_email = $seller_data['email'];
            $vars = array(
                '{username}' => $seller_data['fname'],
                '{item_name}' => '<a href="'.$item_link.'">'.$itm_name->$lan.'</a>',
                '{year}' => $item_data['year'],
                '{bid_price}' => $last_bid['bid_amount'],
                '{lot_number}' => $auction_item['order_lot_no'],
                '{registration_number}' => $item_data['registration_no'],
                '{login_link}' => 'Please login:<a href="'.base_url("user-login").'" > Go to My Account </a>'
            );
            $this->email_model->email_template($seller_email, 'item-sold-email', $vars, false);
			//send email to buyer
			if (!empty($auction_item['buyer_id'])) {
				$buyer_data = $this->db->get_where('users', ['id' => $auction_item['buyer_id']])->row_array();
				$email = $buyer_data['email'];
	            $vars['{username}'] = $buyer_data['fname'];
	            $this->email_model->email_template($email, 'item-win-email', $vars, false);
	        }
			//check item is already sold
			$sold = $this->db->get_where('sold_items', ['auction_item_id' => $auction_item['id']]);
			if ($sold->num_rows() == 0) {
				$this->db->insert('sold_items', $sold_data);
			}
		} else {
			$updated = $this->db->update('auction_items', ['sold_status' => $status],['auction_id'=>$auction_id,'item_id'=>$item_id]);
			$this->db->update('item', ['sold' => 'no'],['id'=>$item_id]);
			$result = $this->db->delete('sold_items',['auction_id'=>$auction_id,'item_id'=>$item_id]);

		}

		if($updated){
		$output = 'Item Status has been updated.';
			echo json_encode(array('error'=>false,'response'=>$output));	
		}else{
		$output = 'Item Status has not been updated.';
			echo json_encode(array('error'=>true,'response'=>$output));	
		}
	}

	public function notSoldAuctionBid(){
		$auction_id = $this->input->post('auction_id');
		$item_id = $this->input->post('item_id');
		$updated = $this->db->update('auction_items', ['sold_status' => 'not_sold'],['auction_id'=>$auction_id,'item_id'=>$item_id]);
		$this->db->update('item', ['sold' => 'no'],['id'=>$item_id]);

		$last_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['item_id' => $item_id,'auction_id' => $auction_id])->row_array();
		$log_update = $this->db->update(' live_auction_bid_log',['bid_status'=>'win'],['id'=> $last_bid['id']]);

			$pusher_data['item_id'] = $item_id;
	        $pusher_data['auction_id'] =$auction_id;
		if($updated){
			//broadcast pusher data
			$options = array(
				'cluster' => 'ap1',
				'useTLS' => true
			);
			$pusher = new Pusher\Pusher(
				$this->config->item('pusher_app_key'),
                $this->config->item('pusher_app_secret'),
                $this->config->item('pusher_app_id'),
				$options
			);
			$pusher->trigger('ci_pusher', 'sold-event', $pusher_data);
			$output = 'Item has been moved to unsold.';
			echo json_encode(array('error'=>false,'response'=>$output));	
		}else{
			$output = 'Auction Item has not been moved to unsold.';
			echo json_encode(array('error'=>true,'response'=>$output));	
		}
	}
	
	public function soldAuctionBid(){
		$auction_id = $this->input->post('auction_id');
		$item_id = $this->input->post('item_id');
		$updated = $this->db->update('auction_items', ['sold_status' => 'sold'],['auction_id'=>$auction_id,'item_id'=>$item_id]);
		$this->db->update('item', ['sold' => 'yes'],['id'=>$item_id]);

		// print_r($max);exit(); 
		$auction_item = $this->db->get_where('auction_items',['auction_id'=>$auction_id,'item_id'=>$item_id])->row_array();

		$last_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['item_id' => $item_id,'auction_id' => $auction_id])->row_array();
		$log_update = $this->db->update('live_auction_bid_log',['bid_status'=>'win'],['id'=> $last_bid['id']]);

		$sold_data = [
			'item_id' => $item_id,
			'auction_id' => $auction_id,
			'auction_item_id' => $auction_item['id'],
			'seller_id' => $last_bid['seller_id'],
			'price' => $last_bid['bid_amount'],
			'payable_amount' => $last_bid['bid_amount'],
			'created_by' => $this->loginUser->id,
			'sale_type' => 'live'
		];

		if($last_bid['bid_type'] == 'online'){
			$sold_data['buyer_id'] = $last_bid['user_id'];
			$this->db->update('auction_items',['buyer_id' => $last_bid['user_id']], ['id' => $auction_item['id']]);
		} 

		//check item is already sold
		$sold = $this->db->get_where('sold_items', ['auction_item_id' => $auction_item['id']]);
		if ($sold->num_rows() == 0) {
			$this->db->insert('sold_items', $sold_data);
		}

		$pusher_data['item_id'] = $item_id;
        $pusher_data['auction_id'] =$auction_id;

		if($updated){
			//broadcast pusher data
			$options = array(
				'cluster' => 'ap1',
				'useTLS' => true
			);
			$pusher = new Pusher\Pusher(
				$this->config->item('pusher_app_key'),
                $this->config->item('pusher_app_secret'),
                $this->config->item('pusher_app_id'),
				$options
			);
			$pusher->trigger('ci_pusher', 'sold-event', $pusher_data);
			
			$seller_data = $this->db->get_where('users', ['id' => $last_bid['seller_id']])->row_array();
            $item_data = $this->db->get_where('item', ['id' => $item_id])->row_array();
            $item_link = base_url('live-online-detail/').$auction_id.'/'.$item_id;
			$itm_name = json_decode($item_data['name']);
			$lan = $this->language;
			$seller_email = $seller_data['email'];
            $vars = array(
                '{username}' => $seller_data['fname'],
                '{item_name}' => '<a href="'.$item_link.'">'.$itm_name->$lan.'</a>',
                '{year}' => $item_data['year'],
                '{bid_price}' => $last_bid['bid_amount'],
                '{lot_number}' => $auction_item['order_lot_no'],
                '{registration_number}' => $item_data['registration_no'],
                '{login_link}' => 'Please login:<a href="'.base_url("user-login").'" > Go to My Account </a>'
            );
            $this->email_model->email_template($seller_email, 'item-sold-email', $vars, false);
			if($last_bid['bid_type'] == 'online'){
				$buyer_data = $this->db->get_where('users', ['id' => $last_bid['user_id']])->row_array();
				$email = $buyer_data['email'];
				$vars['{username}'] = $buyer_data['fname'];
	            $this->email_model->email_template($email, 'item-win-email', $vars, false);
			}
			$output = 'Item has been sold.';
			echo json_encode(array('error'=>false,'response'=>$output));
		}else{
			$output = 'Auction item has not been sold.';
			echo json_encode(array('error'=>true,'response'=>$output));	
		}
	}

	public function filter_items_with_category()
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

    public function bidLogAPi(){
        $output = '';
        $auction_id = $this->input->post('auctionId');
        $item_id = $this->input->post('itemId');
        // $bid_amount = $this->input->post('bid_amount');

        //$bidLog = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $auction_id])->result_array();

        $this->db->select('bid_type,bid_status,item_id,bid_amount,created_on,bid_increment_amount');
        $this->db->from('live_auction_bid_log');
        $this->db->where('auction_id',$auction_id);
        $this->db->limit(40, 0);
        $this->db->order_by('id',"desc");
        $bidLog = $this->db->get();
        $bidLog = $bidLog->result_array();


        $i=0;
        foreach ($bidLog as $bidLogValue) {
            $i++;
            $bidder = 'Hall Bidder';
            $bid_type = 'Hall Bid';

            $active = '';
            if ($i == 1) {
                $active = 'active';
            }

            $icon = '<i><img style="width: 14px;" src="'.base_url("assets_admin/images/law-gray.svg").'"></i>';
            $color = 'text-gray';

            if ($bidLogValue['bid_type'] == 'online') {
                $icon = '<i><img style="width: 14px;" src="'.base_url("assets_admin/images/law-red.svg").'"></i>';
                $color = 'text-red';
                $bidder = 'Online Bidder';
                $bid_type = 'Online Bid';
            }

            if ($bidLogValue['bid_status'] == 'win') {
                $icon = '<i class="fa fa-check"></i>';
                $color = 'text-green';
                $item = $this->db->get_where('item', ['id' => $bidLogValue['item_id']])->row_array();
                $item_name = json_decode($item['name']);
                $output.= '<li class="'.$active.' '.$color.'">'.$icon.@$item_name->english.' sold to '.$bidder.' for AED '.$bidLogValue['bid_amount'].' at '.$bidLogValue['created_on'].'</li>';
            }

            $output.= '<li class="'.$active.' '.$color.'">'.$icon.' '.$bid_type.' '.$bidLogValue['bid_increment_amount'].' '.$bidLogValue['created_on'].'</li>';

        }
        echo json_encode(array('error'=>false,'response'=>$output));
    }

	public function getAuctionItemDetail(){
		$item_detail = array();
		$auctionItemDetail = array();
		$response = array();
		$id = $this->input->post('id');
		$item_id = $this->input->post('item_id');
		$auction_id = $this->input->post('auction_id');
		
		//$auctionItemDetail = $this->livecontroller_model->getAuctionItemsDetail($id);
		//$auctionItemDetail = $this->db->get_where('auction_items', ['item_id' => $id])->row_array();
		//$item_detail = $this->livecontroller_model->getItemDetail($auctionItemDetail['item_id']);

		$item_detail = $this->lam->get_live_auction_items_low_load($auction_id,'','',[$item_id]);
		if(!empty($item_detail)){
			$item_detail = $item_detail[0];
			$response = array(
				'order_lot_no' => $item_detail['order_lot_no'],
				'name' => $item_detail['item_name'],
				'reserve_price' => $item_detail['item_price'],
				'item_id' => $item_id,
				'auction_id' => $auction_id
			);


			//data for bradcasting pusher
			// $pusher_data = $this->broadcast_pusher($response['item_id'], $response['auction_id']);
			//return print_r($pusher_data);
			$pusher_data['select_item'] = 'select_item';
			$pusher_data['item_id'] = $response['item_id'];
	        $pusher_data['auction_id'] =$response['auction_id'];

			//broadcast pusher data
			$options = array(
				'cluster' => 'ap1',
				'useTLS' => true
			);
			$pusher = new Pusher\Pusher(
				$this->config->item('pusher_app_key'),
                $this->config->item('pusher_app_secret'),
                $this->config->item('pusher_app_id'),
				$options
			);
			$pusher->trigger('ci_pusher', 'live-event', $pusher_data);


			echo json_encode(array('error'=>false,'response'=>$response));
		}else{
			echo json_encode(array('error'=>true,'response'=>$auctionItemDetail,'message'=>'Invalid Item found.'));
		}

	}

	public function broadcast_pusher($item_id, $auction_id){
		
		//item data
        $item = $this->lam->get_live_auction_items($auction_id,'','',[$item_id]);
        $item = $item[0];
        
        if($item['item_make_model'] == 'yes'){
            $make = $this->db->get_where('item_makes', ['id' => $item['item_make']])->row_array();
            $model = $this->db->get_where('item_models', ['id' => $item['item_model']])->row_array();
            $item['vehicle'] = true;
            $item['make'] = $make;
            $item['model'] = $model;
        }
        
        //item images
        $images = explode(',', $item['item_images']);
        $item_images = $this->files_model->get_multiple_files_by_ids($images);

        //current bid data
        $current_bid = $this->db->order_by('id', 'DESC')->get_where('live_auction_bid_log', ['auction_id' => $auction_id, 'item_id' => $item_id])->row_array();
        if (!empty($current_bid)) {
        	$currentBidAmount = $current_bid['bid_amount'];
        }

        // $item_category_fields = $this->db->get_where('item_category_fields',['category_id' => $item['category_id'],'name' => 'transmission'])->result_array();
        
        $select = "sort_catagories.sort_id AS sortId, item_category_fields.label AS fieldName, item_fields_data.value AS fieldValue";
        $sortedCateFields = $this->db->select($select)->from('sort_catagories')
            ->join('item_category_fields', 'sort_catagories.field_id = item_category_fields.id')
            ->join('item_fields_data', 'item_category_fields.id = item_fields_data.fields_id')
            ->where(['sort_catagories.category_id' => $item['category_id'], 'item_fields_data.item_id' => $item_id])
            ->order_by('sort_catagories.sort_id', 'ASC')
            ->get()->result_array();
        $item_fields_data = array();
        if($sortedCateFields){ 
            foreach ($sortedCateFields as $key => $field) {
                $fieldNames = explode("|", $field['fieldName']);
                $item_fields_data[$key]['fieldName'] = ($fieldNames[0]) ? $fieldNames[0] : '';
                $fieldValues = explode("|", $field['fieldValue']);
                $item_fields_data[$key]['fieldValue'] = ($fieldValues[0]) ? $fieldValues[0] : '';
			} 
        }
        

        //auction data
        $auction = $this->db->get_where('auctions', ['id' => $auction_id])->row_array();
        $auction_blink_text = $this->db->get_where('auction_items', ['item_id' => $item_id, 'auction_id' => $auction_id])->row_array();

        $push['item_id'] = $item_id;
        $push['auction_id'] =$auction_id;
        $push['auction'] = $auction;
        $push['cb_amount'] = @$currentBidAmount;
        $push['current_bid'] = $current_bid;
        $push['data'] = $item;
        $push['item_fields_data'] = $item_fields_data;
        $push['item_images'] = $item_images;
        $push['lot_number'] = $item['order_lot_no'];
        $push['blink_text'] = $auction_blink_text['blink_text'];
        echo json_encode($push);
        // return $push;
	}

	public function get_online_users()
    {
        $data = $this->input->post();
        if($data){
        	$this->db->join('live_auction_bid_log', 'users.id = live_auction_bid_log.user_id', 'LEFT');
        	$this->db->group_by('users.email');
            $online_users = $this->db->get_where('users', [
            	'live_auction_bid_log.auction_id' => $data['auction_id'], 
            	'live_auction_bid_log.item_id' => $data['item_id'], 
            	'live_auction_bid_log.bid_type' => 'online']);

            if($online_users->num_rows() > 0){
                $online_users = $online_users->result_array();
                $response = $this->load->view('live_auction_controller/tblOnlineUsers', ['online_users' => $online_users, 'language' => $this->language], true);
                $output = ['status' => true, 'response' => $response];
            }else{
                $output = ['status' => false];
            }
        }else{
            $output = ['status' => false];
        }
        echo json_encode($output);
    }

    function return_to_reauction(){
    	$auction_id = $this->input->post('auction_id');
		$item_id = $this->input->post('item_id');
    	// update auction item status
		$updated = $this->db->update('auction_items', ['sold_status' => 'not', 'buyer_id' => NULL],['auction_id'=>$auction_id,'item_id'=>$item_id]);
    	// update item status
		$this->db->update('item', ['sold' => 'no'],['id'=>$item_id]);
		$this->db->delete('live_auction_bid_log',['auction_id'=>$auction_id,'item_id'=>$item_id]);
    	// delete sold items
		$result = $this->db->delete('sold_items',['auction_id'=>$auction_id,'item_id'=>$item_id]);
		if($updated){
			$output = 'Item available for re-auction.';
			echo json_encode(array('status'=>true,'error'=>false,'msg'=>$output));	
		}else{
			$output = 'Something went wrong. Please try again later.';
			echo json_encode(array('status'=>false,'error'=>true,'msg'=>$output));	
		}
    }
}//end controller

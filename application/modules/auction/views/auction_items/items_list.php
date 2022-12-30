
<style type="text/css">
    #page-wrapper {
        margin-top: 100px;
    }
    .dataTables_wrapper .dataTables_length{
        position: absolute !important;
        top: 0% !important;
        /*right: -2% !important;*/
    }
</style>
<div class="clearfix"></div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div> 
          <div class="clearfix"></div>
      </div>
        <div class="x_title">
            <h2><?php echo $small_title; ?></h2>
            <div class="nav navbar-right">
              <a type="button" href="<?php echo base_url().'auction'; ?>"  class="btn btn-info"><i class="fa fa-arrow-left"></i> Back Auction List</a>
              <?php if ($auction_expiry_time > date('Y-m-d H:i:s')) : ?>
                <button type="button" id="<?php echo urlencode(base64_encode($auction_id)); ?>" data-toggle="modal" data-target=".bs-example-modal-sm2"  data-backdrop="static" data-keyboard="false" class="btn btn-success oz_stock_list"><i class="fa fa-plus"></i> Add Item</button>
              <?php endif; ?>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="x_content"></div>
          <?php if($this->session->flashdata('msg')){ ?>
        <div class="alert">
            <div class="alert alert-success alert-dismissible fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black;"><span aria-hidden="true">×</span>
              </button>
              <?php  echo $this->session->flashdata('msg');   ?>
          </div>
      </div>
    <?php }?>
    <div>

    <form method="post" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left ">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

      <input type="hidden" name="auction_id" value="<?php echo $auction_id; ?>">
      <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
        <div class="form-group ">   
            
            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
                <select class="form-control col-md-7 col-xs-12 seller_id" multiple="" id="seller_id" name="seller_id[]">
                  <?php foreach ($seller_list as $type_value) { ?>
                  <option value="<?php echo $type_value['id']; ?>">
                    <?php echo $type_value['fname'].' '.$type_value['lname']; ?>
                  </option>
                  <?php  } ?>
                </select>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
                <select class="form-control col-md-7 col-xs-12 sale_person_id" multiple="" id="sale_person_id" name="sale_person_id[]">
                  <?php foreach ($application_user_list as $type_value) { ?>
                  <option value="<?php echo $type_value['id']; ?>">
                    <?php echo $type_value['fname'].' '.$type_value['lname']; ?>
                  </option>
                  <?php  } ?>
                </select>
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
              <select class="form-control select2" id="days_filter" name="days_filter">
                <option value="">Choose Days</option>
                <option value="today">Today</option>
                <option value="-1">Yesterday</option>
                <option value="-7">Last week</option>
                <option value="-15">Last 15 Days</option>
                <option value="-30">Last 30 Days</option>
              </select>
            </div>
        </div>
        
        <div class="form-group ">   
          <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
            <div class='input-group date' id='datefrom'>
              <input type='text' class="form-control" name="datefrom" placeholder="From" />
              <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>
          <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
            <div class='input-group date' id='dateto'>
              <input type='text' class="form-control" name="dateto" placeholder="To" />
              <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>


            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
              <input type="text" name="registration_no" placeholder="Please Enter Registration No" class="form-control">
            </div>

        </div>
        
        <div class="form-group ">     
            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
              <input type="text" name="keyword" placeholder="Please Enter keyword" class="form-control">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
              <select class="form-control select2" id="item_status" name="item_status">
                <option value="">Select Item Status</option>
                <option value="created">Created</option>
                <option value="inspected">Inspected</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Canceled</option>
              </select>
            </div>

            

        </div>
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-1">
                <button type="button" id="send" class="btn btn-info">Filter</button>
            </div>
        </div>
      </form>
      <div id="result"></div>
      
        <div class="ln_solid"></div>
       <div class="clearfix"></div> 
       

      <!-- <button type="button" id="<?php echo urlencode(base64_encode($auction_id)); ?>" data-toggle="modal" data-target=".bs-example-modal-sm2"  data-backdrop="static" data-keyboard="false" class="btn btn-success btn-sm oz_stock_list"><i class="fa fa-plus"></i> Add Item</button> -->
        
      <!-- <div class="x_content">
        <br>
        <hr>
         <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="item_category" data-url="<?php echo base_url(); ?>items/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete Selected Rows</button>

      </div> -->
      
<div class="x_content">
<?php if (strtotime($auction_expiry_time) > strtotime(date('Y-m-d'))) { ?>
<button style="margin-left: 50px; display: none;" type="button" id="bulk_rules" data-toggle="modal" data-target="#bulk_rules_model" data-backdrop="static" data-keyboard="true" class="btn btn-success btn-xs bulk-actions"><i class="fa fa-pencil-square-o"></i>Bulk Rules</button><br>
<?php } ?>
<?php
// $b64_auction_id = urlencode(base64_encode($auction_id));
$b64_auction_id = $auction_id;
if(!empty($items_list)){?>
<div id="content_items_inner">
<table id="datatable-responsive" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
<!-- <table id="datatable-responsive" class="table table-striped" cellspacing="0" width="100%"> -->
  <thead>
      <tr>
          <th>Image</th>
          <th data-orderable="false">
              <!-- <input type="checkbox" id="check-allll" class="flatt" name="table_records"> -->
          </th>
          <th>Name</th>
          <th>Category</th>
          <th>Registration #</th>
          <th>Lot #</th>
          <!-- <th>Make</th> -->
          <!-- <th>Model</th> -->
          <th>Reserve</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Bidder Name</th>
          <th>Bid Amount</th>
          <th>Status</th> 
          <th>Sold Status</th> 
          <th data-orderable="false">Actions</th>
      </tr>
  </thead>
  <tbody class="">
      <?php 
      $j = 0;
      $role = $this->session->userdata('logged_in')->role; 
      $CI =& get_instance();
      $have_documents = false;
      foreach($items_list as $value){
        // print_r($value['bid_data']);die();
          $j++;
          $auction_info = json_decode($value['category_name']);
          $itemid = $value['id'];
          $result_if_already = $CI->auction_model->auction_item_bidding_rule_list($auction_id,$itemid);
          $sold_item = $this->db->get_where('sold_items', ['item_id' => $itemid, 'auction_id' => $auction_id]);
          if(isset($result_if_already) && !empty($result_if_already))
          {
              $checked_ = 'checked';
              if(isset($result_if_already[0]['bid_start_time']) && !empty($result_if_already[0]['bid_end_time'])){
              $if_rules = 'btn-success';
              }
              else{
              $if_rules = 'btn-warning';
              }
              
              if(isset($result_if_already[0]['order_lot_no']) && !empty($result_if_already[0]['order_lot_no'])){
              $if_lot_no = 'btn-success';
              }
              else{
              $if_lot_no = 'btn-warning';
              }

          }else{
              $checked_ = '';
              $if_rules = 'btn-warning';
              $if_lot_no = 'btn-warning';
          } 

          if(!empty($value['item_images']) || !empty($value['item_attachments']))
          {
              $documents_class = 'btn-success';
              $have_documents = true;
              if(empty($value['item_attachments']))
              {
                  $documents_class = 'btn-info';
              }
          }
          else
          {
              $have_documents = false;
              $documents_class = 'btn-warning';
          }
          ?>
          <tr id="row-<?php echo  $value['id']; ?>">
              
              <!-- <td><?php //echo $j ?></td> -->
              <td><a class="text-success" href="<?php echo base_url().'auction/details/'.$value['id'].'/'.$b64_auction_id;  ?>">
                <?php 
                if(isset($value['item_images']) && !empty($value['item_images']))
                {
                  $images_ids = explode(",",$value['item_images']);
                  $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
                }
                else
                {
                  $files_array = array();
                }
                
              ?>
               <?php if(isset($files_array[0]['name']) && !empty($files_array[0]['name']))
                {
                  if (file_exists(base_url().'uploads/items_documents/'.$value["id"].'/37x36_'.$files_array[0]['name'])) { ?>
                  <img style="width: 50px; height: 33px;" class="img-responsive avatar-view" src="<?php echo base_url().'uploads/items_documents/'.$value['id'].'/37x36_'.$files_array[0]['name']; ?>" alt="<?php $item_name = json_decode($value['name']); echo ucwords($item_name->english); ?>">
              <?php } else { ?>
                  <img style="width: 50px; height: 33px;" class="img-responsive avatar-view" src="<?php echo base_url().'uploads/items_documents/'.$value['id'].'/'.$files_array[0]['name']; ?>" alt="<?php $item_name = json_decode($value['name']); echo ucwords($item_name->english); ?>">
              <?php } }
                  else
                  { ?>
                <img style="width: 50px; height: 33px;" class="img-responsive avatar-view" src="<?php echo base_url().'assets_admin/images/product-default.jpg'; ?>" alt="<?php $item_name = json_decode($value['name']); echo ucwords($item_name->english); ?>">
              <?php } ?>
                
              </a>
              </td>
              <td class="a-center ">
              <input type="checkbox" onclick="zain(this)" class="multiple_rows flatt" id="<?php echo  $value['id']; ?>" name="table_records[]">
              </td>
              <td><label>
                <a class="text-success" href="<?php echo base_url().'auction/details/'.$value['id'].'/'.$b64_auction_id;  ?>"><?php $item_name = json_decode($value['name']); echo ucwords($item_name->english); ?>
                </a>
                </label>
              </td>
              <td><?php echo (isset($auction_info->english) && !empty($auction_info->english)) ? $auction_info->english : ''; ?></td>
              <td><?php echo $value['registration_no']; ?></td>
              <td><?php echo $value['order_lot_no']; ?></td>
              <!-- <td><?php echo (isset($value['make_name']) && !empty($value['make_name'])) ? $value['make_name'] : ''; ?></td> -->
              <!-- <td><?php echo (isset($value['model_name']) && !empty($value['model_name'])) ? $value['model_name'] : ''; ?></td> -->
              <td><?php echo (isset($value['price']) && !empty($value['price'])) ? $value['price'] : ''; ?></td>
              <td><?php echo $value['bid_start_time']; ?></td>
              <td><?php echo $value['bid_end_time']; ?></td>
              <td class="bidder-<?= $value['id']; ?>" ><?php echo (!empty($value['bid_data'])) ? $value['bid_data']['username'] : 'No Bid Available' ; ?></td>
              <td class="<?= $value['id']; ?>"><?php echo (!empty($value['bid_data'])) ? $value['bid_data']['bid_amount'] : '0' ; ?></td>
              <td><?php echo ucwords($value['item_status']); ?></td>
              <td>
                <?php 
                  // if($value['sold'] == 'return'){
                  //   echo "Item Returned";
                  // } else{

                    $sild_ststus = $value['sold_status'];
                    switch ($sild_ststus) {
                      case 'not':
                      echo "Available";
                      break;

                      case 'not_sold':
                      echo "Unsold";
                      break;

                      case 'sold':
                      echo "Sold Out";
                      break;

                      case 'approval':
                      echo "Need Approval From Customer";
                      break;

                      case 'return':
                      echo "Item Returned";
                      break;

                      default:
                      echo "";
                      break;
                    } 
                  // }?>   
              </td>
              <td>
                <?php if($role == 1 && $value['sold'] == 'no'){ 
                  $return_url = urlencode(base_url($_SERVER['REQUEST_URI']));
                  ?>
                  <!-- <a href="<?php echo base_url().'auction/update_auction_item/'.$value['id'].'/'.$b64_auction_id; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a> -->

                  <a href="<?php echo base_url().'items/update_item/'.$value['id'].'?rurl='.$return_url; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                <?php } ?>
                <a href="<?php echo base_url().'auction/documents/'.$value['id'].'/'.$b64_auction_id; ?>" class="btn <?php echo  $documents_class; ?> btn-xs"><i class="fa fa-pencil"></i> Documents</a>
                
                <?php if($have_documents){ ?>
                  <a href="<?php echo base_url().'auction/view_documents/'.$value['id'].'/'.$b64_auction_id; ?>" class="btn <?php echo  $documents_class; ?> btn-xs"><i class="fa fa-pencil"></i> View Documents</a>
                <?php } ?>
                  <?php if ($category_id == 1) {?>
                    <a href="<?php echo base_url('auction/inspection_report/').$itemid ;?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Inspection Report</a>
                    <?php }?>
                    
                
                <button type="button" id="<?php echo $value['id']; ?>" data-toggle="modal" data-target=".bs-example-modal-print"  data-backdrop="static" data-keyboard="true" class="btn btn-info btn-xs oz_print_qr" onclick="get_qr_code(this)"><i class="fa fa-qrcode"></i> QR Code</button>
                <?php if (strtotime($auction_expiry_time) > strtotime(date('Y-m-d'))) { ?>
                  <button type="button" id="<?php echo $value['id']; ?>" onclick="get_rules(<?php echo $auction_id.",".$itemid ?>)"  data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="true" class="btn <?php echo $if_rules; ?> btn-xs oz_bidding_model"><i class="fa fa-pencil-square-o"></i>Rules</button>
                <?php } ?>
                <button type="button" id="<?php echo $value['id']; ?>" onclick="get_lotting(<?php echo $auction_id.",".$itemid ?>)"  data-toggle="modal" data-target=".bs-example-modal-sm_lot" data-backdrop="static" data-keyboard="true" class="btn <?php echo $if_lot_no; ?> btn-xs oz_lotting_model"><i class="fa fa-pencil-square-o"></i>Lotting</button>
                <button type="button" id="<?php echo $value['id']; ?>" data-toggle="modal" data-target=".bs-example-modal-banner"  data-backdrop="static" data-keyboard="true" onclick="getBanner(this);" class="btn btn-info btn-xs oz_banner_pr"><i class="fa fa-file"></i> Banner</button>
                <?php if(!empty($value['bid_data'])){
                    ?>
                    <button type="button" data-id="<?= $value['id']; ?>" data-auction-id="<?= $auction_id; ?>" onclick="getlog(this)" data-toggle="modal" data-target=".log-model"  data-backdrop="static" data-keyboard="true" class="btn btn-info btn-xs sale_item"><i class="fa fa-list"></i> Bid Log</button>
                <?php } ?>

                <?php if($value['sold'] == 'return'){ ?>
                  <a href="javascript:void(0)" class="btn btn-info btn-xs"><i class="fa fa-money"></i> Returned</a>
                <!-- <?php //}elseif($value['sold'] == 'yes'){ ?> -->
                  <!-- <a href="javascript:void(0)" class="btn btn-info btn-xs"><i class="fa fa-money"></i> Sold Out</a> -->
                <?php }elseif($value['sold_status'] == 'not_sold'){ ?>
                  <!-- <a href="javascript:void(0)" class="btn btn-info btn-xs"><i class="fa fa-money"></i> Rejected </a> -->
                <?php }elseif($value['sold_status'] == 'sold'){ ?>
                <?php } else { 
                  if(!empty($value['bid_data'])){
                    $itms_name = json_decode($value['name']);
                    ?>
                    <button type="button" data-id="<?= $value['id']; ?>" onclick="saleitem(this)" name="<?= $itms_name->english; ?>" reserve_price="<?= $value['price']; ?>" bid_price="<?= $value['bid_data']['bid_amount']; ?>" data-toggle="modal" data-target=".sale-model"  data-backdrop="static" data-keyboard="true" class="btn btn-info btn-xs sale_item"><i class="fa fa-file"></i> Sale</button>
                    <!-- <a href="<?php echo base_url().'auction/sale_item/'.$value['id'].'/'.$b64_auction_id; ?>" data-toggle="modal" data-target=".sale-model"  data-backdrop="static" data-keyboard="false" class="btn btn-info btn-xs"><i class="fa fa-money"></i> Sale</a> -->
                <?php } } ?>

                <?php if($value['sold_status'] == 'sold' && !empty($value['bid_data'])){ ?>
                  <a href="<?= base_url('user-payments/').$value['bid_data']['user_id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-money"></i> Make Payment</a>
                <?php } ?>

                <?php $days_to_sale_item = $this->config->item('sold_button_days_limit'); 
                if($sold_item->num_rows() > 0){ 
                  $sold_item_data = $sold_item->row_array();
                    if ($sold_item_data['payment_status'] == 0 && date('Y-m-d H:i:s',strtotime("+$days_to_sale_item days",strtotime($auction_expiry_time))) >= date('Y-m-d H:i:s')) { ?>
                      <a href="<?php echo base_url('auction/unsold/').$value['id']."/".$b64_auction_id ."/not_sold";?>" class="btn btn-info btn-xs"><i class="fa fa-money"></i> Unsold</a>
                    <?php } 
                } ?>

                <?php if($role == 1 && empty($value['bid_data'])){ ?>
                    <button onclick="deleteRecord(this)" type="button" data-obj="auction_items" data-id="<?php echo $value['id'].'-'.$auction_id; ?>" data-token-name="<?= $this->security->get_csrf_token_name(); ?>" data-token-value="<?= $this->security->get_csrf_hash(); ?>" data-url="<?php echo base_url(); ?>auction/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
                <?php } ?> 

              </td>
          </tr>
      <?php }?>
        
        <?php 
    }else{
        echo "<h3>Items Not added yet!</h3>";
    }
    ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
    </div>
   <!--  <?php 
      $ids = $value['id'];
    ?> -->
       <div class="modal fade bs-example-modal-banner" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Banner</h4>
            </div>
            <div id="DivIdToPrintBanner" class="modal-banner-body">

            </div>

            <div class="modal-footer">
            <input type="hidden" name="itemPrintId" id="itemPrintId">
            
            <a id="link" target="_blank" href="<?php echo base_url('auction/get_print_tab');?>" type="button" class="btn btn-info btn-xs"><i class="fa fa-print"></i>Print</a> 
            </div>
          </div>
        </div>
      </div>

      

       <div class="modal fade bs-example-modal-sm_lot" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width:750px; ">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close inner_lotting_model" title="close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Lotting</h4>
            </div>
            <div class="modal-body_lotting">
              
            </div>

          </div>
        </div>
      </div>

       <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width:750px; ">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close inner_bidding_model" title="close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Bidding Rules</h4>
            </div>
            <div class="modal-body_rules">
              
            </div>

          </div>
        </div>
      </div> 

      <div class="modal fade bulk_rules_model" id="bulk_rules_model" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width:750px; ">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" title="Close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel"> Bulk Bidding Rules</h4>
            </div>
            <div class="modal-bulk_rules">
              <br>
              <div class="col-md-10">
                <div class="error_message_rule"></div>
              </div>
              <!-- <?php   // $bidding_info[0]['bid_start_time']."<br>"; ?> -->
              <!-- <?php  //$auction_expiry_time = date('m/d/Y h:i:s A', strtotime("+1 day", strtotime($auction_expiry_time))); ?> -->
              <form method="post" novalidate="" id="rules-form2" name="rules_form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left rules_form2">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

              <?= $already_ids = ''; ?>
              <input type="hidden" name="auction_item_ids_list" id="auction_item_ids_list" value="<?php echo (isset($already_ids) && !empty($already_ids) ? $already_ids : ''); ?>">
               <!-- <?php if(isset($bidding_info) && !empty($bidding_info)){ ?>
                <input type="hidden" name="id" value="<?php if(isset($bidding_info) && !empty($bidding_info)) { echo $bidding_info[0]['id']; } ?>">
                <?php } ?> -->   
                 <input type="hidden" name="auction_id" value="<?php if(isset($auction_id) && !empty($auction_id)) { echo $auction_id; } ?>">
                

                
                <div class="item form-group">    
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bid_start_price">Start Price <span class="required">*</span>
                      </label>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="number" id="bid_start_price" name="bid_start_price" value="" class="form-control col-md-7 col-xs-12">
                    <span class="bid_start_price-error text-danger"></span>
                  </div>
                </div> 
                
                <div class="item form-group">    
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bid_start_price">Minimum Bid Price <span class="required">*</span>
                      </label>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="number" id="minimum_bid_price" name="minimum_bid_price" value="" class="form-control col-md-7 col-xs-12">
                    <span class="minimum_bid_price-error text-danger"></span>
                  </div>
                </div> 

                <div class="item form-group">    
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bid_start_time">Start Time <span class="required">*</span>
                  </label>

                  <div class="col-md-6 col-sm-6 col-xs-12  ">
                    <div class='input-group date' id='bid_start_time'>
                      <input type='text' class="form-control" readonly name="bid_start_time" placeholder="Bidding Start Time" value="" />
                      <span class="bid_start_time-error text-danger"></span>
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="item form-group">    
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bid_end_time">End Time <span class="required">*</span>
                  </label>

                  <div class="col-md-6 col-sm-6 col-xs-12  ">
                    <div class='input-group date' id='bid_end_time'>
                      <input type='text' class="form-control" readonly name="bid_end_time" placeholder="Bidding Expiry Time" value="" required />
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      <span class="bid_end_time-error text-danger"></span>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="button" id="send_rules" class="btn btn-success">Submit</button> 
                    </div>
                </div>

              </form>
              
            </div>

          </div>
        </div>
      </div> 


      <div class="modal fade bs-example-modal-print" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">QR Code</h4>
            </div>
            <div id="DivIdToPrint" class="modal-qr-body container-fluid">
              
            </div>
            <div class="modal-footer">
            <button type="button" id="<?php echo $value['id']; ?>" onclick="printDiv();" class="btn btn-info btn-xs"><i class="fa fa-print"></i> Print</button>
            </div>
          </div>
        </div>
      </div>


      <div class="modal fade sale-model" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Sale item</h4>
            </div>
            <div class="modal-sold-body">
              <div>
                <span style="font-weight: bold; margin-left: 20px;">Item Name: </span><span id="item_name"></span>
              </div>
              <div>
                <span style="font-weight: bold; margin-left: 20px;"> Reserve Price: </span><span id="reserve_price"></span>
              </div>   
              <div>
                <span style="font-weight: bold; margin-left: 20px;"> Current Bid Price: </span><span id="bid_price"></span>
              </div>              
            </div>
            <div class="modal-footer">
            <a id="sale" href="#" class="btn btn-info btn-xs"><i class="fa fa-money"></i> Sale</a>
            <a id="not_sale" href="" class="btn btn-info btn-xs"><i class="fa fa-crass"></i> Not sale</a>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade log-model" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Item Log</h4>
            </div>
            <div class="modal-log-body">              
            </div>
            <div class="modal-footer">
            </div>
          </div>
        </div>
      </div>

       <div class="modal fade bs-example-modal-sm2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close stock_list_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel2">Item/Stock List</h4>
            </div>
            <div class="modal-stock-body">
              
            </div>
            <div class="modal-footer">
              <button style="display: none;" type="button" onclick="add_auction_items();" name="add" id="add_items" class="btn btn-success">Add</button>
            </div>
          </div>
        </div>
      </div>

</div>

<script src="https://js.pusher.com/6.0/pusher.min.js"></script>

<script type="text/javascript">
  var token_name = '<?= $this->security->get_csrf_token_name();?>';
  var token_value = '<?=$this->security->get_csrf_hash();?>';

  //Enable pusher logging - don't include this in production
  Pusher.logToConsole = true;

  var pusher = new Pusher("<?= $this->config->item('pusher_app_key'); ?>", {
    cluster: 'ap1'
  });

  var channel = pusher.subscribe('ci_pusher');
  channel.bind('my-event', function(push) {
    // alert(JSON.stringify(push));
    $('.'+push.item_id).html(push.bid_amount);
    $('.bidder-'+push.item_id).html(push.username);
    // $('#your-bid-amount'+push.item_id).html(push.bid_amount+' + ');
  });

    
  $('#datefrom').datetimepicker({
      format: 'YYYY-MM-DD'
  });

  $('#dateto').datetimepicker({
      format: 'YYYY-MM-DD'
  });
   
   $("#auction_type").select2({
    placeholder: "Select Auction Type", 
    width: '200px',
  });

$('.inner_bidding_model').on('click', function(){
  $(".bs-example-modal-sm").modal("hide");
  })

$('.inner_lotting_model').on('click', function(){
  $(".bs-example-modal-sm_lot").modal("hide");
  })

  $(".seller_id").select2({
    placeholder: "Select Seller", 
    width: '200px'
  });
  
  $(".sale_person_id").select2({
    placeholder: "Select Appraiser Person", 
    width: '200px'
  });

    $('input:checkbox').on('ifChecked', function () 
    { 
       if ($("input:checkbox:checked").length > 0){
       $('#delete_bulk').show();
       }
    });
    $('input:checkbox').on('ifUnchecked', function ()
    {
        if ($("input:checkbox:checked").length <= 0){
         $('#delete_bulk').hide();
        }
    });    

     $('.stock_list_close').on('click', function(){
      location.reload();
    });

  var href = $('#link').attr('href');
  function getBanner(e){
    // alert('clicker');
    var url = '<?php echo base_url();?>';
    var auction_id = '<?php echo $auction_id;?>';
    var id = $(e).attr("id");
    console.log(id);
    $.ajax({
      url: url + 'auction/get_banner_details',
      type: 'POST',
      data: {id:id,auction_id:auction_id, [token_name]:token_value},
      beforeSend: function(){
        $('.modal-banner-body').html('<img src="'+url+'assets_admin/images/loading.gif" align="center" />');
      },
     }).then(function(data) {
      var objData = jQuery.parseJSON(data);
      // console.log(objData);
      $('#add_items').hide(); 
      if (objData.msg == 'success') 
      {
        $('.modal-banner-body').html(objData.data);
        // $('#itemId').html(objData.item_id);
        $('input[name="itemPrintId"]').val(objData.item_id);
         $('#link').attr('href' , href + '/'+ objData.auction_id + '/' + objData.item_id);
      }
    });
  }

      function add_auction_items(){

      var ids_list = $("#item_ids_list").val();
      var url = '<?php echo base_url();?>';
      // var auction_id = '<?php //echo $auction_id;?>';
      var auction_id = $("#auction_id").val();
      var category_id = $("#category_id").val();
      // console.log(ids_list);

        $.ajax({
               //AJAX type is "Post".
               type: "POST",
               //Data will be sent to "ajax.php".
               url: url + 'auction/add_auction_items',
               //Data, that will be sent to "ajax.php".
               data: {
                   //Assigning value of "name" into "search" variable.
                   ids_list: ids_list,auction_id:auction_id,category_id:category_id, [token_name]:token_value
               },
                beforeSend: function(){
                  $('#result_add_items').html('<img src="'+url+'assets_admin/images/load.gif" align="center" />');
                },
               //If result found, this funtion will be called.
               success: function(html) {
                   //Assigning result to "display" div in "search.php" file.
                  var objData = jQuery.parseJSON(html);
                   if(objData.msg == 'success'){
                  $("#result_add_items").html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.data + '</div></div>');
                   }
                   reload_item_list();
                   
                  $('#add_items').hide(); 
                   location.reload();
               }
           });

    }

    function get_qr_code(e){
         
      var url = '<?php echo base_url();?>';
      var id = $(e).attr("id");
      console.log(id);
      $.ajax({
        url: url + 'auction/get_qrcode',
        type: 'POST',
        data: {id:id, [token_name]:token_value},
      }).then(function(data) {
        var objData = jQuery.parseJSON(data);
        console.log(objData);
        $('#add_items').hide(); 
        if (objData.msg == 'success') 
        {
          $('.modal-qr-body').html(objData.data);
        }
      });
    }

  function saleitem(e){
    var url = '<?php echo base_url('auction/sale_item/');?>';
    var b64_auction_id = '<?= $b64_auction_id;?>';
    var id = $(e).attr("data-id");
    var name = $(e).attr("name");
    var reserve_price = $(e).attr("reserve_price");
    var bid_price = $(e).attr("bid_price");
    console.log(name); 
    $('#not_sale').attr('href', url + id + '/' + b64_auction_id +'/not_sold');
    $('#sale').attr('href', url + id + '/' + b64_auction_id +'/sold');
    $('#item_name').html(name);
    $('#reserve_price').html(reserve_price);
    $('#bid_price').html(bid_price);
  }

  function getlog(e){
    var url = '<?php echo base_url(); ?>';
    var b64_auction_id = '<?= $b64_auction_id;?>';
    var id = $(e).attr("data-id");
    var auction_id = $(e).attr("data-auction-id");
    $.ajax({
      url: url + 'auction/get_log',
      type: 'POST',
      data: {id:id,auction_id:auction_id, [token_name]:token_value},
      }).then(function(data) {
        var objData = jQuery.parseJSON(data);
        console.log(objData);
        $('#add_items').hide(); 
        if (objData.msg == 'success') 
        {
          $('.modal-log-body').html(objData.data);
        }
      });
  }

 $('.oz_stock_list').on('click',function()
 {
    var url = '<?php echo base_url();?>';
    var id = $(this).attr("id");
    console.log(id);
    $.ajax(
    {
      url: url + 'auction/get_stock_list_inner',
      type: 'POST',
      data: {id:id, [token_name]:token_value},
    }).then(function(data) 
    {
      var objData = jQuery.parseJSON(data);
      console.log(objData);
      $('#add_items').hide(); 
      if (objData.msg == 'success') 
      {
        $('.modal-stock-body').html(objData.data);
      }
    });
  });

function printBannerDiv() 
{

  var divToPrintBanner=document.getElementById('DivIdToPrintBanner');

  var newWin=window.open('','Print-Window');

  newWin.document.open();

  newWin.document.write('<html><body onload="window.print()">'+divToPrintBanner.innerHTML+'</body></html>');

  newWin.document.close();

  // setTimeout(function(){newWin.close();},10);

}
function printDiv() 
{

  var divToPrint=document.getElementById('DivIdToPrint');

  var newWin=window.open('','Print-Window');

  newWin.document.open();

  newWin.document.write('<html style="height: 80px;"><head><style type="text/css" media="print"> @page { size: auto; margin: 0; }</style></head><body onload="window.print()" style="margin: 0;">'+divToPrint.innerHTML+'</body></html>');

  newWin.document.close();

  // setTimeout(function(){newWin.close();},10);

}

     function get_lotting(auction_id,item_id){

              var url = '<?php echo base_url();?>';
              var auction_id = auction_id;
              var id = item_id;
               $.ajax({
                url: url + 'auction/get_lotting',
                type: 'POST',
                data: {id:id,auction_id:auction_id, [token_name]:token_value},
                beforeSend: function(){
                   $('.modal-body_lotting').html('<img src="'+url+'assets_admin/images/loading.gif" align="center" />');
                },
                }).then(function(data) {
                  var objData = jQuery.parseJSON(data);
                  // console.log(objData);
                  if (objData.msg == 'success') 
                  {
                    $('.modal-body_lotting').html(objData.data);
                  }

          });
     }

     function get_rules(auction_id,item_id){


              var url = '<?php echo base_url();?>';
              var auction_id = auction_id;
              var id = item_id;
               $.ajax({
                url: url + 'auction/get_bidding_rules',
                type: 'POST',
                data: {id:id,auction_id:auction_id, [token_name]:token_value},
                }).then(function(data) {
                  var objData = jQuery.parseJSON(data);
                  console.log(objData);
                  if (objData.msg == 'success') 
                  {
                    $('.modal-body_rules').html(objData.data);
                  }

          });
     }

  function newexportaction(e, dt, button, config) {
	  var self = this;
	  var oldStart = dt.settings()[0]._iDisplayStart;
	  dt.one('preXhr', function (e, s, data) {
		  // Just this once, load all data from the server...
		  data.start = 0;
		  data.length = 2147483647;
		  dt.one('preDraw', function (e, settings) {
			  // Call the original action function
			  if (button[0].className.indexOf('buttons-copy') >= 0) {
				  $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
			  } else if (button[0].className.indexOf('buttons-excel') >= 0) {
				  $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
					  $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
					  $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
			  } else if (button[0].className.indexOf('buttons-csv') >= 0) {
				  $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
					  $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
					  $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
			  } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
				  $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
					  $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
					  $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
			  } else if (button[0].className.indexOf('buttons-print') >= 0) {
				  $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
			  }
			  dt.one('preXhr', function (e, s, data) {
				  // DataTables thinks the first item displayed is index 0, but we're not drawing that.
				  // Set the property to what it was before exporting.
				  settings._iDisplayStart = oldStart;
				  data.start = oldStart;
			  });
			  // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
			  setTimeout(dt.ajax.reload, 0);
			  // Prevent rendering of the full data to the DOM
			  return false;
		  });
	  });
	  // Requery the server with the new one-time export settings
	  dt.ajax.reload();
  }
    function show_content(){


        show_items_content

    }
    let exportOptions = {'rows': {selected: true} ,columns: ['2,3,4,5,6,7,8,9,10,11,12']};
    let dtButtons = [{ extend : 'excel',exportOptions: exportOptions, "action": newexportaction},{ extend : 'csv',exportOptions: exportOptions, "action": newexportaction},{ extend : 'pdf',exportOptions: exportOptions, "action": newexportaction},{ extend : 'print',exportOptions: exportOptions, "action": newexportaction},];

  var datatable = $('#datatable-responsive').DataTable({
    responsive: true,
    dom: 'Bfltip',
    buttons: [
      {
        extend: 'collection',
        text: 'Export',
        className: 'btn-sm',
        buttons: dtButtons
       },{
        extend: 'selectAll',
        className: 'btn-sm',
        text: 'Select All',
        exportOptions: exportOptions
      },{
        extend: 'selectNone',
        className: 'btn-sm',
        text: 'Deselect',
        exportOptions:exportOptions
      }, 
    ],
    'select': {
      style: 'multi'
    },
    // buttons: [
    //     'copy', 'csv', 'excel', 'pdf', 'print', 'select all'
    // ],
    columnDefs: [ 
      { targets:"_all" },
      { targets:[13], className: "tablet, mobile" }

    ]
  });
// $(document).ready(function(){

//   $('.ozproceed_check').on('ifChecked',function() {
//       var count = $('[name="table_records"]:checked').length
//       console.log(count);
//         // $(this).toggleClass('selected');
//         // if(datatable.rows('.selected').data().length > 0){
//         // var id = datatable.rows('.selected').data();
//         //   $('#bulk_rules').css("display", "block");
//         // } else {
//         //   $('#bulk_rules').css("display", "none");
//         // }
//     } );
// })

  var url = "<?php echo base_url(); ?>";
  var formaction_path = '<?php echo $formaction_path;?>';

  $("#send").on('click', function(e) { //e.preventDefault();
      var formData = new FormData($("#demo-form2")[0]);
      //formData.append('<?//= $this->security->get_csrf_token_name();?>', '<?//= $this->security->get_csrf_hash();?>');
      console.log(formData);
        $.ajax({
          url: url + 'auction/' + formaction_path,
          type: 'POST',
          data: formData,
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function(){

            $('#content_items_inner').html('<img src="'+url+'assets_admin/images/loading.gif" align="center" />');
          }
          }).then(function(data) {
            var objData = jQuery.parseJSON(data);
            if (objData.msg == 'success') 
            { 
              $('#content_items_inner').html(objData.data);
             
            } 
            else 
            {
              $('.msg-alert').css('display', 'block');
              $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');

                 window.setTimeout(function() {
              $(".alert").fadeTo(500, 0).slideUp(500, function(){
                  $(this).remove(); 
              });
            }, 3000);
            }
          });
  });    
   var CustomerIDArray=[<?php echo $already_ids; ?>];
   function zain(e){
    console.log(e);

    // return false;
      var len = $("#datatable-responsive input[name='table_records[]']:checked").length;
    // alert(len);

      var ids_list_str = '';

      if(e.checked) {
        if (len > 0) { 
          var found = jQuery.inArray(e.id, CustomerIDArray);
          if(CustomerIDArray.length != 0)
          {
            if(jQuery.inArray(e.id, CustomerIDArray) !== -1){
            }
            else
            {
              CustomerIDArray.push(e.id);
            }
          }
          else
          {
          CustomerIDArray.push(e.id);
          }
          $('.bulk-actions').show();
        } else {
          $('.bulk-actions').hide(); 
        }

      }
      else
      {
        // console.log(e.id+' unchecked');
        if (len > 0) {
            // CustomerIDArray.slice(e.id);
            CustomerIDArray = jQuery.grep(CustomerIDArray, function (value) {
                return value != e.id;
            });
            $('.bulk-actions').show();
        } 
        else
        {
            CustomerIDArray = jQuery.grep(CustomerIDArray, function (value) {
                return value != e.id;
            });
            $('.bulk-actions').hide();
        }
        
      }
          ids_list_str = CustomerIDArray.join();
          $('#auction_item_ids_list').val(ids_list_str);

    }

    $(function () {
        $('#bid_start_time').datetimepicker({ 
           maxDate: "<?php if(isset($bidding_info[0]['bid_end_time']) && !empty($bidding_info[0]['bid_end_time'])) { echo date('m/d/Y h:i:s A',strtotime($bidding_info[0]['bid_end_time'])); }else{ echo date('m/d/Y h:i:s A', strtotime('+2 month'));}?>",
          minDate: "<?php if(isset($bidding_info[0]['bid_start_time']) && !empty($bidding_info[0]['bid_start_time'])) { echo date('m/d/Y h:i:s A',strtotime($bidding_info[0]['bid_start_time'])); }else{ echo date('m/d/Y h:i:s A', strtotime('-2 month'));} ?>",
          useCurrent: false,
           ignoreReadonly : true
        });
        
        $('#bid_end_time').datetimepicker({
            useCurrent: false, //Important! See issue #1075 
            maxDate: "<?php if(isset($auction_expiry_time) && !empty($auction_expiry_time)&& $auction_expiry_time > date('m/d/Y h:i:s')) { echo date('m/d/Y h:i:s A',strtotime($auction_expiry_time)); }else{ echo date('m/d/Y h:i:s A', strtotime('+2 month'));}?>",
           minDate: "<?php if(isset($bidding_info[0]['bid_start_time']) && !empty($bidding_info[0]['bid_start_time'])) { echo date('m/d/Y h:i:s A',strtotime($bidding_info[0]['bid_start_time'])); }else{ echo date('m/d/Y h:i:s A');} ?>",
            // maxDate: "<?php if(isset($auction_expiry_time) && !empty($auction_expiry_time)) { echo date('m/d/Y h:i:s A',strtotime($auction_expiry_time)); }?>",
            ignoreReadonly : true
           
        });
        $("#bid_start_time").on("dp.change", function (e) {
           $('#bid_end_time').data("DateTimePicker").minDate(e.date);
        });
        $("#bid_end_time").on("dp.change", function (e) {
            $('#bid_start_time').data("DateTimePicker").maxDate(e.date);
        });
    });

     // console.log('i m here');
     $('#send_rules').on('click',function(){
       // $('#demo-form2').parsley().on('field:validated', function() {
       //      var ok = $('.parsley-error').length === 0;
       //    }) .on('form:submit', function() {
      var validation = false;
        selectedInputs = ['bid_start_price','minimum_bid_price', 'bid_start_time', 'bid_end_time'];
        validation = validateFields(selectedInputs);


        if(validation == false){
          return false;
        }

        if(validation == true){



          var url = '<?php echo base_url();?>';
          var rulesData2 = $('#rules-form2').serializeArray();
          //console.log(formData2);
          //return false;
           $.ajax({
            url: url + 'auction/update_bidding_rules',
            type: 'POST',
            data: rulesData2
            }).then(function(data) {
              var objData = jQuery.parseJSON(data);
              console.log(objData);
              if (objData.msg == 'success') 
              {
                // $('.modal-body').html(objData.data);
                // $(".rule_form_data").hide();
                // $('.msg-alert').css('display', 'block');

                $(".rules_form2").html('<div class="alert" style="width:100% !important;" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.data + '</div></div>');
                 setTimeout(function(){
                  $(".bulk_rules_model").modal("hide");
                 },2500);
                 
                 window.setTimeout(function(){location.reload()},3000)
                 // window.location = url + 'items';
              }
              else
              {
                // $('.msg-alert').css('display', 'block');
                $(".error_message_rule").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.data + '</div></div>');
              }

          });
        }
                // return false;
        });

$(document).ready(function(){

  // if ($("input.flatt")[0]) {
  //   $(document).ready(function () {
  //       $('input.flatt').iCheck({
  //           checkboxClass: 'icheckbox_flat-green',
  //           radioClass: 'iradio_flat-green'
  //       });
  //   });
  // }
});

  // $('table input').on('ifChecked', function () {
  //   checkState = '';
  //   $(this).parent().parent().parent().addClass('selected');
  //   countChecked();
  // });
  // $('table input').on('ifUnchecked', function () {
  //     checkState = '';
  //     $(this).parent().parent().parent().removeClass('selected');
  //     countChecked();
  // });

  // var checkState = '';

  // $('.bulk_action input').on('ifChecked', function () {
  //     checkState = '';
  //     $(this).parent().parent().parent().addClass('selected');
  //     countChecked();
  // });
  // $('.bulk_action input').on('ifUnchecked', function () {
  //     checkState = '';
  //     $(this).parent().parent().parent().removeClass('selected');
  //     countChecked();
  // });
  // var checkState = '';
  // $('.bulk_action input #check-allll').on('ifChecked', function () {
  //   checkState = 'all';
  //   allChecked();
  // });
  // $('.bulk_action input #check-allll').on('ifUnchecked', function () {
  //   checkState = 'none';
  //   allChecked();
  // });

  // function allChecked() {
  //   if (checkState === 'all') {
  //     $(".bulk_action input[name='table_records[]']").iCheck('check');
  //   }
  //   if (checkState === 'none') {
  //     $(".bulk_action input[name='table_records[]']").iCheck('uncheck');
  //   }

  //   var checkCount = $(".bulk_action input[name='table_records[]']:checked").length;

    // if (checkCount) {
    //   $('.column-title').hide();
    //   $('.bulk-actions').show();
    //   $('.action-cnt').html(checkCount + ' Records Selected');
    // } else {
    //   $('.column-title').show();
    //   $('.bulk-actions').hide();
    // }
  // }
datatable.on('click', 'thead #check-allll', function(e) {
    if (this.checked) {
        datatable.rows().select();
      $(".flatt").iCheck('check');
    } else {
        datatable.rows().deselect();
      $(".flatt").iCheck('uncheck');
    }
      
    // e.stopPropagation();
});

</script>

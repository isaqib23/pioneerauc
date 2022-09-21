<script src="<?php echo base_url();?>assets_admin/js/formBuilder/jquery-ui.min.js"></script>
<script src="<?php echo base_url();?>assets_admin/js/formBuilder/form-render.min.js"></script>
<style type="text/css">
    .dz-image img{
    max-width: 120px;
    max-height: 120px;
    }
</style>
<!-- PNotify -->
    <link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
 <script type="text/javascript" src="<?php echo base_url(); ?>assets_admin/vendors/galleria/galleria.min.js"></script>
<div class="x_panel"> 
  <div class="x_content"> 
    <div class="col-md-12 col-sm-12 col-xs-12">
      <?php 
        $role = $this->session->userdata('logged_in')->role; 
        $CI =& get_instance(); 
                                    
        $item_id = $this->uri->segment(3); 
        // $auction_id = base64_decode(urldecode($this->uri->segment(4)));
        $auction_id = $this->uri->segment(4);
        // print_r($auction_id);die('zainnn');

        $role = $this->session->userdata('logged_in')->role; 
        $have_documents = false;
       $result_if_already = $CI->auction_model->auction_item_bidding_rule_list($auction_id,$item_id);
        if(isset($result_if_already) && !empty($result_if_already))
        {
          $checked_ = 'checked';
          if(isset($result_if_already[0]['bid_start_time']) && !empty($result_if_already[0]['bid_end_time'])){
          $if_rules = 'btn-success';
          }
          else{
          $if_rules = 'btn-warning';
          }
          
          if(isset($result_if_already[0]['lot_no']) && !empty($result_if_already[0]['lot_no'])){
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

        if(!empty($item_row[0]['item_images']) || !empty($item_row[0]['item_attachments']))
        {
            $documents_class = 'btn-success';
            $have_documents = true;
            if(empty($item_row[0]['item_attachments']))
            {
                $documents_class = 'btn-info';
            }
        }
        else
        {
            $documents_class = 'btn-warning';
        }
        $b64_auction_id = urlencode(base64_encode($this->uri->segment(4)));
      ?>
      <a type="button" href="<?php echo base_url().'auction/'.$back_navigate.'/'.$b64_auction_id; ?>"  class="btn btn-info"><i class="fa fa-arrow-left"></i> Back</a>

      <div class="all_buttons navbar-right">
        <?php if($role == 1 && $item_row[0]['sold'] == 'no'){ 
          $return_url = urlencode(base_url($_SERVER['REQUEST_URI']));
        ?>
          <!-- <a href="<?php //echo base_url().'items/update_item/'.$item_id; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a> -->
          <a href="<?php echo base_url().'items/update_item/'.$item_id.'?rurl='.$return_url; ?>" type="button" class="btn btn-info btn-xs oz_edit_item"><i class="fa fa-pencil"></i> Edit</a>
        <?php } ?>
        <!-- <a href="<?php //echo base_url().'items/documents/'.$item_id; ?>" class="btn <?php //echo  $documents_class; ?> btn-xs"><i class="fa fa-pencil"></i> Documents</a> -->

        <a href="<?php echo base_url('auction/documents/').$item_id.'/'.$auction_id;?>" type="button" class="btn btn-info btn-xs oz_edit_item"><i class="fa fa-pencil"></i> Documents</a>
        <?php if (($back_navigate == 'items') && (strtotime($auction_expiry_time) > strtotime(date('Y-m-d')))) : ?>
            <button type="button" id="<?php echo $item_id; ?>" onclick="get_rules(<?php echo $auction_id.",".$item_id ?>)"  data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="true" class="btn <?php echo $if_rules; ?> btn-xs oz_bidding_model"><i class="fa fa-pencil-square-o"></i>Rules</button>
        <?php endif; ?>

        <button type="button" id="<?php echo $item_id; ?>" onclick="get_lotting(<?php echo $auction_id.",".$item_id ?>)"  data-toggle="modal" data-target=".bs-example-modal-sm_lot" data-backdrop="static" data-keyboard="true" class="btn <?php echo $if_lot_no; ?> btn-xs oz_lotting_model"><i class="fa fa-pencil-square-o"></i>Lotting</button>

        <?php //if($have_documents){ ?>
          <!-- <a href="<?php //echo base_url().'items/view_documents/'.$item_id; ?>" class="btn <?php //echo  $documents_class; ?> btn-xs"><i class="fa fa-pencil"></i> View Documents</a> -->
        <?php //} ?>
        <button type="button" id="<?php echo $item_id; ?>" data-toggle="modal" data-target=".bs-example-modal-print"  data-backdrop="static" data-keyboard="true" class="btn btn-info btn-xs oz_print_qr"><i class="fa fa-qrcode"></i> QR Code</button>

        <button type="button" id="<?php echo $item_id; ?>" data-toggle="modal" data-target=".bs-example-modal-banner"  data-backdrop="static" data-keyboard="true" class="btn btn-info btn-xs oz_banner_pr"><i class="fa fa-file"></i> Banner</button>
    
      </div>
      <div id="result"></div>
      <?php if($this->session->flashdata('msg')){ ?>
        <div class="alert">
            <div class="alert alert-success alert-dismissible fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black;"><span aria-hidden="true">×</span>
              </button>
              <?php  echo $this->session->flashdata('msg');   ?>
          </div>
        </div>
      <?php }?>
      <div class="ln_solid"></div>
   

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
          <p class="lead"><?php if(isset($item_row[0]['name']) && !empty($item_row[0]['name'])) { $item_name = json_decode($item_row[0]['name']); echo $item_name->english; }else{ echo 'Product'; } ?></p>
          <div id="galleria" class="galleria">
            <?php if(isset($files_array) && !empty($files_array))
            {
              foreach ($files_array as $file_value) 
              {
              
             ?>             
              <img src="<?php echo base_url().'uploads/items_documents/'.$item_row[0]['id'].'/'.$file_value['name']; ?>"> 

            <?php
                } 
              }
              else
              { 
            ?>
              <img style="max-width: 350px" class="img-responsive avatar-view" src="<?php echo base_url().'assets_admin/images/product-default.jpg'; ?>" alt="Visa">
            <?php } ?>
          </div>
          <h3>Details: </h3>
          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;max-width: 350px">
              <?php if(isset($item_row[0]['detail']) && !empty($item_row[0]['detail'])) { $item_details = json_decode($item_row[0]['detail']); echo $item_details->english; }else{ echo ''; } ?>
          </p>

        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          <p class="lead">Created Date: <?php echo (isset($item_row[0]['created_on']) && !empty($item_row[0]['created_on'])) ? date('d F Y',strtotime($item_row[0]['created_on'])) : '' ?></p>
          <div class="table-responsive">
            <table class="table">
              <tbody>
                <tr>
                  <th style="width:50%">Reserve Price:</th>
                  <td><?php echo (isset($item_row[0]['price']) && !empty($item_row[0]['price'])) ? $item_row[0]['price'] : '' ?> AED</td>
                </tr>
                 <tr>
                  <th style="width:50%">Registration No:</th>
                  <td><?php echo (isset($item_row[0]['registration_no']) && !empty($item_row[0]['registration_no'])) ? $item_row[0]['registration_no'] : '' ?></td>
                </tr>
                 <tr>
                  <th style="width:50%">Make:</th>
                  <?php $make_new = json_decode($item_row[0]['make_name']); ?>
                  <td><?php echo (isset($item_row[0]['make_name']) && !empty($item_row[0]['make_name'])) ? $make_new->english : '' ?> </td>
                </tr>
                 <tr>
                  <th style="width:50%">Model:</th>
                  <?php $model_new = json_decode($item_row[0]['model_name']); ?>
                  <td><?php echo (isset($item_row[0]['model_name']) && !empty($item_row[0]['model_name'])) ? $model_new->english : '' ?> </td>
                </tr>
               

                 <tr>
                  <th style="width:50%">Lot:</th>
                  <td><?php 
                  $result_if_lot = $CI->auction_model->auction_item_bidding_rule_list($auction_id,$item_id);
                  echo (isset($result_if_lot[0]['lot_no']) && !empty($result_if_lot[0]['lot_no'])) ? $result_if_lot[0]['lot_no'] : '' ?> </td>
                </tr>
                <?php 
                   $dynamic_values_array =  $CI->items_model->get_itemfields_byItemid($item_row[0]['id']);
                   if(isset($dynamic_values_array) && !empty($dynamic_values_array)){
                    foreach ($dynamic_values_array as $value) {
                      $value['value'] = str_replace(array( '[', ']' ), '', $value['value']);
                   $dynamic_fields_array =  $CI->items_model->fields_data_by_id($value['fields_id']);
                ?>

                <tr>
                  <th style="width:50%"><?php echo ucwords($dynamic_fields_array[0]['label']); ?></th>
                  <?php 
                   $HiddenProducts = $dynamic_fields_array[0]['multiple']; 
                   $check_array = array();
                   $from_array = array();
                   $existed_values = array();
                  if($HiddenProducts == 'true'){

                   $check_array = explode(',', $value['value']);
                   $from_array =  json_decode($dynamic_fields_array[0]['values'],true);
                    foreach ($check_array as $check_array_value) 
                    {
                        foreach ($from_array as $from_key => $from_array_value) 
                        {
                            if($from_array[$from_key]['value'] == $check_array_value ) 
                            { 
                                $existed_values[] = $from_array[$from_key]['label'];
                            }
                        }
                    }

                  ?>
                  <td><?php echo (isset($existed_values) && !empty($existed_values)) ? implode(",",$existed_values) : ''; ?> </td>
              <?php }else{ ?>
                  <td><?php

                    if ($dynamic_fields_array[0]['values'] != '' && $dynamic_fields_array[0]['type'] != 'checkbox-group') {
                      $val = json_decode($dynamic_fields_array[0]['values'], true);
                      foreach ($val as $key => $DDval) {
                        if ($DDval['value'] == $value['value']) :
                          echo $DDval['label'];
                        endif;
                      }
                    } else { echo (isset($value['value']) && !empty($value['value'])) ? $value['value'] : '';
                    } ?> </td>
          <?php } ?>
                </tr>
                <?php } } ?>
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
    </div>   

    <div class="col-md-12">
      <div class="" role="tabpanel" data-example-id="togglable-tabs">
        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
          <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Other Documents</a>
          </li>
          <li role="presentation" class=""><a href="#tab_content3" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Test Documents</a>
          </li>
          <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Images & Orders</a>
          </li>
          <!-- <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Profile</a>
          </li> -->
        </ul>
        <div id="myTabContent" class="tab-content">
          <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
            <div class="panbody">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Document</th>
                    <th>Status</th>
                    <th>Order</th>
                  </tr>
                </thead>
                <tbody>
                  <form method="post" name="documents_order" id="documents_form">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                    <?php
                    if(isset($documents_array) && !empty($documents_array)){ 
                        $i = 0;
                        // $item_id = $this->uri->segment(3);
                      foreach ($documents_array as $value) 
                      {
                        $i++;
                    ?>
                        <tr>
                          <td scope="row"><?php echo $i; ?></td>
                          <td>
                            <span class="image"  style="cursor: pointer;" data-toggle="modal" data-target="#ozDocModel" onclick="openDocumentViewer(this);" data-path="<?= base_url('uploads/items_documents').'/'.$item_row[0]['id'].'/'.$value['name'];?>">
                                <?php 
                                $name_file_arr = explode('.', basename($value['name']));
                                if(isset($value['name']) && (strtolower(end($name_file_arr)) == 'docx' || strtolower(end($name_file_arr)) == 'doc') )
                                {
                                    ?>

                                <img style="width:50px;height: 50px;" src="<?= base_url('assets_admin/images/file-icons/docx-icon.png'); ?>" class="" alt="avatar">

                                <?php }else if(isset($value['name']) && strtolower(end($name_file_arr)) == 'pdf' )
                                { 
                                    ?>
                                    
                                <img style="width:50px;height: 50px;" src="<?= base_url('assets_admin/images/file-icons/pdf-icon.png'); ?>" class="" alt="avatar">
                              <?php   } ?>
                            </span>
                            </td>
                          <td><?php echo $value['status']; ?></td>
                          <td><input type="text" name="documents_order[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>" value="<?php echo $value['file_order']; ?>"> </td>
                        </tr>
                    <?php
                    } 
                      }
                    ?>
                  </form>
                </tbody>
              </table>
              <button type="button" id="documents_order" class="btn btn-primary">Update </button>
            </div>
          </div>
          <div role="tabpanel" class="tab-pane fade " id="tab_content3" aria-labelledby="home-tab">
            <div class="panbody">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Document</th>
                    <th>Status</th>
                    <th>Order</th>
                  </tr>
                </thead>
                <tbody>
                <form method="post" name="documents_order" id="test_documents_form">
                  <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                  <?php
                  if(isset($test_documents_array) && !empty($test_documents_array)){ 
                      $i = 0;
                      // $item_id = $this->uri->segment(3);
                    foreach ($test_documents_array as $value) 
                    {
                      $i++;
                  ?>
                      <tr>
                        <td scope="row"><?php echo $i; ?></td>
                        <td>
                          <span class="image" style="cursor: pointer;" data-toggle="modal" data-target="#ozDocModel" onclick="openDocumentViewer(this);" data-path="<?= base_url('uploads/items_documents').'/'.$item_row[0]['id'].'/'.$value['name'];?>">
                              <?php 
                              $name_file_arr = explode('.', basename($value['name']));
                              if(isset($value['name']) && (strtolower(end($name_file_arr)) == 'docx' || strtolower(end($name_file_arr)) == 'doc') )
                              {
                                  ?>

                              <img style="width:50px;height: 50px;" src="<?= base_url('assets_admin/images/file-icons/docx-icon.png'); ?>" class="" alt="avatar">

                              <?php }else if(isset($value['name']) && strtolower(end($name_file_arr)) == 'pdf' )
                              { 
                                  ?>
                                  
                              <img style="width:50px;height: 50px;" src="<?= base_url('assets_admin/images/file-icons/pdf-icon.png'); ?>" class="" alt="avatar">
                            <?php   } ?>
                          </span>
                          </td>
                        <td><?php echo $value['status']; ?></td>
                        <td><input type="text" name="test_documents_order[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>" value="<?php echo $value['file_order']; ?>"> </td>
                      </tr>
                  <?php
                  } 
                    }
                  ?>
                </form>
                </tbody>
              </table>
              <button type="button" id="test_documents_order" class="btn btn-primary">Update </button>
            </div>
          </div>

          <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
            <div class="panebody">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>Order</th>
                  </tr>
                </thead>
                <tbody>
                <form method="post" name="images_order" id="image_form">
                  <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                  <?php
                  if(isset($files_array) && !empty($files_array)){ 
                      $i = 0;
                      // $item_id = $this->uri->segment(3);
                    foreach ($files_array as $value) 
                    {
                      // print_r($value['name']);
                      $i++;
                  ?>
                      <tr>
                        <td scope="row"><?php echo $i; ?></td>
                        <td>
                          <span class="image">
                             <a href="#" class="pop"> 
                              <img id="myImg" style="width:50px;height: 50px;" src="<?php echo base_url().'uploads/items_documents/'.$item_id.'/'.$value['name'] ?>" class="" alt="<?php echo $value['name']; ?>" title="<?php echo $value['name']; ?>">
                             </a>
                          </span>
                          </td>
                        <td><?php echo $value['status']; ?></td>
                        <td>
                          <input type="text" name="images_orders[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>" value="<?php echo $value['file_order']; ?>"> 
                        </td>
                      </tr>
                  <?php
                  } 
                    }
                  ?>
                </form>
                </tbody>
              </table>
              <button type="button" id="images_order" class="btn btn-primary">Update </button>
            </div>
          </div>
          <!-- <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
            <p>xxFood truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui
              photo booth letterpress, commodo enim craft beer mlkshk </p>
          </div> -->
        </div>
      </div>
    </div>
  </div>
</div>
      

<div class="modal fade bs-example-modal-docViewer" id="ozDocModel" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Document Viewer</h4>
      </div>
      <div id="DivIdToPrintDoc" class="modal-docViewer-body container-fluid">
        <div class="col-md-10">
         <iframe id="viewer" frameborder="0" scrolling="no" width="400" height="600" class="docUrl" src=""></iframe>
        </div>
      </div>
      <div class="modal-footer">
      
      </div>
    </div>
  </div>
</div>

<div class="modal fade bs-example-modal-banner" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Banner</h4>
      </div>
      <div id="DivIdToPrintBanner" class="modal-banner-body ">
        
      </div>
      <div class="modal-footer">
        <a id="link" target="_blank" href="<?php echo base_url('auction/get_print_tab/'."$auction_id"."/"."$item_id");?>" type="button" class="btn btn-info btn-xs"><i class="fa fa-print"></i>Print</a> 
      <!-- <button type="button" id="<?php echo $value['id']; ?>" onclick="printBannerDiv();" class="btn btn-info btn-xs"><i class="fa fa-print"></i> Print</button> -->
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

<div class="modal fade bs-example-modal-item_edit" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div id="DivIdToItemEdit" class="modal-item-edit-body container-fluid">
        
      </div> 
    </div>
  </div>
</div>

<div class="modal fade bs-example-modal-item_document" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close item_document_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div id="DivIdToItemDocument" class="modal-item-Document-body container-fluid">
        
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

    <!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.js"></script>

<script>
  var token_name = '<?= $this->security->get_csrf_token_name();?>';
  var token_value = '<?=$this->security->get_csrf_hash();?>';
  
  var base_url = '<?php echo base_url(); ?>';
  var url = '<?php echo base_url();?>';
  var itemId = '<?php echo $item_id;?>';
  var auction_id = '<?php echo $auction_id;?>';

  $('.item_document_close').on('click', function(){
      location.reload();
    });


    function openDocumentViewer(e){

      var docpath = $(e).data('path');
      var filePath = 'https://docs.google.com/viewer?url='+docpath+'&embedded=true';
      console.log(filePath);
       // https://docs.google.com/gview?url="url"&embedded=true
      $('.docUrl').attr('src', filePath);
    }
    
$('.inner_bidding_model').on('click', function(){
  $(".bs-example-modal-sm").modal("hide");
  })

$('.inner_lotting_model').on('click', function(){
  $(".bs-example-modal-sm_lot").modal("hide");
  })

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

  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

  newWin.document.close();

  //setTimeout(function(){newWin.close();},10);

}
  Galleria.loadTheme(base_url+'assets_admin/vendors/galleria/themes/classic/galleria.classic.min.js');
      $('.galleria').galleria({
          width: 355,
          height: 334,
          autoplay: 5000,
          preload: 1,
          lightbox: true,
          background: '#ffffff',
          TIMEOUT: 300000

      });


 $('.oz_banner_pr').on('click',function(){
         // alert('clicker');
    var url = '<?php echo base_url();?>';
    var id = $(this).attr("id");
    console.log(id);
     $.ajax({
      url: base_url + 'auction/get_banner_details',
      type: 'POST',
      data: {id:id,auction_id:auction_id, [token_name]:token_value},
      beforeSend: function(){

         $('.modal-banner-body').html('<img src="'+base_url+'assets_admin/images/loading.gif" align="center" />');
      },
      }).then(function(data) {
        var objData = jQuery.parseJSON(data);
        // console.log(objData);
        $('#add_items').hide(); 
        if (objData.msg == 'success') 
        {
          $('.modal-banner-body').html(objData.data);
        }
      });
  });

 $('.oz_edit_item').on('click',function(){
         
    var url = '<?php echo base_url();?>';
    var id = $(this).attr("id");
    console.log(id);
     $.ajax({
      url: base_url + 'items/edit_item_detail_view',
      type: 'POST',
      data: {id:id, [token_name]:token_value},
      beforeSend: function(){
        $('.modal-item-edit-body').html('<img src="'+url+'assets_admin/images/loading.gif" align="center" />');
      },
      }).then(function(data) {
        var objData = jQuery.parseJSON(data);
        console.log(objData);
        $('#add_items').hide(); 
        if (objData.msg == 'success') 
        {
          $('.modal-item-edit-body').html(objData.data);
        }
      });
  });

 $('.oz_edit_document').on('click',function(){
         
    var url = '<?php echo base_url();?>';
    var id = $(this).attr("id");
    // console.log(id);
     $.ajax({
      url: base_url + 'items/item_details_documents',
      type: 'POST',
      data: {id:id, [token_name]:token_value},
      }).then(function(data) {
        var objData = jQuery.parseJSON(data);
        console.log(objData);
        $('#add_items').hide(); 
        if (objData.msg == 'success') 
        {
          $('.modal-item-Document-body').html(objData.data);
        }
      });
  });

 $('.oz_print_qr').on('click',function(){
         
    var url = '<?php echo base_url();?>';
    var id = $(this).attr("id");
    console.log(id);
     $.ajax({
      url: base_url + 'items/get_qrcode',
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
  });



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

    const imagebutton = document.querySelector('#images_order');
    const docbutton = document.querySelector('#documents_order');
    imagebutton.addEventListener('click', sort_order);
    docbutton.addEventListener('click', sort_order_docs);

    function sort_order()
    {
             var id = itemId;
             var formData = $('#image_form').serialize();
               $.ajax({
                url: base_url + 'items/update_files_order',
                type: 'POST',
                data: formData,
                }).then(function(data) {
                  var objData = jQuery.parseJSON(data);
                if (objData.msg == 'success') 
                {
                  
                  new PNotify({
                                  title: 'Success',
                                  text: ""+objData.response+"",
                                  type: 'success',
                                  addclass: 'custom-success',
                                  styling: 'bootstrap3'
                              });
                }
                else
                {
                   if(objData.response != '')
                   {
                    new PNotify({
                                  title: 'Error!',
                                  text: ""+objData.response+"",
                                  type: 'error',
                                  addclass: 'custom-error',
                                  styling: 'bootstrap3'
                              }); 
                   }
                   
                }
                window.setTimeout(function() 
              {
                $(".alert").fadeTo(500, 0).slideUp(500, function()
                {
                    $(this).remove(); 
                });
              }, 4000);
            });
    }
     function sort_order_docs()
    {
             var id = itemId;
             var formData = $('#documents_form').serialize();
               $.ajax({
                url: url + 'items/update_files_order',
                type: 'POST',
                data: formData,
                }).then(function(data) {
                  var objData = jQuery.parseJSON(data);
                if (objData.msg == 'success') 
                {
                 // $("#result").html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.response + '</div></div>');
                  new PNotify({
                                  title: 'Success',
                                  text: ""+objData.response+"",
                                  type: 'success',
                                  addclass: 'custom-success',
                                  styling: 'bootstrap3'
                              });
                }
                else
                {
                   //$("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.response + '</div></div>');
                     if(objData.response != '')
                   {
                    new PNotify({
                                  title: 'Error!',
                                  text: ""+objData.response+"",
                                  type: 'error',
                                  addclass: 'custom-error',
                                  styling: 'bootstrap3'
                              }); 
                   }
                }
                window.setTimeout(function() 
              {
                $(".alert").fadeTo(500, 0).slideUp(500, function()
                {
                    $(this).remove(); 
                });
              }, 4000);
                 
            });
    }

    // item document settings


   
    $(document).ready(function() {
        // You can use the locally-scoped $ in here as an alias to jQuery.
   
            $("#send").on('click', function(e) { //e.preventDefault();
                    
                    $('#send').text('Loading..');
                    $('#send').attr('disabled',true);
                            dz1.newId = itemId;
                            dz2.newId = itemId;
                            dz1.processQueue();
                            dz2.processQueue();  
                if (dz1.getUploadingFiles().length === 0 && dz2.getQueuedFiles().length === 0) 
                {
                    console.log('all done');
                }
            });
 
  });

  </script>
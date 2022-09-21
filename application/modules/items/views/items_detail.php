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
            $item_id = base64_decode(urldecode($this->uri->segment(3))); 
            $role = $this->session->userdata('logged_in')->role; 
            $have_documents = false;
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
            ?>
            <a type="button" href="<?php echo base_url().'items'; ?>"  class="btn btn-info"><i class="fa fa-arrow-left"></i> Back Item List</a>

            <div class="all_buttons navbar-right">
                <?php if ($item_row) {?>
                    <?php if($role == 1 && $item_row[0]['sold'] == 'no'){ ?>
                        <!-- <a href="<?php //echo base_url().'items/update_item/'.$item_id; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a> -->
                        <!-- <button type="button" id="<?php echo $item_id; ?>" data-toggle="modal" data-target=".bs-example-modal-item_edit"  data-backdrop="static" data-keyboard="true" class="btn btn-info btn-xs oz_edit_item"><i class="fa fa-pencil"></i> Edit</button> -->
                        <a type="button" class="btn btn-info btn-xs oz_edit_item" href="<?php echo base_url('items/update_item/'.$item_id);?>"><i class="fa fa-pencil"></i> Edit</a>
                    <?php } ?>
                <?php }?>
                <!--  <button type="button" id="<?php echo $item_id; ?>" data-toggle="modal" data-target=".bs-example-modal-item_document"  data-backdrop="static" data-keyboard="true" class="btn <?php echo  $documents_class; ?> btn-xs oz_edit_document"><i class="fa fa-pencil"></i> Documents</button> -->
                <a type="button" class="btn btn-info btn-xs oz_edit_item" href="<?php echo base_url('items/documents/'.$item_id);?>"><i class="fa fa-pencil"></i> Documents</a>
            
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
                <p class="lead"><?php if(isset($item_row[0]['name']) && !empty($item_row[0]['name'])) { $item_name = json_decode($item_row[0]['name']); echo $item_name->english; }else { echo 'Product'; } ?></p>
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
                    <?php if(isset($item_row[0]['detail']) && !empty($item_row[0]['detail'])) { $item_detail = json_decode($item_row[0]['detail']); echo $item_detail->english; }else{ echo ''; } ?>
                </p>

                </div>
                <!-- /.col -->
                <div class="col-xs-6">
                  <p class="lead">Created Date: <?php echo (isset($item_row[0]['created_on']) && !empty($item_row[0]['created_on'])) ? date('d F Y',strtotime($item_row[0]['created_on'])) : '' ?></p>
                  <div class="table-responsive">
                    <table class="table">
                      <tbody>
                        <tr>
                          <th style="width:50%">Created By:</th>
                          <td><?php if (isset($item_row[0]['created_by']) && !empty($item_row[0]['created_by'])) { $creater = $this->db->get_where('users', ['id' => $item_row[0]['created_by']])->row_array(); echo $creater['username']; } else { echo 'N/A'; } ?></td>
                        </tr>
                        <tr>
                          <th style="width:50%">Updated On:</th>
                          <td><?php echo (isset($item_row[0]['updated_on']) && !empty($item_row[0]['updated_on'])) ? date('d F Y', strtotime($item_row[0]['updated_on'])) : 'N/A' ?></td>
                        </tr>
                        <tr>
                          <th style="width:50%">Updated By:</th>
                          <td><?php if(isset($item_row[0]['updated_by']) && !empty($item_row[0]['updated_by'])) { $updater = $this->db->get_where('users', ['id' => $item_row[0]['updated_by']])->row_array(); echo $updater['username']; } else { echo 'N/A'; } ?></td>
                        </tr>
                        <tr>
                          <th style="width:50%">Reserve Price:</th>
                          <td><?php echo (isset($item_row[0]['price']) && !empty($item_row[0]['price'])) ? $item_row[0]['price'] : '' ?> AED</td>
                        </tr>
                        <?php if(!empty($item_row[0]['registration_no'])) : ?>
                          <tr>
                            <th style="width:50%">Registration No:</th>
                            <td><?php echo (isset($item_row[0]['registration_no']) && !empty($item_row[0]['registration_no'])) ? $item_row[0]['registration_no'] : '' ?></td>
                          </tr>
                        <?php endif;
                        if(!empty($item_row[0]['make_name'])) : ?>
                          <tr>
                            <th style="width:50%">Make:</th>
                            <td><?php echo (isset($item_row[0]['make_name']) && !empty($item_row[0]['make_name'])) ? json_decode($item_row[0]['make_name'])->english : '' ?> </td>
                          </tr>
                        <?php endif;
                        if(!empty($item_row[0]['model_name'])) : ?>
                          <tr>
                            <th style="width:50%">Model:</th>
                            <td><?php echo (isset($item_row[0]['model_name']) && !empty($item_row[0]['model_name'])) ? json_decode($item_row[0]['model_name'])->english: '' ?> </td>
                          </tr>
                        <?php endif;?>
                        <?php 
                            $role = $this->session->userdata('logged_in')->role; 
                            $CI =& get_instance(); 
                         ?>
                        <?php if(!empty($item_row[0]['lot_no'])) : ?>
                          <tr>
                            <th style="width:50%">Lot:</th>
                            <td><?php echo (isset($item_row[0]['lot_no']) && !empty($item_row[0]['lot_no'])) ? $item_row[0]['lot_no'] : '' ?> </td>
                          </tr>
                        <?php endif; ?>
                        <?php if ($item_row) {?>
                        <?php 
                           $dynamic_values_array =  $CI->items_model->get_itemfields_byItemid($item_row[0]['id']);
                           if(isset($dynamic_values_array) && !empty($dynamic_values_array)){
                            foreach ($dynamic_values_array as $value) {
                              $value['value'] = str_replace(array( '[', ']' ), '', $value['value']);
                           $dynamic_fields_array =  $CI->items_model->fields_data_by_id($value['fields_id']);
                           if (!empty($dynamic_fields_array)) {
                            // print_r($dynamic_fields_array);
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
                          <td><?php 
                          
                            echo (isset($existed_values) && !empty($existed_values)) ? implode(",",$existed_values) : ''; ?> </td>
                      <?php }else{ ?>
                          <td><?php
                          // print_r($dynamic_fields_array);
                          if ($dynamic_fields_array[0]['values'] != '' && $dynamic_fields_array[0]['type'] != 'checkbox-group') {
                            $val = json_decode($dynamic_fields_array[0]['values'], true);
                            foreach ($val as $key => $DDval) {
                              if ($DDval['value'] == $value['value']) :
                                echo $DDval['label'];
                              endif;
                            }
                          } else {
                           echo (isset($value['value']) && !empty($value['value'])) ? $value['value'] : ''; } ?> </td>
                  <?php } ?>
                        </tr>
                        <?php } } } ?>
                      <?php }?>
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
                    <li role="presentation" class=""><a href="#expenses_content" role="tab" id="expenses-tab" data-toggle="tab" aria-expanded="false">Item Expenses</a>
                    </li>
                    <!--  <li role="presentation" class=""><a href="#tab_content4" role="tab" id="charges-tab" data-toggle="tab" aria-expanded="false">Other Charges</a>
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
                                <input type="hidden" id="csrf_hash" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
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

                    <div role="tabpanel" class="tab-pane fade " id="expenses_content" aria-labelledby="expenses-tab">
                        <div class="panbody">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Apply Vat</th>
                                <th>Created On</th>
                              </tr>
                            </thead>
                            <tbody>
                            <form method="post" name="documents_order" id="test_documents_form">
                              <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                              <?php
                              if(isset($item_expencses) && !empty($item_expencses)){ 
                                  $i = 0;
                                  // $item_id = $this->uri->segment(3);
                                foreach ($item_expencses as $value) 
                                {
                                  $i++;
                              ?>
                                  <tr>
                                    <td scope="row"><?php echo $value['title']; ?></td>
                                    <td scope="row"><?php echo $value['amount']; ?></td>
                                    
                                    <td><?php echo ucfirst($value['apply_vat']); ?></td>
                                    <td><?php echo ucfirst($value['created_on']); ?></td>
                                  </tr>
                              <?php
                              } 
                                }
                              ?>
                            </form>
                            </tbody>
                          </table>
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

                    <!-- <div role="tabpane4" class="tab-pane fade" id="tab_content4" aria-labelledby="profile-tab">
                        <div class="panebody">
                         <form method="post" name="otherChargesForm" id="otherChargesForm">
                            <div class="form-group">
                              <label>Other Charges: </label>
                              <input type="number" name="charges" id="" value="<?php //echo (isset($item_row[0]['other_charges']) && !empty($item_row[0]['other_charges'])) ? $item_row[0]['other_charges'] : ''; ?>"> 
                              <input type="hidden" name="id" value="<? //= (isset($item_row[0]['id']) && !empty($item_row[0]['id'])) ? $item_row[0]['id'] : ''?>">
                            </div>
                           </form>
                          <button type="button" id="other_charges" class="btn btn-primary">Update </button>
                        </div>
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
            <div id="DivIdToPrintBanner" class="modal-banner-body">
              
            </div>
            <div class="modal-footer">
            <button type="button"  id="<?php if(isset($value['id'])) {echo $value['id'];}  ?>" onclick="printBannerDiv();" class="btn btn-info btn-xs"><i class="fa fa-print"></i> Print</button>
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
            <button type="button" id="<?php if(isset($value['id'])) {echo $value['id'];}  ?>" onclick="printDiv();" class="btn btn-info btn-xs"><i class="fa fa-print"></i> Print</button>
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

    <!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.js"></script>

  <script>
    var base_url = '<?php echo base_url(); ?>';
    var url = '<?php echo base_url();?>';
    let itemId = '<?php echo $item_id;?>';

  $('.item_document_close').on('click', function(){
      location.reload();
    });

function printBannerDiv() 
{

  var divToPrintBanner=document.getElementById('DivIdToPrintBanner');

  var newWin=window.open('','Print-Window');

  newWin.document.open();

  newWin.document.write('<html><body onload="window.print()">'+divToPrintBanner.innerHTML+'</body></html>');

  newWin.document.close();

  setTimeout(function(){newWin.close();},10);

}

function printDiv() 
{

  var divToPrint=document.getElementById('DivIdToPrint');

  var newWin=window.open('','Print-Window');

  newWin.document.open();

  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

  newWin.document.close();

  setTimeout(function(){newWin.close();},10);

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
    var csrf_hash = $('#csrf_hash').val();
    console.log(id);
     $.ajax({
      url: base_url + 'items/get_banner_details',
      type: 'POST',
      data: {id:id, csrf_test_name:csrf_hash},
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
    var csrf_hash = $('#csrf_hash').val();
    console.log(id);
     $.ajax({
      url: base_url + 'items/edit_item_detail_view',
      type: 'POST',
      data: {id:id, csrf_test_name:csrf_hash},
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
    var csrf_hash = $('#csrf_hash').val();
    // console.log(id);
     $.ajax({
      url: base_url + 'items/item_details_documents',
      type: 'POST',
      data: {id:id, csrf_test_name:csrf_hash},
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
    var csrf_hash = $('#csrf_hash').val();
    console.log(id);
     $.ajax({
      url: base_url + 'items/get_qrcode',
      type: 'POST',
      data: {id:id, csrf_test_name:csrf_hash},
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

    const imagebutton = document.querySelector('#images_order');
    const chargesBtn = document.querySelector('#other_charges');
    const docbutton = document.querySelector('#documents_order');
    const testdocbutton = document.querySelector('#test_documents_order');
    imagebutton.addEventListener('click', sort_order);
    docbutton.addEventListener('click', sort_order_docs);
    testdocbutton.addEventListener('click', sort_order_test_docs);
    chargesBtn.addEventListener('click', updateCharges);

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
    function updateCharges()
    {
             var id = itemId;
             var formData = $('#otherChargesForm').serialize();
               $.ajax({
                url: base_url + 'items/updateOtherCharges',
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
    function sort_order_test_docs()
    {
             var id = itemId;
             var formData = $('#test_documents_form').serialize();
               $.ajax({
                url: url + 'items/update_files_order',
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

    // item document settings

    function openDocumentViewer(e){

      var docpath = $(e).data('path');
      var filePath = 'https://docs.google.com/viewer?url='+docpath+'&embedded=true';
      console.log(filePath);
       // https://docs.google.com/gview?url="url"&embedded=true
      $('.docUrl').attr('src', filePath);
    }
   
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
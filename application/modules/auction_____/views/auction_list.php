<style type="text/css">
    #page-wrapper {
        margin-top: 100px;
    }
</style>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo $small_title; ?></h2>
            <div class="clearfix"></div>
        </div>

        <?php if($this->session->flashdata('msg')){ ?>
            <div class="alert">
                <div class="alert alert-success alert-dismissible fade in" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black;"><span aria-hidden="true">×</span>
                  </button>
                  <?php  echo $this->session->flashdata('msg');   ?>
                </div>
            </div>
        <?php }?>
        <div class="clearfix"></div>
    <div>
    <div class="clearfix"></div>

    <div class="x_content">
        <div id="result"></div>
        <a type="button" href="<?php echo base_url().'auction/save_auction'; ?>"  class="btn btn-success"><i class="fa fa-plus"></i> Add</a>
        <form method="post" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left ">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
            <div class="form-group ">   
                <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
                    <select class="form-control col-md-7 col-xs-12" id="category_id" name="category_id" >
                        <option value="">Select Category</option>
                        <?php foreach ($category_list as $category_value) { 
                            $auctionInfoo = json_decode($category_value['title']); ?>
                            <option value="<?php echo $category_value['id']; ?>"><?php echo $auctionInfoo->english; ?></option>
                        <?php } ?> 
                    </select>
                </div> 

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
            </div>
             
            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-1">
                    <button type="button" id="send" class="btn btn-info">Filter</button>
                </div>
            </div>
        </form>
    </div>
    <div class="clearfix"></div>
        
    <div class="x_content"><br><hr>
        <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="auctions" data-url="<?php echo base_url(); ?>auction/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete Selected Rows</button>
    </div>
    <div class="clearfix"></div>

    <div class="x_content">
        <div id="content_auction_items" class="">
            <?php if(!empty($auction_list)){ ?>
                <table id="datatable-responsive_2" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Auction Type</th>
                            <th>Auction Code</th>
                            <th>Auction Category</th>
                            <th>Start Date</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                            <th data-orderable="false">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                        $j = 0;
                        $role = $this->session->userdata('logged_in')->role; 
                        $CI =& get_instance();
                        foreach($auction_list as $key => $value){
                            $j++;
                            $b64_auction_id = urlencode(base64_encode($value['id']));
                            $result_if_already_item_ids = array();
                            $result_if_already = $CI->auction_model->get_auction_item_ids($value['id']);
                            foreach ($result_if_already as $key => $item_id) {
                              $result_if_already_item_ids[] = $item_id['item_id'];
                            }
                            $result_if_already_implode = implode(',', $result_if_already_item_ids);
                            // print_r($result_if_already_item_ids);die();
                            if(isset($result_if_already) && !empty($result_if_already))
                            {
                                $if_items = 'btn-success';
                            }else{
                                $if_items = 'btn-warning';
                            }

                            //title functionality
                            $title= json_decode($value['title']);
                            ?>
                            <tr id="row-<?php echo  $value['id']; ?>">
                                <td><?php echo ucwords($title->english); ?></td>
                                <td><?php echo (isset($value['access_type']) && !empty($value['access_type'])) ? ucfirst($value['access_type']) : ''; ?></td>        
                                <td><?php echo (isset($value['registration_no']) && !empty($value['registration_no'])) ? $value['registration_no'] : ''; ?></td>
                                <td><?php $auctionInfoo = json_decode($value['category_name']); echo $auctionInfoo->english ?></td>
                                <td><?php echo (isset($value['start_time']) && !empty($value['start_time'])) ? $value['start_time'] : ''; ?></td>
                                <td><?php echo (isset($value['expiry_time']) && !empty($value['expiry_time'])) ? $value['expiry_time'] : ''; ?></td>
                                <td><?php echo ucfirst($value['status']); ?></td>
                                
                                <td>
                                    <a href="<?php echo base_url().'auction/update_auction/'.$value['id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                                    <?php if ($value['expiry_time'] > date('Y-m-d H:i:s')) : ?>
                                        <button type="button" onclick="oz_stock_list(this)" id="<?php echo $value['id']; ?>" data-toggle="modal" data-target=".bs-example-modal-sm2"  data-backdrop="static" data-keyboard="true" class="btn btn-info btn-xs oz_stock_list"><i class="fa fa-plus"></i> Add Item</button>
                                    <?php endif; ?>

                                    <a href="<?php echo base_url().'auction/items/'.$b64_auction_id; ?>" class="btn <?php echo  $if_items; ?> btn-xs"><i class="fa fa-search"></i> View Items</a>

                                    <?php if($if_items == 'btn-success'){ ?>
                                      <button type="button" id="<?= $value['id']; ?>" item-id="<?php echo $result_if_already_implode ?>" data-toggle="modal" onclick="getBanner(this);" data-target=".bs-example-modal-banner"  data-backdrop="static" data-keyboard="true" class="btn btn-info btn-xs oz_banner_pr"><i class="fa fa-file"></i> Banner</button>
                                    <?php } ?>

                                    <button type="button" id="<?= $value['id']; ?>" item-id="<?php echo $result_if_already_implode ?>" data-toggle="modal" onclick="getlots(this);" data-target=".bs-example-modal-lot"  data-backdrop="static" data-keyboard="true" class="btn btn-info btn-xs"><i class="fa fa-file"></i> Lot Info</button>

                                    <?php if(($role == 1) && ($if_items == 'btn-warning')){ ?>
                                        <button onclick="deleteRecord(this)" type="button" data-obj="auctions" data-token-name="<?= $this->security->get_csrf_token_name();?>" data-token-value="<?=$this->security->get_csrf_hash();?>" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>auction/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } //end foreach ?>
                    </tbody>
                </table>
            <?php }else{
                echo "<h1>No Record Found</h1>";
            } ?>
        </div>
    </div>
    <div class="clearfix"></div>
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
             <!-- <button type="button" id="<?php //echo $value['id']; ?>" onclick="printBannerDiv();" class="btn btn-info btn-xs"><i class="fa fa-print"></i> Print</button> -->
             <a id="links" target="_blank" href="<?php echo base_url('auction/get_auction_bannerPrintDetail');?>" type="button" class="btn btn-info btn-xs"><i class="fa fa-print"></i>Print</a> 
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade bs-example-modal-lot" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Lot</h4>
            </div>
            <div id="DivIdToPrintlot" class="modal-lot-body">
            
            </div>
          <div class="modal-footer">
             <!-- <button type="button" id="<?php //echo $value['id']; ?>" onclick="printLotDiv();" class="btn btn-info btn-xs"><i class="fa fa-print"></i> Print</button> -->
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

<script type="text/javascript">
  var token_name = '<?= $this->security->get_csrf_token_name();?>';
  var token_value = '<?=$this->security->get_csrf_hash();?>';
    

  $('#datefrom').datetimepicker({
      format: 'YYYY-MM-DD'
  });

  $('#dateto').datetimepicker({
      format: 'YYYY-MM-DD'
  });

    var url = "<?php echo base_url(); ?>"; 
    var datatable = $('#datatable-responsive_2').DataTable({
            responsive: true,
            columnDefs: [ 
              { targets:"_all" },
              { targets:[7], className: "tablet, mobile" }
            ]
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

    function add_auction_items(){

      var ids_list = $("#item_ids_list").val();
      var url = '<?php echo base_url();?>';
      // var auction_id = '<?php //echo $auction_id;?>';
      var auction_id = $("#auction_id").val();
      var category_id = $("#cat_id").val();
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
        }
      });
    }
    $('#datatable-responsive_3').DataTable();
      // $('.oz_stock_list').on('click',function(){
    function oz_stock_list(e) {
      var url = '<?php echo base_url();?>';
      var id = $(e).attr("id");
      console.log(id);
      $.ajax({
        url: url + 'auction/get_stock_list',
        type: 'POST',
        data: {id:id, [token_name]:token_value},
      }).then(function(data) {
        var objData = jQuery.parseJSON(data);
        console.log(objData);
        $('#add_items').hide(); 
        if (objData.msg == 'success') 
        {
          $('.modal-stock-body').html(objData.data);
        }

      });
    // });
    }

    var formaction_path = '<?php echo $formaction_path;?>';
    $("#send").on('click', function(e) { //e.preventDefault();
      var formData = new FormData($("#demo-form2")[0]);
      $.ajax({
        url: url + 'auction/' + formaction_path,
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false
      }).then(function(datas) {
        var objData = jQuery.parseJSON(datas);
        if (objData.msg == 'success'){ 
          $('#content_auction_items').html(objData.data);
          // $('#datatable-responsive_2').dataTable().reload();
        }else{
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
  var href = $('#links').attr('href');
  function getBanner(e){
    var url = '<?php echo base_url();?>';
    var id = $(e).attr("id");
    var item_id = $(e).attr("item-id");
    console.log(id);
     $.ajax({
      url: url + 'auction/get_auction_banner_details',
      type: 'POST',
      data: {id:id,item_id:item_id, [token_name]:token_value},
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
          $('#links').attr('href' , href + '/'+ objData.auction_id + '/' + objData.item_id);
        }
      });
  }

  function getlots(e){
    var url = '<?php echo base_url();?>';
    var id = $(e).attr("id");
    var item_id = $(e).attr("item-id");
    console.log(id);
     $.ajax({
      url: url + 'auction/get_auction_lot_listing',
      type: 'POST',
      data: {id:id,item_id:item_id, [token_name]:token_value},
      beforeSend: function(){

        $('.modal-lot-body').html('<img src="'+url+'assets_admin/images/loading.gif" align="center" />');
      },
      }).then(function(data) {
        var objData = jQuery.parseJSON(data);
        // console.log(objData);
        if (objData.msg == 'success') 
        {
          $('.modal-lot-body').html('');
          $('.modal-lot-body').html(objData.data);
        }
      });
  }
</script>
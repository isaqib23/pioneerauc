<style type="text/css">
    #page-wrapper {
      margin-top: 100px;
    }

</style>
<div class="clearfix"></div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      
        <div class="x_title">
            <h2>
              <?php echo $small_title; ?>
            </h2>
            <div class="clearfix"></div>
        </div>
        <div> 
            <a type="button" href="<?php echo base_url().'auction/live'; ?>"  class="btn btn-info"><i class="fa fa-arrow-left"></i> Back Auction List</a>
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
        <h4>Filter Record: </h4>

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
                <option value="cancelled">Cancelled</option>
              </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-1">
                <button type="button" id="send" class="btn btn-info">Filter</button>
            </div>
        </div>
    </form>
    </div>
        <div id="result"></div>
        <div class="ln_solid"></div>
        <div class="clearfix"></div> 
       
        <?php if ($auction_expiry_time > date('Y-m-d H:i:s')) : ?>
            <button type="button" id="<?php echo urlencode(base64_encode($auction_id)); ?>" data-toggle="modal" data-target=".bs-example-modal-sm2"  data-backdrop="static" data-keyboard="false" class="btn btn-success btn-sm oz_stock_list"><i class="fa fa-plus"></i> Add Item</button>
        <?php endif; ?>
        <div class="x_content">
            <br>
            <hr>
			<?php if(!empty($items_list)){?>
			<a href="#" data-toggle="modal" data-target="#lootingModal" class="btn btn-primary">Bulk Looting</a>
			<?php } ?>
            <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="item_category" data-url="<?php echo base_url(); ?>items/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete Selected Rows</button>
        </div>

        <input type="hidden" id="item_buyer_id">
        <input type="hidden" id="auction_item_id">
        <div class="x_content content_items_inner">
            <?php if(!empty($items_list)){?>
                <table id="datatable-responsive" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th data-visible="false" data-orderable="false"></th>
                            <th data-orderable="false"></th>
                            <!-- <th>Image</th> -->
                            <th>Name</th>
                            <th>Category</th>
                            <th>Registration #</th>
                            <th>Lot #</th>
                            <th data-orderable="false">Make</th>
                            <th data-orderable="false">Model</th>
                            <th>Reserve</th>
                            <th>Bid Amount</th>
                            <th>Created On</th>
                            <th>Sold Status</th>
                            <th data-orderable="false">Buyer</th>
                            <th data-orderable="false">Seller</th>
                            <!-- <th>Updated On</th> -->
                            <th>Status</th>
                            <th data-orderable="false" class="tablet mobile">Actions</th>
                        </tr>
                    </thead>
                </table>
            <?php 
            }else{
                echo "<h3>Items Not added yet!</h3>";
            }
            ?>
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
           <a id="link" target="_blank" href="<?php echo base_url('auction/get_print_tab/'."$auction_id");?>" type="button" class="btn btn-info btn-xs"><i class="fa fa-print"></i>Print</a> 
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

       <div class="modal fade bs-example-modal-sm_blink" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width:750px; ">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close inner_blink_model" title="close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Screen 1 Blinking Text</h4>
            </div>
            <div class="modal-body_blinking">
            </div>
          </div>
        </div>
      </div>

		<div class="modal fade bs-example-modal-sm_blink" tabindex="-1" role="dialog" aria-hidden="true" id="lootingModal">
	<div class="modal-dialog modal-lg" style="max-width:750px; ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close inner_blink_model" title="close"><span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title" id="Looting">Bulk Looting</h4>
			</div>
			<div class="modal-body_blinking">
				<form style="text-align: center" method="post" id="lootingForm">
					<table class="table table-bordered" style="width: 80%; margin: 0 auto">
						<thead>
							<tr>
								<th>Sr. #</th>
								<th>Name</th>
								<th>Lot #</th>
							</tr>
						</thead>
						<tbody>
						<?php if(!empty($items_list)){
							foreach ($items_list as $key => $val){
							?>
							<tr style="text-align: left">
								<td><?= $key+1 ?></td>
								<td>
									<div>
										<?=$val["thumb_nail"]?>
										<span><?=$val["itemName"]?></span>
									</div>
								</td>
								<input type="hidden" name="auction" value="<?=$auction_id?>">
								<input type="hidden" name="item[]" value="<?=$val["id"]?>">
								<td><input type="text" name="lot[]" value="<?=$val["order_lot_no"]?>"></td>
							</tr>
						<?php }} ?>
						</tbody>
					</table>
					<button type="submit" id="lootingBtn" class="btn btn-success btn-lg" style="margin-top: 20px; margin-bottom: 20px;"> Submit</button>
				</form>
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
        <div class="modal fade bs-example-modal-print" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel">QR Cnnnode</h4>
                </div>
                <div id="DivIdToPrint" class="modal-qr-body container-fluid">
                </div>
                <div class="modal-footer">
                <button type="button qr_class" id="" onclick="printDiv();" class="btn btn-info btn-xs"><i class="fa fa-print"></i> Print</button>
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

  $("#lootingForm").submit(function(event) {

	  event.preventDefault();
	  var formData = $(this).serialize();
	  var url = '<?php echo base_url();?>';
	  $.ajax({
		  type: 'post',
		  url: url + 'auction/update_bulk_lotting',
		  data: formData,
		  success: function (response) {
			  console.log(JSON.parse(response));
		  }
	  });

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

$('.inner_blink_model').on('click', function(){
  $(".bs-example-modal-sm_blink").modal("hide");
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

   // $('#datatable-responsive').DataTable({
   //    responsive: true,
   //          columnDefs: [ 
   //            { targets:"_all", orderable: false },
   //            { targets:[12], className: "tablet, mobile" }
   //          ]
   //  });

   // ajax base item list of live auction


  let exportOptions = {'rows': {selected: true} ,columns: ['2,3,4,5,6,7,8,9,10,11,12,13']};
  let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
  let dt1 = customeDatatable({
    // index zero must be csrf token
    '<?= $this->security->get_csrf_token_name();?>':'<?= $this->security->get_csrf_hash();?>',
    'div' : '#datatable-responsive',
    'url' : '<?=base_url()?>auction/auctionItemList/<?= $this->uri->segment(3)?>',
    'orderColumn' : 5,
    'iDisplayLength' : 10,
    'dom' : '<"pull-left top"B><"pull-right"lf><"clear">r<t><"bottom"ip><"clear">',
    'columnCustomeTypeTarget' : 5,
    'defaultSearching': true,
    'responsive': true,
    'rowId': 'id',
    'stateSave': true,
    'lengthMenu': [[ 10, 25, 50, 100, 150], [10, 25, 50, 100, 150]], // [5, 10, 25, 50, 100], [5, 10, 25, 50, 100]
    'order' : 'desc',
    'selectStyle' : 'multi',
    'collectionButtons' : dtButtons,
    'processing' : true,
    'serverSide' : true,
    'processingGif' : '<span style="display:inline-block; margin-top:-105px;">Loading.. <img  src="<?=base_url()?>/assets_admin/images/load.gif" /></span>',
    'buttonClassName' : 'btn-sm',
    'columnCustomType' : 'date-eu',
    'customFieldsArray': ['category_id','seller_id','seller_code','make_id','model_id','sale_person_id','vin_no','sold','item_status','keyword','registration_no','days_filter','datefrom','dateto'],
    'dataColumnArray': [{'data':'id'},{'data':'image'},{'data':'name'},{'data':'category_id'},{'data':'registration_no'},{'data':'order_lot_no'},{'data':'make'},{'data':'model'},{'data':'price'},{'data':'bid_amount'},{'data':'created_on'},{'data':'sold'},{'data':'buyer'},{'data':'seller'},{'data':'item_status'},{'data':'action'}]
  });


  $(document).ready(function(){
    $(".input-sm").css("margin-right", "35px");
  });

  var href = $('#link').attr('href');
  function getBanner(e){
         // alert('clicker');
    var url = '<?php echo base_url();?>';
    var auction_id = '<?php echo $auction_id;?>';
    var id = $(e).attr("id");
    $('.banner_class').attr("id", id);
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
          $('input[name="itemPrintId"]').val(objData.item_id);
          $('#link').attr('href' , href + '/'+ objData.item_id);
        }
      });
  };

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
               }
           });

    }

  function getQRcode(e){
         
    var url = '<?php echo base_url();?>';
    var id = $(e).attr("id");
    $('.qr_class').attr("id", id);
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
  };

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

  newWin.document.write('<html style="height: 80px;"><head><style type="text/css" media="print"> @page { size: auto;    margin: 0; }</style></head><body onload="window.print()" style="margin: 0;">'+divToPrint.innerHTML+'</body></html>');

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

     function getBlinking(auction_id,item_id){

              var url = '<?php echo base_url();?>';
              var auction_id = auction_id;
              var id = item_id;
               $.ajax({
                url: url + 'auction/get_blinking',
                type: 'POST',
                data: {id:id,auction_id:auction_id, [token_name]:token_value},
                beforeSend: function(){
                   $('.modal-body_blinking').html('<img src="'+url+'assets_admin/images/loading.gif" align="center" />');
                },
                }).then(function(data) {
                  var objData = jQuery.parseJSON(data);
                  // console.log(objData);
                  if (objData.msg == 'success') 
                  {
                    $('.modal-body_blinking').html(objData.data);
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

  
    function show_content(){


        show_items_content

    }

    var url = "<?php echo base_url(); ?>";
  var formaction_path = '<?php echo $formaction_path;?>';
  

  $("#send").on('click', function(e) { //e.preventDefault();
    var formData = new FormData($("#demo-form2")[0]);
    $.ajax({
      url: url + 'auction/' + formaction_path,
      type: 'POST',
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function(){

        $('.content_items_inner').html('<img src="'+url+'assets_admin/images/loading.gif" align="center" />');
      }
      }).then(function(data) {
        var objData = jQuery.parseJSON(data);
        if (objData.msg == 'success') 
        { 
          $('.content_items_inner').html('');
          $('.content_items_inner').html(objData.data);
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

 //Load buyer popup
  function selectbuyer(e){
    event.preventDefault();
    var url = '<?php echo base_url();?>';
    var auctionitem_id = $(e).attr('auctionitem_id');
    $('#auction_item_id').val(auctionitem_id);
    console.log('select buyer');
    $.ajax({ 
      type: "post", //create an ajax request to display.php
      url: url + "auction/load_user_popup",    
      data:{[token_name]:token_value},
      success: function(data) {
          objdata = $.parseJSON(data);
          if(objdata.success == true)
          {
            $('#data_table').html('');
            $('#data_table').html(objdata.opup_data);
            $('#users').modal({
              backdrop: 'static'
            })
            $('#users').modal();
          }
          else
          {
            // $('.make_case').hide();
            $('#model').attr('disabled',false);
            $('#model').html('<option value="">Select Model</option>');
          }
      }
    });
  }

  // add item buyer
  function update_item_buyer(){
    
    var auction_item_id = $('#auction_item_id').val();
    if (auction_item_id != '') {
      var item_buyer_id = $('#item_buyer_id').val();
      alert(auction_item_id);
      alert(item_buyer_id);
     // ajax for sale on approva live auction item
      $.ajax({
        url: "<?php echo base_url(); ?>" + "livecontroller/updateitembuyer",
        type: 'post',
        data: {auction_item_id: auction_item_id,item_buyer_id: item_buyer_id, [token_name]:token_value},
        success: function(data) {
          var objData = jQuery.parseJSON(data);
          console.log(objData);
          if(objData.error == false){
            location.reload();
          // getSoldItems();          
        }else{
      
        }
        }
      }); 
    }
  }

  function saleitem(e){
    var url = '<?php echo base_url('auction/sale_live_item/');?>';
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

</script>

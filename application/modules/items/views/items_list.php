<!-- <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/css/dataTables.checkboxes.css" rel="stylesheet" /> -->
<!-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script> -->

<style type="text/css">
    #page-wrapper {
        margin-top: 100px;
    }
div.dataTables_wrapper div.dataTables_processing{
  height: auto;
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
        <div class="x_content"></div>
          <?php if($this->session->flashdata('msg')){ ?>
        <div class="alert">
            <div class="alert alert-success alert-dismissible  in" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black;"><span aria-hidden="true">×</span>
              </button>
              <?php  echo $this->session->flashdata('msg');   ?>
          </div>
      </div>
    <?php }?>
    <div> 
      <h4>Filter Record: </h4>

    <form method="post" id="demo-form2" action="" enctype="" class="form-horizontal form-label-left ">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
        <div class="form-group ">   
            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
             <select class="form-control col-md-7 col-xs-12" id="category_id" name="category_id" >
                <option value="">Select Category</option>
                <?php foreach ($category_list as $category_value) { ?>
                <?php $title = json_decode($category_value['title']); ?>
                <option value="<?php echo $category_value['id']; ?>"><?php echo ucfirst($title->english); ?></option>
                <?php  } ?>
              </select>
            </div>
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
        </div>
        
        <div class="form-group ">   
          <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
            <div class='input-group date datefrom' id=''>
              <input type='text' class="form-control" id="datefrom" name="datefrom" placeholder="From" />
              <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>
          <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
            <div class='input-group date dateto' id=''>
              <input type='text' class="form-control" id="dateto" name="dateto" placeholder="To" />
              <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>


            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
              <input type="text" id="registration_no" name="registration_no" placeholder="Please Enter Registration No" class="form-control">
            </div>

        </div>
        
        <div class="form-group ">     
            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
              <input type="text" id="keyword" name="keyword" placeholder="Please Enter keyword" class="form-control">
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
              <input type="text" id="vin_no" name="vin_no" placeholder="Please Enter VIN" class="form-control">
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
             <select class="form-control col-md-7 col-xs-12" id="make_id" multiple name="make_id[]" > 
              </select>
            </div> 
            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
             <select class="form-control col-md-7 col-xs-12" id="model_id" multiple name="model_id[]" > 
              </select>
            </div> 

        </div>
      </form>
      <div id="result"></div>
      
        <div class="ln_solid"></div>
       <div class="clearfix"></div> 
        <div> 
          <a type="button" href="<?php echo base_url().'items/save_item'; ?>"  class="btn btn-success"><i class="fa fa-plus"></i> Add</a>
          <div class="clearfix"></div>
      </div>
        
        <br>
      <div class="x_content bulk_actions" style="display: none;">
        <label>Bulk Actions:</label>
        
         <button data-toggle="modal" data-target=".bs-example-modal-bulk-print"  onclick="getBulkQRcode(this)" data-backdrop="static"  id="qr_bulk" data-table="userTable" type="button" data-obj="item" data-url="<?php echo base_url(); ?>items/get_bulk_qrcode" class="btn btn-info btn-xs" title="QR Code"><i class="fa fa-barcode"></i> Get QR Code</button>
         <!-- <button onclick="deleteRows_Bulk(this)" id="delete_bulk" data-table="userTable" type="button" data-obj="item" data-url="<?php echo base_url(); ?>items/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete Rows</button> -->

        <hr>
      </div>
      
      <div class="x_content">       
        <table id='userTable' class='table jambo_table bulk_action'>
          <thead>
            <tr>
              <th data-visible="false" data-orderable="false"></th>
              <th data-orderable="false"></th>
              <th>Name</th>
              <th>Category</th>
              <th>Registration#</th>
              <th>Make</th>
              <th>Model</th>
              <th>VIN</th>
              <th>Reserve</th>
              <th>Seller Code</th>
              <th>Created On</th>
              <th>Updated On</th>
              <th>Status</th>
              <th>Sold Status</th>
              <th data-orderable="false" class="tablet mobile">Action</th>
            </tr>
          </thead>
        </table>
    </div>
</div>
    </div>

      <div class="modal fade bs-example-modal-banner" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span><span class="sr-only">Close</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Banner</h4>
            </div>
            <div id="DivIdToPrintBanner" class="modal-banner-body">
              
            </div>
            <div class="modal-footer">
            <button type="button" id="<?php //echo $value['id']; ?>" onclick="printBannerDiv();" class="btn btn-info btn-xs"><i class="fa fa-print"></i> Print</button>
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
            <button type="button" id="<?php //echo $value['id']; ?>" onclick="printDiv();" class="btn btn-info btn-xs"><i class="fa fa-print"></i> Print</button>
            </div>
          </div>
        </div>
      </div>

       <div class="modal fade bs-example-modal-bulk-print" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <br>
              <h4 class="pull-left modal-title" id="myModalLabel">QR Code</h4>

               <button type="button" onclick="printDivBulk();" class="pull-right btn btn-info btn-xs"><i class="fa fa-print"></i> Print</button>
            </div>
            <div id="DivIdToPrintBulk" class="modal-bulk-qr-body container-fluid">
              
            </div>
            <div class="modal-footer">
           
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Bidding Rules</h4>
            </div>
            <div class="modal-body">
              
            </div>
          </div>
        </div>
      </div>

</div>

<script type="text/javascript">
  var token_name = '<?= $this->security->get_csrf_token_name();?>';
  var token_value = '<?=$this->security->get_csrf_hash();?>';

    
  $('.datefrom').datetimepicker({
      format: 'YYYY-MM-DD'
  });

  $('.dateto').datetimepicker({
      format: 'YYYY-MM-DD'
  });
     
  $("#auction_type").select2({
    placeholder: "Select Auction Type", 
    width: '200px',
  });

  var selectOz = remoteSelect2({
    [token_name] : token_value,
    'selectorId' : '.seller_id',
    'placeholder' : 'Select Seller',
    'table' : 'users',
    'values' : 'Name',
    'width' : '200px',
    'delay' : '250',
    'cache' : false,
    'minimumInputLength' : 1,
    'limit' : 5,
    'url' : '<?= base_url();?>items/seller_id_api'
  });

  var select2Oz = remoteSelect2({
    [token_name] : token_value,
    'selectorId' : '.sale_person_id',
    'placeholder' : 'Select Appraiser Person',
    'table' : 'users',
    'values' : 'Name',
    'width' : '200px',
    'delay' : '250',
    'cache' : false,
    'minimumInputLength' : 1,
    'limit' : 5,
    'url' : '<?= base_url();?>items/sale_person_id_api'
  });

  var select2Oz = remoteSelect2({
    [token_name] : token_value,
    'selectorId' : '#make_id',
    'placeholder' : 'Select Make',
    'table' : 'item_makes',
    'values' : 'Name',
    'width' : '200px',
    'delay' : '250',
    'cache' : false,
    'minimumInputLength' : 1,
    'limit' : 5,
    'url' : '<?= base_url();?>items/itemMakeApi'
  });

  var select2Oz = remoteSelect2({
    [token_name] : token_value,
    'selectorId' : '#model_id',
    'placeholder' : 'Select Model',
    'table' : 'item_models',
    'values' : 'Name',
    'width' : '200px',
    'delay' : '250',
    'cache' : false,
    'minimumInputLength' : 1,
    'limit' : 5,
    'url' : '<?= base_url();?>items/itemModelApi'
  });


  // $('input:checkbox').on('ifChecked', function () 
  // { 
  //    if ($("input:checkbox:checked").length > 0){
  //    $('#delete_bulk').show();
  //    }
  // });
  // $('input:checkbox').on('ifUnchecked', function ()
  // {
  //     if ($("input:checkbox:checked").length <= 0){
  //      $('#delete_bulk').hide();
  //     }
  // });    

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

  function printDivBulk() 
  {

    var divToPrint=document.getElementById('DivIdToPrintBulk');

    var newWin=window.open('','Print-Window');

    newWin.document.open();

    newWin.document.write('<html><head><style type="text/css" media="print"> @page { size: auto;    margin: 0; }</style></head><body onload="window.print()"><style>h1,h2,h3,h4,h5,h6 {margin: 0;}.container {margin-bottom: 15px}</style> '+divToPrint.innerHTML+'</body></html>');

    newWin.document.close();

    // setTimeout(function(){newWin.close();},10);

  }

  $('.oz_bidding_model').on('click',function(){
    var url = '<?php echo base_url();?>';
    var id = $(this).attr("id");
    console.log(id);
    $.ajax({
      url: url + 'items/get_bidding_rules',
      type: 'POST',
      data: {id:id, [token_name]:token_value},
    }).then(function(data) {
      var objData = jQuery.parseJSON(data);
      console.log(objData);
      if (objData.msg == 'success') 
      {
        $('.modal-body').html(objData.data);
      }

    });
  });

  $('.oz_banner_pr').on('click',function(){
         // alert('clicker');
   
  });
  function getBanner(e){
    var url = '<?php echo base_url();?>';
    var id = $(e).attr("id");
    console.log(id);
    $.ajax({
      url: url + 'items/get_banner_details',
      type: 'POST',
      data: {id:id, [token_name]:token_value},
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
      }
    });
  }
 $('.oz_print_qr').on('click',function(){
    
  });         

 function getQRcode(e){

  var url = '<?php echo base_url();?>';
  var id = $(e).attr("id");
  console.log(id);
  $.ajax({
    url: url + 'items/get_qrcode',
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

  function getBulkQRcode(x){
    var ids = [];
    var obj = $(x).data('obj');
    var tableId = $(x).data('table');
    $.each($("#"+tableId+" tr.selected"),function(n){
        ids[n]=$(this).attr('id');
    }); 
    var link = $(x).data('url'); 
    var ids = ids.toString(); 

    var url = '<?php echo base_url();?>';
    // var id = $(e).attr("id");
    // console.log(id);
    $.ajax({
      url: url + 'items/get_bulk_qrcode',
      type: 'POST',
      data: {id:ids, [token_name]:token_value},
    }).then(function(data) {
      var objData = jQuery.parseJSON(data);
      // console.log(objData);
      $('#add_items').hide(); 
      if (objData.msg == 'success') 
      {
        $('.modal-bulk-qr-body').html(objData.data);
      }
    });
  }

  let exportOptions = {'rows': {selected: true} ,columns: ['2,3,4,5,6,7,8,9,10,11,12']};
  let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
  let dt1 = customeDatatable({
    // index zero must be csrf token
    '<?= $this->security->get_csrf_token_name();?>':'<?= $this->security->get_csrf_hash();?>',
    'div' : '#userTable',
    'url' : '<?=base_url()?>items/itemList',
    'orderColumn' : 11,
    'iDisplayLength' : 10,
    'dom' : '<"pull-left top"B><"pull-right"l><"clear">fr<t><"bottom"ip><"clear">',
    'columnCustomeTypeTarget' : 11,
    'defaultSearching': false,
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
    'customFieldsArray': ['category_id','seller_id','seller_code','make_id','model_id','sale_person_id','vin_no','item_status','keyword','registration_no','days_filter','datefrom','dateto'],
    'dataColumnArray': [{'data':'id'},{'data':'image'},{'data':'name'},{'data':'category_id'},{'data':'registration_no'},{'data':'make'},{'data':'model'},{'data':'vin_number'},{'data':'price'},{'data':'seller_id'},{'data':'created_on'},{'data':'updated_on'},{'data':'item_status'},{'data':'sold'},{'data':'action'}]
  });


  $(document).ready(function(){
    $('#userTable tbody').on( 'click', 'tr', function () {
      $(this).toggleClass('selected');
    });
    $('.buttons-select-none').on( 'click', function () {
      $('.bulk_actions').css('display','none');
    });
    $('.buttons-select-all').on( 'click', function () {
      $('.bulk_actions').css('display','block');
    });
    $('#item_status,#seller_id,#make_id,#model_id,#sale_person_id,#category_id,#days_filter').change(function(){
      dt1.draw();
    });
    $('#keyword,#registration_no,#vin_no').keyup(function(){
      dt1.draw();
    });
       
    $('.dateto').on('dp.change', function(e){ 
      if($(".datefrom").find("input").val() != ''){
        dt1.draw();
      }
    });

    $('.datefrom').on('dp.change', function(e){ 
      if($(".dateto").find("input").val() != ''){
        dt1.draw();
      }
    });
    $('#userTable tbody').on( 'click', 'tr', function (e) {
      var rows_selected = dt1.rows('.selected').data();
      if(dt1.rows('.selected').data().length > 0){
        $('.bulk_actions').css('display','block');
      }else{
        $('.bulk_actions').css('display','none');
      }
    });  

  });

  

</script>
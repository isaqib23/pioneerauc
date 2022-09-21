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
       <h4>Auction Deposit: </h4>

    <form method="post" id="demo-form3" action="" enctype="multipart/form-data" class="form-horizontal form-label-left ">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
        <input type='hidden' class="form-control" name="auction_id" id="auction_id" value="<?= $auction_id; ?>" />
        <input type='hidden' class="form-control" name="user_id" id="user_id"/>
        <input type='hidden' class="form-control" disabled name="id" id="id"/>
        <div class="form-group"> 

            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
                <div style="display: flex; align-items: center;"> 
                  <input type="text" class="form-control" id="username" autocomplete="off" placeholder="User name" readonly="readonly" />
                    <a id="btnid" href="javascript:void(0)" class="btn btn-success" style="margin-left: 10px;">Select</a>
                </div>
                  <div id="user_error" class="text-danger"></div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
                <input type="number" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" required="required" class="form-control" name="amount" id="amount" placeholder="Amount" />
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
                <input type="number" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" required="required" class="form-control" name="card_number" id="card_number" placeholder="Pedal Number" />
            </div>
        </div>
            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
                <select class="form-control col-md-7 col-xs-12" id="payment_type" name="payment_type" >
                    <option value="cash">Cash</option>
                    <option value="cheque">Cheque</option>
                </select>
            </div>
        <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
            <input type="text"  class="form-control" name="description" id="description" placeholder="Discription" />
        </div>
         
        <!-- <div class="form-group"> -->
            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
                <input type="submit" id="save1" class="btn btn-info" value="Add"> 
            </div>
        <!-- </div> -->
      </form>
      <hr>

      <div id="result"></div>
      
       <div class="clearfix"></div> 
        <div> 
            <?php $id = $this->uri->segment(3);?>
          <a type="button" href="<?php echo base_url().'auction/save_user/'.$id; ?>"  class="btn btn-success"><i class="fa fa-plus"></i> New User</a>
          <div class="clearfix"></div>
      </div>
        
      <div class="x_content">
        <br>
        <hr>
            <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="auctions" data-url="<?php echo base_url(); ?>auction/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete Selected Rows</button>


      </div>

        <?php if(!empty($users_list)){?>
        <div class="x_content">
            <table id="usertable" class="table jambo_table" cellspacing="0" width="100%">
                <thead>
                    <?php $role = $this->session->userdata('logged_in')->role;  ?>
                    <tr>
                        <th>#</th>
                        <th>Card Number</th>
                        <th>User Name</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Deposit Amount</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                 
                </tbody>
            </table>
        </div>
            <?php 
        }else{
            echo "<h1>No Record Found</h1>";
        }
        ?>
    </div>
    </div>
</div>
<script>
    var token_name = '<?= $this->security->get_csrf_token_name();?>';
    var token_value = '<?= $this->security->get_csrf_hash();?>';

    // var table = $('#usertable');
        // table.clear();
    let exportOptions = {'rows': {selected: true} ,columns: ['2,3']};
    let exportOptionsSelect = { modifier: { selected: null} };
    let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
    let dt1 = customeDatatableOzair({
        // index zero must be csrf token
        '<?= $this->security->get_csrf_token_name();?>':'<?= $this->security->get_csrf_hash();?>',
        'div' : '#usertable',
        'url' : '<?= base_url()?>auction/deposit_users_list/'+ <?= $auction_id ?>,
        'orderColumn' : 0,
        'iDisplayLength' : 10,
        // 'dom' : 'fr<t><"bottom"lip><"clear">', // r<t><"bottom"lip><"clear">
        "dom": '<"top"if>r<t><"bottom"lp>',
        'columnCustomeTypeTarget' : 2,
        'defaultSearching': true,
        'responsive': true,
        'rowId': 'id',
        'stateSave': true,
        'lengthMenu': [[10, 25, 50, 100, 150], [10, 25, 50, 100, 150]], // [5, 10, 25, 50, 100], [5, 10, 25, 50, 100]
        'order' : 'desc',
        'selectStyle' : 'single',
        'collectionButtons' : dtButtons,
        // 'exportOptionsSelect' : exportOptionsSelect,
        'processing' : true,
        'serverSide' : true,
        'processingGif' : '<span style="display:inline-block; margin-top:-105px;"> <img  src="<?=base_url()?>/assets_admin/images/load.gif" /></span>',
        'buttonClassName' : 'btn-sm',
        'columnCustomType' : 'date-eu',
        'customFieldsArray': [],
        'dataColumnArray': [{'data':'id'},{'data':'card_number'},{'data':'fname'},{'data':'mobile'},{'data':'email'},{'data':'amount'},{'data':'created_on'},{'data':'action'}]
    });

    // $(document).ready(function(){
    //     dt1.clear();
    // });
    // $('#save1').on('click', function(){
    //     event.preventDefault();
        // $('#demo-form3').submit();
        $('#demo-form3').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
          }) .on('form:submit', function() {
                $('#user_error').hide();
            var user_name = $('#username').val();
            if (user_name == '') {
                $('#user_error').text('Please select user.').show();
            }else{

                var formData = $("#demo-form3").serializeArray();
                 // console.log(formData);
                $.ajax({
                    url: base_url + 'auction/update_deposit',
                    type: 'POST',
                    data: formData,
                    success : function(data) {
                    var objData = jQuery.parseJSON(data);
                        if (objData.status == 'success') { 
                            dt1.draw();
                            // $('#result').css('display', 'block');
                            $("#result").delay(3000).fadeOut().innerHTML = "";
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');

                            $("#id").val('');
                            // $("#searchby").val('');
                            $("#id").attr('disabled','disabled');
                            $("#user_id").val('');
                            $("#username").val('');
                            $("#amount").val('');
                            $("#card_number").val('');
                            $("#description").val('');
                            $("#payment_type").val('');
                        } 
                        else 
                        {
                            // $('.msg-alert').css('display', 'block');
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
                        }  
                    }

                });
            }
            return false; // Don't submit form for this demo
        });
    // });

    var typingTimer;                //timer identifier
    var doneTypingInterval = 1000;  //time in ms, 5 second for example
    var $input = $('#searchby');

    //on keyup, start the countdown
    $input.on('keyup', function () {
      clearTimeout(typingTimer);
      typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });

    //on keydown, clear the countdown 
    $input.on('keydown', function () {
      clearTimeout(typingTimer);
    });

    //user is "finished typing," do something
    function doneTyping () {
        $("#user_error").hide();
        $("#user_id").val('');
        $("#id").attr('disabled','disabled');
        $("#username").val('');
        var url = '<?php echo base_url();?>';
        var search = $('#searchby').val();
        $.ajax({
            type: "POST",
            url: url+ 'auction/find_user',
            data: {search : search, [token_name]:token_value},
            beforeSend: function(){
                $('#username').html('<img src="'+url+'assets_admin/images/load.gif" />');
            },
            success: function(data){
                var objData = jQuery.parseJSON(data);
                if(objData.status == 'success'){
                    $("#user_error").hide();
                    $("#user_id").val(objData.user['id']);
                    $("#username").val(objData.user['fname']);
                }else{
                    $("#user_error").text(objData.msg);
                    $("#user_id").val('');
                    $("#username").val('');
                    $("#user_error").show();
                }
            }

        });
    }
    

    function myfunc(e){
        // e.preventDefault();
        var value = $(e).attr("data-id")
        console.log(value);
        // $("#user_error").hide();
        $("#id").val($(e).attr("data-id"));
        $("#user_id").val($(e).attr("data-user_id"));
        $("#username").val($(e).attr("data-name"));
        $("#amount").val($(e).attr("data-amount"));
        $("#card_number").val($(e).attr("data-card"));
        $("#description").val($(e).attr("data-description"));
        $("#payment_type").val($(e).attr("data-payment_type"));
        $("#id").removeAttr('disabled');
    }

    // function myfuncnew(e){
    //     var url = '<?php echo base_url();?>';
    //     var value = $(e).attr("data-id");
    //     $.ajax({
    //          data: {'id':value},
    //         url: url+ 'auction/user_payment_receipt', 
    //     });
    // }


    

    // $('#usertable').selectStyle(function(){
    //      dt1.draw();
    // });


   
</script>
<script >
    
     $('#btnid').on('click', function(event){
        event.preventDefault();
        var url = '<?php echo base_url();?>';
        console.log('jhdv cxj');
          $.ajax({   
            type: "post", //create an ajax request to display.php
            url: url + "auction/load_user_popup",    
            data:{[token_name]:token_value},
            success: function(data) {
                objdata = $.parseJSON(data);
                if(objdata.success == true)
                {
                    $("#id").val('');
                    $("#id").attr('disabled','disabled');
                    $("#user_id").val('');
                    $("#username").val('');
                    $("#amount").val('');
           
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
    }); 


function permanent(e){
    var id  = $(e).data('id');
    var obj = $(e).data('obj');
    var column = $(e).data('field');
    var amount = $(e).data('amount');
    var link = $(e).data('url');


    if(id!='' && obj!=''){
        swal({
                title: amount +' AED', 
                text: "The amount will be added into your account.",
                type: "success",
                showCancelButton: true,
                cancelButtonClass: 'btn-default btn-md waves-effect',
                confirmButtonClass: 'btn-danger btn-md waves-effect waves-light',
                confirmButtonText: 'Confirm'
            },
            function(isConfirm) {
                if (isConfirm) {
                    $("#loading").show();
                    $.post(link, {id: id, obj: obj, column: column, [token_name]:token_value}, function(result){
                        if(result!='0'){

                            var data = JSON.parse(result);
                            if(data.type == 'success'){
                                // alert('success');
                                //hide gallery image
                                location.reload();
                                swal("Success!", data.msg, "success");
                                $("#row-"+id).fadeOut("slow");
                               $("#row-"+id).remove();

                                // var oTable = $('#datatable-responsive').dataTable();
                                // var row = $(x).closest("tr").get(0);
                                // oTable.fnDeleteRow( oTable.fnGetPosition(row)).draw( false );;

                                // var page_number = oTable.fnPagingInfo().iPage;
                                // oTable.fnDeleteRow(oTable.fnGetPosition(row), function(){oTable.fnPageChange(page_number);}, false);

                            }

                            if(data.type == 'error'){
                                swal("Error!", data.msg, "error");
                            }

                        }else{
                            swal("Error!", "Something went wrong.", "error");
                        }
                        $('#loading').hide();
                    }

                    );

                } else {
                    swal("Cancelled", "Your action has been cancelled!", "error");
                }
            }

        );
    }else{
        swal("Error!", "Information Missing. Please reload the page and try again.", "error");
    }
}

</script>

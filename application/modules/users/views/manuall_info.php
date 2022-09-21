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
       <!-- <h4>Filter Record: </h4> -->

    <!-- <form method="post" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left ">
        <div class="form-group ">   
            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
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
      </form> -->

      <div id="result"></div>
      
       <div class="clearfix"></div> 
        <div> 
          <a type="button" href="<?php echo base_url().'users/add_manuall_deposite/'.$userid; ?>"  class="btn btn-success"><i class="fa fa-plus"></i> Add Manuall Deposite</a>
          <div class="clearfix"></div>
      </div>
        
      <div class="x_content">
        <br>
        <hr>
         <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="auctions" data-url="<?php echo base_url(); ?>auction/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete Selected Rows</button>
      </div>
      <div class="x_content">
       <?php if(!empty($auction_list)){?>
        <div id="content_live_auction">
            <table id="datatable-responsive_2" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="check-all" class="flat ozproceed_check" name="table_records">
                    </th>
                    <th>Account Number</th>
                    <th>Account Title</th>
                    <th>Bank Name</th>
                    <th>Manuall Amount</th>
                    <th>Deposit By</th>
                    <th>Deposit Currency</th>
                    <th>Date</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
            <tbody class="">
                 <?php 
                $j = 0;
                $role = $this->session->userdata('logged_in')->role; 
                $CI =& get_instance();

                foreach($auction_list as $auction_value){
                  // echo "<pre>";
                  $li =json_decode($auction_value['transaction_info']);
                     // var_dump($li->bank_name_arabic);die();

                    // var_dump($li);die();
                    ?>

                    <tr id="">
                        <td class="a-center ">
                        <input type="checkbox" class="flat multiple_rows ozproceed_check" id="" name="table_records"  value="">
                        </td>
                        <td><?php echo (isset($li->manuall_account_number) && !empty($li->manuall_account_number)) ? $li->manuall_account_number : ''; ?></td>
                        <td><?php echo (isset($li->manuall_title) && !empty($li->manuall_title)) ? $li->manuall_title : ''; ?></td>
                        <td><?php echo (isset($li->manuall_bank_name->english) && !empty($li->manuall_bank_name->english)) ? $li->manuall_bank_name->english : ''; ?></td>
                        <td><?php echo (isset($li->manuall_amount) && !empty($li->manuall_amount)) ? $li->manuall_amount : ''; ?></td>
                        <td><?php echo (isset($li->manuall_deposit_name->english) && !empty($li->manuall_deposit_name->english)) ? $li->manuall_deposit_name->english : ''; ?></td>
                        <td><?php echo (isset($li->manuall_currency) && !empty($li->manuall_currency)) ? $li->manuall_currency : ''; ?></td>
                        <td><?php echo (isset($li->manuall_deposit_date) && !empty($li->manuall_deposit_date)) ? $li->manuall_deposit_date : ''; ?></td>

                            <td>
                              <a href="<?php echo base_url('users/delete_manuall/').$auction_value['id'].'?userid='.$userid; ?>" 
                             class="btn btn-danger btn-xs"
                            ><i class="fa fa-trash"></i> Remove</a>
                            </td>
                    </tr>
                <?php }?>
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
    
    var url = "<?php echo base_url(); ?>"; 
    $('#datatable-responsive_2').DataTable({
      responsive: true,
            columnDefs: [ 
              { targets:"_all", orderable: false },
              { targets:[8], className: "tablet, mobile" }
            ]
    });

    $('input:checkbox').on('ifChecked', function () 
    { 
       if ($("input:checkbox:checked").length > 0){
       $('#delete_bulk').show();

       }
    });
      $('#datefrom').datetimepicker({
      format: 'YYYY-MM-DD'
    });

    $('#dateto').datetimepicker({
        format: 'YYYY-MM-DD'
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
                   ids_list: ids_list,auction_id:auction_id,category_id:category_id
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
      function oz_stock_list(e){
      // $('.oz_stock_list').on('click',function(){
              var url = '<?php echo base_url();?>';
              var id = $(e).attr("id");
              console.log(id);
               $.ajax({
                url: url + 'auction/get_stock_list',
                type: 'POST',
                data: {id:id},
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
          url: url + 'users/' + formaction_path,
          type: 'POST',
          data: formData,
          cache: false,
          contentType: false,
          processData: false
          }).then(function(data) {
          var objData = jQuery.parseJSON(data);
          if (objData.msg == 'success') 
          { 
                  
              $('#content_live_auction').html(objData.data);
           
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

</script>
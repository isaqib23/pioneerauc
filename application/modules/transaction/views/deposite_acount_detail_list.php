  
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

<div class="clearfix"></div>

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
  <div Mileage To="x_content"> 
    <div Mileage From="x_content"> 
        
     <form method="post" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left ">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />


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


        <div class="form-group ">   
         
        </div>
      </div>

        <div id="loading"></div>
      
            <div style="float: right" class=" col-md-offset-1">
                <button type="button" id="send" class="btn btn-success">Filter</button>
            </div>
          </form>
        <?php
        if($this->session->userdata('logged_in')->role == 4 || $this->uri->segment(3))
        { ?>
          <div class="x_content">
           <a type="button" href="<?php echo base_url().'customers'; ?>"  class="btn  btn-info"><i class="fa fa-arrow-left"></i> Back</a>
            <hr>
            <div Mileage Depreciation="x_content"> 
              <!-- <a type="button" href="<?php echo base_url().'transaction/show_deposite_options/'.$this->uri->segment(3);?>"   class="btn btn-md btn-success  "><i class="fa fa-plus"></i> New Deposit Detail</a> -->
            </div>
            <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="user_deposit_detail" data-url="<?php echo base_url(); ?>users/delete_deposit_detail_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
          </div>
        <?php
        }
        ?>
      <div class="x_content" id="new_DT">
       <?php if(!empty($deposit_list)){ ?>
          <table id="datatable-responsive" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <?php
                  if($this->session->userdata('logged_in')->role == 1)
                  { ?>
                    <th class="td">
                        <!-- <input type="checkbox" id="check-all" class="flat"> -->
                    </th>
                  <?php
                  }
                  ?>
                    <th>#</th>
                    <th>Payment Type</th>
                    <th>Amount</th>
                    <th>Account</th>
                    <th>Created On</th>
                    <th>Status</th>
                    <?php
                    if($this->session->userdata('logged_in')->role == 1)
                    { ?>
                      <th data-orderable="false">Actions</th>
                    <?php
                    }
                    ?>
                </tr>
              </thead>
            <tbody id="user_listing">
              <?php 
              $j = 0;
              foreach($deposit_list as $value){
                  $j++;
                  ?>
                  <tr id="row-<?php echo  $value['id']; ?>">
                    <?php
                    if($this->session->userdata('logged_in')->role == 1)
                    { ?>
                      <td class="a-center td">
                      <!-- <input type="checkbox"  id="delete_bulk_button" value="<?php echo  $value['id']; ?>" class="flat" name="table_records"> -->
                      </td>
                    <?php
                    }?>
                      <td><?php echo $j ?></td>
                      <td><?php
                        switch ($value['payment_type']) {
                          case 'cash':
                            echo "Cash";
                            break;
                          case 'card':
                            echo "Card";
                            break;
                          case 'bank_transfer':
                            echo "Bank Transfer";
                            break;
                          case 'cheque':
                            echo "Cheque";
                            break;
                          case 'manual_deposit':
                            echo "Manual Deposit";
                            break;
                          
                          default:
                            echo "";
                            break;
                        } ?>    
                      </td>
                      <td><?php echo $value['amount'].' AED'; ?></td>
                      <td><?php echo $value['account']; ?></td>
                      <td><?php echo $value['created_on']; ?></td>
                      <td><?php echo ucfirst($value['status']); ?></td>
                      <?php
                      if($this->session->userdata('logged_in')->role == 1)
                      { ?>
                        <td>
                            <!-- <a href="<?php echo base_url().'users/update_deposit_detail/'.$value['user_id'].'/'.$value['id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a> -->
                            <button onclick="deleteRecord(this)" type="button" data-obj="<?= isset($value['status']) ? 'auction_deposit' : 'transaction'; ?>" data-token-name="<?= $this->security->get_csrf_token_name();?>" data-token-value="<?=$this->security->get_csrf_hash();?>" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>transaction/delete_deposit_detail" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>

                            <button type="button" id="<?php echo $value['id']; ?>" data-toggle="modal" data-target=".bs-example-modal-banner"  data-backdrop="static" data-keyboard="true" class="btn btn-info btn-xs oz_banner_pr"><i class="fa fa-file"></i> Print</button>
                        </td>
                      <?php
                      }
                      ?>
                  </tr>
              <?php }?>
            </tbody>
          </table>
          <tfoot>
            <th colspan="50%" style="padding: 10px;">
              <div class="pull-left actions">                         
              </div>                
          </tfoot>
        <?php 
          }else{
            echo "<h1>No Record Found</h1>";
          }
        ?>
      </div>
    </div>

        <div class="modal fade bs-example-modal-banner" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Transaction Detail</h4>
            </div>
            <div id="DivIdToPrintBanner" class="modal-banner-body">
              
            </div>
            <div class="modal-footer">
            <button type="button" id="<?php echo $value['id']; ?>" onclick="printBannerDiv();" class="btn btn-info btn-xs"><i class="fa fa-print"></i> Print</button>
            </div>
          </div>
        </div>
      </div>



      </div>
    </div>
  </div>
  
<script type="text/javascript">
  var token_name = '<?= $this->security->get_csrf_token_name();?>';
  var token_value = '<?=$this->security->get_csrf_hash();?>';

  $(document).ready(function() {
    $('#datatable-responsive').DataTable( {
        columnDefs: [ {
            orderable: false,
            className: 'select-checkbox flat',
            targets:   0
        } ],
        select: {
            style:    'multi',
            selector: 'td:first-child'
        },
        order: [[ 1, 'asc' ]]
    } );
  });

  window.setTimeout(function() {
      $(".alert").fadeTo(500, 0).slideUp(500, function(){
          $(this).remove(); 
      });
  }, 3000);

  $('#datefrom').datetimepicker({
    format: 'YYYY-MM-DD'
  });

  $('#dateto').datetimepicker({
      format: 'YYYY-MM-DD'
  });



  $('input').on('ifChecked', function () { 
    $('#delete_bulk').show();
  })
  $('input').on('ifUnchecked', function (){
    $('#delete_bulk').hide();
  })

  $('.oz_banner_pr').on('click',function(){
    // alert('clicker');
    var url = '<?php echo base_url();?>';
    var id = $(this).attr("id");
    console.log(id);
    $.ajax({
      url: url + 'users/get_transaction_details',
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



  var url = '<?php echo base_url();?>';
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
    
              // datatable.clear();
          // var datatable = $('#datatable-responsive').DataTable();
          // $.get('myUrl', function(newDataArray) {
          $('#new_DT').html(objData.data);
              // datatable.data(objData.data);
              // datatable.draw();
          // });
            // $("#datatable-responsive").reload();
            // $('#datatable-responsive').DataTable().ajax.reload();
            // refreshTable();
       
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

  
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


        <div class="x_content">
        <hr>
      </div>    

      <div class="x_content">
       <?php if(!empty($email_template_list)){?>
        <table id="" class="table table-striped" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="check-all" class="flat">
                    </th>
                    <th>#</th>
                    <th>Template Name</th>
                    <th>Slug</th>
                    <th>Subject</th>
                    <!-- <th>Body</th> -->
                    <th>Status</th>
                    <th>Active/Inactive</th>
                    <th data-orderable="false">Actions</th>

                </tr>
            </thead>
        

            <tbody id="user_listing">
                <?php 
                $j = 0;
                foreach($email_template_list as $value){
                    $j++;
                    ?>
                    <tr id="row-<?php echo  $value['id']; ?>">
                        <td class="a-center ">
                        <input type="checkbox"  id="delete_bulk_button" value="<?php echo  $value['id']; ?>" class="flat" name="table_records">
                        </td>
                        <td><?php echo $j ?></td>
                        <td><?php echo $value['template_name']; ?></td>
                        <td><?php echo $value['slug']; ?></td>
                        <td><?php echo $value['subject']; ?></td>
                        <!-- <td><?php //echo $value['body']; ?></td> -->
                        <td><?php echo $value['status']; ?></td>
                        <!-- <td id="status"> -->
                         <td> <?php if($value['status'] == "inactive") { ?>
                            <a class="btn btn-success" href="<?php echo base_url().'settings/chage_status?user_id='.$value['id'].'&status=active'?>">Active</a>


                         <!-- <button id="statuss" onclick="changeStatus(this)" value="1" data-id="<?php echo $value['id']; ?>" class="btn btn-success"> Acitve </button> -->
                        <?php } if($value['status'] == "active") { ?>

                          <a class="btn btn-warning" href="<?php echo base_url().'settings/chage_status?user_id='.$value['id'].'&status=inactive'?>">InActive</a>
                          <!-- 
                          <button id="statuss" onclick="changeStatus(this)" value="0" data-id="<?php echo $value['id']; ?>" class="btn btn-warning">InActive</button> -->
                        <?php } ?> 
                      </td>
                        <td>
                            <a href="<?php echo base_url().'settings/email_template/'.$value['id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>

                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
        <tfoot>
                <th colspan="50%" style="padding: 10px;">
                    <div class="pull-left actions">        
                             
                </tfoot>
            </div>                
            <?php 
              }else{
                echo "<h1>No Record Found</h1>";
                }
              ?>
          </div>
        </div>
      </div>
    </div>
  
<script type="text/javascript">
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

</script>

<script>
    var token_name = '<?= $this->security->get_csrf_token_name();?>';
    var token_value = '<?=$this->security->get_csrf_hash();?>';

    $(document).ready(function() {
      $('#engine_sizes').DataTable();
    });
    $('#delete_button').click(function(){
      var url = '<?php echo base_url();?>';
      var selected = "";
      $('#customer_lead_listing input:checked').each(function() {
        selected+=$(this).attr('value')+",";  
      });

      $.ajax({
        url: url + 'crm/delete_customer_type/?ids='+selected,
        type: 'POST',
      }).then(function(data) {
        var objData = jQuery.parseJSON(data);
        console.log(objData);
        if (objData.msg == 'success') {
          window.location = url + 'crm/crm';
        }
      }); 
    });

    $('input').on('ifChecked', function () { 
      $('#delete_bulk').show();
    })
    $('input').on('ifUnchecked', function (){
     $('#delete_bulk').hide();
    })

    // var url = '<?php echo base_url();?>';
    // var formaction_path = '<?php// echo $formaction_path; ?>';
    // $("#send").on('click', function(e) { //e.preventDefault();
    //   var formData = new FormData($("#demo-form2")[0]);
    //   $.ajax({
    //     url: url + 'users/' + formaction_path,
    //     type: 'POST',
    //     data: formData,
    //     cache: false,
    //     contentType: false,
    //     processData: false
    //   }).then(function(data) {
    //     var objData = jQuery.parseJSON(data);
    //     if (objData.msg == 'success') 
    //     { 
      
    //             // datatable.clear();
    //         // var datatable = $('#datatable-responsive').DataTable();
    //         // $.get('myUrl', function(newDataArray) {
    //         $('#user_listing').html(objData.data);
    //             // datatable.data(objData.data);
    //             // datatable.draw();
    //         // });
    //           // $("#datatable-responsive").reload();
    //           // $('#datatable-responsive').DataTable().ajax.reload();
    //           // refreshTable();
         
    //     } else {
    //       $('.msg-alert').css('display', 'block');
    //       $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');

    //       window.setTimeout(function() {
    //         $(".alert").fadeTo(500, 0).slideUp(500, function(){
    //           $(this).remove(); 
    //         });
    //       }, 3000);
    //     }
    //   });
    // });
    function changeStatus(x){

      var url = '<?php echo base_url();?>';
      var user_id  = $(x).data('id');
      var statusId = $('#statuss').val();
      $.ajax({
        url: url + 'users/chage_status/?status_id='+statusId + '&user_id='+user_id, 
        // url: url + 'crm/delete_customer_type/?ids='+selected,
        type: 'POST',            
      }).then(function(data) {
        // alert(data);
        $('#loading').html('loading...');
        // window.location = url + 'users/';

      });
    }
</script>

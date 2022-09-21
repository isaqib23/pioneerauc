  
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

        <div id="loading"></div>
      
            
          </form>
        <div class="x_content">
           <a type="button" href="<?php echo base_url().'customers'; ?>"   class="btn  btn-info"><i class="fa fa-arrow-left"></i>  Back</a>
        <hr>
        <div Mileage Depreciation="x_content"> 
          <a type="button" href="<?php echo base_url().'users/save_bank_detail/'.$this->uri->segment(3); ?>"   class="btn btn-md btn-success  "><i class="fa fa-plus"></i> New Bank Detail</a>
        </div>
         <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="user_bank_detail" data-url="<?php echo base_url(); ?>users/delete_bank_detail_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
      </div>

      

      <div class="x_content">
       <?php if(!empty($bank_list)){?>
        <table id="datatable-responsive" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="check-all" class="flat">
                    </th>
                    <th>#</th>
                    <th>Bank Name</th>
                    <th>Branch name</th>
                    <th>PO Box</th>
                    <th>Account Name</th>
                    <th>IBAN No</th>
                    <th>Swift Code</th>
                    <th>Created On</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
            <tbody id="user_listing">
                <?php 
                $j = 0;
                foreach($bank_list as $value){
                    $j++;
                    ?>
                    <tr id="row-<?php echo  $value['id']; ?>">
                        <td class="a-center ">
                        <input type="checkbox"  id="delete_bulk_button" value="<?php echo  $value['id']; ?>" class="flat" name="table_records">
                        </td>
                        <td><?php echo $j ?></td>
                        <td><?php echo $value['bank_name']; ?></td>
                        <td><?php echo $value['branch_name']; ?></td>
                        <td><?php echo $value['po_box']; ?></td>
                        <td><?php echo $value['account_name']; ?></td>
                        <td><?php echo $value['iban_no']; ?></td>
                        <td><?php echo $value['swift_code']; ?></td>
                        <td><?php echo $value['created_on']; ?></td>
                        <td>
                            <a href="<?php echo base_url().'users/update_bank_detail/'.$value['user_id'].'/'.$value['id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                            <button onclick="deleteRecord(this)" type="button" data-obj="user_bank_detail" data-token-name="<?= $this->security->get_csrf_token_name();?>" data-token-value="<?=$this->security->get_csrf_hash();?>" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>users/delete_bank_detail" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
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
 </div>
  
      <script type="text/javascript">
          window.setTimeout(function() {
              $(".alert").fadeTo(500, 0).slideUp(500, function(){
                  $(this).remove(); 
              });
          }, 3000);

      $('input').on('ifChecked', function () { 
       $('#delete_bulk').show();
      })
       $('input').on('ifUnchecked', function (){
         $('#delete_bulk').hide();
       })

   var url = '<?php echo base_url();?>';
  
    
  $("#datatable-responsive").DataTable({
    dom: "Bfrtip",
    responsive: true
  });
 </script>

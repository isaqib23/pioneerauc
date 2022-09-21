
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
        <div Mileage Depreciation="x_content"> 
          <a type="button" href="<?php echo base_url().'crm/new_lead_category'; ?>"  class="btn btn-md btn-success  "><i class="fa fa-plus"></i> New Lead Category</a>
          <div class="clearfix"></div>
      </div>

      <div class="x_content">
        <br>
        <hr>
         <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="crm_lead_category" data-token-name="<?= $this->security->get_csrf_token_name();?>" data-token-value="<?=$this->security->get_csrf_hash();?>" data-url="<?php echo base_url(); ?>crm/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
      </div>

      <div class="x_content">
       <?php if(!empty($lead_category_list)){?>
        <table id="datatable-responsive" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="check-all" class="flat">
                    </th>
                    <th>#</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
            <tbody id="lead_category_listing">
                <?php 
                $j = 0;
                foreach($lead_category_list as $value){
                    $j++;
                    ?>
                    <tr id="row-<?php echo  $value['id']; ?>">
                        <td class="a-center ">
                        <input type="checkbox" id="delete_bulk_button" value="<?php echo  $value['id']; ?>" class="flat" name="table_records">
                        </td>
                        <td><?php echo $j ?></td>
                        <td><?php echo $value['title']; ?></td>
                      
                        <td><?php echo $value['status']; ?></td>
                        <td>
                            <a href="<?php echo base_url().'crm/edit_lead_category/'.$value['id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>

                            <!-- <button onclick="deleteRecord(this)" type="button" data-obj="crm_lead_category" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>crm/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button> -->
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
</script>









          <script>
              $(document).ready(function() {
              $('#engine_sizes').DataTable();
              });

$('#delete_button').click(function(){
var url = '<?php echo base_url();?>';
var selected = "";
$('#lead_category_listING input:checked').each(function() {
    selected+=$(this).attr('value')+",";
    
  });

$.ajax({
url: url + 'crm/delete_lead_category/?ids='+selected,
type: 'POST',
}).then(function(data) {
  var objData = jQuery.parseJSON(data);
  console.log(objData);
   if (objData.msg == 'success') {
    window.location = url + 'crm/lead_category';

  }
});
});

  $('input').on('ifChecked', function () { 
      $('#delete_bulk').show();
    })
  $('input').on('ifUnchecked', function (){
      $('#delete_bulk').hide();
    })
          </script>
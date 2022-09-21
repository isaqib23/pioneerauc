
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

  <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="valuation_millage" data-url="<?php echo base_url(); ?>cars/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete Selected Record</button>


  <div Mileage To="x_content"> 
    <div Mileage From="x_content"> 
        <div Mileage Depreciation="x_content"> 
          <a type="button" href="<?php echo base_url().'cars/add_mileage'; ?>"  class="btn btn-primary"><i class="fa fa-plus"></i> Add</a>
          <div class="clearfix"></div>
      </div>

      <div class="x_content">
       <?php if(!empty($mileage_list)){?>
        <div class="table-responsive" id="checkboxes">
          <table id="engine_sizes" class="table table-striped jambo_table bulk_action ">
            <!-- jambo_table bulk_action (for jambo action table) -->
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="check-all" class="flat">
                    </th>
                    <th class="column-title">#</th>
                    <th class="column-title">Mileage To</th>
                    <th class="column-title">Mileage From</th>
                    <th class="column-title">Mileage Depreciation</th>
                    <th class="column-title">Mileage Status</th>
                    <th data-orderable="false">Actions</th>
                    <th class="bulk-actions" colspan="7">
                          <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                      </th>
                </tr>
                </thead>
                <tbody id="mileage_depriciation_listing">
                    <?php 
                    $j = 0;
                    foreach($mileage_list as $value){
                        $j++;
                        ?>
                        <tr id="row-<?php echo  $value['id']; ?>">
                            <td class="a-center ">
                            <input type="checkbox" value="<?php echo  $value['id']; ?>" class="flat" name="table_records">
                            </td>
                            <td><?php echo $j ?></td>
                            <td><?php echo $value['millage_to']; ?></td>
                            <td><?php echo $value['millage_from']; ?></td>
                            <td><?php echo $value['millage_depreciation']; ?></td>
                            <td><?php echo $value['status']; ?></td>
                            <td>
                                <a href="<?php echo base_url().'cars/edit_mileage/'.$value['id']; ?>"  class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i>  Edit</a>

                                <button onclick="deleteRecord(this)" type="button" data-obj="valuation_millage" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>cars/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>

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
</div>
</div>

<script type="text/javascript">
    var checkboxes = $("input[type='checkbox']");
    $('input').on('ifChecked', function () {
        if(('checked').length > 0)
        { 
            $('#delete_bulk').show();
        }
    })
    $('input').on('ifUnchecked', function (){
        if(!checkboxes.is(":checked"))
        {
        $('#delete_bulk').hide();
        }
    })
</script>
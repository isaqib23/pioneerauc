<!-- <?php print_r($task_list); ?> -->
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

  <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="task" data-url="<?php echo base_url();?>jobcard/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete Selected Record</button>


  <div Mileage To="x_content"> 
    <div Mileage From="x_content"> 
        <div Mileage Depreciation="x_content"> 
          <a type="button" href="<?php echo base_url().'jobcard/add_task'; ?>"  class="btn btn-primary"><i class="fa fa-plus"></i> Add</a>
          <div class="clearfix"></div>
      </div>

      <div class="x_content">
       <?php if(!empty($task_list)){?>
        <div class="table-responsive" id="checkboxes">
          <table id="engine_sizes" class="table table-striped jambo_table bulk_action ">
            <!-- jambo_table bulk_action (for jambo action table) -->
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="check-all" class="flat">
                    </th>
                    <th class="column-title">#</th>
                    <th class="column-title">Title</th>
                    <th class="column-title">Description</th>
                    <th class="column-title">Created on</th>
                    <th class="column-title">updated on</th>
                    <th class="column-title">updated by</th>
                    <th class="column-title">Created By</th>
                    <th class="column-title">Status</th> 
                    <th data-orderable="false">Actions</th>
                    <th class="bulk-actions" colspan="7">
                          <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                      </th>
                </tr>
                </thead>
                <tbody id="mileage_depriciation_listing">
                   <?php 
                    $j = 0;
                    foreach($task_list as $value){
                        $j++;
                        ?> 
                        <tr id="row-<?php echo  $value['id']; ?>">
                            <td class="a-center ">
                            <input type="checkbox" value="<?php echo  $value['id']; ?>" class="flat" name="table_records">
                            </td>
                            <td><?php echo $j ?></td>
                            <td><?php echo $value['title']; ?></td>
                            <td><?php echo $value['description']; ?></td>
                            <td><?php echo $value['created_on']; ?></td>
                            <td><?php echo $value['updated_on']; ?></td>
                            <td><?php echo $value['updated_by']; ?></td>
                            <td><?php echo $value['created_by']; ?></td> 
                            <td><?php echo $value['status']; ?></td>                       
                           <!--  <td></td> -->
                            <td>
                                <a href="<?php echo base_url().'jobcard/edit_task/'.$value['id']; ?>" class="btn btn-sm btn-warning"> Edit</a>

                               <!--  <button type="button" id="<?php echo $value['id']; ?>" data-toggle="modal" data-target=".bs-example-modal-sm" class="btn btn-info btn-xs oz_assignto_model"><i class="fa fa-pencil-square-o"></i> Assign</button> -->

                                <button onclick="deleteRecord(this)" type="button" data-obj="task" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>jobcard/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                    <?php }?> 
                </tbody>
            </table>
        </div>
        <!-- <?php 
             }else{
                 echo "<h1>No Record Found</h1>";
                 }

             ?> -->
        </div>

       <!--   <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Assign To</h4>
            </div>
            <div class="modal-body">           
            </div>
          </div>
        </div>
      </div> -->
     <!--    <div class="modal fade bs-example-modal-sm2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel2">User Information</h4>
            </div>
            <div class="modal-body2">
              
            </div>

          </div>
        </div>
      </div> -->
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
    });

      $('input').on('ifChecked', function () { 
       $('#delete_bulk').show();
      })
       $('input').on('ifUnchecked', function (){
         $('#delete_bulk').hide();
       })

          $('.oz_assignto_model').on('click',function(){
              var url = '<?php echo base_url();?>';
              var id = $(this).attr("id");
              console.log(id);
               $.ajax({
                url: url + 'jobcard/add_task',
                type: 'POST',
                data: {id:id},
                }).then(function(data) {
                  var objData = jQuery.parseJSON(data);
                  console.log(objData);
                  if (objData.msg == 'success') 
                  {
                    $('.modal-body').html(objData.data);
                  }

          });
        });
</script>
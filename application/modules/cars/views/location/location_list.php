
    <div class="x_title">
        <h2>
            <?php echo $small_title; ?>
        </h2>
        <div class="clearfix"></div>
    </div>
      
       <?php if ($this->session->flashdata('msg')) {?>
        <div class="alert">
            <div class="alert alert-domain alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span>
                  </button>
                <?php echo $this->session->flashdata('msg'); ?>
            </div>
        </div>
        <?php }?>

         <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="valuation_location" data-url="<?php echo base_url(); ?>cars/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i>  Delete Selected Record</button>
        
    <div class=""> 
          <a type="button" href="<?php echo base_url().'cars/new_loaction'; ?>"  class="btn btn-md btn-success  "><i class="fa fa-plus"></i> New Location</a>
            <!-- <div class="clearfix"></div> -->
    </div>
    
    <div class="table-responsive">
          <table id="location_table" class="table table-striped jambo_table bulk_action ">
            <!-- jambo_table bulk_action (for jambo action table) -->
              <thead>
                  <tr class="headings">
                      <th>
                          <input type="checkbox" id="check-all" class="flat">
                      </th>
                      <th class="column-title">Name</th>
                      <th class="column-title no-link last"><span class="nobr">Action</span>
                      </th>
                      <th class="bulk-actions" colspan="7">
                          <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                      </th>
                  </tr>
              </thead>
              <tbody id="location_listing">
                  <?php  foreach($location_list as $value){ ?>
              
                    <tr id="row-<?php echo  $value['id']; ?>" class="even pointer">
                        <td class="a-center ">
                            <input type="checkbox" value="<?php echo  $value['id']; ?>" class="flat" name="table_records">
                        </td>
                        <td class=""><?php echo $value['name']; ?></td>
                        <td ><a class="btn btn-sm btn-warning" href="<?php echo base_url().'cars/edit_location/'.$value['id']; ?>">Edit</a> 
                        <button onclick="deleteRecord(this)" type="button" data-obj="valuation_location" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>cars/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
                      </td>
                    </tr>
                  <?php }?>
              </tbody>
          </table>
        </div>
          <script>
      $(document).ready(function() {
         $('#location_table').DataTable();
         });

            $('#delete_button').click(function(){
              var url = '<?php echo base_url();?>';
              var selected = "";
              $('#model_listing input:checked').each(function() {
                  selected+=$(this).attr('value')+",";
                  
                });

              $.ajax({
                      url: url + 'cars/delete_location/?ids='+selected,
                      type: 'POST',              
                  }).
                then(function(data) {
                var objData = jQuery.parseJSON(data);
                console.log(objData);
                 if (objData.msg == 'success') {
                  window.location = url + 'cars/location';
            }
           
        });
    });

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
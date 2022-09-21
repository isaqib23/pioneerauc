
    <div class="x_title">
        <h2>
            <?php echo $small_title; ?>
        </h2>
        <div class="clearfix"></div>
        <?php if ($this->session->flashdata('msg')) {?>
        <div class="alert">
            <div class="alert alert-domain alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span>
                  </button>
                <?php echo $this->session->flashdata('msg'); ?>
            </div>
        </div>
        <?php }?>

    </div>
    <div class="x_content bulk_actions" id="delete_bulk" style="display: none;">
      <label>Bulk Actions:</label>
      <button onclick="deleteRecord_Bulk(this)" type="button" data-obj="valuation_enginesize" data-url="<?php echo base_url(); ?>cars/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
      <hr>
    </div>
    <div class="clearfix"></div>
    
    <div class=""> 
          <a type="button" href="<?php echo base_url().'cars/add_engine_sizes'; ?>"  class="btn btn-md btn-success  "><i class="fa fa-plus"></i> Add Engine Size</a>
            <!-- <div class="clearfix"></div> -->
    </div>
    
    <div class="table-responsive">
      <table id="engine_sizes" class="table table-striped jambo_table bulk_action">
        <!-- jambo_table bulk_action (for jambo action table) -->
          <thead>
              <tr class="headings">
                  <!-- <th>
                      <input type="checkbox" id="check-all" class="flat">
                  </th> -->
                  <th class="column-title no-link last"><span class="nobr">Title</span> </th>
                  <th class="column-title no-link last"><span class="nobr">Action</span>
                  </th>
                  <th class="bulk-actions" colspan="7">
                      <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                  </th>
              </tr>
          </thead>
          <tbody id=engine_size_listing>
              <?php  foreach($eng_size_list as $value){ ?>
          
              <tr id="row-<?php echo  $value['id']; ?>" class="even pointer">
                  <!-- <td class="a-center ">
                      <input type="checkbox" value="<?php echo  $value['id'];?>" class="flat" name="table_records">
                  </td> -->
                  <td class=""><?php echo $value['title']; ?></td>
                  <td><a class="btn btn-xs btn-warning" href="<?php echo base_url().'cars/edit_engine_sizes/'.$value['id']; ?>"><i class="fa fa-pencil"></i> Edit</a>
                    <button onclick="deleteRecord(this)" type="button" data-obj="valuation_enginesize" data-token-name="<?= $this->security->get_csrf_token_name();?>" data-token-value="<?=$this->security->get_csrf_hash();?>" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>cars/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
                  </td>

            <!-- <td class="btn btn-sm btn-warning "> Edit</td> -->
              </tr>
              <?php }?>
          </tbody>
      </table>
    </div>
        <script>
            $(document).ready(function() {
            });
              $('#engine_sizes').DataTable();

            $('#delete_button').click(function(){
              var url = '<?php echo base_url();?>';
              var selected = "";
              $('#engine_size_listing input:checked').each(function() {
                selected+=$(this).attr('value')+",";
                
              });

              $.ajax({
                    url: url + 'admin/cars/delete_engine_sizes/?ids='+selected,
                    type: 'POST',                                            
                }).
              then(function(data) {
                var objData = jQuery.parseJSON(data);
                console.log(objData);
                if (objData.msg == 'success') {
                  window.location = url + 'admin/cars/engine_sizes';
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
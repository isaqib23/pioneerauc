
    <div class="x_title">
        <h2>
            <?php echo $small_title; ?>
        </h2>
        <div class="clearfix"></div>
    </div>
    
    <div class=""> 
          <a type="button" href="<?php echo base_url().'admin/cars/add_vehicle'; ?>"  class="btn btn-md btn-success  "><i class="fa fa-plus"></i> New Vehicle Detail</a>
            <!-- <div class="clearfix"></div> -->
    </div>
    
    <div class="table-responsive">
          <table id="engine_sizes" class="table table-striped jambo_table bulk_action ">
            <!-- jambo_table bulk_action (for jambo action table) -->
              <thead>
                  <tr class="headings">
                      <th>
                          <input type="checkbox" id="check-all" class="flat">
                      </th>
                      <th class="column-title">Car </th>
                      <th class="column-title">Engine </th>
                      <th class="column-title">Model </th>
                      <th class="column-title">Body </th>
                      <th class="column-title no-link last"><span class="nobr">Action</span>
                      </th>
                      <th class="bulk-actions" colspan="7">
                          <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                      </th>
                  </tr>
              </thead>
              <tbody>
                  <?php  foreach($car_datail_list as $value){ ?>
              
                    <tr id="row-<?php echo  $value['id']; ?>" class="even pointer">
                   
                        <td class="a-center ">
                            <input type="checkbox" class="flat" name="table_records">
                        </td>
                        <td class=""><?php echo $value['car_title']; ?></td>
                        <td class=""><?php echo $value['engine_title']; ?></td>
                        <td class=""><?php echo $value['model_type']; ?></td>
                        <td class=""><?php echo $value['body']; ?></td>
                        <!-- <td class=""><?php echo $value['body']; ?></td> -->
                        <td class="btn btn-sm btn-warning"><a href="<?php echo base_url().'admin/cars/edit_make/'.$value['id']; ?>">Edit</a></td>

                    </tr>
                  <?php }?>
              </tbody>
          </table>
          <script>
              $(document).ready(function() {
              $('#engine_sizes').DataTable();
              });
          </script>

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
        <div class="clearfix"></div>
    </div>  
    <div class=""> 
        <a type="button" href="<?php echo base_url().'cars/new_car'; ?>"  class="btn btn-md btn-success  "><i class="fa fa-plus"></i> New Car</a>
          <!-- <div class="clearfix"></div> -->
    </div>
    
    <div class="table-responsive">
        <table id="engine_sizes" class="table table-striped jambo_table bulk_action">
          <!-- jambo_table bulk_action (for jambo action table) -->
            <thead>
                <tr class="headings">
                    <th>
                        <input type="checkbox" id="check-all" class="flat">
                    </th>
                    <th class="column-title">Title </th>
                    <th class="column-title">Make </th>
                    <th class="column-title">Model </th>
                    <th class="column-title">Status </th>
                    <th class="column-title">Requestd By </th>
                    <th class="column-title">Date</th>
                    <th class="column-title no-link last"><span class="nobr">Action</span>
                    </th>
                    <th class="bulk-actions" colspan="7">
                        <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php  foreach($car_list as $value){ ?>
            
                <tr id="row-<?php echo  $value['id']; ?>" class="even pointer">
               <!-- <td><?php echo $j ?></td> -->
                    <td class="a-center ">
                        <input type="checkbox" class="flat" name="table_records">
                    </td>
                    <td class=""><?php echo $value['title']; ?></td>
                    <td class=""><?php echo $value['make_title']; ?></td>
                    <td class=""><?php echo $value['model_title']; ?></td>
                    <td class=""><?php echo $value['status']; ?></td>
                    <td class=""><?php echo $value['email']; ?></td>
                    <td class=""><?php echo $value['date']; ?></td>
                    <td ><button class="btn btn-sm btn-warning"><a href="<?php echo base_url().'cars/new_car/'.$value['id']; ?>">Edit</a></button><button class="btn btn-sm btn-info  "><i class="fa fa-eye "></i><a href="<?php echo base_url().'cars/car_list/'.$value['id']; ?>">View</a></button> <button class="             btn btn-sm btn-default  "><a href="<?php echo base_url().'cars/biding_car/'. $value['id']; ?>">Bidding</a></button> </td>

              <!-- <td class="btn btn-sm btn-warning "> Edit</td> -->
                </tr>
                <?php }?>
            </tbody>
        </table>
        <script>
            $(document).ready(function() {
            $('#engine_sizes').DataTable();
            });
        </script>


<table id="datatable-responsive" class="table  jambo_table bulk_action" cellspacing="0" width="100%">
  <thead>
    <?php $role = $this->session->userdata('logged_in')->role;  ?>
      <tr>
          <th data-orderable="false">
            <?php if($role == 1){ ?>
              <input type="checkbox" id="check-all" class="flat ozproceed_check" name="table_records">
            <?php } ?>
          </th>
          <th data-orderable="false">Image</th>
          <th>Name</th>
          <th>Category</th>
          <th>Registration #</th>
          <th>Make</th>
          <th>Model</th>
          <th>Reserve</th>
          <th>Seller Code</th>
          <th>Created On</th>
          <th>Status</th>
          <th data-orderable="false">Actions</th>
      </tr>
  </thead>
  <tbody class="">
       <?php if(!empty($items_list)){?>
        
                <?php 
                $j = 0;
                $have_documents = false;
                foreach($items_list as $value){
                    $j++;
                     $item_id = urlencode(base64_encode($value['id']));
                     $role = $this->session->userdata('logged_in')->role; 
                    if(!empty($value['item_images']) || !empty($value['item_attachments']))
                    {
                        $documents_class = 'btn-success';
                        $have_documents = true;
                        if(empty($value['item_attachments']))
                        {
                            $documents_class = 'btn-info';
                        }
                    }
                    else
                    {
                        $documents_class = 'btn-warning';
                    }

                    ?>
                    <tr id="row-<?php echo  $value['id']; ?>">
                        <td class="a-center ">
                        <input type="checkbox" class="flat multiple_rows ozproceed_check" id="<?php echo  $value['id']; ?>" name="table_records">
                        </td>
                       <td>
                        <a class="text-success" href="<?php echo base_url().'items/details/'.$item_id;  ?>">
                        <?php 
                          if(isset($value['item_images']) && !empty($value['item_images']))
                          {
                            $images_ids = explode(",",$value['item_images']);
                            $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
                          }
                          else
                          {
                            $files_array = array();
                          }
                          
                        ?>
                         <?php if(isset($files_array[0]['name']) && !empty($files_array[0]['name']))
                          {
                           ?>
                          <img style="max-width: 50px" class="img-responsive avatar-view" src="<?php echo base_url().'uploads/items_documents/'.$value['id'].'/'.$files_array[0]['name']; ?>" alt="Visa">
                        <?php }
                            else
                            { ?>
                          <img style="max-width: 50px" class="img-responsive avatar-view" src="<?php echo base_url().'assets_admin/images/product-default.jpg'; ?>" alt="Visa">
                        <?php } ?>
                        </a></td>

                        <td><label><a class="text-success" href="<?php echo base_url().'items/details/'.$item_id;  ?>"><?php echo ucwords($value['name']); ?></a></label></td>
                        <!-- <td><?php //echo $value['name']; ?></td> -->
                        <td><?php echo (isset($value['category_name']) && !empty($value['category_name'])) ? $value['category_name'] : ''; ?></td>
                        <!-- <td><?php //echo $value['unique_code']; ?></td> -->
                        <td><?php echo $value['registration_no']; ?></td>
                        <!-- <td><?php //echo $value['lot_id']; ?></td> -->
                        <td><?php echo (isset($value['make_name']) && !empty($value['make_name'])) ? $value['make_name'] : ''; ?></td>
                        <td><?php echo (isset($value['model_name']) && !empty($value['model_name'])) ? $value['model_name'] : ''; ?></td>
                        <td><?php echo (isset($value['price']) && !empty($value['price'])) ? $value['price'] : ''; ?></td>
                        <td><?php echo $value['seller_code']; ?></td>
                        <td><?php echo $value['created_on']; ?></td>
                        <!-- <td><?php //echo $value['updated_on']; ?></td> -->
                        <td><?php echo $value['item_status']; ?></td>
                        <td>
                            <a href="<?php echo base_url().'items/update_item/'.$value['id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>

                             <a href="<?php echo base_url().'items/documents/'.$value['id']; ?>" class="btn <?php echo $documents_class; ?> btn-xs"><i class="fa fa-pencil"></i> Documents</a>
                            <?php if($have_documents){ ?>
                             <a href="<?php echo base_url().'items/view_documents/'.$value['id']; ?>" class="btn <?php echo  $documents_class; ?> btn-xs"><i class="fa fa-pencil"></i> View Documents</a>
                            <?php } ?>
                             <button type="button" id="<?php echo $value['id']; ?>" data-toggle="modal" data-target=".bs-example-modal-print"  data-backdrop="static" data-keyboard="false" class="btn btn-info btn-xs oz_print_qr"><i class="fa fa-qrcode"></i> QR Code</button>
                             <button type="button" id="<?php echo $value['id']; ?>" data-toggle="modal" data-target=".bs-example-modal-banner"  data-backdrop="static" data-keyboard="false" class="btn btn-info btn-xs oz_banner_pr"><i class="fa fa-file"></i> Banner</button>

                            <button onclick="deleteRecord(this)" type="button" data-obj="item" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>items/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>

                        </td>
                    </tr>
                <?php }?>
            
        <?php 
    }else{
        echo '<tr  ><td colspan="8"><h3 class="text-center">No Record Found</h3></td></tr>';
    }
    ?>
 </tbody>
</table>
<!-- <script src="<?php echo base_url();?>assets_admin/vendors/iCheck/icheck.min.js"></script> -->
<!-- <script src="<?php echo base_url();?>assets_admin/vendors/datatables.net/js/jquery.dataTables.min.js"></script> -->
<script type="text/javascript">

 
   $("#datatable-responsive").DataTable({
            dom: "Bfrtip",
            buttons: [            
            
            {
              extend: "csv",
              className: "btn-sm",
              exportOptions: 
              {
                  columns: [1,2,3,4,5,6,7,8,9,10]
              }
            },
            {
              extend: "excel",
              className: "btn-sm",
              exportOptions: 
              {
                  columns: [1,2,3,4,5,6,7,8,9,10]
              }
            },
            {
              extend: "pdfHtml5",
              className: "btn-sm",
              exportOptions: 
              {
                  columns: [1,2,3,4,5,6,7,8,9,10]
              }
            }, 
            ],
            responsive: true, 
            columnDefs: [ 
              { targets:"_all" },
              { targets:[11], className: "tablet, mobile" }
            ]
          });

$('.oz_print_qr').on('click',function(){
    var url = '<?php echo base_url();?>';
    var id = $(this).attr("id");
    console.log(id);
     $.ajax({
      url: url + 'items/get_qrcode',
      type: 'POST',
      data: {id:id},
      }).then(function(data) {
        var objData = jQuery.parseJSON(data);
        console.log(objData);
        $('#add_items').hide(); 
        if (objData.msg == 'success') 
        {
          $('.modal-qr-body').html(objData.data);
        }
      });
});

 $('.oz_banner_pr').on('click',function(){
         // alert('clicker');
    var url = '<?php echo base_url();?>';
    var id = $(this).attr("id");
    console.log(id);
     $.ajax({
      url: url + 'items/get_banner_details',
      type: 'POST',
      data: {id:id},
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
  
</script>
<style type="text/css">
#page-wrapper{
	margin-top:100px;
}
</style> 
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
  
            <div class="clearfix"></div>
 
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Roles <small>List</small></h2>
 
                    <div class="clearfix"></div>
                  </div>
                  <?php if($this->session->flashdata('msg_error')){ ?>
                    <div class="alert">
                    <div class="alert alert-danger alert-dismissible fade in" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                      </button>
                        <?php  echo $this->session->flashdata('msg_error');   ?>
                    </div>
                    </div>
                    <?php }?>
                    <?php if($this->session->flashdata('msg')){ ?>
                    <div class="alert">
                    <div class="alert alert-success alert-dismissible fade in" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                      </button>
                        <?php  echo $this->session->flashdata('msg');   ?>
                    </div>
                    </div>
                    <?php }?>
                    <div class="x_content"> 
                  <a type="button" href="<?php echo base_url().'acl/Acl_roles/add_acl_roles_form/'; ?>"  class="btn btn-primary"><i class="fa fa-plus"></i> Add</a>
                    <div class="clearfix"></div>
                  	</div>

                  <div class="x_content">
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th>description</th>
                          <th>Action</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                       	<?php 
                      	if(count($all_roles) > 0 ){
                          $i = 0;
                          $role = $this->session->userdata('logged_in')->role; 
                  foreach ($all_roles as $key => $value) { 
                          $i++;
                      	 ?>
                        <tr id="row-<?php echo $value['id']; ?>">
                          <td><?php echo $i;?></td>
                          <td><?php echo $value['name']?></td>
                          <td><?php echo $value['description']?> </td> 
                          <td>
                            <a href="<?php echo base_url().'acl/Acl_roles/edit_acl_roles_form/'.$value['id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                          <?php if($role == '-1'){ ?>
                            <!-- <button onclick="deleteRecord(this)" data-obj="acl_roles"  type="button"  data-id="<?php //echo $value['id']; ?>" data-url="<?php //echo base_url(); ?>acl/Acl_roles/delete" class="btn btn-danger btn-xs"  title="Delete" ><i class="fa fa-trash"></i> Delete </button> -->
                          <?php } ?>
                            <a href="<?php echo base_url().'acl/Acl_roles/permissions/'.$value['id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-gears"></i> Permissions </a>
                          </td>
                        </tr>

                        <?php
                        	 }
                    		}
                    	?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
          </div>

<script>

 var url_val = '<?php echo base_url()."acl/Domain/delete_domain_list_row/"?>';
 $('#datatable-responsive').dataTable({
    columnDefs: [{ orderable: false, targets: -1}]
  });
</script> 
	 


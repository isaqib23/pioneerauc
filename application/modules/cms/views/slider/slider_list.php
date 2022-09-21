
<style type="text/css">
    #page-wrapper {
        margin-top: 100px;
    }
</style>
<div class="clearfix"></div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>
                <?php echo $small_title; ?>
            </h2>
            <div class="clearfix"></div>
        </div>
        <?php if($this->session->flashdata('success')){ ?>
        <div class="alert">
            <div class="alert alert-success alert-dismissible fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black;"><span aria-hidden="true">×</span>
              </button>
              <?php  echo $this->session->flashdata('success');   ?>
          </div>
        </div>
        <?php }?>
        <div> 
          <a type="button" href="<?php echo base_url().'cms/add_slider'; ?>"  class="btn btn-success"><i class="fa fa-plus"></i> Add</a>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
        <?php
        
        if(!empty($slider_info))
        {

        ?>
        <table id="datatable" class="table table-striped" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Slider Image</th>
                    <th>Small Title</th>
                    <th>Title</th>
                    <th>Category Name</th>
                    <th>Status</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
            <tbody class="content_auction_items">
            <?php 
                $j = 0;
                $count = 1;
                 if (isset($slider_info) && !empty($slider_info))
                foreach($slider_info as $value)
                {
                    $question = json_decode($value['title']);
                    $small_title = json_decode($value['small_title']);
                    $categoryItem = $this->db->get_where('item_category',['id' => $value['category_id']])->row_array();
                    // print_r($value['category_id']);
                    $categoryName = json_decode($categoryItem['title']);
                    ?>
                    <tr id="row-<?php echo  $value['id']; ?>">
                        <td><?php echo $count++?></td>
                        <td>
                             <?php if(isset($slider_info)){
                             $pfile = $this->db->get_where('files', ['id' => $value['image']])->row_array(); 
                                ?>
                            <img src="<?= base_url($pfile['path'] . $pfile['name']); ?>" height="40" width="40" style="margin-top: 15px">
                        <?php } ?>
                        </td>
                        <td><?php echo (isset($question) && !empty($question)) ? $question->english : ''; ?></td>
                        <td><?php echo (isset($small_title) && !empty($small_title)) ? $small_title->english : ''; ?></td>
                        <td><?php echo (isset($categoryItem) && !empty($categoryItem)) ? ucfirst($categoryName->english) : ''; ?></td>
                        <td><?php echo (isset($value['status']) && !empty($value['status'])) ? ucfirst($value['status']) : ''; ?></td>
                        <td>
                            <a href="<?php echo base_url().'cms/edit_slider/'.$value['id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                              
                            <button onclick="deleteRecord(this)" type="button" data-obj="home_slider" data-token-name="<?= $this->security->get_csrf_token_name();?>" data-token-value="<?=$this->security->get_csrf_hash();?>" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>cms/delete_slider" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button> 
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
    <?php 
        }
        else
        {
            echo "<h1>No Record Found</h1>";
        }
    ?>
        </div>
    </div>
</div>

<script src="<?php echo base_url();?>assets_admin/js/formBuilder/jquery-ui.min.js"></script>
<script src="<?php echo base_url();?>assets_admin/js/formBuilder/form-render.min.js"></script>
<style type="text/css">
    .dz-image img{
    max-width: 120px;
    max-height: 120px;
    }
</style>
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

<div class="clearfix"></div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
          <?php 
          $cat_id = $this->uri->segment(3);
          $name = $this->db->get_where('item_category',['id' =>$cat_id])->row_array();
          $name_itm = json_decode($name['title']);
          ?>
            <h2>
               <?php echo 'Catagories Sorting For '?> <?= $name_itm->english;?>
            </h2>

            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div id="result"></div>

            <?php if($this->session->flashdata('msg')){ ?>
            <div class="alert">
                <div class="alert alert-domain alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span>
                      </button>
                    <?php echo $this->session->flashdata('msg'); ?>
                </div>
            </div>
            <?php }?>
           <?php if(!empty($cat_data)){?>
            <form method="post" action="<?php echo base_url('items/categories_sort_save');?>" class="form-horizontal form-label-left">
              <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_id">Sort order 1 <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12"  name="1" required="required">
                      <?php 
                      foreach ($cat_data as $key => $cat) {?>
                        <option
                          <?php if(isset($sort_data) && !empty($sort_data)){
                             
                               if($sort_data[0]['field_id'] == $cat['id']){ echo 'selected';}
                           
                          }?>
                            value="<?php echo $cat['id']; ?>"><?php if(isset($cat)) echo $cat['label']; ?>
                        </option>
                        
                      <?php }?>
                    </select>
                </div>
                </div>  
                <input type="hidden" name="cat_id" value="<?php if(isset($cat)) echo $cat['category_id'];?>" >
                <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_id">Sort order 2 <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12"  name="2" required="required">
                      <?php 
                      
                      foreach ($cat_data as $key => $cat) { ?>
                        <option 
                          <?php if(isset($sort_data) && !empty($sort_data)){
                            // foreach ($sort_data as $sort_key => $val) {
                              if($sort_data[1]['field_id'] == $cat['id']){ echo 'selected';}
                            // }
                          }?>
                           value="<?php echo $cat['id']; ?>"><?php if(isset($cat)) echo $cat['label']; ?>
                        </option>
                      <?php }?>
                    </select>
                </div>
                </div>

                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_id">Sort order 3 <span class="required">*</span></label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <select class="form-control col-md-7 col-xs-12"  name="3" required="required">
                        <?php 
                        foreach ($cat_data as $key => $cat) {?>
                          <option 
                            <?php if(isset($sort_data) && !empty($sort_data)){
                            // foreach ($sort_data as $sort_key => $val) {
                              // if($val['field_id'] == $cat['id']){ echo 'selected';}
                            // }
                              if($sort_data[2]['field_id'] == $cat['id']){ echo 'selected';}
                          }?>
                             value="<?php echo $cat['id']; ?>"><?php if(isset($cat)) echo $cat['label']; ?>
                          </option>
                        <?php }?>
                      </select>
                  </div>
                </div>

                <div class="item form-group">
                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_id">Sort order 4  <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12"  name="4" required="required">
                      <?php 
                      foreach ($cat_data as $key => $cat) {?>
                        <option 
                          <?php if(isset($sort_data) && !empty($sort_data)){
                            // foreach ($sort_data as $sort_key => $val) {
                            //   if($val['field_id'] == $cat['id']){ echo 'selected';}
                            // }
                             if($sort_data[3]['field_id'] == $cat['id']){ echo 'selected';}
                          }?>
                         value="<?php echo $cat['id']; ?>"><?php if(isset($cat)) echo $cat['label']; ?>   
                        </option>
                      <?php }?>
                    </select>
                </div>
                </div>
                <a type="button" href="<?php echo base_url('items/categories');?>" class="btn btn-primary fa fa-arrow-left ">  Back</a>
                <button type="submit" id="send" class="btn btn-success">Submit</button>      
            </form>
        <?php }else{?>
                <h3>No Feilds Available.</h3>
       <?php }?>
        </div>
    </div>
    <div id="map"></div>       
</div>

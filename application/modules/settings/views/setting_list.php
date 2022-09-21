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
        <div class="x_content"></div>
          <?php if($this->session->flashdata('msg')){ ?>
        <div class="alert">
            <div class="alert alert-success alert-dismissible fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black;"><span aria-hidden="true">×</span>
              </button>
              <?php  echo $this->session->flashdata('msg');   ?>
          </div>
      </div>
    <?php }?>
    <div> 
    <div> 
        <a type="button" href="<?php echo base_url().'items/save_item'; ?>"  class="btn btn-success"><i class="fa fa-plus"></i> Add</a>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
       <?php if(!empty($items_list)){?>
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

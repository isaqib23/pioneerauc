<!-- Design Management start -->
<div class="page-title">
  <div class="title_left">
    <h3>Expenses <small></small></h3>
  </div>
  <div class="title_right"></div>
</div>

<?php //print_r($history); ?>

<?php if($this->session->flashdata('error')){ ?>
  <div class="alert alert-danger alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Error!</strong> <?= $this->session->flashdata('error'); ?>
  </div>
<?php } ?>

<?php if($this->session->flashdata('success')){ ?>
  <div class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Success!</strong> <?= $this->session->flashdata('success'); ?>
  </div>
<?php } ?>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Item Expenses <small></small></h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <br>
        <form name="myform" method="post"
          action="<?= base_url('items/add_item_expense'); ?>" 
          id="frm" 
          class="form-horizontal form-label-left">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
          <input type="hidden" name="item_id" id="item_id" value="<?= $item['id']; ?>" />
          <?php $item_name = json_decode($item['name']); ?>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="itemname">Item Name <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" 
                name="itemname" 
                id="itemname"
                value="<?php if(isset($item)){ echo $item_name->english;} ?>"
                disabled="" 
                class="form-control col-md-7 col-xs-12" />
              <div class="itemname-error text-danger"></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Expense Title <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" 
                name="title" 
                id="title"
                class="form-control col-md-7 col-xs-12" />
              <div class="title-error" id="slots-error"></div>
            </div>
          </div>

          <div class="item form-group">    
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description <span class="required"></span>
                  </label>

              <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="description" rows="4" name="description" class="form-control col-md-7 col-xs-12"> </textarea>
              </div>
          </div> 

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="amount">Expense Amount <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" 
                name="amount"
                id="amount"
                value="0" 
                oninput="this.value=this.value.replace(/[^0-9.]/g,'');"
                class="form-control col-md-7 col-xs-12 parsley-success validThis" />
              <div class="amount-error text-danger"></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="amount">Apply Vat <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select name="apply_vat" id="apply_vat" class="form-control col-md-7 col-xs-12">
                <option value="yes">Yes</option>
                <option value="no">No</option>
              </select>
              <div class="amount-error text-danger"></div>
            </div>
          </div>

          <div class="ln_solid"></div>
          <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              <a href="<?= $data['back_url']; ?>" class="btn btn-primary">Back</a>
              <input type="submit" class="btn btn-success" value="Add" id="add_expense" />
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Expense Detail <small>Expense history for <?php if(isset($item)){echo $item_name->english;}?></small></h2>
        <!-- <div class="nav navbar-right">
          <h2>Total Package Power: <?= $total_package_power; ?> TH/s</h2>
        </div> -->
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <table id="datatable" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Id</th>
              <th>Title</th>
              <th>Amount</th>
              <th>Apply Vat</th>
              <th>Created On</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            if( (isset($item_expencses)) && !(empty($item_expencses)) ){
              foreach ($item_expencses as $key => $value) {
                ?>
                <tr>
                  <td><?= $value['id']; ?></td>
                  <td><?= $value['title']; ?></td>
                  <td><?= $value['amount']; ?></td>
                  <td><?= ucfirst($value['apply_vat']); ?></td>
                  <td><?= $value['created_on']; ?></td>
                  <td>
                    <button onclick="deleteRecord(this)" type="button" data-token-name="<?= $this->security->get_csrf_token_name();?>" data-token-value="<?=$this->security->get_csrf_hash();?>" data-obj="item_expencses" data-id="<?= $value['id']; ?>" data-url="<?=base_url().'items/delete'; ?>" class="btn btn-danger btn-xs" title="Remove"><i class="fa fa-trash"></i> Remove </button>
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
selectedInputs = ['title','amount'];
$(document).ready(function(){
  $('#datatable').DataTable();
});


$('#add_expense').on('click', function(event){
  event.preventDefault();
  var validation = false;
  //selectedInputs = ['auction_id','auction_item_id','deposit'];
  validation = validateFields(selectedInputs);
  if(validation == false){
    return false;
  }
  if(validation == true){
    $(this).closest("form").submit();
  }
});

</script>
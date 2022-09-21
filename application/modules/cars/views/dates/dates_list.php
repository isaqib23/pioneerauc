
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

<div class="clearfix"></div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2><?php echo $small_title; ?></h2>

        <div class="clearfix"></div>
    </div>
    <?php if($this->session->flashdata('msg')){ ?>
        <div class="alert">
            <div class="alert alert-success alert-dismissible fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black;"><span aria-hidden="true">×</span>
              </button>
              <?php  echo $this->session->flashdata('msg');   ?>
          </div>
      </div>
  <?php }?>

  <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="valuation_dates" data-url="<?php echo base_url(); ?>cars/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i>  Delete Selected Record</button>

  <div Mileage To="x_content"> 
    <div Mileage From="x_content"> 
        <div Mileage Depreciation="x_content"> 
          <a type="button" href="<?php echo base_url().'cars/add_dates'; ?>"  class="btn btn-primary"><i class="fa fa-plus"></i> Add</a>
          <div class="clearfix"></div>
      </div>

      <div class="x_content">
       <?php if(!empty($dates_list)){?>
        <table id="datatable-responsive" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="check-all" class="flat">
                    </th>
                    <th>#</th>
                    <th>Location</th>
                    <th>Dates</th>
                    <th>Action</th>                 
                </tr>
            </thead>
            <tbody id="dates_listing">
                <?php 
                $j = 0;
                foreach($dates_list as $value){
                    $j++;
                    ?>
                    <tr id="row-<?php echo  $value['id']; ?>">
                        <td class="a-center ">
                        <input type="checkbox" value="<?php echo  $value['id'];?>" class="flat" name="table_records">
                        </td>
                        <td><?php echo $j ?></td>
                        <td><?php echo $value['location_name']; ?></td>
                        <td><?php echo $value['times']; ?></td>
                        <td><button class="btn btn-sm btn-warning"><a href="<?php echo base_url().'cars/edit_dates/'.$value['id']; ?>">Edit</a></button>
                          <button onclick="deleteRecord(this)" type="button" data-obj="valuation_dates" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>cars/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
      </div>
        <?php 
    }else{
        echo "<h1>No Record Found</h1>";
    }
    ?>
</div>
</div>
</div>
</div>

<script type="text/javascript">
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 3000);

    $('#delete_button').click(function(){
                  var url = '<?php echo base_url();?>';
                  var selected = "";
                  $('#dates_listing input:checked').each(function() {
                    selected+=$(this).attr('value')+",";
                    
                  });

                  $.ajax({
                        url: url + 'cars/delete_dates/?ids='+selected,
                        type: 'POST',                                            
                    }).
                  then(function(data) {
                  var objData = jQuery.parseJSON(data);
                  console.log(objData);
                   if (objData.msg == 'success') {
                    window.location = url + 'cars/dates';

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
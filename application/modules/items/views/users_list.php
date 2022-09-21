
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

<div class="clearfix"></div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <!-- <h2><?php echo $small_title; ?></h2> -->

            <form method="post" id="demo-form2" action="" enctype="" class="form-horizontal form-label-left ">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <div class="form-group ">   
                    <div class="col-md-8 col-sm-12 col-xs-8  col-md-offset-1">
                      <input type="text" id="searchby" value="" name="searchby" placeholder="Search" class="form-control">
                    </div>
                </div>
            </form>

            <div class="clearfix"></div>
        </div>

        <?php if(!empty($users_list)){?>
        <div class="x_content">
            <table id="userdatatable" class="table jambo_table bulk_action" cellspacing="0" width="100%">
                <thead>
                    <?php $role = $this->session->userdata('logged_in')->role;  ?>
                    <tr>
                        <th>#</th>
                        <th>User Name</th>
                        <th>phone Number</th>
                    </tr>
                </thead>
                <tbody>
                   
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
<script>
    var table = $('#userdatatable');
        // table.clear();
    let exportOptions = {'rows': {selected: true} ,columns: ['2,3']};
    let exportOptionsSelect = { modifier: { selected: null} };
    let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
    let dt1 = customeDatatableOzair({
        // index zero must be csrf token
        '<?= $this->security->get_csrf_token_name();?>':'<?= $this->security->get_csrf_hash();?>',
        'div' : '#userdatatable',
        'url' : '<?= base_url()?>items/load_user',
        'orderColumn' : 1,
        'iDisplayLength' : 5,
        // 'dom' : 'fr<t><"bottom"lip><"clear">', // r<t><"bottom"lip><"clear">
        "dom": '<"top"i>rt<"bottom"lfp>',
        'columnCustomeTypeTarget' : 2,
        'defaultSearching': false,
        'responsive': true,
        'rowId': 'id',
        'stateSave': false,
        'lengthMenu': [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]], // [5, 10, 25, 50, 100], [5, 10, 25, 50, 100]
        'order' : 'asc',
        'selectStyle' : 'single',
        'collectionButtons' : dtButtons,
        // 'exportOptionsSelect' : exportOptionsSelect,
        'processing' : true,
        'serverSide' : true,
        'processingGif' : '<span style="display:inline-block; margin-top:-105px;"> <img  src="<?=base_url()?>/assets_admin/images/load.gif" /></span>',
        'buttonClassName' : 'btn-sm',
        'columnCustomType' : '',
        'customFieldsArray': ['searchby'],
        'dataColumnArray': [{'data':'id'},{'data':'fname'},{'data':'mobile'}]
    });

    $(document).ready(function(){
        dt1.clear();
    });
    $('#searchby').keyup(function(){
         dt1.draw();
    });

    // $('#userdatatable').selectStyle(function(){
    //      dt1.draw();
    // });
</script>

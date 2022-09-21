
<?= $this->load->view('template/auction_cat');?>
<div class="main gray-bg">
    <div class="row custom-wrapper deposit-h">
    	<?= $this->load->view('template/template_user_leftbar') ?>
        <div class="right-col">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="section-title text-left"><?= $this->lang->line('uploaded_documents');?> &nbsp; 
                        <a id="security" href="<?= base_url('customer/docs');?>"><i class="fa fa-plus" style="color: #d62228;"></i></a>
                         <script>
                                $( document ).ready(function() {
                                    $("#security").tooltip({
                                      title:"<?= $this->lang->line('upload_documents'); ?>",
                                      placement:"bottom"
                                    });
                                });
                            </script>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <form class="customform">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                        <div class="wrap">
                            <input type="text" placeholder="<?= $this->lang->line('search');?>" class="form-control" name="searchval" id="searchval">
                            <div class="icon">
                                <img src="<?= NEW_ASSETS_USER;?>images/search-icon.png">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="deposit-wrapper document-card">
                    <table id="documents_table" class="table_id display">
                        <thead>
                            <tr>
					            <th class="nmae"><?= $this->lang->line('name');?></th>
                                <th class="item"><?= $this->lang->line('item');?></th>
					            <th class="created_on"><?= $this->lang->line('created_on');?></th>
					        </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if($docs){
                                foreach ($docs as $key => $doc) {
                                    $file_ids = explode(',', $doc['file_id']);
                                    $files = $this->db->where_in('id', $file_ids)->get('files')->result_array();
                                    ?>

                                    <tr>
                                        <td>
                                            <?php 
                                            $doc_name = json_decode($doc['document_type']);
                                            ?>
                                            <?= $doc_name->$language; ?>
                                            <!-- <div class="image">
                                                <img src="assets/images/">
                                            </div> -->
                                        </td>
                                        <td>
                                            <ul>
                                            <?php 
                                            if($files){
                                                $i = 0;
                                                foreach ($files as $k => $file) {
                                                    $i++;
                                                    ?>
                                                    <li>
                                                        <a download="" href="<?= base_url('uploads/users_documents/').$user_id.'/'.$file['name']; ?>"><?= 'FILE-'.$i; ?></a>
                                                    </li>
                                                    <?php
                                                }
                                            }else{
                                                ?>
                                                <li>No files found.</li>
                                                <?php
                                            }
                                            ?>
                                            </ul>
                                        </td>
                                        <td class="ltr"><?= $doc['created_on']; ?></td>
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


<!-- <script type="text/javascript">      

	$(document).ready(function(){
		$('#security_table').DataTable().destroy();

		let exportOptions = {'rows': {selected: true} ,columns: ['2,3,4']};
		let exportOptionsSelect = { modifier: { selected: null} };
		let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
		let dt1 = customeDatatable({
		'div' : '#security_table',
		'url' : '<?//= base_url()?>customer/get_security',
		'orderColumn' : 0,
		'iDisplayLength' : 10,
		// 'dom' : 'fr<t><"bottom"lip><"clear">', // r<t><"bottom"lip><"clear">
		"dom": '<"top"i>rt<"bottom"flp>',
		'columnCustomeTypeTarget' : 2,
		'defaultSearching': false,
		'responsive': true,
		'rowId': 'id',
		'stateSave': true,
		'lengthMenu': [[ 10, 25, 50, 100, 150], [10, 25, 50, 100, 150]], // [5, 10, 25, 50, 100], [5, 10, 25, 50, 100]
		'order' : 'desc',
		'selectStyle' : 'multi',
		'collectionButtons' : dtButtons,
		// 'exportOptionsSelect' : exportOptionsSelect,
		'processing' : true,
		'serverSide' : true,
		'processingGif' : '<div class="loader-wrapper" ><div class="sk-spinner sk-spinner-wave"><div class="sk-rect1"></div> <img src="<?//= NEW_ASSETS_IMAGES; ?>load.gif"> <div class="sk-rect2"></div> <div class="sk-rect3"></div> <div class="sk-rect4"></div> <div class="sk-rect5"></div> </div> </div>',
		'buttonClassName' : 'btn-sm',
		'columnCustomType' : '',
		'customFieldsArray': ['searchval'],
		'dataColumnArray': [{'data':'id'},{'data':'deposit_mode'},{'data':'deposit'},{'data':'created_on'},{'data':'status'}]
		});
	});

    $('#searchval').keyup(function(){
		dt1.draw();
    });

    </script> -->
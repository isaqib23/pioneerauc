<?php
$userId = $this->session->userdata('logged_in');
// $data=$this->db->get_where('users',['id'=>$userId])->row_array();
$docList = $this->db->get('documents_type')->result_array();
$id = $this->session->userdata('logged_in');
$CustomerId = $id->id;
?>
 <link href="<?php echo base_url();?>assets_admin/vendors/dropzone/dist/min/dropzone.min.css" rel="stylesheet">
 <script src="<?php echo base_url();?>assets_admin/vendors/dropzone/dist/dropzone.js"></script>
 <script src="<?php echo base_url();?>assets_admin/vendors/moment/min/moment.min.js"></script>
 <?= $this->load->view('template/auction_cat');?>
 <div class="main gray-bg document">
 <div class="row custom-wrapper">
    <?= $this->load->view('template/template_user_leftbar') ?>

 <div class="right-col">
                <h1 class="section-title"><?= $this->lang->line('docs');?></h1>
                <div class="content-box">
                    <h2><?= $this->lang->line('reqired_upload');?></h2>
                    <form class="customform">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                        <div class="form">
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="hidden" name="item_id" value="<?php $id;?>"></input>
                                    <select class="selectpicker form-control ct" id="cats" title="Document Type">
                                        <?php
                                        foreach ($docList as $key => $value) {
                                        ?>
                                        <?php $docs_name =json_decode($value['name']); ?>
                                        <option <?php if($key == 0){ echo 'selected'; }?> value="<?php echo $value['id'];?>"><?php echo $docs_name->$language;?></option>
                                       <?php  }?>
                                    </select>
                                </div>
                                </div>
                                <div class="col-md-12">
                                <div class="dz-message">
                                    <!-- <h3>Drop files here</h3> or <strong>click</strong> to upload -->
                                <div id="dz0" class="dropzone"></div>
                                </div>
                                <!-- <div id="dz1" class="dropzone"></div> -->
                                </div>
                                </div>
                                <div class="button-row">
                                    <button type="button" id="send" class="btn btn-default"><?= $this->lang->line('update');?></button>
                                </div>
                                </div>
                            </div>
                        </div>  
                    </form>
                </div>
            </div>
        </div>
	</div>	

<script>
    var catid = $('#cats').find(":selected").val();
    var user_id = '<?php echo (isset($CustomerId)) ? json_decode($CustomerId) : ''; ?>';
    var get_docs = '<?php echo (isset($item_documents)) ? json_encode($item_documents) : ''; ?>';
    var base_url = "<?= base_url('uploads/users_documents/'); ?>";

    $("#cats").change(function(){
    var catid =$('#cats').val();
        $.ajax({
               url:"<?php echo base_url('customer/docs/'); ?>",
               method:"POST",
               data:{'catid':catid,"<?= $this->security->get_csrf_token_name();?>":"<?=$this->security->get_csrf_hash();?>"},
               success:function(data)
               {
                $('#dz0').remove();
                $('.dz-message').html('<div id="dz0" class="dropzone"></div>');
                    abc = new makeDropzone({
                        'url': "<?php echo base_url('customer/save_user_documents/'); ?>",
                        'deleteURL':"<?php echo base_url('customer/delete_customerDocs/'); ?>",
                        'div':"#dz0",
                        'paramName':"images",
                        'maxFilesize':8,
                        "dictDefaultMessage": "Drag & drop image here",
                        'maxFiles':30,
                        'acceptedFiles':'png,.jpg,.pdf,.doc,.docx,.ppt,.pptx,.txt,.xls,.xlsx',
                        'uploadMultiple':true,
                        'existedFiles':data,
                        'existedFilesPath':"<?= base_url('uploads/users_documents/'); ?>"+user_id+"/",
                        'iconPath':"<?= base_url('assets_admin/images/file-icons/'); ?>"
                    });

               }
        }); 
    });
        
    var abc = new makeDropzone({
        'url': "<?php echo base_url('customer/save_user_documents/'); ?>"+catid,
        'deleteURL':"<?php echo base_url('customer/delete_customerDocs/'); ?>",
        'div':"#dz0",
        'paramName':"images",
        'maxFilesize':8,
        "dictDefaultMessage": "Drag & drop image here",
        'maxFiles':30,
        'acceptedFiles':'png,.jpg,.pdf,.doc,.docx,.ppt,.pptx,.txt,.xls,.xlsx',
        'uploadMultiple':true,
        'existedFiles':get_docs,
        'existedFilesPath':"<?= base_url('uploads/users_documents/'); ?>"+user_id+"/",
        'iconPath':"<?= base_url('assets_admin/images/file-icons/'); ?>"
     });
        
    var currentFile = null;
    var status_done_doc = 'false';
    var thumb = "<?php echo base_url('assets_user/images/logo.png');?>";
    function makeDropzone(options){
        Dropzone.autoDiscover = false;
         var dz = new Dropzone(options.div, {
            url: options.url,
            paramName: options.paramName,
            dictDefaultMessage: options.dictDefaultMessage,
            maxFilesize: options.maxFilesize,
            maxFiles: options.maxFiles,
            acceptedFiles : options.acceptedFiles,
            addRemoveLinks : true,
            // reset : true,
            dictRemoveFile : 'X',
            parallelUploads: 10,
            uploadMultiple: options.uploadMultiple,
            autoProcessQueue: false,
            thumbnailWidth: 150,
            thumbnailHeight: 150,
            previewsContainer: options.div,  
            init: function() { 
                this.on('addedfile', function(file){
                    // this.emit("thumbnail", file,thumb);
                    this.options.addRemoveLinks = true;
                    this.options.dictRemoveFile = 'X';
                     var extension = file.name.substr( (file.name.lastIndexOf('.')) );
                    if(file.type){
                        if (extension.match(/.pdf/)) {
                            this.emit("thumbnail", file, options.iconPath+'pdf-icon.png');
                        }
                        if (extension.match(/.ai/)) {
                            this.emit("thumbnail", file, options.iconPath+'ai-icon.png');
                        }
                        if (extension.match(/.docx/)) {
                            this.emit("thumbnail", file, options.iconPath+'docx-icon.png');
                        }
                        if (extension.match(/.doc/)) {
                            this.emit("thumbnail", file, options.iconPath+'docx-icon.png');
                        }
                        if (extension.match(/.eps/)) {
                            this.emit("thumbnail", file, options.iconPath+'eps-icon.png');
                        }
                        if (extension.match(/.id/)) {
                            this.emit("thumbnail", file, options.iconPath+'id-icon.png');
                        }
                        if (extension.match(/.ppt/)) {
                            this.emit("thumbnail", file, options.iconPath+'ppt-icon.png');
                        }
                        if (extension.match(/.pptx/)) {
                            this.emit("thumbnail", file, options.iconPath+'ppt-icon.png');
                        }
                        if (extension.match(/.psd/)) {
                            this.emit("thumbnail", file, options.iconPath+'psd-icon.png');
                        }
                        if (extension.match(/.xlsx/)) {
                            this.emit("thumbnail", file, options.iconPath+'xlsx-icon.png');
                        }
                        if (extension.match(/.txt/)) {
                            this.emit("thumbnail", file, options.iconPath+'txt-icon.png');
                        }
                        if (extension.match(/.xls/)) {
                            this.emit("thumbnail", file, options.iconPath+'xlsx-icon.png');
                        }    
                    }
                });
                if(options.existedFiles){
                    var existedFiles = JSON.parse(options.existedFiles);  
                    for(let i = 0; i < existedFiles.length; i++) {
                        let existedFile = existedFiles[i];
                        // Create the mock file:
                        var mockFile = {name: existedFile.name, size: existedFile.size, url: existedFile.url};
                        var extension = existedFile.name.substr( (existedFile.name.lastIndexOf('.') +1) );
                        this.options.addedfile.call(this, mockFile);
                        console.log(extension);
                        if(extension == 'pdf'){
                            this.options.thumbnail.call(this, mockFile, options.iconPath+'pdf-icon.png');
                        }else if(extension == 'docx'){ 
                            this.options.thumbnail.call(this, mockFile, options.iconPath+'docx-icon.png');
                        }else if(extension == 'doc'){ 
                            this.options.thumbnail.call(this, mockFile, options.iconPath+'docx-icon.png');
                        }else if(extension == 'xlsx'){
                            this.options.thumbnail.call(this, mockFile, options.iconPath+'xlsx-icon.png');
                        }else if(extension == 'xls'){
                            this.options.thumbnail.call(this, mockFile, options.iconPath+'xlsx-icon.png');
                        }else if(extension == 'ppt'){
                            this.options.thumbnail.call(this, mockFile, options.iconPath+'ppt-icon.png');
                        }else if(extension == 'pptx'){
                            this.options.thumbnail.call(this, mockFile, options.iconPath+'ppt-icon.png');
                        }else if(extension == 'txt'){
                            this.options.thumbnail.call(this, mockFile, options.iconPath+'txt-icon.png');
                        }else if(extension == 'psd'){
                            this.options.thumbnail.call(this, mockFile, options.iconPath+'psd-icon.png');
                        }else{
                            // this.emit("addedfile", mockFile);
                            this.emit("thumbnail", mockFile, options.existedFilesPath+existedFile.url);
                        }
                        this.emit("complete", mockFile);
                        var existingFileCount = i; // The number of files already uploaded
                        this.options.maxFiles = this.options.maxFiles - existingFileCount;
                        mockFile.previewElement.classList.add('dz-success');
                        mockFile.previewElement.classList.add('dz-complete');
                    }
                }
                this.on('sending', function(file, xhr, formData){
                    var catid = $('#cats').find(":selected").val();
                    formData.append('catid', catid);
                    formData.append("<?= $this->security->get_csrf_token_name();?>","<?=$this->security->get_csrf_hash();?>");
                    if(options.itemId != ''){
                        formData.append('item_id', options.itemId);
                    }
                });
                this.on("success", function(file, response) { 
                    this.options.addRemoveLinks = false;
                    this.options.dictRemoveFile = '';
                    if(response.status == 'success'){
                        new PNotify({
                            text: "<?= $this->lang->line('user_documents_has_updated_successfully'); ?>",
                            type: 'success',
                            addclass: 'custom-success',
                            title: '<?= $this->lang->line('success_'); ?>',
                            styling: 'bootstrap3'
                        });
                    }
                });              
                this.on("maxfilesexceeded", function (file) {
                    this.removeAllFiles();
                    this.addFile(file);
                });
                 this.on("queuecomplete", function (file) {
                    $('#send').text("<?= $this->lang->line('submit'); ?>");
                    $('#send').attr('disabled',false);
                });
                this.on("completemultiple", function (file) {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                    }
                }); 
                if(options.existedFiles){
                    this.on("removedfile", function(file) {
                        if (this.files.length == 0) {
                            // var catid = ;
                            var catid = $('#cats').find(":selected").val();
                            var name = file.name; 
                            $.post(options.deleteURL, { file_to_be_deleted: name,catid: catid,"<?= $this->security->get_csrf_token_name();?>":"<?=$this->security->get_csrf_hash();?>"} );
                        } 
                    });   
                }
            }// end init function 
        });     
        return dz;
    } // End funciton 
   
    var url = '<?php echo base_url();?>';
    $(document).ready(function() {
        // You can use the locally-scoped $ in here as an alias to jQuery.
            $(document).on('click','#send' , function(e) { //e.preventDefault();
                    $('#send').text("<?= $this->lang->line('loading'); ?>");
                    $('#send').attr('disabled',true);
                    abc.processQueue();
                if (abc.getUploadingFiles().length === 0 ){
                    $('#send').text("<?= $this->lang->line('submit'); ?>");
                    $('#send').removeAttr('disabled');
                }
            });
 
    });
</script>
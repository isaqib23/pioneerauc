<div class="main-wrapper account-page">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>"><?= $this->lang->line('home')?></a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url('customer/deposit'); ?>"><?= $this->lang->line('my_account_new')?></a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url('customer/uploaded_docs'); ?>"><?= $this->lang->line('my_docs_new')?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $this->lang->line('uploaded_documents_new')?></li>
        </ol>
        <h2><?= $this->lang->line('my_account_new')?></h2>
        <div class="account-wrapper">
            <?= $this->load->view('template/new/template_user_leftbar'); ?>
            <div class="right-col">
                <h3><?= $this->lang->line('documents')?></h3>
                <div class="flex heading justify-content-between ">
                    <h4 class="border-title p-0 m-0 border-0"><?= $this->lang->line('req_upload')?></h4>
                    <div class="account-form">
                        <input type="hidden" name="item_id" value="<?php $id;?>"></input>
                        <select class="selectpicker ct" id="cats" title="Document Type">
                            <?php
                            foreach ($docList as $key => $value) {
                            ?>
                            <?php $docs_name =json_decode($value['name']); ?>
                            <option <?php if($key == 0){ echo 'selected'; }?> value="<?php echo $value['id'];?>"><?php echo $docs_name->$language;?></option>
                           <?php  }?>
                        </select>
                        <!-- <select class="selectpicker">
                            <option>Id Card</option>
                            <option>Passport</option>
                            <option>Driving License</option>
                            <option>Trade License</option>
                            <option>Others</option>
                        </select> -->
                    </div>
                </div>
                <div class="dz-message">
                    <div id="dz0" class="dropzone"></div>
                </div>

                <!-- <form action="/file-upload" class="dropzone" id="dropzone"></form> -->
                <div class="button-row pt-4">
                    <button type="button" id="send" class="btn btn-primary"><?= $this->lang->line('submit'); ?></button>
                </div>
            </div>
        </div>
        <?= $this->load->view('template/new/faq_footer'); ?>
    </div>
</div>

<script>
    var catid = $('#cats').find(":selected").val();
    var user_id = '<?php echo (isset($userId)) ? json_decode($userId) : ''; ?>';
    var get_docs = '<?php echo (isset($item_documents)) ? json_encode($item_documents) : ''; ?>';
    var base_url = "<?= base_url('uploads/users_documents/'); ?>";

    $(document).ready(function(){
        imageShow();
    });

    $("#cats").change(function(){
        imageShow();
    });

    function imageShow(){
        var catId = $('#cats').find(":selected").val();
        var user_id = '<?= $userId; ?>';
        var existedPath = "<?= base_url('uploads/users_documents/'); ?>"+user_id+"/";
        $.ajax({
            url:"<?php echo base_url('customer/docsLoad/'); ?>",
            method:"POST",
            data:{'catId':catId,"<?= $this->security->get_csrf_token_name();?>":"<?=$this->security->get_csrf_hash();?>"},
            success:function(resp){
                console.log(resp);
                $('#dz0').remove();
                $('.dz-message').html('<div id="dz0" class="dropzone"></div>');
                abc = new makeDropzone({
                    'url': "<?php echo base_url('customer/save_user_documents/'); ?>",
                    'deleteURL': "<?php echo base_url('customer/delete_customerDocs/'); ?>",
                    'div': "#dz0",
                    'paramName': "images",
                    'maxFilesize': 8,
                    "dictDefaultMessage": "<?= $this->lang->line('drag_drop_image_here'); ?>",
                    'maxFiles': 30,
                    'acceptedFiles': '.png,.jpg,.pdf,.doc,.docx,.ppt,.pptx,.txt,.xls,.xlsx',
                    'uploadMultiple': true,
                    'existedFiles': resp,
                    'existedFilesPath': existedPath,
                    'iconPath': "<?= base_url('assets_admin/images/file-icons/'); ?>"
                });
            }
        }); 
    }
    
    Dropzone.autoDiscover = false;
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
                    objdata = $.parseJSON(response);
                    if(objdata.status == 'success'){
                        new PNotify({
                            text: "<?= $this->lang->line('user_documents_updated'); ?>",
                            type: 'success',
                            addclass: 'custom-success',
                            title: '<?= $this->lang->line('success_'); ?>',
                            styling: 'bootstrap3'
                        });
                    }
                    if(objdata.status == 'error'){
                        new PNotify({
                            text: objdata.message,
                            type: 'error',
                            addclass: 'custom-error',
                            title: '<?= $this->lang->line('error_'); ?>',
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

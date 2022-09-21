<style type="text/css">
    .dz-image img{
    max-width: 150px;
    max-height: 150px;
    width: 100%;
    height: 100%;
    }
    .spinner-border-load{
    width:100%;
    height:100%;
    position:fixed;
    z-index:9999;
    background:url("<?php echo base_url(); ?>assets_admin/images/loading2.gif") no-repeat center center rgba(0,0,0,0.25)
}
</style>
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

<div class="clearfix"></div>

<div class="spinner-border-load" style="display: none;"></div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>
                <?php  echo $small_title; ?></h2>

            <div class="clearfix"></div>
        </div>
        <div class="x_content">

        <div id="result"></div>

            <?php if($this->session->flashdata('error')){ ?>
            <div class="alert">
                <div class="alert alert-domain alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span>
                      </button>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            </div>
            <?php }?>
            <form method="post" novalidate="" id="demo-form2" name="ozform" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
               <?php if(isset($item_info) && !empty($item_info)){ ?>
                <input type="hidden" name="item[id]" value="<?php echo $item_id; ?>">
            <?php } ?>  
                 
 
                <?php if(isset($item_info) && !empty($item_info)){ ?>
                 <div class="form-group">                   
                <span class="section">Images</span>   
                <div class="image-gallery">  
                <div id="dz0" class="dropzone"></div>
                </div>
                </div>
                <div class="form-group">
                <span class="section">Documents</span>   
                <div class="image-gallery">  
                <div id="documents" class="dropzone"></div>
                </div>
                </div>
               
                

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="button" id="send" class="btn btn-success">Submit</button>
                    <!-- <a href="<?php //echo base_url().'items'; ?>" class="custom-class" type="button">
                        Cancel
                    </a> -->
                    </div>
                </div>
                <?php }else{

                    echo '<h3>No Item Found</h3>';
                } ?>
            </form>
        </div>
    </div>
</div>


<script>

      
    $('.select2').select2();
    $('#send').hide();
    $('#send').text('Submit');
    $('#send').attr('disabled',false);
    var data_live = '<?php echo (isset($item_images)) ? json_encode($item_images) : ''; ?>';
    var data_live_documents = '<?php echo (isset($item_documents)) ? json_encode($item_documents) : ''; ?>'; 
    var itemId_new = '<?php echo $item_id;?>'; 
    var currentFile = null;
    var status_done_img = 'false';
    var status_done_doc = 'false';
    var args = [];
    var baseurl = '<?php echo base_url();?>uploads/items_documents/'+itemId_new+'/';
    var divID = "#dz0";
    Dropzone.autoDiscover = false;
    var dz1 = new Dropzone(divID, {
        url: "<?php echo base_url('items/save_item_file_images'); ?>",
        paramName: "images",
        maxFilesize: 10,
        maxFiles: 30,
        acceptedFiles : 'image/*',
        addRemoveLinks : true,
        dictRemoveFile : 'X',
        parallelUploads: 10,
        uploadMultiple: true,
        autoProcessQueue: false,
        thumbnailWidth: 150,
        thumbnailHeight: 150,
        previewsContainer: divID, 
        newId: '',
        init: function() {
            let thisDropzone = this; // Closure
            if(data_live != '')
             {
              $.each(JSON.parse(data_live), function(key,value){ //loop through it
                // console.log(value);

                var mockFile = { name: value.name, size: value.size, type: value.type }; // here we get the file name and size as response 
                // mockFile.uploadMultiple  = true;
                thisDropzone.options.addedfile.call(thisDropzone, mockFile);

                thisDropzone.options.thumbnail.call(thisDropzone, mockFile, baseurl+value.name);//uploadsfolder is the folder where you have all those uploaded files

                // thisDropzone.emit("complete", mockFile);
                mockFile.previewElement.classList.add('dz-success');
                mockFile.previewElement.classList.add('dz-complete');

            });  
              $("#send").on('click', function(e) { 
                console.log('inside running');
                // thisDropzone.newId = itemId_new;
                // thisDropzone.processQueue();
              });
             }

            this.on('addedfile', function(file){
                this.options.addRemoveLinks = true;
                this.options.dictRemoveFile = 'X';
                var extension = file.name.substr( (file.name.lastIndexOf('.')) );
                $('#send').show();
                if (file.type.match(/.pdf/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/pdf-icon.png'); ?>");
                }
                if (extension.match(/.ai/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/ai-icon.png'); ?>");
                }
                if (extension.match(/.docx/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/docx-icon.png'); ?>");
                }
                if (extension.match(/.doc/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/docx-icon.png'); ?>");
                }
                if (extension.match(/.eps/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/eps-icon.png'); ?>");
                }
                if (extension.match(/.id/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/id-icon.png'); ?>");
                }
                if (extension.match(/.ppt/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/ppt-icon.png'); ?>");
                }
                if (extension.match(/.pptx/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/ppt-icon.png'); ?>");
                }
                if (extension.match(/.psd/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/psd-icon.png'); ?>");
                }
                if (extension.match(/.xlsx/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/xlsx-icon.png'); ?>");
                }
                if (extension.match(/.xls/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/xlsx-icon.png'); ?>");
                }
                if (extension.match(/.txt/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/txt-icon.png'); ?>");
                }
                if(file.size > (1024 * 1024 * 5)) // not more than 5mb
                {
                // this.removeFile(file); 
                }
            });
            this.on('sending', function(file, xhr, formData){
                var item_id = "<?= isset($item_id) ? $item_id : ''; ?>";
                formData.append('item_id', item_id);
                formData.append('newId', this.newId);
            });
            this.on("success", function(file, response) { 
                this.options.addRemoveLinks = false;
                this.options.dictRemoveFile = '';
                 
            });
          
            this.on("maxfilesexceeded", function (file) {
                this.removeAllFiles();
                this.addFile(file);
            });

            this.on("queuecomplete", function (file) {
                // console.log("All files Que have completed ");

                // setTimeout(function(){window.location.href = url + 'items'},10000);
                $('#send').text('Submit');
                $('#send').attr('disabled',false);
                $('#send').hide();
            });
            this.on("completemultiple", function (file) {
                status_done_img = 'true';
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                    // submitButton.removeAttribute("disabled");
                }
            });  
             if(data_live != '')
             {
            this.on("removedfile", function(file) {
              // var server_file = $(file.previewTemplate).children('.server_file').text();
              // alert(server_file);
              if (this.files.length == 0) {
                var name = file.name; 
                // Do a post request and pass this path and use server-side language to delete the file
                $.post("<?php echo base_url('items/delete_item_image/'); ?>"+itemId_new, { file_to_be_deleted: name } );
                }
                console.log(name);
            }); 
        }

        }// end init function
    });

      

    var divID2 = "#documents";
    Dropzone.autoDiscover = false;
    var dz2 = new Dropzone(divID2, {
        url: "<?php echo base_url('items/save_item_file_documents'); ?>",
        paramName: "documents",
        maxFilesize: 5,
        maxFiles: 10,
        acceptedFiles : '.pdf,.doc,.docx,.ppt,.pptx',
        addRemoveLinks : true,
        dictRemoveFile : 'X',
        parallelUploads: 10,
        uploadMultiple: true,
        autoProcessQueue: false,
        thumbnailWidth: 150,
        thumbnailHeight: 150,
        previewsContainer: divID2, 
        newId: '',
        init: function() {

             let thisDropzone2 = this; // Closure
            if(data_live_documents != '')
             {
              $.each(JSON.parse(data_live_documents), function(key,value){ //loop through it
                // console.log(value);

                var mockFile = { name: value.name, size: value.size , type: value.type }; // here we get the file name and size as response 

                thisDropzone2.options.addedfile.call(thisDropzone2, mockFile);
                var names = value.name;
                var extension = names.substr( (names.lastIndexOf('.') +1) );
                    if(extension == 'pdf'){

                        thisDropzone2.options.thumbnail.call(thisDropzone2, mockFile, "<?= base_url('assets_admin/images/file-icons/pdf-icon.png'); ?>");//uploadsfolder is the folder where you have all those uploaded files        
                    }else{
                        thisDropzone2.options.thumbnail.call(thisDropzone2, mockFile, "<?= base_url('assets_admin/images/file-icons/docx-icon.png'); ?>");//uploadsfolder is the folder where you have all those uploaded files
                    }
                    mockFile.previewElement.classList.add('dz-success');
                mockFile.previewElement.classList.add('dz-complete');
                // thisDropzone2.emit("complete", mockFile);
                

            });  
             }

            this.on('addedfile', function(file){
                this.options.addRemoveLinks = true;
                this.options.dictRemoveFile = 'X';
                $('#send').show();
                if (file.type.match(/.pdf/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/pdf-icon.png'); ?>");
                }
                if (file.type.match(/.ai/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/ai-icon.png'); ?>");
                }
                if (file.type.match(/.docx/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/docx-icon.png'); ?>");
                }
                if (file.type.match(/.doc/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/docx-icon.png'); ?>");
                }
                if (file.type.match(/.eps/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/eps-icon.png'); ?>");
                }
                if (file.type.match(/.id/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/id-icon.png'); ?>");
                }
                if (file.type.match(/.ppt/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/ppt-icon.png'); ?>");
                }
                if (file.type.match(/.pptx/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/ppt-icon.png'); ?>");
                }
                if (file.type.match(/.psd/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/psd-icon.png'); ?>");
                }
                if (file.type.match(/.xlsx/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/xlsx-icon.png'); ?>");
                }
                if (file.type.match(/.xls/)) {
                    this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/xlsx-icon.png'); ?>");
                }
                if(file.size > (1024 * 1024 * 5)) // not more than 5mb
                {
                // this.removeFile(file); 
                }
            });
            this.on('sending', function(file, xhr, formData){
                var item_id = "<?= isset($item_id) ? $item_id : ''; ?>";
                formData.append('item_id', item_id);
                formData.append('newId', this.newId);
            });
            this.on("success", function(file, response) { 
                this.options.addRemoveLinks = false;
                this.options.dictRemoveFile = '';
                  console.log("All files have uploaded ");
            });
            this.on("maxfilesexceeded", function (file) {
                this.removeAllFiles();
                this.addFile(file);
            });
             this.on("completemultiple", function (file) {
                status_done_doc = 'true';
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                    // submitButton.removeAttribute("disabled");
                }
            }); 
            this.on("queuecomplete", function (file) {
                // console.log("All files Que have completed ");
                
                // setTimeout(function(){window.location.href = url + 'items'},5000);
                $('#send').text('Submit');
                $('#send').attr('disabled',false);
                $('#send').hide();

            });
            this.on("removedfile", function(file) {
                  // var server_file = $(file.previewTemplate).children('.server_file').text();
                  // alert(server_file);
                  if (this.files.length == 0) {
                    var name = file.name; 
                    // Do a post request and pass this path and use server-side language to delete the file
                    $.post("<?php echo base_url('items/delete_item_documents/'); ?>"+itemId_new, { file_to_be_deleted: name } );
                    }
                    console.log(name);
            }); 

          //   this.on("complete", (function(_this) {

          //   return function(file) {

          //     if (_this.getUploadingFiles().length === 0 && _this.getQueuedFiles().length === 0) {

          //       setTimeout(function(){window.location.href='https://www.example.com/redirected-page'},2500);

          //     }
          //   };
          // })(this));
        }// end init function
    });

 

    var url = '<?php echo base_url();?>';
    $(document).ready(function() {
        // You can use the locally-scoped $ in here as an alias to jQuery.
      
            $("#send").on('click', function(e) { //e.preventDefault();
                    
                    $('#send').text('Loading..');
                    $('#send').attr('disabled',true);
                            dz1.newId = itemId_new;
                            dz2.newId = itemId_new;
                            dz1.processQueue();
                            dz2.processQueue();  
                if (dz1.getUploadingFiles().length === 0 && dz2.getQueuedFiles().length === 0) 
                {
                    console.log('all done');
                }
            });
 
});
    
 
</script>
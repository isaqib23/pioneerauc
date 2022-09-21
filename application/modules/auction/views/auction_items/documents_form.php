<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">

<style type="text/css">
    .dz-image img {
        max-width: 150px;
        max-height: 150px;
        width: 100%;
        height: 100%;
    }

    .spinner-border-load {
        width: 100%;
        height: 100%;
        position: fixed;
        z-index: 9999;
        background: url("<?php echo base_url(); ?>assets_admin/images/loading2.gif") no-repeat center center rgba(0, 0, 0, 0.25)
    }
</style>
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

<div class="clearfix"></div>

<div class="spinner-border-load" style="display: none;"></div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>
                <?php echo $small_title; ?></h2>

            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a type="button" href="<?php echo base_url() . $back_url; ?>" class="btn btn-info"><i
                        class="fa fa-arrow-left"></i> Back</a>
            <!-- <a type="button" href="<?php echo base_url() . $back_url . $this->uri->segment(4); ?>"  class="btn btn-info"><i class="fa fa-arrow-left"></i> Back</a> -->
            <div id="result"></div>

            <?php if ($this->session->flashdata('error')) { ?>
                <div class="alert">
                    <div class="alert alert-domain alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                                style="color: black"><span aria-hidden="true">×</span>
                        </button>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                </div>
            <?php } ?>
            <form method="post" novalidate="" id="demo-form2" name="ozform" action="" enctype="multipart/form-data"
                  class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
                       value="<?= $this->security->get_csrf_hash(); ?>"/>
                <?php if (isset($item_info) && !empty($item_info)) { ?>
                    <input type="hidden" name="item[id]" value="<?php echo $this->uri->segment(3); ?>">
                <?php } ?>


                <?php if (isset($item_info) && !empty($item_info)) { ?>
                    <div class="form-group">
                        <span class="section">Images<small style="font-size: 12px;"> (image/*)</small></span>
                        <div class="image-gallery">
                            <div id="dz0" class="dropzone"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <span class="section">3D Images<small style="font-size: 12px;"> (jpeg,png,jpg)</small></span>
                        <div class="image-gallery">
                            <div id="threed_images" class="dropzone"></div>
                        </div>
                    </div>

                    <div class="col-md-6 form-group">
                        <span class="section">Private Documents<small style="font-size: 12px;"> (pdf,doc,docx,ppt,pptx,txt,xls,xlsx)</small></span>
                        <div class="image-gallery">
                            <div id="documents" class="dropzone"></div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group">
                        <span class="section">Public Documents<small style="font-size: 12px;"> (pdf,doc,docx,ppt,pptx,txt,xls,xlsx)</small></span>
                        <div class="image-gallery">
                            <div id="test_documents" class="dropzone"></div>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="button" id="send" class="btn btn-success">Submit</button>
                            <!-- <a href="<?php echo base_url() . 'auction/items/' . $this->uri->segment(4); ?>" class="custom-class" type="button"> -->
                            <!-- <a href="<?php echo base_url() . 'auction'; ?>" class="custom-class" type="button">
                        Cancel
                    </a> -->

                            <button onclick="window.history.back();" class="custom-class">
                                Cancel
                            </button>
                        </div>
                    </div>
                <?php } else {

                    echo '<h3>No Item Found</h3>';
                } ?>
            </form>
        </div>
    </div>
</div>

<!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>

<script>


    $('.select2').select2();
    var j = 1;
    // $('#send').hide();
    // $('#send').text('Submit');
    // $('#send').attr('disabled',false);

    var data_live = '<?php echo (isset($item_images)) ? json_encode($item_images) : ''; ?>';
    var specific_auction = '<?php echo $this->uri->segment(4) ?>';
    var data_live_documents = '<?php echo (isset($item_documents)) ? json_encode($item_documents) : ''; ?>';
    var data_live_test_documents = '<?php echo (isset($item_test_documents)) ? json_encode($item_test_documents) : ''; ?>';
    var itemId = '<?php echo $this->uri->segment(3);?>';
    var data_three_d = '<?php echo (isset($three_d)) ? json_encode($three_d) : ''; ?>';
    var currentFile = null;
    var status_done_img = 'false';
    var status_done_doc = 'false';
    var args = [];

    var abc = makeDropzone({
        'url': "<?php echo base_url('auction/save_item_file_images'); ?>",
        'deleteURL': "<?php echo base_url('auction/delete_item_image/'); ?>" + itemId,
        'div': '#dz0',
        'paramName': "images",
        'maxFilesize': 5,
        "dictDefaultMessage": "Drag & drop image here",
        'itemId': itemId,
        'maxFiles': 30,
        'acceptedFiles': '.jpeg,.png,.jpg',
        'uploadMultiple': true,
        'existedFiles': data_live,
        'existedFilesPath': "<?= base_url('uploads/items_documents/'); ?>" + itemId + "/",
        'iconPath': "<?= base_url('assets_admin/images/file-icons/'); ?>"
    });

    var three_d_dz = makeDropzone({
        'url': "<?php echo base_url('auction/save_3d_file_images'); ?>",
        'deleteURL': "<?php echo base_url('auction/delete_3d_image/'); ?>" + itemId,
        'div': '#threed_images',
        'paramName': "threed_images",
        'maxFilesize': 5,
        "dictDefaultMessage": "Upload 3D Images here <br>",
        'itemId': itemId,
        'maxFiles': 36,
        'acceptedFiles': '.jpeg,.png,.jpg',
        'uploadMultiple': true,
        'existedFiles': data_three_d,
        'existedFilesPath': "<?= base_url('uploads/items_threed/'); ?>" + itemId + "/",
        'iconPath': "<?= base_url('assets_admin/images/file-icons/'); ?>"
    });


    var docDz = makeDropzone({
        'url': "<?php echo base_url('auction/save_item_file_documents'); ?>",
        'deleteURL': "<?php echo base_url('auction/delete_item_documents/'); ?>" + itemId,
        'div': '#documents',
        'paramName': "documents",
        'maxFilesize': 10,
        "dictDefaultMessage": "Upload Condition Report here",
        'itemId': itemId,
        'maxFiles': 30,
        'acceptedFiles': '.pdf,.doc,.docx,.ppt,.pptx,.txt,.xls,.xlsx',
        'uploadMultiple': true,
        'existedFiles': data_live_documents,
        'existedFilesPath': "<?= base_url('assets_admin/images/file-icons/'); ?>" + itemId + "/",
        'iconPath': "<?= base_url('assets_admin/images/file-icons/'); ?>"
    });

    var TestdocDz = makeDropzone({
        'url': "<?php echo base_url('auction/save_item_test_report'); ?>",
        'deleteURL': "<?php echo base_url('auction/delete_item_test_documents/'); ?>" + itemId,
        'div': '#test_documents',
        'paramName': "test_documents",
        'maxFilesize': 10,
        "dictDefaultMessage": "Upload Test Report here",
        'itemId': itemId,
        'maxFiles': 30,
        'acceptedFiles': '.pdf,.doc,.docx,.ppt,.pptx,.txt,.xls,.xlsx',
        'uploadMultiple': true,
        'existedFiles': data_live_test_documents,
        'existedFilesPath': "<?= base_url('assets_admin/images/file-icons/'); ?>" + itemId + "/",
        'iconPath': "<?= base_url('assets_admin/images/file-icons/'); ?>"
    });

    function makeDropzone(options) {
        Dropzone.autoDiscover = false;
        var dz = new Dropzone(options.div, {
            url: options.url,
            paramName: options.paramName,
            dictDefaultMessage: options.dictDefaultMessage,
            maxFilesize: options.maxFilesize,
            // maxFilesize: 1024,
            maxFiles: 150,
            acceptedFiles: options.acceptedFiles,
            addRemoveLinks: true,
            dictRemoveFile: 'X',
            parallelUploads: 3,
            uploadMultiple: options.uploadMultiple,
            autoProcessQueue: true,
            thumbnailWidth: 150,
            thumbnailHeight: 150,
            previewsContainer: options.div,
            init: function () {
                this.on('addedfile', function (file) {
                    this.options.addRemoveLinks = true;
                    this.options.dictRemoveFile = 'X';
                    if (file.type) {
                        if (file.type.match(/.pdf/)) {
                            this.emit("thumbnail", file, options.iconPath + 'pdf-icon.png');
                        }
                        if (file.type.match(/.ai/)) {
                            this.emit("thumbnail", file, options.iconPath + 'ai-icon.png');
                        }
                        if (file.type.match(/.docx/)) {
                            this.emit("thumbnail", file, options.iconPath + 'docx-icon.png');
                        }
                        if (file.type.match(/.doc/)) {
                            this.emit("thumbnail", file, options.iconPath + 'docx-icon.png');
                        }
                        if (file.type.match(/.eps/)) {
                            this.emit("thumbnail", file, options.iconPath + 'eps-icon.png');
                        }
                        if (file.type.match(/.id/)) {
                            this.emit("thumbnail", file, options.iconPath + 'id-icon.png');
                        }
                        if (file.type.match(/.ppt/)) {
                            this.emit("thumbnail", file, options.iconPath + 'ppt-icon.png');
                        }
                        if (file.type.match(/.pptx/)) {
                            this.emit("thumbnail", file, options.iconPath + 'ppt-icon.png');
                        }
                        if (file.type.match(/.psd/)) {
                            this.emit("thumbnail", file, options.iconPath + 'psd-icon.png');
                        }
                        if (file.type.match(/.xlsx/)) {
                            this.emit("thumbnail", file, options.iconPath + 'xlsx-icon.png');
                        }
                        if (file.type.match(/.xls/)) {
                            this.emit("thumbnail", file, options.iconPath + 'xlsx-icon.png');
                        }
                    }
                });
                if (options.existedFiles) {
                    var existedFiles = JSON.parse(options.existedFiles);
                    for (let i = 0; i < existedFiles.length; i++) {
                        let existedFile = existedFiles[i];

                        // Create the mock file:
                        var mockFile = {name: existedFile.name, size: existedFile.size, url: existedFile.url};
                        var extension = existedFile.name.substr((existedFile.name.lastIndexOf('.') + 1));
                        this.options.addedfile.call(this, mockFile);
                        console.log(extension);
                        if (extension == 'pdf') {
                            this.options.thumbnail.call(this, mockFile, options.iconPath + 'pdf-icon.png');
                        } else if (extension == 'docx') {
                            this.options.thumbnail.call(this, mockFile, options.iconPath + 'docx-icon.png');
                        } else if (extension == 'doc') {
                            this.options.thumbnail.call(this, mockFile, options.iconPath + 'docx-icon.png');
                        } else if (extension == 'xlsx') {
                            this.options.thumbnail.call(this, mockFile, options.iconPath + 'xlsx-icon.png');
                        } else if (extension == 'xls') {
                            this.options.thumbnail.call(this, mockFile, options.iconPath + 'xlsx-icon.png');
                        } else if (extension == 'ppt') {
                            this.options.thumbnail.call(this, mockFile, options.iconPath + 'ppt-icon.png');
                        } else if (extension == 'pptx') {
                            this.options.thumbnail.call(this, mockFile, options.iconPath + 'ppt-icon.png');
                        } else if (extension == 'psd') {
                            this.options.thumbnail.call(this, mockFile, options.iconPath + 'psd-icon.png');
                        } else {
                            // this.emit("addedfile", mockFile);
                            this.emit("thumbnail", mockFile, options.existedFilesPath + existedFile.url);
                        }
                        this.emit("complete", mockFile);
                        var existingFileCount = i; // The number of files already uploaded
                        this.options.maxFiles = this.options.maxFiles - existingFileCount;
                        mockFile.previewElement.classList.add('dz-success');
                        mockFile.previewElement.classList.add('dz-complete');
                    }
                }

                this.on('sending', function (file, xhr, formData) {
                    // append formData
                    // var item_id = "<?= isset($item_id) ? $item_id : ''; ?>";
                    if (options.itemId != '') {
                        formData.append('item_id', options.itemId);
                        formData.append("<?= $this->security->get_csrf_token_name();?>", "<?=$this->security->get_csrf_hash();?>");
                    }
                });
                this.on("success", function (file, response) {
                    this.options.addRemoveLinks = false;
                    this.options.dictRemoveFile = '';
                    objdata = $.parseJSON(response);
                    if (objdata.error == false) {
                        PNotify.removeAll();
                        new PNotify({
                            text: "Item documents has been updated successfully.",
                            type: 'success',
                            addclass: 'custom-success',
                            title: 'Success',
                            styling: 'bootstrap3'
                        });
                    }
                    if (objdata.error == true) {
                        PNotify.removeAll();
                        new PNotify({
                            text: objdata.message,
                            type: 'error',
                            addclass: 'custom-error',
                            title: 'Error',
                            styling: 'bootstrap3'
                        });
                        this.removeFile(file);
                    }
                });
                this.on("maxfilesexceeded", function (file) {

                    if (j == 1) {
                        alert('File limit exceeded. Remove some files first.');
                    }
                    j = j + 1;
                    this.removeFile(file);
                    // this.removeAllFiles();
                    // this.addFile(file);
                });
                // this.on("queuecomplete", function (file) {
                //    // hide button on submit form 
                // });
                this.on("queuecomplete", function (file) {
                    // console.log("All files Que have completed ");
                    // setTimeout(function(){window.location.href = url + 'items'},10000);
                    $('#send').text('Submit');
                    $('#send').attr('disabled', false);
                    // $('#send').hide();
                });
                this.on("completemultiple", function (file) {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                        // submitButton.removeAttribute("disabled");
                    }
                });

                if (options.existedFiles) {
                    this.on("removedfile", function (file) {
                        if (this.files.length == 0) {
                            var name = file.name;
                            $.post(options.deleteURL, {
                                file_to_be_deleted: name,
                                "<?= $this->security->get_csrf_token_name();?>": "<?=$this->security->get_csrf_hash();?>"
                            });
                        }
                    });
                }
            }// end init function 
        });
        return dz;
    } // End funciton 

    // var baseurl = '<?php echo base_url();?>uploads/items_documents/'+itemId+'/';
    // var divID = "#dz0";
    // Dropzone.autoDiscover = false;
    // var dz1 = new Dropzone(divID, {
    //     url: "<?php echo base_url('auction/save_item_file_images'); ?>",
    //     paramName: "images",
    //     maxFilesize: 10,
    //     maxFiles: 70,
    //     acceptedFiles : 'image/*',
    //     addRemoveLinks : true,
    //     dictRemoveFile : 'X',
    //     parallelUploads: 10,
    //     uploadMultiple: true,
    //     autoProcessQueue: false,
    //     thumbnailWidth: 150,
    //     thumbnailHeight: 150,
    //     previewsContainer: divID, 
    //     newId: '',
    //     init: function() {
    //         let thisDropzone = this; // Closure
    //         if(data_live != '')
    //          {
    //           $.each(JSON.parse(data_live), function(key,value){ //loop through it
    //             // console.log(value);

    //             var mockFile = { name: value.name, size: value.size, type: value.type }; // here we get the file name and size as response 
    //             // mockFile.uploadMultiple  = true;
    //             thisDropzone.options.addedfile.call(thisDropzone, mockFile);

    //             thisDropzone.options.thumbnail.call(thisDropzone, mockFile, baseurl+value.name);//uploadsfolder is the folder where you have all those uploaded files

    //             // thisDropzone.emit("complete", mockFile);
    //             mockFile.previewElement.classList.add('dz-success');
    //             mockFile.previewElement.classList.add('dz-complete');

    //         });  
    //           $("#send").on('click', function(e) { 
    //             console.log('inside running');
    //             // thisDropzone.newId = itemId;
    //             // thisDropzone.processQueue();
    //           });
    //          }

    //         this.on('addedfile', function(file){
    //             this.options.addRemoveLinks = true;
    //             this.options.dictRemoveFile = 'X';
    //             $('#send').show();
    //             if (file.type.match(/.pdf/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/pdf-icon.png'); ?>");
    //             }
    //             if (file.type.match(/.ai/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/ai-icon.png'); ?>");
    //             }
    //             if (file.type.match(/.docx/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/docx-icon.png'); ?>");
    //             }
    //             if (file.type.match(/.doc/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/docx-icon.png'); ?>");
    //             }
    //             if (file.type.match(/.eps/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/eps-icon.png'); ?>");
    //             }
    //             if (file.type.match(/.id/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/id-icon.png'); ?>");
    //             }
    //             if (file.type.match(/.ppt/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/ppt-icon.png'); ?>");
    //             }
    //             if (file.type.match(/.pptx/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/ppt-icon.png'); ?>");
    //             }
    //             if (file.type.match(/.psd/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/psd-icon.png'); ?>");
    //             }
    //             if (file.type.match(/.xlsx/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/xlsx-icon.png'); ?>");
    //             }
    //             if (file.type.match(/.xls/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/xlsx-icon.png'); ?>");
    //             }
    //             if(file.size > (1024 * 1024 * 5)) // not more than 5mb
    //             {
    //             // this.removeFile(file); 
    //             }
    //         });
    //         this.on('sending', function(file, xhr, formData){
    //             var item_id = "<?= isset($item_id) ? $item_id : ''; ?>";
    //             formData.append('item_id', item_id);
    //             formData.append('newId', this.newId);
    //         });
    //         this.on("success", function(file, response) { 
    //             this.options.addRemoveLinks = false;
    //             this.options.dictRemoveFile = '';

    //         });

    //         this.on("maxfilesexceeded", function (file) {
    //             this.removeAllFiles();
    //             this.addFile(file);
    //         });

    //         this.on("queuecomplete", function (file) {
    //             // console.log("All files Que have completed ");

    //             // setTimeout(function(){window.location.href = url + 'auction/items/'+specific_auction},10000);
    //              $('#send').text('Submit');
    //             $('#send').attr('disabled',false);
    //             $('#send').hide();
    //         });
    //         this.on("completemultiple", function (file) {
    //             status_done_img = 'true';
    //             if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
    //                 // submitButton.removeAttribute("disabled");
    //             }
    //         });  
    //          if(data_live != '')
    //          {
    //         this.on("removedfile", function(file) {
    //           // var server_file = $(file.previewTemplate).children('.server_file').text();
    //           // alert(server_file);
    //           if (this.files.length == 0) {
    //             var name = file.name; 
    //             // Do a post request and pass this path and use server-side language to delete the file
    //             $.post("<?php echo base_url('auction/delete_item_image/'); ?>"+itemId, { file_to_be_deleted: name } );
    //             }
    //             console.log(name);
    //         }); 
    //     }

    //     }// end init function
    // });


    var divID2 = "#documents";
    Dropzone.autoDiscover = false;
    // var dz2 = new Dropzone(divID2, {
    //     url: "<?php echo base_url('auction/save_item_file_documents'); ?>",
    //     paramName: "documents",
    //     maxFilesize: 15,
    //     maxFiles: 10,
    //     acceptedFiles : '.pdf,.doc,.docx,.ppt,.pptx',
    //     addRemoveLinks : true,
    //     dictRemoveFile : 'X',
    //     parallelUploads: 10,
    //     uploadMultiple: true,
    //     autoProcessQueue: false,
    //     thumbnailWidth: 150,
    //     thumbnailHeight: 150,
    //     previewsContainer: divID2, 
    //     newId: '',
    //     init: function() {

    //          let thisDropzone2 = this; // Closure
    //         if(data_live_documents != '')
    //          {
    //           $.each(JSON.parse(data_live_documents), function(key,value){ //loop through it
    //             // console.log(value);

    //             var mockFile = { name: value.name, size: value.size , type: value.type }; // here we get the file name and size as response 

    //             thisDropzone2.options.addedfile.call(thisDropzone2, mockFile);
    //             var names = value.name;
    //             var extension = names.substr( (names.lastIndexOf('.') +1) );
    //                 if(extension == 'pdf'){

    //                     thisDropzone2.options.thumbnail.call(thisDropzone2, mockFile, "<?= base_url('assets_admin/images/file-icons/pdf-icon.png'); ?>");//uploadsfolder is the folder where you have all those uploaded files        
    //                 }else{
    //                     thisDropzone2.options.thumbnail.call(thisDropzone2, mockFile, "<?= base_url('assets_admin/images/file-icons/docx-icon.png'); ?>");//uploadsfolder is the folder where you have all those uploaded files
    //                 }
    //                 mockFile.previewElement.classList.add('dz-success');
    //             mockFile.previewElement.classList.add('dz-complete');
    //             // thisDropzone2.emit("complete", mockFile);


    //         });  
    //          }

    //         this.on('addedfile', function(file){
    //             this.options.addRemoveLinks = true;
    //             this.options.dictRemoveFile = 'X';
    //             $('#send').show();
    //             if (file.type.match(/.pdf/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/pdf-icon.png'); ?>");
    //             }
    //             if (file.type.match(/.ai/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/ai-icon.png'); ?>");
    //             }
    //             if (file.type.match(/.docx/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/docx-icon.png'); ?>");
    //             }
    //             if (file.type.match(/.doc/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/docx-icon.png'); ?>");
    //             }
    //             if (file.type.match(/.eps/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/eps-icon.png'); ?>");
    //             }
    //             if (file.type.match(/.id/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/id-icon.png'); ?>");
    //             }
    //             if (file.type.match(/.ppt/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/ppt-icon.png'); ?>");
    //             }
    //             if (file.type.match(/.pptx/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/ppt-icon.png'); ?>");
    //             }
    //             if (file.type.match(/.psd/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/psd-icon.png'); ?>");
    //             }
    //             if (file.type.match(/.xlsx/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/xlsx-icon.png'); ?>");
    //             }
    //             if (file.type.match(/.xls/)) {
    //                 this.emit("thumbnail", file, "<?= base_url('assets_admin/images/file-icons/xlsx-icon.png'); ?>");
    //             }
    //             if(file.size > (1024 * 1024 * 5)) // not more than 5mb
    //             {
    //             // this.removeFile(file); 
    //             }
    //         });
    //         this.on('sending', function(file, xhr, formData){
    //             var item_id = "<?= isset($item_id) ? $item_id : ''; ?>";
    //             formData.append('item_id', item_id);
    //             formData.append('newId', this.newId);
    //         });
    //         this.on("success", function(file, response) { 
    //             this.options.addRemoveLinks = false;
    //             this.options.dictRemoveFile = '';
    //               console.log("All files have uploaded ");
    //         });
    //         this.on("maxfilesexceeded", function (file) {
    //             this.removeAllFiles();
    //             this.addFile(file);
    //         });
    //          this.on("completemultiple", function (file) {
    //             status_done_doc = 'true';
    //             if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
    //                 // submitButton.removeAttribute("disabled");
    //             }
    //         }); 
    //         this.on("queuecomplete", function (file) {
    //             // console.log("All files Que have completed ");

    //             // setTimeout(function(){window.location.href = url + 'auction/items/'+specific_auction},5000);
    //              $('#send').text('Submit');
    //             $('#send').attr('disabled',false);
    //             $('#send').hide();
    //         });
    //         this.on("removedfile", function(file) {
    //               // var server_file = $(file.previewTemplate).children('.server_file').text();
    //               // alert(server_file);
    //               if (this.files.length == 0) {
    //                 var name = file.name; 
    //                 // Do a post request and pass this path and use server-side language to delete the file
    //                 $.post("<?php echo base_url('auction/delete_item_documents/'); ?>"+itemId, { file_to_be_deleted: name } );
    //                 }
    //                 console.log(name);
    //         }); 

    //       //   this.on("complete", (function(_this) {

    //       //   return function(file) {

    //       //     if (_this.getUploadingFiles().length === 0 && _this.getQueuedFiles().length === 0) {

    //       //       setTimeout(function(){window.location.href='https://www.example.com/redirected-page'},2500);

    //       //     }
    //       //   };
    //       // })(this));
    //     }// end init function
    // });


    var url = '<?php echo base_url();?>';
    $(document).ready(function () {
        // You can use the locally-scoped $ in here as an alias to jQuery.

        $("#send").on('click', function (e) { //e.preventDefault();

            $('#send').text('Loading..');
            $('#send').attr('disabled', true);
            abc.processQueue();
            docDz.processQueue();
            TestdocDz.processQueue();
            three_d_dz.processQueue();
            if (abc.getUploadingFiles().length === 0 && docDz.getQueuedFiles().length === 0 && TestdocDz.getQueuedFiles().length === 0 && three_d_dz.getQueuedFiles().length === 0) {
                $('#send').text('Submit');
                $('#send').attr('disabled', false);
                console.log('all done');
            }
            //             dz1.newId = itemId;
            //             dz2.newId = itemId;
            //             dz1.processQueue();
            //             dz2.processQueue();
            // if (dz1.getUploadingFiles().length === 0 && dz2.getQueuedFiles().length === 0)
            // {
            //     console.log('all done');
            // }
        });

    });


</script>
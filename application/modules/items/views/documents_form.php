<small style="font-size: 12px;"></small>
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

    .dropzone .dz-preview .dz-error-message {
        top: 145px;
    }
</style>

<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">


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

            <a type="button" href="<?php echo base_url() . 'items'; ?>" class="btn btn-info"><i
                        class="fa fa-arrow-left"></i> Back Item List</a>
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
                        <span class="section">Images<small style="font-size: 12px;"> (jpeg,png,jpg)</small></span>
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
                            <a href="<?php echo base_url() . 'items'; ?>" class="custom-class" type="button">
                                Cancel
                            </a>
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
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.js"></script>

<script>

    var itemId = '<?php echo $this->uri->segment(3);?>';
    var item_id = "<?= isset($item_id) ? $item_id : ''; ?>";
    var data_live = '<?php echo (isset($item_images)) ? json_encode($item_images) : ''; ?>';
    var data_three_d = '<?php echo (isset($three_d)) ? json_encode($three_d) : ''; ?>';
    var abc = makeDropzone({
        'url': "<?php echo base_url('items/save_item_file_images'); ?>",
        'deleteURL': "<?php echo base_url('items/delete_item_image/'); ?>" + itemId,
        'div': '#dz0',
        'paramName': "images",
        'maxFilesize': 5,
        "dictDefaultMessage": "Drag & drop image here",
        'itemId': item_id,
        'maxFiles': 30,
        'acceptedFiles': '.jpeg,.png,.jpg',
        'uploadMultiple': true,
        'existedFiles': data_live,
        'existedFilesPath': "<?= base_url('uploads/items_documents/'); ?>" + itemId + "/",
        'iconPath': "<?= base_url('assets_admin/images/file-icons/'); ?>"
    });
    var three_d_dz = makeDropzone({
        'url': "<?php echo base_url('items/save_3d_file_images'); ?>",
        'deleteURL': "<?php echo base_url('items/delete_3d_image/'); ?>" + itemId,
        'div': '#threed_images',
        'paramName': "threed_images",
        'maxFilesize': 5,
        "dictDefaultMessage": "Upload 3D Images here <br>",
        'itemId': item_id,
        'maxFiles': 36,
        'acceptedFiles': '.jpeg,.png,.jpg',
        'uploadMultiple': true,
        'existedFiles': data_three_d,
        'existedFilesPath': "<?= base_url('uploads/items_threed/'); ?>" + itemId + "/",
        'iconPath': "<?= base_url('assets_admin/images/file-icons/'); ?>"
    });

    $('.select2').select2();
    // $('#send').hide();
    // $('#send').text('Submit');
    // $('#send').attr('disabled',false);
    var data_live_documents = '<?php echo (isset($item_documents)) ? json_encode($item_documents) : ''; ?>';
    var data_live_test_documents = '<?php echo (isset($item_test_documents)) ? json_encode($item_test_documents) : ''; ?>';

    var currentFile = null;
    var status_done_doc = 'false';
    var uploaded_file = false;

    function makeDropzone(options) {
        Dropzone.autoDiscover = false;
        var dz = new Dropzone(options.div, {
            url: options.url,
            paramName: options.paramName,
            dictDefaultMessage: options.dictDefaultMessage,
            maxFilesize: options.maxFilesize,
            maxFiles: 100,
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
                    var extension = file.name.substr((file.name.lastIndexOf('.')));
                    console.log(file.type);
                    if (file.type) {
                        if (extension.match(/.pdf/)) {
                            this.emit("thumbnail", file, options.iconPath + 'pdf-icon.png');
                        }
                        if (extension.match(/.ai/)) {
                            this.emit("thumbnail", file, options.iconPath + 'ai-icon.png');
                        }
                        if (extension.match(/.docx/)) {
                            this.emit("thumbnail", file, options.iconPath + 'docx-icon.png');
                        }
                        if (extension.match(/.doc/)) {
                            this.emit("thumbnail", file, options.iconPath + 'docx-icon.png');
                        }
                        if (extension.match(/.eps/)) {
                            this.emit("thumbnail", file, options.iconPath + 'eps-icon.png');
                        }
                        if (extension.match(/.id/)) {
                            this.emit("thumbnail", file, options.iconPath + 'id-icon.png');
                        }
                        if (extension.match(/.ppt/)) {
                            this.emit("thumbnail", file, options.iconPath + 'ppt-icon.png');
                        }
                        if (extension.match(/.pptx/)) {
                            this.emit("thumbnail", file, options.iconPath + 'ppt-icon.png');
                        }
                        if (extension.match(/.psd/)) {
                            this.emit("thumbnail", file, options.iconPath + 'psd-icon.png');
                        }
                        if (extension.match(/.xlsx/)) {
                            this.emit("thumbnail", file, options.iconPath + 'xlsx-icon.png');
                        }
                        if (extension.match(/.txt/)) {
                            this.emit("thumbnail", file, options.iconPath + 'txt-icon.png');
                        }
                        if (extension.match(/.xls/)) {
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
                        } else if (extension == 'txt') {
                            this.options.thumbnail.call(this, mockFile, options.iconPath + 'txt-icon.png');
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

                this.on("addedfile", function (file) {
                    uploaded_file = true;
                });

                this.on("success", function (file, response) {
                    // console.log(response);
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
                    }
                    // if(response.status == 'fail'){
                    //     new PNotify({
                    //         text: ""+response.msg+"",
                    //         type: 'error',
                    //         title: 'Error',
                    //         styling: 'bootstrap3'
                    //     });
                    // }

                });

                this.on("maxfilesexceeded", function (file) {
                    alert('File limit exceeded. Remove some files first.');
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


    var docDz = makeDropzone({
        'url': "<?php echo base_url('items/save_item_file_documents'); ?>",
        'deleteURL': "<?php echo base_url('items/delete_item_documents/'); ?>" + itemId,
        'div': '#documents',
        'paramName': "documents",
        'maxFilesize': 10,
        "dictDefaultMessage": "Upload Other Documents here <br>",
        // <small> (Note: Max 10 Files) </small>
        'itemId': item_id,
        'maxFiles': 30,
        'acceptedFiles': '.pdf,.doc,.docx,.ppt,.pptx,.txt,.xls,.xlsx',
        'uploadMultiple': true,
        'existedFiles': data_live_documents,
        'existedFilesPath': "<?= base_url('assets_admin/images/file-icons/'); ?>" + itemId + "/",
        'iconPath': "<?= base_url('assets_admin/images/file-icons/'); ?>"
    });

    var TestdocDz = makeDropzone({
        'url': "<?php echo base_url('items/save_item_test_report'); ?>",
        'deleteURL': "<?php echo base_url('items/delete_item_test_documents/'); ?>" + itemId,
        'div': '#test_documents',
        'paramName': "test_documents",
        'maxFilesize': 10,
        "dictDefaultMessage": "Upload Test Report here",
        'itemId': item_id,
        'maxFiles': 30,
        'acceptedFiles': '.pdf,.doc,.docx,.ppt,.pptx,.txt,.xls,.xlsx',
        'uploadMultiple': true,
        'existedFiles': data_live_test_documents,
        'existedFilesPath': "<?= base_url('assets_admin/images/file-icons/'); ?>" + itemId + "/",
        'iconPath': "<?= base_url('assets_admin/images/file-icons/'); ?>"
    });


    var url = '<?php echo base_url();?>';
    $(document).ready(function () {
        // You can use the locally-scoped $ in here as an alias to jQuery.

        $("#send").on('click', function (e) { //e.preventDefault();
            $('#send').text('Loading..');
            $('#send').attr('disabled', true);

            if (uploaded_file == true) {
                abc.processQueue();
                docDz.processQueue();
                TestdocDz.processQueue();
                three_d_dz.processQueue();
            } else {
                PNotify.removeAll();
                new PNotify({
                    text: "No documents found to upload.",
                    type: 'warning',
                    title: 'Warning',
                    styling: 'bootstrap3'
                });
            }
            // dz1.newId = itemId;
            // dz2.newId = itemId;

            if (abc.getUploadingFiles().length === 0 && docDz.getQueuedFiles().length === 0 && TestdocDz.getQueuedFiles().length === 0 && three_d_dz.getQueuedFiles().length === 0) {
                console.log('all done');
                // if (error = 'true') {
                //     PNotify.removeAll();
                //     new PNotify({
                //         text: "No documents found to upload.",
                //         type: 'warning',
                //         title: 'Warning',
                //         styling: 'bootstrap3'
                //     });
                // }
                $('#send').text('Submit');
                $('#send').attr('disabled', false);
            }
        });

    });


</script>
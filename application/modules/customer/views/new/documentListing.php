
<div class="main-wrapper account-page">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>"><?= $this->lang->line('home')?></a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url('customer/deposit'); ?>"><?= $this->lang->line('my_account_new')?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $this->lang->line('my_docs_new')?></li>
        </ol>
        <h2><?= $this->lang->line('my_account_new')?></h2>
        <div class="account-wrapper">
            <?= $this->load->view('template/new/template_user_leftbar'); ?>
            <div class="right-col">
                <h3>
					<?= $this->lang->line('uploaded_documents_new')?>
                    <a href="<?= base_url('customer/docs');?>" data-toggle="tooltip" data-placement="top" title="Upload Documents">
                        <span class="material-icons">
                            add_circle
                        </span>
                    </a>
                </h3>
                <table class="customtable datatable table-responsive" cellpadding="0" cellspacing="0" >
                    <thead>
                        <th width="24%"><?= $this->lang->line('docs_name')?></th>
                        <th class="item-arrow hide-on-768" width="30%"><?= $this->lang->line('docs_items')?></th>
                        <th class="hide-on-768" width="18%"><?= $this->lang->line('created_on_new')?></th>
                    </thead>
                    <tbody>
                        <?php 
                        if($docs){
                            foreach ($docs as $key => $doc) {
                                $file_ids = explode(',', $doc['file_id']);
                                $file_ids = array_filter($file_ids, 'is_numeric');
                                $files = $this->db->where_in('id', $file_ids)->get('files')->result_array();
                                $doc_name = json_decode($doc['document_type']); ?>
                                <tr>
                                    <td><?= $doc_name->$language; ?></td>
                                    <td><?php 
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
                                            <li><?= $this->lang->line('no_files_found')?></li>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td class="hide-on-768">
                                        <?= $doc['created_on']; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        } ?>
                        <!-- <tr>
                            <td>Passport</td>
                            <td>File 2</td>
                            <td>2020-09-20 08:54:07</td>
                        </tr> -->
                    </tbody>
                </table>
            </div>
        </div>

        <?= $this->load->view('template/new/faq_footer'); ?>
    </div>
</div>

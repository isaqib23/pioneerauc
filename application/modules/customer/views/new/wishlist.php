
<div class="main-wrapper account-page">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>"><?= $this->lang->line('home')?></a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url('customer/deposit'); ?>"><?= $this->lang->line('my_account_new')?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $this->lang->line('my_wishlist')?></li>
        </ol>
        <h2><?= $this->lang->line('my_account_new')?></h2>
        <div class="account-wrapper">
            <?= $this->load->view('template/new/template_user_leftbar') ?>
            <div class="right-col">
                <h3><?= $this->lang->line('my_wishlist')?></h3>
                <table class="customtable datatable-checkbox" id="fav_table" cellpadding="0" cellspacing="0" >
                    <thead>
                        <th class="checkbox" width="3%" ></th>
                        <th class="name" class="pl-0" width="30%"><?= $this->lang->line('name_of_item')?></th>
                     
                        <th class="status" width="20%"><?= $this->lang->line('status_new')?></th>
                        <th class="action hide-on-768" width="20%"><?= $this->lang->line('action_new')?></th>
                    </thead>
                    <tbody>
                        <?php foreach ($favoriteItems as $key => $value) : 
                            if ($value['sold'] == 'no') : ?>
                                <tr id="<?= $value['id']; ?>">
                                    <td class=""></td>
                                    <td class="pl-0">
                                        <div class="item">
                                            <div class="image">
                                                <?= $value['image']; ?>
                                            </div>
                                            <div class="desc">
                                                <h6><?= json_decode($value['name'])->$language; ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- <td class="hide-on-768"><?= json_decode($value['detail'])->$language; ?></td> -->
                                    <td><?= $value['auction_item_status']; ?></td>
                                    <td class="hide-on-768"><?= $value['action']; ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="javascript:void(0)" onclick="removeSelected()" class="btn-link remove-row"><?= $this->lang->line('remove_selected')?></a>
            </div>
        </div>
        <?= $this->load->view('template/new/faq_footer') ?>
    </div>
</div>

<script type="text/javascript">
    var token_name = '<?= $this->security->get_csrf_token_name();?>';
    var token_value = '<?=$this->security->get_csrf_hash();?>';
    var table;
    var language = '<?= ($language == 'arabic') ? NEW_ASSETS_USER."new/js/DTArabic.json" : ""; ?>';
    $(document).ready(function(){
        $('#fav_table').DataTable().destroy();
        table = $('#fav_table').DataTable({
        columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        select: {
            style:    'multi',
            selector: 'td:first-child'
        },
        order: [[ 1, 'asc' ]],
        "language": {
            "url": language,
            "paginate": {
                "previous": '<span class="material-icons">keyboard_arrow_left</span>',
                "next": '<span class="material-icons">keyboard_arrow_right</span>'
            }
        }
      });
    } );
    function removeSelected(){
        var selected = table.rows('.selected').data();
        var selected_ids = [];
        jQuery.each(selected, function() {
            console.log(this.DT_RowId);
            selected_ids.push(this.DT_RowId);
        });
        $.ajax({
            type: 'post',
            url: '<?= base_url('customer/removeFavorite');?>',
            data: {'selected_ids':selected_ids, [token_name] : token_value},
            success : function(data){
                var response = data;
                if (response == 'success') {
                    window.location.reload();
                }
            }
        })
    }
</script>

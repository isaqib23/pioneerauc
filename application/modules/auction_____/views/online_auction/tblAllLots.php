<table class="customtable datatable table_id" id="tblAllLots">
    <thead>
        <th><?= $this->lang->line('lot');?></th>
        <th><?= $this->lang->line('status');?></th>
        <th><?= $this->lang->line('name');?></th>
        <th><?= $this->lang->line('description');?></th>
    </thead>
    <tbody>
        <?php 
        if($all_lots){
            foreach ($all_lots as $key => $value) {

                //Status
                switch ($value['sold_status']) {
                    case 'not':
                        $sold_status = $this->lang->line('coming_up');
                        break;
                    case 'sold':
                        $sold_status = $this->lang->line('sold');
                        break;
                    case 'not_sold':
                        $sold_status = $this->lang->line('sold');
                        break;
                    
                    default:
                        $sold_status = $this->lang->line('unknown');
                        break;
                }

                //Item Detail
                $item_detail = $this->db->get_where('item', ['id' => $value['item_id']])->row_array();
                $detail = json_decode($item_detail['detail']);

                $item_images_ids = explode(',', $item_detail['item_images']);
                
$images = $this->db->where('id', $item_images_ids[0])->order_by('file_order', 'ASC')->get('files')->result_array();

if($images){
    $image_src = base_url('uploads/items_documents/').$item_detail['id'].'/'.$images[0]['name'];
}else{
    $image_src = '';
}
 // print_r($image_src);              
            ?>
                <tr>
                    <td><?= $value['order_lot_no']; ?></td>
                    <td><?= $sold_status; ?></td>
                    <td class="pl-0">
                                    <div class="item">
                                        <div class="image">
                                            <a class="text-success" href=""><img style="max-width: 50px" class="img-responsive avatar-view" src="<?= $image_src; ?>" alt="Visa"></a>                                        </div>
                                        <div class="desc hide-on-768">
                                            <h6><?= json_decode($item_detail['name'])->$language; ?></h6>
                                        </div>
                                    </div>
                                </td>


                           

                    <td><?= $detail->$language; ?></td>
                </tr>
            <?php
            }
        }
        ?>
    </tbody>
</table>

<script>
    // $(document).ready(function(){
    //     $('#tblAllLots').DataTable({
    //         responsive: true,
    //         autoWidth: false,
    //         "dom": '<"top"i>rt<"bottom"flp>',
    //     });
    // });
    if ( ! $.fn.DataTable.isDataTable('#tblAllLots') ) {
        var language = '<?= ($language == 'arabic') ? "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Arabic.json" : ""; ?>';
        var table = $('#tblAllLots').DataTable({
            responsive: true,
            autoWidth: false,
            // "dom": '<"top"l>rt<"bottom"ip>',
            // "dom": '<"top"i>rt<"bottom"p><"clear">',
            "language":  {"url": language}
        });
        table.columns.adjust().draw();
    }
</script>
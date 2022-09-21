<?php if($auto_bids): ?>
    <table class="table_id" id="tblAutoBids"> 
        <thead>
            <th><?= $this->lang->line('lot_cap');?></th>
            <th><?= $this->lang->line('bid_amount_cap');?></th>
            <th><?= $this->lang->line('description');?></th>
            <th><?= $this->lang->line('date_cap');?></th>
        </thead>
        <tbody>
            <?php 
            foreach ($auto_bids as $key => $auto_bid): 
                $auction_item = $this->db->get_where('auction_items', ['id' => $auto_bid['auction_item_id']])->row_array();
                $item = $this->db->get_where('item', ['id' => $auto_bid['item_id']])->row_array();
                $item_name = json_decode($item['name']);
                ?>
                <tr>
                    <td><?= $auction_item['order_lot_no']; ?></td>
                    <td><?= $auto_bid['bid_limit']; ?></td>
                    <td><?= strtoupper($item_name->$language); ?></td>
                    <td><?= $auto_bid['date']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<script>
    if ( ! $.fn.DataTable.isDataTable('#tblAutoBids') ) {
        var language = '<?= ($language == 'arabic') ? "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Arabic.json" : ""; ?>';
        var table = $('#tblAutoBids').DataTable({
            responsive: true,
            autoWidth: false,
            "dom": '<"top"i>rt<"bottom"flp>',
            "language":  {"url": language}
        });
        table.columns.adjust().draw();
    }
</script>
<?php 
$j=9;
$i=0;
    foreach ($fields as $key => $value) {
        if (!empty($value['data-value'])) {
            $i++;
            if($i<= $j) { ?>
                <ul>
                    <li><?= $this->template->make_dual($value['label']); ?></li>
                    <li><?= $this->template->make_dual($value['data-value']); ?></li>
                </ul>
                <?php
            }
        }
    } 
?>

<ul>
                                    <li><?= $this->lang->line('item_location'); ?></li>
                                    <li id="itemLoction"></li>
                                </ul>

                                <ul style="<?= ($category['include_make_model'] == 'no') ? 'display: none' : ''; ?>">
                                    <li><?= $this->lang->line('mileage'); ?></li>
                                    <li id="itemMilage"></li>
                                </ul>
                                <ul>
                                    <li><p id="itemDetail"></p></li>
                                </ul>

 <div class="button-row report-btn">
                                    <ul>
                                        <?php if (($category['include_make_model'] == 'yes') && ($item['inspected']=='yes')) : ?>
                                        <li>
                                            <a href="<?= base_url('inspection_report/').$item['id']; ?>" class="btn-primary" target="_blank"><?= $this->lang->line('report');?></a>
                                        </li>
                                        <?php endif; ?>

                                        <?php
                                        $f = 0;
                                        if (!empty($item['item_test_report']) || $item['item_test_report'] !='' ) {
                                            $test_report_ids = explode(',', $item['item_test_report']);
                                            $item_test_report = $this->db->where_in('id', $test_report_ids)->get_where('files')->result_array();
                                            foreach ($item_test_report as $key1 => $document) { $f++;
                                            ?>
                                                <li>
                                                    <a href="<?= base_url().$document['path'].$document['name']; ?>" class="btn-primary" download><?php $path_parts = pathinfo($document['orignal_name']);
                                                echo $path_parts['filename']; ?></a>
                                                </li>
                                        <?php } } ?>
                                    </ul>
                                </div>
                                
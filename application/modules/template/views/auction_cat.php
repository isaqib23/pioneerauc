<?php 
$active_auction_categories_header = $this->home_model->get_active_auction_categories();
foreach ($active_auction_categories_header as $key => $value) {
    $count = 0;
    $auctions_online = $this->home_model->get_online_auctions($value['id']);
    if (!empty($auctions_online)) {
        $active_auction_categories_header[$key]['auction_id'] =  $auctions_online['id'];
        $active_auction_categories_header[$key]['expiry_time'] =  $auctions_online['expiry_time'];
        $count= $this->db->select('*')->from('auction_items')->where('auction_id',$auctions_online['id'])->where('sold_status','not')->where('auction_items.bid_start_time <',date('Y-m-d H:i'))->get()->result_array();
        $count = count($count);
    }
    if ($this->session->userdata('logged_in')) {
        $u_id = $this->session->userdata('logged_in')->id;
        $close_auctions = $this->db->where("FIND_IN_SET('".$u_id."', close_auction_users)")->get_where('auctions', ['expiry_time >=' => date('Y-m-d H:i:s'), 'status' => 'active','access_type' => 'closed','category_id' => $value['id']])->result_array(); 
        if ($close_auctions) {
            foreach ($close_auctions as $key1 => $close_auction) {
                $item_ids = array();
                $sub_total = count($this->lam->get_live_auction_items($close_auction['id'], 0, 0, $item_ids));
                $count = $count + $sub_total;
            }
        }
    }
    $live_auctions = $this->db->get_where('auctions', ['expiry_time >=' => date('Y-m-d H:i:s'), 'status' => 'active','access_type' => 'live','category_id' => $value['id']])->result_array();  
    if ($live_auctions) {
        foreach ($live_auctions as $key2 => $live_auction) {
            $live_item_ids = array();
            $total = count($this->lam->get_live_auction_items($live_auction['id'], 0, 0, $live_item_ids));
            $count = $count + $total;
        }
    }
    $active_auction_categories_header[$key]['item_count'] =  $count;
} 
?>
<div class="auction-list">
    <div class="container">
        <div class="wrapper">
            <div class="left d-flex">
                <?php if($active_auction_categories_header):
                    $j=0;
                 ?>
                    <?php foreach ($active_auction_categories_header as $key => $value):
                        $j++;
                        $title = json_decode($value['title']);
                        $cat_id = '';
                        if (isset($category_id)) {
                            $cat_id = $category_id;
                        }
                    ?>
                        <div class="icon <?= ($value['id'] == $cat_id) ? 'active' : '' ?>" id="icon<?=$j;?>">
                            <script>
                                $( document ).ready(function() {
                                    $('#icon<?=$j;?>').tooltip({
                                      title:'<?= $title->$language; ?>',
                                      placement:'bottom'
                                    });
                                });
                            </script>
                            <!-- <?php 
                            if(isset($value['item_count']) && !empty($value['item_count'])){
                            ?>
                            <span class="counter" style="background-color: black"><?php echo $value['item_count'];?></span>
                            <?php }?> -->
                            <?php if(isset($value)){
                            $file = $this->db->get_where('files', ['id' => $value['category_icon']])->row_array(); ?>
                            <input type="hidden" name="old_file" value="<?= $file['name']; ?>">
                            <img src="<?= base_url($file['path'] . $file['name']); ?>" alt="">
                             <?php } ?>
                             <?php if (!empty($value['item_count'])) { ?>
                            <a href="<?= base_url('auction/online-auction/'.$value['id']); ?>" title="<?= $title->$language; ?>" class="link"></a>
                            <?php } ?> 
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="right">
                <a href="<?= base_url('contact-us')?>" class="btn btn-default"><?= $this->lang->line('contact_us');?></a>
            </div>
        </div>
    </div>
</div>


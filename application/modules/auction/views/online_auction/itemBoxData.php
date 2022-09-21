


<li>
                                <h4><?= $this->lang->line('report'); ?></h4>
                                <img src="<?= NEW_ASSETS_USER; ?>/new/images/detail-icons/driving-test-icons.svg">
                                <p><?= ($item['inspected']=='yes')? $this->lang->line('available') : $this->lang->line('not_available'); ?></p>
                            </li>
                            <?php if (isset($item['mileage']) && !empty($item['mileage'])) : ?>
                                <li>
                                    <h4><?= $this->lang->line('odometer'); ?></h4>
                                    <img src="<?= NEW_ASSETS_USER; ?>/new/images/detail-icons/odometer-icons.svg">
                                    <p id="Itemmileage"><?= ($item['mileage']); ?></p>
                                </li>
                            <?php endif; ?>
                            <?php if (isset($item['specification']) && !empty($item['specification'])) : ?>
                                <li>
                                    <h4><?= $this->lang->line('specifications'); ?></h4>
                                    <img src="<?= NEW_ASSETS_USER; ?>/new/images/detail-icons/specification-icons.svg">
                                    <?php 
                                    if ($language == 'english') {
                                        if ($item['specification'] == 'GCC') {
                                            $specsType = 'GCC';
                                        }else{
                                            $specsType = 'IMPORTED';
                                        }
                                    }else{
                                        if ($item['specification'] == 'GCC') {
                                            $specsType = 'خليجية';
                                        }else{
                                            $specsType = 'وارد';
                                        }
                                    } ?>
                                    <p><?= $specsType; ?></p>
                                </li>
                            <?php endif; 
                            $i=1;
                            foreach ($fields as $key => $value) {
       
             if (!empty($value['data-value'])) {
                $i++;
                if($i==2){
            ?>

                            <li>
                                <h4><?= $this->template->make_dual($value['label']);?></h4>
                                <img src="<?= NEW_ASSETS_USER; ?>/new/images/detail-icons/color-icons.svg">
                                <p>
                                <?php 
                                    if ($language == 'english') {
                                      $t= isset($value['data-value'])? explode('|',$value['data-value'])[0]: $value['data-value'];
                                      echo $t;

                                    }else{
                                       $t= isset($value['data-value'])? explode('|',$value['data-value'])[1]: $value['data-value'];
                                       echo $t;
                                    } ?>  
                                
                             
                            </li>
                        <?php }
                    }
                                        }
                    ?>
                            <li>
                                <h4><?= $this->lang->line('year'); ?></h4>
                                <img src="<?= NEW_ASSETS_USER; ?>/new/images/detail-icons/year-icons.svg">
                                <p id="ItemYear"><?= $item['year']; ?></p>
                            </li>
                            <li>
                                <h4><?= $this->lang->line('vat'); ?></h4>
                                <img src="<?= NEW_ASSETS_USER; ?>/new/images/detail-icons/vat-icons.svg">
                                <p><?= $this->lang->line('applicable'); ?></p>
                            </li>
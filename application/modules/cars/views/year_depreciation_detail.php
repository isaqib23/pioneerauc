
  <?php if(!empty($valuation_year)){ ?>
    <table id="models" class="table table-striped jambo_table bulk_action">
          <!-- jambo_table bulk_action (for jambo action table) -->
            <thead>
                <tr class="headings">
                   
                    <th class="column-title">Year  </th>
                    <th class="column-title">Depreciation </th>
                    <th class="column-title">Created On </th>
                   

                </tr>
            </thead>
            <tbody id="model_listing">
                <?php 
                    foreach($valuation_year as $years){
                        ?>  
                    <td class=""><?php echo $years['year']; ?></td>
                    <td class=""><?php echo $years['year_depreciation']; ?></td>
                    <td class=""><?php echo $years['created_on']; ?></td>
                </tr>
                <?php 
                        } 
                ?>
            </tbody>
        </table>
      <?php }else{ ?>
        <p style=" align-content: : center">No Record Found</p>
          <?php } ?>





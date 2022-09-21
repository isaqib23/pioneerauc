<?php
$total_charges = [];
$charges_columns_count = ($charges) ? count($charges) : 0;
?>
<table id="dt-sales-reports" class="table table-striped table-bordered">
	<thead>	
		<tr>
			<th>Lot</th>
			<th>Make</th>
			<th>Model</th>
			<th>Item</th>
			<th>Year</th>
			<th>Reg No</th>
			<th>Auction</th>
			<th>Reserved</th>
			<th>Seller Name</th>
			<th>Seller Code</th>
			<th>Buyer Name</th>
			<th>Buyer Mobile</th>
			<th>Buyer Code</th>
			<th>Sales Price</th>
			<th>Status</th>
			<?php
			if($charges){
				foreach ($charges as $key => $value) {
					echo '<th>'.$value['charges_title'].'</th>';
				}
			}
			if($vat){
				echo '<th>VAT</th>';
			}
			?>
			<th>Total Charges</th>
			<th>Net Settlement</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if($data){
			foreach ($data as $key => $value) {
				?>
				<tr>
					<td><?= $value['lot_no']; ?></td>
					<td><?= $value['make']; ?></td>
					<td><?= $value['model']; ?></td>
					<td><?= $value['item_name']; ?></td>
					<td><?= $value['item_year']; ?></td>
					<td><?= $value['registration_no']; ?></td>
					<td><?= $value['auction_name']; ?></td>
					<td><?= $value['reserved_price']; ?></td>
					<td><?= $value['seller_name']; ?></td>
					<td><?= $value['seller_code']; ?></td>
					<td><?= $value['buyer_name']; ?></td>
					<td><?= $value['buyer_mobile']; ?></td>
					<td><?= $value['buyer_code']; ?></td>
					<td><?= $value['sold_price']; ?></td>
					<td><?= $value['item_status']; ?></td>
					<?php
					if($charges){
						foreach ($charges as $k => $v) {
							$comm = 0;
							if($v['charges_type'] == 'percent'){
								$comm = ((float)$value['sold_price'] * (float)$v['commission']) / 100;
							}else{
								$comm = $v['commission'];
							}
							array_push($total_charges, $comm);
							echo '<td>'.round($comm).'</td>';
						}
					}

					if($vat){
						$vat_value = 0;
						if($vat['type'] == 'percentage'){
							$vat_value = ((float)$value['sold_price'] * (float)$vat['value']) / 100;
						}else{
							$vat_value = $vat['value'];
						}
						array_push($total_charges, $vat_value);
						echo '<td>'.round($vat_value).'</td>';
					}
					?>
					<td>
						<?php
						$total_charges_value = 0;
						if($total_charges){
							$total_charges_value = array_sum($total_charges);
							$total_charges = [];
						}
						echo round($total_charges_value);
						?>
					</td>
					<td><?php echo round((float)$value['sold_price'] - $total_charges_value); ?></td>
				</tr>
			<?php } ?>
				<tr>
					<td></td>
					<td>Totals</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<?php
					if($charges){
						foreach ($charges as $key => $value) {
							?>
							<td id="charges-<?= $key+1; ?>"></td>
							<?php
						}
					}
					if($vat){
						echo '<td id="totVat"></td>';
					}
					?>
					<td id="totCharges"></td>
					<td id="totNet"></td>
				</tr>

		<?php } ?>
	</tbody>
</table>

<script>

	jQuery.fn.dataTable.Api.register( 'sum()', function () {
	    return this.flatten().reduce( function ( a, b ) {
	        if ( typeof a === 'string' ) {
	            a = a.replace(/[^\d.-]/g, '') * 1;
	        }
	        if ( typeof b === 'string' ) {
	            b = b.replace(/[^\d.-]/g, '') * 1;
	        }
	 
	        return a + b;
	    }, 0 );
	});

	$(document).ready( function () {
		/*$("#dt-sales-reports").append(
	       $('<tfoot/>').append( $("#dt-sales-reports thead tr").clone() )
	   	);*/

		$("#dt-sales-reports").DataTable({
			dom: '<"pull-left top"B><"pull-right"fl><"clear">r<t><"bottom"ip><"clear">',
		  	buttons: [
	            {
	                extend: 'collection',
	                text: 'Export',
	                className: 'btn-sm',
	                buttons: [
				        { 
				            extend : 'excel',
				            exportOptions: {
						        columns: ':visible',
						        orthogonal: 'excel',
						        modifier: {
						            order: 'current',
						            page: 'all',
						            selected: false,
						        }
						    }
				        }
			    	]
	            }
	        ],
	        "scrollX": true,
	        "order": [[0, 'asc']],
	        "ordering": false,
	        drawCallback: function () {
				var api = this.api();
				var dynamicCols = "<?= $charges_columns_count; ?>";
				dynamicCols = parseFloat(dynamicCols);
				var totals = 14 + dynamicCols;

				console.log(dynamicCols);

				for (var i = 1; i <= dynamicCols; i++) {
					$("#charges-"+i).html(api.column( (14+i), {page:'all'} ).data().sum());
				}

				$("#totVat").html(api.column( (totals + 1), {page:'all'} ).data().sum());
				$("#totCharges").html(api.column( (totals + 2), {page:'all'} ).data().sum());
				$("#totNet").html(api.column((totals + 3), {page:'all'} ).data().sum());
				//$( api.table().footer() ).html();
		    }
		});
	});



	
</script>
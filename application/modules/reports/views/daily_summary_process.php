<table id="dt-sales-reports" class="table table-striped table-bordered">
	<thead>	
		<tr>
			<th>Summary</th>
			<th>Amount</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		if($data){
			foreach ($data as $key => $value) {
				?>
				<tr>
					<td><?= ($key == "Temporary") ? 'Temporary Deposit' : $key; ?></td>
					<td><?= $value; ?></td>
				</tr>
				<?php
			}
		}
		?>
	</tbody>
</table>

<script>
	$(document).ready( function () {
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
	        "scrollX": false,
	        "order": [[0, 'asc']],
	        "ordering": false
		});
	});
</script>
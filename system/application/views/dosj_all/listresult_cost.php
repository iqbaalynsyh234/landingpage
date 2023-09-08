		<table width="100%" cellpadding="3" class="table sortable no-margin" style="margin: 3px;">
			<thead>
				<tr>
					<th width="2%">No.</td>
					<th style="text-align:center;">Destination</th>
					<th style="text-align:center;">Vehicle Type</th>
					<th style="text-align:center;">Cost</th>
					<th style="text-align:center;">Control</th>
				</tr>
			</thead>
			<tbody>
			<?php
			for($i=0; $i < count($data); $i++)
			{
			?>
				<tr <?=($i%2) ? "class='odd'" : "";?>>
					<td style="text-align:center;"><?=$i+1+$offset?></td>
					<td style="text-align:left;"><?=$data[$i]->destination_name;?></td>
					<td style="text-align:left;"><?=$data[$i]->cost_vehicle_type;?></td>
					<td style="text-align:right;"><?php echo "Rp."." ".number_format($data[$i]->cost);?></td>
					<td style="text-align:center;">
						<a href="javascript: cost_edit('<?php echo $data[$i]->cost_id;?>')" title="Edit"><img src="<?php echo base_url();?>assets/images/edit.gif" /></a>
						<a href="javascript: cost_delete_data('<?php echo $data[$i]->cost_id;?>')" title="Delete"><img src="<?php echo base_url();?>assets/images/trash.gif" /></a>
					</td>
				</tr>
			<?php
			}
			?>
			</tbody>
			<tfoot>
					<tr>
							<td colspan="10"><?php if (isset($paging)) { echo $paging; } ?></td>
					</tr>
			</tfoot>
		</table>

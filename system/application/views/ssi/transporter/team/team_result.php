		
		<table width="100%" cellpadding="3" class="table sortable no-margin" style="margin: 3px;">
			<thead>
				<tr>
					<th width="2%">No.</td>
					<th width="10%" valign="top" style="text-align:center;">Vehicle</th>
					<th width="10%" valign="top" style="text-align:center;">Start Time</th>
					<th width="10%" valign="top" style="text-align:center;">End Time</th>
					<th width="10%" valign="top" style="text-align:center;">Staff Replenishment</th>
					<th width="10%" valign="top" style="text-align:center;">Driver</th>
					<th width="15%" valign="top" style="text-align:center;">Pengaman</th>
					<th width="5%" valign="top" style="text-align:center;">Shift</th>
					<th width="13%" valign="top" style="text-align:center;">Notes</th>
					<th width="7%" valign="top" style="text-align:center;">Control</th>
				</tr>
			</thead>
			<tbody>
			<?php
			if(count($data) > 0){
			for($i=0; $i < count($data); $i++)
			{
			?>
				<tr <?=($i%2) ? "class='odd'" : "";?>>
					<td valign="top"><?=$i+1+$offset?></td>
					<td valign="top" style="text-align:center;"><?=$data[$i]->team_vehicle_no; ?><br /><?=$data[$i]->team_vehicle_name; ?></td>
					<td valign="top" style="text-align:center;"><?=date("d-m-Y H:i",strtotime($data[$i]->team_date." ".$data[$i]->team_time));?></td>
					<td valign="top" style="text-align:center;"><?=date("d-m-Y H:i",strtotime($data[$i]->team_enddate." ".$data[$i]->team_endtime));?></td>
					<td valign="top" style="text-align:center;"><?=$data[$i]->team_staff; ?><br /><?=$data[$i]->team_staff_npp; ?></td>
					<td valign="top" style="text-align:center;"><?=$data[$i]->team_driver; ?><br /><?=$data[$i]->team_driver_npp; ?></td>
					<td valign="top" style="text-align:center;">
						<?=$data[$i]->team_pengaman1." ";?><?php if (isset($data[$i]->team_pengaman1_nrp)) { echo $data[$i]->team_pengaman1_nrp; }?> <br />
						<?=$data[$i]->team_pengaman2." ";?><?php if (isset($data[$i]->team_pengaman2_nrp)) { echo $data[$i]->team_pengaman2_nrp; }?> <br />
						<?=$data[$i]->team_pengaman3." ";?><?php if (isset($data[$i]->team_pengaman3_nrp)) { echo $data[$i]->team_pengaman3_nrp; }?> <br />
					</td>
					<td valign="top" style="text-align:center;">
						<?php if($data[$i]->team_shift <> 0) {?>
						Shift-<?=$data[$i]->team_shift; ?>
						<?php } ?>
					</td>
					<td valign="top" style="text-align:center;"><?=$data[$i]->team_note; ?></td>
					
					<td valign="top" style="text-align:center;">
						<a href="#" onclick="javascript:edit(<?=$data[$i]->team_id;?>)"><img src="<?=base_url();?>assets/images/edit.gif" border="0" alt="Edit Data" title="Edit Data"></a>
						<a href="#" onclick="javascript:delete_data(<?=$data[$i]->team_id;?>)"><img src="<?=base_url();?>assets/images/trash.gif" border="0" alt="Delete Data" title="Delete Data"></a>
					</td>
					
				</tr>
			<?php
			}
			}else{
				echo "<tr><td colspan='12'>No Data Available</td></tr>";
			}
			?>
			</tbody>
			<tfoot>
					<tr>
						<td colspan="12"><!--<?=$paging?>--></td>
					</tr>
			</tfoot>
		</table>

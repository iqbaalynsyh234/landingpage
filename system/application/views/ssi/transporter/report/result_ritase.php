	<table width="100%" cellpadding="3" class="table sortable no-margin" style="margin: 3px;">
			<thead>
				<tr>
					<th width="2%">No.</td>
					<th width="10%" valign="top" style="text-align:center;">KENDARAAN</th>
					<th width="10%" valign="top" style="text-align:center;">WAKTU MULAI</th>
					<th width="10%" valign="top" style="text-align:center;">WAKTU BERAKHIR</th>
					<th width="10%" valign="top" style="text-align:center;">SHIFT</th>
					<th width="10%" valign="top" style="text-align:center;">STAFF REPLENISHMENT</th>
					<th width="10%" valign="top" style="text-align:center;">PENGEMUDI</th>
					<th width="10%" valign="top" style="text-align:center;">PENGAMAN</th>
					<th width="15%" valign="top" style="text-align:center;">CATATAN</th>
					
				</tr>
			</thead>
			<tbody>
			<?php
			if(count($data_r) > 0){
			for($i=0; $i < count($data_r); $i++)
			{
			?>
				<tr <?=($i%2) ? "class='odd'" : "";?>>
					<td valign="top"><?php echo $i+1;?></td>
					<td valign="top" style="text-align:center;"><?=$data_r[$i]->team_vehicle_no; ?><br /><?=$data_r[$i]->team_vehicle_name; ?></td>
					<td valign="top" style="text-align:center;"><?=date("d-m-Y H:i",strtotime($data_r[$i]->team_date." ".$data_r[$i]->team_time));?></td>
					<td valign="top" style="text-align:center;"><?=date("d-m-Y H:i",strtotime($data_r[$i]->team_enddate." ".$data_r[$i]->team_endtime));?></td>
					<td valign="top" style="text-align:center;"><?="SHIFT-".$data_r[$i]->team_shift; ?></td>
					<td valign="top" style="text-align:center;"><?=$data_r[$i]->team_staff; ?><br /><?=$data_r[$i]->team_staff_npp; ?></td>
					<td valign="top" style="text-align:center;"><?=$data_r[$i]->team_driver; ?><br /><?=$data_r[$i]->team_driver_npp; ?></td>
					<td valign="top" style="text-align:center;">
						<?=$data_r[$i]->team_pengaman1;?><?php if (isset($data_r[$i]->team_pengaman1_nrp)) { echo " - ".$data_r[$i]->team_pengaman1_nrp; }?>
						<?php if (isset($data_r[$i]->team_pengaman2)) { echo $data_r[$i]->team_pengaman2; }?><?php if (isset($data_r[$i]->team_pengaman2_nrp)) { echo " - ".$data_r[$i]->team_pengaman2_nrp; }?> 
						<?php if (isset($data_r[$i]->team_pengaman3)) { echo $data_r[$i]->team_pengaman3; }?><?php if (isset($data_r[$i]->team_pengaman3_nrp)) { echo " - ".$data_r[$i]->team_pengaman3_nrp; }?>
					</td>
					<td valign="top" style="text-align:center;"><?=$data_r[$i]->team_note; ?></td>
				</tr>
			<?php
			}
			}else{
				echo "<tr><td colspan='12'>No Data Available</td></tr>";
			}
			?>
			</tbody>
			<tfoot>
				
						
			</tfoot>
	</table>
	<br />
	
	<table width="100%" cellpadding="3" class="table sortable no-margin">
			<thead>
				<tr>
					<th width="2%">*</td>
					<th width="20%">KELUAR</th>
					<th>MASUK</th>
					<th>DURASI</th>
					
				</tr>
			</thead>
			<tbody>
			<?php 
			$totalritase = 0;
			$j = count($data);
			for($i=0; $i < count($data); $i++) {
			if ($data[$i]->geoalert_direction == 2 && isset($data[$i+1]->geofence_name)) {
			?>
				<tr <?=($i%2) ? "class='odd'" : "";?>>
					<td>*</td>
					<td>
					
					<?php 

						echo $data[$i]->geofence_name;
						echo "<br />";
						echo "Start date". " " .date("d/m/Y H:i:s", $data[$i]->geoalert_time_t) ."<br />";
					?>
					</td>
					<td>
					 <?php 
						if ($data[$i]->geoalert_direction == 2) 
						{ 
							if (isset($data[$i+1]->geofence_name))
							{

								echo $data[$i+1]->geofence_name;
								echo "<br />";
								echo "Finish ". " ". date("d/m/Y H:i:s", $data[$i+1]->geoalert_time_t) . "<br />"; 
							} 
							else
							{
								echo "-";
							}
						} 
					?>
					</td>
					<td>
						<?php
							if (isset($data[$i+1]->geofence_name))
							{
								$startdate = new DateTime(date("d-m-Y H:i:s", $data[$i]->geoalert_time_t));
								$enddate = new DateTime(date("d-m-Y H:i:s", $data[$i+1]->geoalert_time_t));
								$duration = $startdate->diff($enddate);
								$d_day = $duration->format('%d');
								$d_hour = $duration->format('%h');
								$d_minute = $duration->format('%i');
								$d_second = $duration->format('%s');
								if (isset($d_day) && ($d_day > 0))
								{
									echo $d_day ." ". "Day" ." ".$d_hour ." "."Jam" ." ". $d_minute ." ". "Menit" ." ". $d_second ." ". "Detik";
								}
								else if (isset($d_hour) && ($d_hour > 0))
								{
									echo $d_hour ." "."Jam" ." ". $d_minute ." ". "Menit" ." ". $d_second ." ". "Detik";
								}
								else
								{
									echo $d_minute ." ". "Menit" ." ". $d_second ." ". "Detik";
								}
							}
							else
							{
								echo "-";
							} 
						?>
					</td>
				</tr>
			<?php $totalritase+=1; } } ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="7">RITASE Report :  Start <?php echo date("d-m-Y", strtotime($start_date));?> - End <?php echo date("d-m-Y", strtotime($end_date));?>, Total Ritase : <b><?php echo $totalritase;?></b></td>
				</tr>
			</tfoot>
	</table>

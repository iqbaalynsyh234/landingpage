<table width="100%" cellpadding="3" class="table sortable no-margin" style="margin: 3px;">
			<thead>
				<tr>
					<th width="2%">No.</td>
					<th width="10%" valign="top" style="text-align:center;">KENDARAAN</th>
					<th width="10%" valign="top" style="text-align:center;">WAKTU MULAI</th>
					<th width="10%" valign="top" style="text-align:center;">WAKTU BERAKHIR</th>
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
					<td valign="top" style="text-align:center;"><?=$data_r[$i]->team_staff; ?><br /><?=$data_r[$i]->team_staff_npp; ?></td>
					<td valign="top" style="text-align:center;"><?=$data_r[$i]->team_driver; ?><br /><?=$data_r[$i]->team_driver_npp; ?></td>
					<td valign="top" style="text-align:center;">
						<?=$data_r[$i]->team_pengaman1;?><br /><?php if (isset($data_r[$i]->team_pengaman1_nrp)) { echo $data_r[$i]->team_pengaman1_nrp; }?> <br />
						<?=$data_r[$i]->team_pengaman2;?><br /><?php if (isset($data_r[$i]->team_pengaman2_nrp)) { echo $data_r[$i]->team_pengaman2_nrp; }?> <br />
						<?=$data_r[$i]->team_pengaman3;?><br /><?php if (isset($data_r[$i]->team_pengaman3_nrp)) { echo $data_r[$i]->team_pengaman3_nrp; }?> <br />
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
	
<table width="100%" cellpadding="3" class="table sortable no-margin" style="margin: 3px;">
	<thead>
    	<tr>
	        <th style="text-align:center;" width="3%">NO</td>
			<th style="text-align:center;" width="10%">KENDARAAN</th>
			<th style="text-align:center;" width="10%">WAKTU MULAI</td>
			<th style="text-align:center;" width="10%">WAKTU BERAKHIR</th>
			<th style="text-align:center;" width="5%">MESIN</th>			
			<th style="text-align:center;" width="20%">LOKASI MULAI</th>
			<th style="text-align:center;" width="20%">LOKASI BERAKHIR</th>
			<th style="text-align:center;" width="7%">DURASI</th>
			<th style="text-align:center;" width="7%">JARAK TEMPUH</th>		
			<th style="text-align:center;" width="7%">AKUMULASI JARAK TEMPUH</th>
			
		</tr>
    </thead>
	<tbody>
		<?php
			if (count($data)>0)
			{
				$j = 0;
				for ($i=0;$i<count($data);$i++)
				{ 
					$j = $j + $data[$i]->trip_mileage_trip_mileage;
				?>
				<tr>
					<td style="text-align:center;" ><?php echo $i+1;?></td>
					<td><?php echo $data[$i]->trip_mileage_vehicle_name." ".$data[$i]->trip_mileage_vehicle_no;?></td>
					<td><?php echo $data[$i]->trip_mileage_start_time;?></td>
					<td><?php echo $data[$i]->trip_mileage_end_time;?></td>
					<td style="text-align:center;" >
						<?php if($data[$i]->trip_mileage_engine == 0) { ?>
							<?php echo "MATI";?>
						<?php } else { ?>
							<?php echo "HIDUP";?>
						<?php } ?>
					</td>
					<td>
						<?php $geofence_start = strlen($data[$i]->trip_mileage_geofence_start); 
							if (strlen($geofence_start == 1)){	
							$geofence_start_name = "";?>
								<strong><font color="red"><?php echo $geofence_start_name;?></font></strong><br />
								<?php echo $data[$i]->trip_mileage_location_start;?>
						<?php } ?>
						
						<?php
							if (strlen($geofence_start > 1)){	
							$geofence_start_name = $data[$i]->trip_mileage_geofence_start;?>
								<strong><font color="red"><?php echo $geofence_start_name;?></font></strong><br />
								<?php echo $data[$i]->trip_mileage_location_start;?>
						<?php } ?>
					</td>
					
					<td>
						<?php $geofence_end = strlen($data[$i]->trip_mileage_geofence_end); 
							if (strlen($geofence_end == 1)){	
							$geofence_end_name = "";?>
								<strong><font color="red"><?php echo $geofence_end_name;?></font></strong><br />
								<?php echo $data[$i]->trip_mileage_location_end;?>
						<?php } ?>
						
						<?php
							if (strlen($geofence_end > 1)){	
							$geofence_end_name = $data[$i]->trip_mileage_geofence_end;?>
								<strong><font color="red"><?php echo $geofence_end_name;?></font></strong><br />
								<?php echo $data[$i]->trip_mileage_location_end;?>
						<?php } ?>
					</td>
					<td><?php echo $data[$i]->trip_mileage_duration;?></td>
					<td><?php echo $data[$i]->trip_mileage_trip_mileage." "."KM";?></td>
					<td><?php echo $j." "."KM";?></td>
					
				</tr>
		<?php
				}
			}else{
				echo "<tr><td colspan='12'>No Data Available</td></tr>";
			}
			?>
    </tbody>
	
		<tr>
			<td colspan="7"><strong>TOTAL JARAK TEMPUH</strong></td>
			<td colspan="3" style="text-align:center;"><strong><?php echo $j." "."KM";?></strong></td>
		</tr>
		<tr>
		<td colspan="7"><strong>TOTAL DURASI</strong></td>
		<td colspan="3" style="text-align:center;"><strong><?php 
			if (isset($totalduration))
									{
										$conval = $totalduration;
										$seconds = $conval;
										
										// extract hours
										$hours = floor($seconds / (60 * 60));
	 
										// extract minutes
										$divisor_for_minutes = $seconds % (60 * 60);
										$minutes = floor($divisor_for_minutes / 60);
	 
										// extract the remaining seconds
										$divisor_for_seconds = $divisor_for_minutes % 60;
										$seconds = ceil($divisor_for_seconds);
										
										if(isset($hours) && $hours > 0)
										{
											if($hours > 0 && $hours <= 1)
											{
												echo $hours." "."Jam"." ";
											}
											if($hours >= 2)
											{
												echo $hours." "."Jam"." ";
											}
										}
										if(isset($minutes) && $minutes > 0)
										{
											if($minutes > 0 && $minutes <= 1 )
											{
												echo $minutes." "."Menit"." ";
											}
											if($minutes >= 2)
											{
												echo $minutes." "."Menit"." ";
											}
										}
										 if(isset($seconds) && $seconds > 0)
										{
											echo $seconds." "."Detik"." ";
										}
									}
									
									?></strong>
			
			</td>
		</tr>
	
</table>
<script src="<?php echo base_url();?>assets/js/jsblong/jquery.table2excel.js"></script>
<script>
jQuery(document).ready(
		function()
		{
			jQuery("#export_xcel").click(function() 
			{ 
				window.open('data:application/vnd.ms-excel,' + encodeURIComponent(jQuery('#isexport_xcel').html()));
			});
		}
	);


</script>


							<div class="col-lg-6 col-sm-6">	
								<input id="btn_hide_form" class="btn btn-circle btn-danger" title="" type="button" value="Hide Form" onclick="javascript:return option_form('hide')" />
								<input id="btn_show_form" class="btn btn-circle btn-success" title="" type="button" value="Show Form" onClick="javascript:return option_form('show')" style="display:none"/>
							</div>
							<div class="col-lg-2 col-sm-2">	
							</div>
							<br />
							
<div class="row">
	<div class="col-md-12 col-sm-12">
		<div class="panel">
<?php if($startdate == $enddate){ ?>
<p>

<?php } ?>
			<header class="panel-heading panel-heading-blue">REPORT</header>
				<div class="panel-body" id="bar-parent10">
					<div class="row">	
					<?php if (count($data) == 0) {
							echo "<p>No Data</p>";
					}else{ ?>
						<div class="col-md-12 col-sm-12">
							
							<div class="col-lg-4 col-sm-4">	
								<a href="javascript:void(0);" id="export_xcel" type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-circle btn-info"><small>Export to Excel</small></a>
							</div>	
							
							<div id="isexport_xcel">
							<table class="table table-striped custom-table table-hover">
								<thead>
									<tr>
										<th style="text-align:center;" width="3%">No</th>
										<th style="text-align:center;" width="10%">Vehicle</th>
										<th style="text-align:center;" width="10%">Start Time</th>
										<th style="text-align:center;" width="10%">End Time</th>
										<th style="text-align:center;" width="5%">Engine</th>
										<th style="text-align:center;" width="7%">Duration</th>
										<th style="text-align:center;" width="20%">Location Start</th>
										<th style="text-align:center;" width="20%">Location End</th>
										<th style="text-align:center;" width="7%">Trip Mileage</th>		
										<th style="text-align:center;" width="7%">Commulative Mileage</th>
									</tr>
								</thead>
								<tbody>
								
								
	 <?php
		if(isset($data) && (count($data) > 0)){
			$j = 0;
				$jkm = 0;
				for ($i=0;$i<count($data);$i++)
				{ 
					if($data[$i]->trip_mileage_engine == "1"){
						$jkm = $data[$i]->trip_mileage_trip_mileage;	
					}else{
						$jkm = 0;	
					}
					
					$j = $j + $jkm;	
					
					$doorstart_status = "";
					$doorend_status = "";
					$vdoorstatus = "";
					$vtype = "";
					
					if (in_array(strtoupper($data[$i]->trip_mileage_vehicle_type), $this->config->item("vehicle_odometer_portable")))
					{
						$engine_on = "MOVE";
						$engine_off = "STOP";
					}else{
						$engine_on = "ON";
						$engine_off = "OFF";
					}
				?>
				<tr>
					<td style="text-align:center;font-size:12px;"><?php echo $i+1;?></td>
					<td style="text-align:center;font-size:12px;"><?php echo $data[$i]->trip_mileage_vehicle_name;?> - <?php echo $data[$i]->trip_mileage_vehicle_no;?>
					</td>
					<td style="text-align:center;font-size:12px;"><?php echo $data[$i]->trip_mileage_start_time;?></td>
					<td style="text-align:center;font-size:12px;"><?php echo $data[$i]->trip_mileage_end_time;?></td>
					<td style="text-align:center;font-size:12px;">
					
						<?php if($data[$i]->trip_mileage_engine == 0) { ?>
							<?php echo $engine_off;?>
						<?php } else { ?>
							<?php echo $engine_on;?>
						<?php } ?>
					</td>
					<td style="text-align:center;font-size:12px;">
					<?php 
						if( (isset($data[$i]->trip_mileage_coordinate_list)) && ($data[$i]->trip_mileage_engine == "1") && ($data[$i]->trip_mileage_coordinate_list != "") ){ ?>
							<!--<a href="javascript:mn_map(<?=$data[$i]->trip_mileage_id;?>)" target="_blank"><?php echo $data[$i]->trip_mileage_duration;?></a> -->
							<a href="<?php echo base_url();?>operational_report/map/<?=$data[$i]->trip_mileage_id;?>" target="_blank">
							<strong><?php echo $data[$i]->trip_mileage_duration;?></strong>
							</a> 
						<?php }else{?>
							<?php echo $data[$i]->trip_mileage_duration;?>
						<?php }
					?>
					</td>
					<td style="text-align:center;font-size:12px;">
						<?php $geofence_start = strlen($data[$i]->trip_mileage_geofence_start); 
							if (strlen($geofence_start == 1)){	
							$geofence_start_name = "";?>
								<strong><font color="red"><?php echo $geofence_start_name." ";?></font></strong><br />
								<?php echo $data[$i]->trip_mileage_location_start;?>
						<?php } ?>
						
						<?php
							if (strlen($geofence_start > 1)){	
							$geofence_start_name = $data[$i]->trip_mileage_geofence_start;?>
								<strong><font color="red"><?php echo $geofence_start_name." ";?></font></strong><br />
								<?php echo $data[$i]->trip_mileage_location_start;?>
						<?php } ?>
						<strong><font color="red"><br /><?php echo $data[$i]->trip_mileage_coordinate_start;?></font></strong><br />
						<?php 
						//cek apakah sudah pernah ada filenya
						$this->db->order_by("vehicle_id","asc");
						$this->db->select("vehicle_device,vehicle_type");
						$this->db->where("vehicle_device",$data[$i]->trip_mileage_vehicle_id);
						$this->db->where("vehicle_status <>",3);
						$this->db->limit(1);
						$qv = $this->db->get("vehicle");
						$rv = $qv->row();
						if(count($rv) > 0){
							$vtype = $rv->vehicle_type;
							if($vtype == "T5DOOR"){
								$vdoorstatus = "YES";
							}else{
								$vdoorstatus = "NO";
							}
						}else{
							$vtype = "";
						}
						?>
						<!-- cek door status start-->
						<?php if(isset($data[$i]->trip_mileage_door_start) && ($data[$i]->trip_mileage_door_start == 1)){
							$doorstart_status = "OPEN";
						}
							if(isset($data[$i]->trip_mileage_door_start) && ($data[$i]->trip_mileage_door_start == 0)){
							$doorstart_status = "CLOSE";
						}
						?>
						<?php if($vdoorstatus == "YES"){ ?>
							Door: <strong><font color="red"><?php echo $doorstart_status;?></font></strong>
						<?php } ?>
						
					</td>
					
					<td style="text-align:center;font-size:12px;">
						<?php $geofence_end = strlen($data[$i]->trip_mileage_geofence_end); 
							if (strlen($geofence_end == 1)){	
							$geofence_end_name = "";?>
								<strong><font color="red"><?php echo $geofence_end_name." ";?></font></strong><br />
								<?php echo $data[$i]->trip_mileage_location_end;?>
						<?php } ?>
						
						<?php
							if (strlen($geofence_end > 1)){	
							$geofence_end_name = $data[$i]->trip_mileage_geofence_end;?>
								<strong><font color="red"><?php echo $geofence_end_name." ";?></font></strong><br />
								<?php echo $data[$i]->trip_mileage_location_end;?>
						<?php } ?>
						<strong><font color="red"><br /><?php echo $data[$i]->trip_mileage_coordinate_end;;?></font></strong><br />
						
						<!-- cek door status end-->
						<?php if(isset($data[$i]->trip_mileage_door_end) && ($data[$i]->trip_mileage_door_end == 1)){
							$doorend_status = "OPEN";
						}
							else if(isset($data[$i]->trip_mileage_door_end) && ($data[$i]->trip_mileage_door_end == 0)){
							$doorend_status = "CLOSE";
						}
						?>
						<?php if($vdoorstatus == "YES"){ ?>
							Door: <strong><font color="red"><?php echo $doorend_status;?></font></strong>
						<?php } ?>
					</td>
					
					<td style="text-align:center;font-size:12px;"><?php 
						if($data[$i]->trip_mileage_engine == "1"){
							$jkm = round($data[$i]->trip_mileage_trip_mileage,2);	
						}else{
							$jkm = 0;	
						} ?>
						<?php echo $jkm." "."KM";?>
					</td>
					<td style="text-align:center;font-size:12px;"><?php echo $j." "."KM";?></td>
				</tr>
		<?php
				}
			
		}else{
	?>
        <tr>
        	<td colspan="10">No Available Data</td>
		</tr>
	<?php
		}
	?>
								</tbody>
								<tr>
									<td colspan="7" style="text-align:center;font-size:12px;"><strong>Total Mileage</strong></td>
									<td colspan="3" style="text-align:center;font-size:12px;"><strong>
										<?php if (isset($j) && $j > 0){
											echo round($j,2)." "."KM";
										}else{
											echo "";
										}
										?></strong></td>
								</tr>
								<tr>
									<td colspan="7" style="text-align:center;font-size:12px;"><strong>Total Duration <?=$engine_on;?></strong></td>
									<td colspan="3" style="text-align:center;font-size:12px;"><strong><?php 
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
																			echo $hours." "."Hour"." ";
																		}
																		if($hours >= 2)
																		{
																			echo $hours." "."Hours"." ";
																		}
																	}
																	if(isset($minutes) && $minutes > 0)
																	{
																		if($minutes > 0 && $minutes <= 1 )
																		{
																			echo $minutes." "."Minute"." ";
																		}
																		if($minutes >= 2)
																		{
																			echo $minutes." "."Minutes"." ";
																		}
																	}
																	/*  if(isset($seconds) && $seconds > 0)
																	{
																		echo $seconds." "."Detik"." ";
																	} */
																}
																
																?></strong>
										
										</td>
								</tr>
							</table>
							</div>
						</div>	
					
					<?php } ?>
					
					</div>
				</div>
		</div>
	</div>
</div>

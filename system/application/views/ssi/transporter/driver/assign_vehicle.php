
		<form method="post" action="<?php echo base_url();?>transporter/driver/save_assign_car" >
		<table width="100%" cellpadding="3" class="table sortable no-margin" style="margin: 3px;">
			<input type="hidden" name="id" id="id" value="<?php echo $row->driver_id;?>" />
			<input type="hidden" name="car_before" id="car_before" value="<?php echo $row->driver_vehicle;?>" />
			<tr>
				<td colspan="3"><h2><?php echo "Driver Name"." ".$row->driver_name; ?></h2><td>
			</tr>
			<tr>
				<td colspan="3"><h2><b>
					<?php 
							if (isset($car))
										{
											foreach ($car as $car_name)
											{
												if ($car_name->vehicle_id == $row->driver_vehicle)
												{
													echo $car_name->vehicle_no." - ".$car_name->vehicle_name;
												}
											}
										}
						?>
				</b></h2></td>
			</tr>
			<tr>
				<td width="10%">Select Vehicle</td>
				<td width="2%">:</td>
				<td>
					<select name="car_after" id="car_after" >
						<option value="0">--Select / Change Vehicle--</option>
						<?php 
							if (isset($car))
							for($i=0;$i<count($car);$i++)
							{
						?>
							<option value="<?php echo $car[$i]->vehicle_id;?>" ><?php echo $car[$i]->vehicle_no." - ".$car[$i]->vehicle_name." " ?></option>
						<?php
							}
						?>
					</select>
					<input type="submit" name="submit" id="submit" value="Assign" />
					<input type="button" value="Cancel" onclick="javascript:jQuery('#dialog').dialog('close');" />
				</td>
			</tr>
		</table>
		</form>

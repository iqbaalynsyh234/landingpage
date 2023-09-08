<script>
jQuery.maxZIndex = jQuery.fn.maxZIndex = function(opt) {
	    var def = { inc: 10, group: "*" };
	    jQuery.extend(def, opt);
	    var zmax = 0;
	    jQuery(def.group).each(function() {
	        var cur = parseInt(jQuery(this).css('z-index'));
	        zmax = cur > zmax ? cur : zmax;
	    });
	    if (!this.jquery)

	        return zmax;
	
	    return this.each(function() {
	        zmax += def.inc;
	        jQuery(this).css("z-index", zmax);
	    });
	}
jQuery(document).ready(
		function()
		{
			showclock();
			jQuery("#startdate").datepicker(
				{
							dateFormat: 'yy-mm-dd'
						, 	startDate: '1900-01-01'
						, 	showOn: 'button'
						, 	changeYear: true
						,	changeMonth: true
						, 	buttonImage: '<?php echo base_url();?>assets/images/calendar.gif'
						, 	buttonImageOnly: true
						,	beforeShow: 
								function() 
								{	
									jQuery('#ui-datepicker-div').maxZIndex();
								}
				}
			);
			jQuery("#enddate").datepicker(
				{
							dateFormat: 'yy-mm-dd'
						, 	startDate: '1900-01-01'
						, 	showOn: 'button'
						, 	changeYear: true
						,	changeMonth: true
						, 	buttonImage: '<?php echo base_url();?>assets/images/calendar.gif'
						, 	buttonImageOnly: true
						,	beforeShow: 
								function() 
								{	
									jQuery('#ui-datepicker-div').maxZIndex();
								}
				}
			);
		}
	);
	
function frmsave_onsubmit()
	{
		jQuery("#loader").show();
		jQuery.post("<?=base_url()?>ssi_team/save", jQuery("#frmsave").serialize(),	
			function(r)
			{
				jQuery("#loader").hide();
				if (r.error)
				{
					alert(r.message);
					return false;
				}
				
				alert(r.message);
				location = r.redirect;
			}
			, "json"	
		);
		return false;
	}
	
</script>
<div style="position: absolute; margin: 0; padding: 0; z-index: 1000; width: 100%;"> 
<?=$navigation;?>
	<div id="main" style="margin: 20px;">
		<div class="block-border">
		<form class="block-content form" name="frmsave" id="frmsave" onsubmit="javascript: return frmsave_onsubmit()">		
		<input type="hidden" id="mobil_device" name="mobil_device" value="" />
		<input type="hidden" id="mobil_name" name="mobil_name" value="" />
		<input type="hidden" id="mobil_no" name="mobil_no" value="" />
		<input type="hidden" id="company" name="company" value="<?php echo $this->sess->user_company;?>" />
		<input type="hidden" id="group" name="group" value="<?php echo $this->sess->user_group;?>" />
		<input type="hidden" id="creator" name="creator" value="<?php echo $this->sess->user_id;?>" />

		<h1>Add New Team</h1>		
		<table width="100%" cellpadding="3" class="table sortable no-margin">
		<tr>
			<td colspan="6"><h2>Form Team</h2></td>
		</tr>
		<tr>
			<td>Vehicle</td>
			<td>:</td>
			<td>
                <select id="mobil_id" name="mobil_id" >
					<option value="" selected="selected">--Select Vehicle--</option>
								<?php 
									$cmobil = count($rmobil);
									for($i=0;$i<$cmobil;$i++){
										if(isset($row) && $rmobil[$i]->vehicle_id == $row->team_vehicle_id){
											
											$mselected = "selected='selected'";
										}else{
											$mselected = "";
										}
										
										echo "<option value='" . $rmobil[$i]->vehicle_id ."' ". $mselected.">" . $rmobil[$i]->vehicle_no ." - ".$rmobil[$i]->vehicle_name . "</option>";
						
									} ?>
									
							</select></td>
			<td>Schedule Date</td>
			<td>:</td>
			<?php if ($this->sess->user_group == "1224") { ?> <!-- khusus group ssi.mandiri -->
			<td>
				<input type='text' readonly name="startdate"  id="startdate" value="<?=isset($row) ? htmlspecialchars($row->team_date, ENT_QUOTES) : "";?>"  maxlength='10' style="width:150px;">
				<select id="shift" name="shift">
					<option value="">--Select Shift--</option>				
					<option value="1">Shift-1</option>						                
					<option value="2">Shift-2</option>
					<option value="3">Shift-3</option>
				</select>
			</td>
			<?php } else { ?>
			<td>
				<input type='text' readonly name="startdate"  id="startdate" value="<?=isset($row) ? htmlspecialchars($row->team_date, ENT_QUOTES) : "";?>"  maxlength='10' style="width:150px;">
				<select class="textgray" style="font-size: 11px; width: 65px;" id="starttime" name="starttime">						                
												<option value="00:00">00:00</option>						                
												<option value="00:15">00:15</option>
												<option value="00:30">00:30</option>
												<option value="00:45">00:45</option>
												<option value="01:00">01:00</option>						                
												<option value="01:15">01:15</option>						              
												<option value="01:30">01:30</option>						                
												<option value="01:45">01:45</option>
												<option value="02:00">02:00</option>						                
												<option value="02:15">02:15</option>
												<option value="02:30">02:30</option>
												<option value="02:45">02:45</option>
												<option value="03:00">03:00</option>						                
												<option value="03:15">03:15</option>
												<option value="03:30">03:30</option>
												<option value="03:45">03:45</option>
												<option value="04:00">04:00</option>						                
												<option value="04:15">04:15</option>
												<option value="04:30">04:30</option>
												<option value="04:45">04:45</option>
												<option value="05:00">05:00</option>						                
												<option value="05:15">05:15</option>
												<option value="05:30">05:30</option>
												<option value="05:45">05:45</option>
												<option value="06:00">06:00</option>						                
												<option value="06:15">06:15</option>
												<option value="06:30">06:30</option>
												<option value="06:45">06:45</option>
												<option value="07:00">07:00</option>						                
												<option value="07:15">07:15</option>
												<option value="07:30">07:30</option>
												<option value="07:45">07:45</option>
												<option selected="" value="08:00">08:00</option>						                
												<option value="08:15">08:15</option>
												<option value="08:30">08:30</option>
												<option value="08:45">08:45</option>
												<option value="09:00">09:00</option>						                
												<option value="09:15">09:15</option>
												<option value="09:30">09:30</option>
												<option value="09:45">09:45</option>
												<option value="10:00">10:00</option>						                
												<option value="10:15">10:15</option>
												<option value="10:30">10:30</option>
												<option value="10:45">10:45</option>
												<option value="11:00">11:00</option>						                
												<option value="11:15">11:15</option>
												<option value="11:30">11:30</option>
												<option value="11:45">11:45</option>
												<option value="12:00">12:00</option>						                
												<option value="12:15">12:15</option>
												<option value="12:30">12:30</option>
												<option value="12:45">12:45</option>
												<option value="13:00">13:00</option>						                
												<option value="13:15">13:15</option>
												<option value="13:30">13:30</option>
												<option value="13:45">13:45</option>
												<option value="14:00">14:00</option>						                
												<option value="14:15">14:15</option>
												<option value="14:30">14:30</option>
												<option value="14:45">14:45</option>
												<option value="15:00">15:00</option>						                
												<option value="15:15">15:15</option>
												<option value="15:30">15:30</option>
												<option value="15:45">15:45</option>
												<option value="16:00">16:00</option>						                
												<option value="16:15">16:15</option>
												<option value="16:30">16:30</option>
												<option value="16:45">16:45</option>
												<option value="17:00">17:00</option>						                
												<option value="17:15">17:15</option>
												<option value="17:30">17:30</option>
												<option value="17:45">17:45</option>
												<option value="18:00">18:00</option>						                
												<option value="18:15">18:15</option>
												<option value="18:30">18:30</option>
												<option value="18:45">18:45</option>
												<option value="19:00">19:00</option>						                
												<option value="19:15">19:15</option>
												<option value="19:30">19:30</option>
												<option value="19:45">19:45</option>
												<option value="20:00">20:00</option>						                
												<option value="20:15">20:15</option>
												<option value="20:30">20:30</option>
												<option value="20:45">20:45</option>
												<option value="21:00">21:00</option>						                
												<option value="21:15">21:15</option>
												<option value="21:30">21:30</option>
												<option value="21:45">21:45</option>
												<option value="22:00">22:00</option>						                
												<option value="22:15">22:15</option>
												<option value="22:30">22:30</option>
												<option value="22:45">22:45</option>
												<option value="23:00">23:00</option>
												<option value="23:15">23:15</option>
												<option value="23:30">23:30</option>
												<option value="23:45">23:45</option>
										 </select> to 
				<input type='text' readonly name="enddate"  id="enddate" value="<?=isset($row) ? htmlspecialchars($row->team_enddate, ENT_QUOTES) : "";?>"  maxlength='10' style="width:150px;">
				<select class="textgray" style="font-size: 11px; width: 65px;" id="endtime" name="endtime">						                
						                    <option value="00:00">00:00</option>						                
											<option value="00:15">00:15</option>
											<option value="00:30">00:30</option>
											<option value="00:45">00:45</option>
						                    <option value="01:00">01:00</option>						                
											<option value="01:15">01:15</option>						              
											<option value="01:30">01:30</option>						                
											<option value="01:45">01:45</option>
						                    <option value="02:00">02:00</option>						                
											<option value="02:15">02:15</option>
											<option value="02:30">02:30</option>
											<option value="02:45">02:45</option>
						                    <option value="03:00">03:00</option>						                
											<option value="03:15">03:15</option>
											<option value="03:30">03:30</option>
											<option value="03:45">03:45</option>
						                    <option value="04:00">04:00</option>						                
											<option value="04:15">04:15</option>
											<option value="04:30">04:30</option>
											<option value="04:45">04:45</option>
						                    <option value="05:00">05:00</option>						                
											<option value="05:15">05:15</option>
											<option value="05:30">05:30</option>
											<option value="05:45">05:45</option>
						                    <option value="06:00">06:00</option>						                
											<option value="06:15">06:15</option>
											<option value="06:30">06:30</option>
											<option value="06:45">06:45</option>
						                    <option value="07:00">07:00</option>						                
											<option value="07:15">07:15</option>
											<option value="07:30">07:30</option>
											<option value="07:45">07:45</option>
						                    <option value="08:00">08:00</option>						                
											<option value="08:15">08:15</option>
											<option value="08:30">08:30</option>
											<option value="08:45">08:45</option>
						                    <option value="09:00">09:00</option>						                
											<option value="09:15">09:15</option>
											<option value="09:30">09:30</option>
											<option value="09:45">09:45</option>
						                    <option value="10:00">10:00</option>						                
											<option value="10:15">10:15</option>
											<option value="10:30">10:30</option>
											<option value="10:45">10:45</option>
						                    <option value="11:00">11:00</option>						                
											<option value="11:15">11:15</option>
											<option value="11:30">11:30</option>
											<option value="11:45">11:45</option>
						                    <option value="12:00">12:00</option>						                
											<option value="12:15">12:15</option>
											<option value="12:30">12:30</option>
											<option value="12:45">12:45</option>
						                    <option value="13:00">13:00</option>						                
											<option value="13:15">13:15</option>
											<option value="13:30">13:30</option>
											<option value="13:45">13:45</option>
						                    <option value="14:00">14:00</option>						                
											<option value="14:15">14:15</option>
											<option value="14:30">14:30</option>
											<option value="14:45">14:45</option>
						                    <option value="15:00">15:00</option>						                
											<option value="15:15">15:15</option>
											<option value="15:30">15:30</option>
											<option value="15:45">15:45</option>
						                    <option value="16:00">16:00</option>						                
											<option value="16:15">16:15</option>
											<option value="16:30">16:30</option>
											<option value="16:45">16:45</option>
						                    <option selected="" value="17:00">17:00</option>						                
											<option value="17:15">17:15</option>
											<option value="17:30">17:30</option>
											<option value="17:45">17:45</option>
						                    <option value="18:00">18:00</option>						                
											<option value="18:15">18:15</option>
											<option value="18:30">18:30</option>
											<option value="18:45">18:45</option>
						                    <option value="19:00">19:00</option>						                
											<option value="19:15">19:15</option>
											<option value="19:30">19:30</option>
											<option value="19:45">19:45</option>
						                    <option value="20:00">20:00</option>						                
											<option value="20:15">20:15</option>
											<option value="20:30">20:30</option>
											<option value="20:45">20:45</option>
						                    <option value="21:00">21:00</option>						                
											<option value="21:15">21:15</option>
											<option value="21:30">21:30</option>
											<option value="21:45">21:45</option>
						                    <option value="22:00">22:00</option>						                
											<option value="22:15">22:15</option>
											<option value="22:30">22:30</option>
											<option value="22:45">22:45</option>
						                    <option value="23:00">23:00</option>
											<option value="23:15">23:15</option>
											<option value="23:30">23:30</option>
											<option value="23:45">23:45</option>
											<option value="23:45">23:59</option>
						             </select>
			</td> 
			<?php } ?>
		</tr>
		
		
		<tr>
			<td>Staff Replenishment</td>
			<td>:</td>
			<td><input type='text' name="staff"  id="staff" size="30" value="<?=isset($row) ? htmlspecialchars($row->team_staff, ENT_QUOTES) : "";?>"></td>
			<td>NPP</td>
			<td>:</td>
			<td><input type='text' name="staff_npp"  id="staff_npp" size="30" value="<?=isset($row) ? htmlspecialchars($row->team_staff_npp, ENT_QUOTES) : "";?>"></td>
		</tr>
		<tr>
			<td>Driver</td>
			<td>:</td>
			<td><input type='text' name="driver"  id="driver" size="30" value="<?=isset($row) ? htmlspecialchars($row->team_driver, ENT_QUOTES) : "";?>"></td>
			<td>NPP</td>
			<td>:</td>
			<td><input type='text' name="driver_npp"  id="driver_npp" size="30" value="<?=isset($row) ? htmlspecialchars($row->team_driver_npp, ENT_QUOTES) : "";?>"></td>
		</tr>
		<tr>
			<td>Pengaman 1</td>
			<td>:</td>
			<td><input type='text' name="pengaman1"  id="pengaman1" size="30" value="<?=isset($row) ? htmlspecialchars($row->team_pengaman1, ENT_QUOTES) : "";?>"></td>
			<td>NRP</td>
			<td>:</td>
			<td><input type='text' name="pengaman1_nrp"  id="pengaman1_nrp" size="30" value="<?=isset($row) ? htmlspecialchars($row->team_pengaman1_nrp, ENT_QUOTES) : "";?>"></td>
		</tr>
		<tr>
			<td>Pengaman 2</td>
			<td>:</td>
			<td><input type='text' name="pengaman2"  id="pengaman2" size="30" value="<?=isset($row) ? htmlspecialchars($row->team_pengaman2, ENT_QUOTES) : "";?>"></td>
			<td>NRP</td>
			<td>:</td>
			<td><input type='text' name="pengaman2_nrp"  id="pengaman2_nrp" size="30" value="<?=isset($row) ? htmlspecialchars($row->team_pengaman2_nrp, ENT_QUOTES) : "";?>"></td>
		</tr>
        <tr>
          
		  <td>Notes</td>
          <td>:</td>
		  <td colspan="2">
          	<input type='text' name="note"  id="note" size="35" value="<?=isset($row) ? htmlspecialchars($row->team_note, ENT_QUOTES) : "";?>">
		  </td>
		
		  <td>&nbsp;</td>
		  <td>
				<input type="submit" name="btnsave" id="btnsave" value=" Save " />
				<input type="button" name="btncancel" id="btncancel" value=" Cancel " onclick="location='<?=base_url()?>ssi_team';" />
				<img id="loader" src="<?=base_url();?>assets/images/ajax-loader.gif" border="0" alt="" title="" style="display:none;">
			</td>
        </tr>			
				</table>
			</form>		
		</div>
	</div>
</div>
			

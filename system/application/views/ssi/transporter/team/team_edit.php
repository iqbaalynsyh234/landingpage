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
			jQuery("#startdate2").datepicker(
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
			jQuery("#enddate2").datepicker(
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
	
function frmedit_onsubmit()
{
	jQuery("#loaderupdate").show();
	jQuery.post("<?=base_url()?>ssi_team/update", jQuery("#frmedit").serialize(),
	function(r)
	{
		jQuery("#loaderupdate").hide();
		alert(r.message);
								
								if (r.error)
								{								
									return;									
								}								
								page();
								jQuery("#dialog").dialog("close");
							}
							, "json"
						);
						
						return false;
	
}


</script>
<div id="wrapper">
    <form id="frmedit"onsubmit="javascript: return frmedit_onsubmit()">
	<div id="main"><br />
		<div class="block-border">
			<h3>EDIT TEAM</h3>
		</div><br />
		<table width="100%" cellpadding="3" class="table sortable no-margin" style="margin: 3px;">
			<input type="hidden" name="id" id="id" value="<?=isset($row) ? htmlspecialchars($row->team_id, ENT_QUOTES) : "";?>" /></td>
			<input type="hidden" id="mobil_device" name="mobil_device" value="" />
			<input type="hidden" id="mobil_name" name="mobil_name" value="" />
			<input type="hidden" id="mobil_no" name="mobil_no" value="" />
			<input type="hidden" id="company" name="company" value="<?php echo $this->sess->user_company;?>" />
			<input type="hidden" id="group" name="group" value="<?php echo $this->sess->user_group;?>" />
			<input type="hidden" id="creator" name="creator" value="<?php echo $this->sess->user_id;?>" />
			
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
			<td>Schedule</td>
			<td>:</td>
			<?php if ($this->sess->user_group == "1224") { ?> <!-- khusus group ssi.mandiri -->
			<td>
				<input type='text' readonly name="startdate2"  id="startdate2" value="<?=isset($row) ? htmlspecialchars($row->team_date, ENT_QUOTES) : "";?>"  maxlength='10' style="width:150px;">
				<select id="shift" name="shift">
					<option value="">--Select Shift--</option>				
					<option value="1" <? if (( isset($row)) && $row->team_shift == '1') { ?>selected<?php } ?>>Shift-1</option>
					<option value="2" <? if (( isset($row)) && $row->team_shift == '2') { ?>selected<?php } ?>>Shift-2</option>
					<option value="3" <? if (( isset($row)) && $row->team_shift == '3') { ?>selected<?php } ?>>Shift-3</option>
				</select>
			</td>
			<?php } else { ?>
			<td>
				<input type='text' readonly name="startdate2"  id="startdate2" value="<?=isset($row) ? htmlspecialchars($row->team_date, ENT_QUOTES) : "";?>"  maxlength='10' style="width:150px;">
				<select class="textgray" style="font-size: 11px; width: 65px;" id="starttime2" name="starttime2" >						                
												<option value="00:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '00:00') { ?>selected<?php } ?>>00:00</option>
												<option value="00:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '00:15') { ?>selected<?php } ?>>00:15</option>
												<option value="00:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '00:30') { ?>selected<?php } ?>>00:30</option>
												<option value="00:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '00:45') { ?>selected<?php } ?>>00:45</option>
												<option value="01:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '01:00') { ?>selected<?php } ?>>01:00</option>						                
												<option value="01:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '01:15') { ?>selected<?php } ?>>01:15</option>						              
												<option value="01:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '01:30') { ?>selected<?php } ?>>01:30</option>						                
												<option value="01:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '01:45') { ?>selected<?php } ?>>01:45</option>
												<option value="02:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '02:00') { ?>selected<?php } ?>>02:00</option>						                
												<option value="02:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '02:15') { ?>selected<?php } ?>>02:15</option>
												<option value="02:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '02:30') { ?>selected<?php } ?>>02:30</option>
												<option value="02:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '02:45') { ?>selected<?php } ?>>02:45</option>
												<option value="03:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '03:00') { ?>selected<?php } ?>>03:00</option>						                
												<option value="03:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '03:15') { ?>selected<?php } ?>>03:15</option>
												<option value="03:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '03:30') { ?>selected<?php } ?>>03:30</option>
												<option value="03:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '03:45') { ?>selected<?php } ?>>03:45</option>
												<option value="04:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '04:00') { ?>selected<?php } ?>>04:00</option>						                
												<option value="04:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '04:15') { ?>selected<?php } ?>>04:15</option>
												<option value="04:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '04:30') { ?>selected<?php } ?>>04:30</option>
												<option value="04:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '04:45') { ?>selected<?php } ?>>04:45</option>
												<option value="05:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '05:00') { ?>selected<?php } ?>>05:00</option>						                
												<option value="05:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '05:15') { ?>selected<?php } ?>>05:15</option>
												<option value="05:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '05:30') { ?>selected<?php } ?>>05:30</option>
												<option value="05:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '05:45') { ?>selected<?php } ?>>05:45</option>
												<option value="06:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '06:00') { ?>selected<?php } ?>>06:00</option>						                
												<option value="06:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '06:15') { ?>selected<?php } ?>>06:15</option>
												<option value="06:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '06:30') { ?>selected<?php } ?>>06:30</option>
												<option value="06:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '06:45') { ?>selected<?php } ?>>06:45</option>
												<option value="07:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '07:00') { ?>selected<?php } ?>>07:00</option>						                
												<option value="07:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '07:15') { ?>selected<?php } ?>>07:15</option>
												<option value="07:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '07:30') { ?>selected<?php } ?>>07:30</option>
												<option value="07:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '07:45') { ?>selected<?php } ?>>07:45</option>
												<option value="08:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '08:00') { ?>selected<?php } ?>>08:00</option>						                
												<option value="08:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '08:15') { ?>selected<?php } ?>>08:15</option>
												<option value="08:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '08:30') { ?>selected<?php } ?>>08:30</option>
												<option value="08:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '08:45') { ?>selected<?php } ?>>08:45</option>
												<option value="09:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '09:00') { ?>selected<?php } ?>>09:00</option>						                
												<option value="09:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '09:15') { ?>selected<?php } ?>>09:15</option>
												<option value="09:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '09:30') { ?>selected<?php } ?>>09:30</option>
												<option value="09:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '09:45') { ?>selected<?php } ?>>09:45</option>
												<option value="10:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '10:00') { ?>selected<?php } ?>>10:00</option>						                
												<option value="10:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '10:15') { ?>selected<?php } ?>>10:15</option>
												<option value="10:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '10:30') { ?>selected<?php } ?>>10:30</option>
												<option value="10:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '10:45') { ?>selected<?php } ?>>10:45</option>
												<option value="11:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '11:00') { ?>selected<?php } ?>>11:00</option>						                
												<option value="11:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '11:15') { ?>selected<?php } ?>>11:15</option>
												<option value="11:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '11:30') { ?>selected<?php } ?>>11:30</option>
												<option value="11:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '11:45') { ?>selected<?php } ?>>11:45</option>
												<option value="12:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '12:00') { ?>selected<?php } ?>>12:00</option>						                
												<option value="12:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '12:15') { ?>selected<?php } ?>>12:15</option>
												<option value="12:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '12:30') { ?>selected<?php } ?>>12:30</option>
												<option value="12:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '12:45') { ?>selected<?php } ?>>12:45</option>
												<option value="13:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '13:00') { ?>selected<?php } ?>>13:00</option>						                
												<option value="13:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '13:15') { ?>selected<?php } ?>>13:15</option>
												<option value="13:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '13:30') { ?>selected<?php } ?>>13:30</option>
												<option value="13:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '13:45') { ?>selected<?php } ?>>13:45</option>
												<option value="14:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '14:00') { ?>selected<?php } ?>>14:00</option>						                
												<option value="14:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '14:15') { ?>selected<?php } ?>>14:15</option>
												<option value="14:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '14:30') { ?>selected<?php } ?>>14:30</option>
												<option value="14:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '14:45') { ?>selected<?php } ?>>14:45</option>
												<option value="15:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '15:00') { ?>selected<?php } ?>>15:00</option>						                
												<option value="15:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '15:15') { ?>selected<?php } ?>>15:15</option>
												<option value="15:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '15:30') { ?>selected<?php } ?>>15:30</option>
												<option value="15:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '15:45') { ?>selected<?php } ?>>15:45</option>
												<option value="16:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '16:00') { ?>selected<?php } ?>>16:00</option>						                
												<option value="16:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '16:15') { ?>selected<?php } ?>>16:15</option>
												<option value="16:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '16:30') { ?>selected<?php } ?>>16:30</option>
												<option value="16:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '16:45') { ?>selected<?php } ?>>16:45</option>
												<option value="17:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '17:00') { ?>selected<?php } ?>>17:00</option>						                
												<option value="17:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '17:15') { ?>selected<?php } ?>>17:15</option>
												<option value="17:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '17:30') { ?>selected<?php } ?>>17:30</option>
												<option value="17:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '17:45') { ?>selected<?php } ?>>17:45</option>
												<option value="18:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '18:00') { ?>selected<?php } ?>>18:00</option>						                
												<option value="18:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '18:15') { ?>selected<?php } ?>>18:15</option>
												<option value="18:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '18:30') { ?>selected<?php } ?>>18:30</option>
												<option value="18:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '18:45') { ?>selected<?php } ?>>18:45</option>
												<option value="19:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '19:00') { ?>selected<?php } ?>>19:00</option>						                
												<option value="19:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '19:15') { ?>selected<?php } ?>>19:15</option>
												<option value="19:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '19:30') { ?>selected<?php } ?>>19:30</option>
												<option value="19:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '19:45') { ?>selected<?php } ?>>19:45</option>
												<option value="20:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '20:00') { ?>selected<?php } ?>>20:00</option>						                
												<option value="20:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '20:15') { ?>selected<?php } ?>>20:15</option>
												<option value="20:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '20:30') { ?>selected<?php } ?>>20:30</option>
												<option value="20:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '20:45') { ?>selected<?php } ?>>20:45</option>
												<option value="21:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '21:00') { ?>selected<?php } ?>>21:00</option>						                
												<option value="21:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '21:15') { ?>selected<?php } ?>>21:15</option>
												<option value="21:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '21:30') { ?>selected<?php } ?>>21:30</option>
												<option value="21:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '21:45') { ?>selected<?php } ?>>21:45</option>
												<option value="22:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '22:00') { ?>selected<?php } ?>>22:00</option>						                
												<option value="22:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '22:15') { ?>selected<?php } ?>>22:15</option>
												<option value="22:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '22:30') { ?>selected<?php } ?>>22:30</option>
												<option value="22:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '22:45') { ?>selected<?php } ?>>22:45</option>
												<option value="23:00" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '23:00') { ?>selected<?php } ?>>23:00</option>
												<option value="23:15" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '23:15') { ?>selected<?php } ?>>23:15</option>
												<option value="23:30" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '23:30') { ?>selected<?php } ?>>23:30</option>
												<option value="23:45" <? if (( isset($row)) &&  substr($row->team_time, 0, 5) == '23:45') { ?>selected<?php } ?>>23:45</option>
												
										 </select>to <br /> 
				<input type='text' readonly name="enddate2"  id="enddate2" value="<?=isset($row) ? htmlspecialchars($row->team_enddate, ENT_QUOTES) : "";?>"  maxlength='10' style="width:150px;">
				<select class="textgray" style="font-size: 11px; width: 65px;" id="endtime2" name="endtime2" >						                
						                    <option value="00:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '00:00') { ?>selected<?php } ?>>00:00</option>
											<option value="00:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '00:15') { ?>selected<?php } ?>>00:15</option>
											<option value="00:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '00:30') { ?>selected<?php } ?>>00:30</option>
											<option value="00:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '00:45') { ?>selected<?php } ?>>00:45</option>
						                    <option value="01:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '01:00') { ?>selected<?php } ?>>01:00</option>						                
											<option value="01:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '01:15') { ?>selected<?php } ?>>01:15</option>						              
											<option value="01:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '01:30') { ?>selected<?php } ?>>01:30</option>						                
											<option value="01:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '01:45') { ?>selected<?php } ?>>01:45</option>
						                    <option value="02:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '02:00') { ?>selected<?php } ?>>02:00</option>						                
											<option value="02:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '02:15') { ?>selected<?php } ?>>02:15</option>
											<option value="02:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '02:30') { ?>selected<?php } ?>>02:30</option>
											<option value="02:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '02:45') { ?>selected<?php } ?>>02:45</option>
						                    <option value="03:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '03:00') { ?>selected<?php } ?>>03:00</option>						                
											<option value="03:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '03:15') { ?>selected<?php } ?>>03:15</option>
											<option value="03:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '03:30') { ?>selected<?php } ?>>03:30</option>
											<option value="03:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '03:45') { ?>selected<?php } ?>>03:45</option>
						                    <option value="04:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '04:00') { ?>selected<?php } ?>>04:00</option>						                
											<option value="04:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '04:15') { ?>selected<?php } ?>>04:15</option>
											<option value="04:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '04:30') { ?>selected<?php } ?>>04:30</option>
											<option value="04:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '04:45') { ?>selected<?php } ?>>04:45</option>
						                    <option value="05:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '05:00') { ?>selected<?php } ?>>05:00</option>						                
											<option value="05:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '05:15') { ?>selected<?php } ?>>05:15</option>
											<option value="05:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '05:30') { ?>selected<?php } ?>>05:30</option>
											<option value="05:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '05:45') { ?>selected<?php } ?>>05:45</option>
						                    <option value="06:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '06:00') { ?>selected<?php } ?>>06:00</option>						                
											<option value="06:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '06:15') { ?>selected<?php } ?>>06:15</option>
											<option value="06:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '06:30') { ?>selected<?php } ?>>06:30</option>
											<option value="06:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '06:45') { ?>selected<?php } ?>>06:45</option>
						                    <option value="07:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '07:00') { ?>selected<?php } ?>>07:00</option>						                
											<option value="07:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '07:15') { ?>selected<?php } ?>>07:15</option>
											<option value="07:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '07:30') { ?>selected<?php } ?>>07:30</option>
											<option value="07:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '07:45') { ?>selected<?php } ?>>07:45</option>
						                    <option value="08:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '08:00') { ?>selected<?php } ?>>08:00</option>						                
											<option value="08:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '08:15') { ?>selected<?php } ?>>08:15</option>
											<option value="08:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '08:30') { ?>selected<?php } ?>>08:30</option>
											<option value="08:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '08:45') { ?>selected<?php } ?>>08:45</option>
						                    <option value="09:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '09:00') { ?>selected<?php } ?>>09:00</option>						                
											<option value="09:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '09:15') { ?>selected<?php } ?>>09:15</option>
											<option value="09:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '09:30') { ?>selected<?php } ?>>09:30</option>
											<option value="09:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '09:45') { ?>selected<?php } ?>>09:45</option>
						                    <option value="10:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '10:00') { ?>selected<?php } ?>>10:00</option>						                
											<option value="10:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '10:15') { ?>selected<?php } ?>>10:15</option>
											<option value="10:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '10:30') { ?>selected<?php } ?>>10:30</option>
											<option value="10:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '10:45') { ?>selected<?php } ?>>10:45</option>
						                    <option value="11:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '11:00') { ?>selected<?php } ?>>11:00</option>						                
											<option value="11:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '11:15') { ?>selected<?php } ?>>11:15</option>
											<option value="11:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '11:30') { ?>selected<?php } ?>>11:30</option>
											<option value="11:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '11:45') { ?>selected<?php } ?>>11:45</option>
						                    <option value="12:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '12:00') { ?>selected<?php } ?>>12:00</option>						                
											<option value="12:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '12:15') { ?>selected<?php } ?>>12:15</option>
											<option value="12:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '12:30') { ?>selected<?php } ?>>12:30</option>
											<option value="12:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '12:45') { ?>selected<?php } ?>>12:45</option>
						                    <option value="13:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '13:00') { ?>selected<?php } ?>>13:00</option>						                
											<option value="13:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '13:15') { ?>selected<?php } ?>>13:15</option>
											<option value="13:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '13:30') { ?>selected<?php } ?>>13:30</option>
											<option value="13:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '13:45') { ?>selected<?php } ?>>13:45</option>
						                    <option value="14:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '14:00') { ?>selected<?php } ?>>14:00</option>						                
											<option value="14:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '14:15') { ?>selected<?php } ?>>14:15</option>
											<option value="14:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '14:30') { ?>selected<?php } ?>>14:30</option>
											<option value="14:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '14:45') { ?>selected<?php } ?>>14:45</option>
						                    <option value="15:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '15:00') { ?>selected<?php } ?>>15:00</option>						                
											<option value="15:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '15:15') { ?>selected<?php } ?>>15:15</option>
											<option value="15:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '15:30') { ?>selected<?php } ?>>15:30</option>
											<option value="15:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '15:45') { ?>selected<?php } ?>>15:45</option>
						                    <option value="16:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '16:00') { ?>selected<?php } ?>>16:00</option>						                
											<option value="16:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '16:15') { ?>selected<?php } ?>>16:15</option>
											<option value="16:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '16:30') { ?>selected<?php } ?>>16:30</option>
											<option value="16:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '16:45') { ?>selected<?php } ?>>16:45</option>
						                    <option value="17:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '17:00') { ?>selected<?php } ?>>17:00</option>						                
											<option value="17:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '17:15') { ?>selected<?php } ?>>17:15</option>
											<option value="17:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '17:30') { ?>selected<?php } ?>>17:30</option>
											<option value="17:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '17:45') { ?>selected<?php } ?>>17:45</option>
						                    <option value="18:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '18:00') { ?>selected<?php } ?>>18:00</option>						                
											<option value="18:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '18:15') { ?>selected<?php } ?>>18:15</option>
											<option value="18:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '18:30') { ?>selected<?php } ?>>18:30</option>
											<option value="18:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '18:45') { ?>selected<?php } ?>>18:45</option>
						                    <option value="19:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '19:00') { ?>selected<?php } ?>>19:00</option>						                
											<option value="19:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '19:15') { ?>selected<?php } ?>>19:15</option>
											<option value="19:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '19:30') { ?>selected<?php } ?>>19:30</option>
											<option value="19:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '19:45') { ?>selected<?php } ?>>19:45</option>
						                    <option value="20:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '20:00') { ?>selected<?php } ?>>20:00</option>						                
											<option value="20:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '20:15') { ?>selected<?php } ?>>20:15</option>
											<option value="20:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '20:30') { ?>selected<?php } ?>>20:30</option>
											<option value="20:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '20:45') { ?>selected<?php } ?>>20:45</option>
						                    <option value="21:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '21:00') { ?>selected<?php } ?>>21:00</option>						                
											<option value="21:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '21:15') { ?>selected<?php } ?>>21:15</option>
											<option value="21:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '21:30') { ?>selected<?php } ?>>21:30</option>
											<option value="21:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '21:45') { ?>selected<?php } ?>>21:45</option>
						                    <option value="22:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '22:00') { ?>selected<?php } ?>>22:00</option>						                
											<option value="22:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '22:15') { ?>selected<?php } ?>>22:15</option>
											<option value="22:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '22:30') { ?>selected<?php } ?>>22:30</option>
											<option value="22:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '22:45') { ?>selected<?php } ?>>22:45</option>
						                    <option value="23:00" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '23:00') { ?>selected<?php } ?>>23:00</option>
											<option value="23:15" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '23:15') { ?>selected<?php } ?>>23:15</option>
											<option value="23:30" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '23:30') { ?>selected<?php } ?>>23:30</option>
											<option value="23:45" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '23:45') { ?>selected<?php } ?>>23:45</option>
											<option value="23:59" <? if (( isset($row)) &&  substr($row->team_endtime, 0, 5) == '23:59') { ?>selected<?php } ?>>23:59</option>
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
				<input type="button" class="btn btn-primary" value="Cancel" onclick="javascript:jQuery('#dialog').dialog('close');" />
					<img id="loaderupdate" src="<?=base_url();?>assets/images/ajax-loader.gif" border="0" alt="" title="" style="display:none;">
			</td>
        </tr>	
    				
		</table>
		</div>
		</form>

</div>
			

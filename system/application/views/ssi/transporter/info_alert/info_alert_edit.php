<script>
function frmedit_onsubmit()
{
	jQuery("#loaderupdate").show();
	jQuery.post("<?=base_url()?>ssi_info_alert/update_info_alert", jQuery("#frmedit").serialize(),
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
			<h3>EDIT DATA INFO ALERT</h3>
		</div><br />
		<table width="100%" cellpadding="3" class="table sortable no-margin" style="margin: 3px;">
			<input type="hidden" name="id" id="id" value="<?=isset($row) ? htmlspecialchars($row->info_alert_id, ENT_QUOTES) : "";?>" /></td>
			
			<tr>
				<td>Name</td>
				<td>:</td>
				<td><input type="text" size="35" name="info_alert_name" id="info_alert_name" value="<?=isset($row) ? htmlspecialchars($row->info_alert_name, ENT_QUOTES) : "";?>" class="formdefault" /></span></td>
						
				<td>Area</td>
				<td>:</td>
				<td><select id="info_alert_group" name="info_alert_group">
					<option value="" selected='selected'>--Select Area--</option>
					<?php 
						$cg = count($rgroup);
						for($i=0;$i<$cg;$i++){
							if (isset($row)&&($row->info_alert_group == $rgroup[$i]->group_id)){
								$selected = "selected"; 
								}else{
									$selected = "";
									}
									echo "<option value='" . $rgroup[$i]->group_id ."' " . $selected . ">" . $rgroup[$i]->group_name . "</option>";
							}
					?>
				</td>
			</tr>
			<tr>
				<td>Mobile Phone</td>
				<td>:</td>
				<td><input type="text" size="20" maxlength = "13" name="info_alert_mobile" id="info_alert_mobile" value="<?=isset($row) ? htmlspecialchars($row->info_alert_mobile, ENT_QUOTES) : "";?>" class="formdefault" /></td>
				<td>Alert SMS</td>
				<td>:</td>
				<td>	
					<input name="info_alert_config_mobile" type="radio" value="1" <? if (( isset($row)) && ($row->info_alert_config_mobile == 1)) { ?>checked<?php } ?>> Yes</option>
					<input name="info_alert_config_mobile" type="radio" value="0" <? if (( isset($row)) && ($row->info_alert_config_mobile == 0)) { ?>checked<?php } ?>> No </option>
				</td>
			</tr>
			
			<tr>
		 
			  <td>Status Active</td>
				<td>:</td>
				<td><input type="checkbox" name="info_alert_status" id="info_alert_status" value="1" <?php if (! isset($row)) { echo "checked"; } else if ($row->info_alert_status == 1) { echo "checked"; } ?> /></td>

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
			

<script>
function frmedit_onsubmit()
{
	jQuery("#loaderupdate").show();
	jQuery.post("<?=base_url()?>ssi_info_alert/save_comment", jQuery("#frmedit").serialize(),
	function(r)
	{
		jQuery("#loaderupdate").hide();
		alert(r.message);
								
								if (r.error)
								{								
									return;									
								}								
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
			<h3>Vehicle Comment</h3>
		</div><br />
	
		<table width="100%" cellpadding="3" class="table sortable no-margin" style="margin: 3px;">
		<input type="hidden" name="vid" id="vid" value="<?php echo $rowv->vehicle_id; ?>" />
		<input type="hidden" name="vname" id="vname" value="<?php echo $rowv->vehicle_name; ?>" />
		<input type="hidden" name="vno" id="vno" value="<?php echo $rowv->vehicle_no; ?>" />
		<input type="hidden" name="vdevice" id="vdevice" value="<?php echo $rowv->vehicle_device; ?>" />
		
			<tr>
				<td>Comment</td>
				<td>:</td>
				<td><input type="text" size="45" name="title" id="title" value="" maxlength="160" class="formdefault" />
					<input type="submit" name="btnsave" id="btnsave" value=" Save " />
					<input type="button" class="btn btn-primary" value="Cancel" onclick="javascript:jQuery('#dialog').dialog('close');" />
					<img id="loaderupdate" src="<?=base_url();?>assets/images/ajax-loader.gif" border="0" alt="" title="" style="display:none;"></td>
			</tr>
	
		</table>
		</div>
	</form>
</div>
			

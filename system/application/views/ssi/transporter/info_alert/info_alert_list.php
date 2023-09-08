<script>
	
	jQuery(document).ready(
		function()
		{
			showclock();
			
			jQuery("#sortby").val('<?=$sortby?>');
			jQuery("#orderby").val('<?=$orderby?>')
			
			field_onchange();
			page(0);			
		}
	);
	
	function page(p)
	{
		if(p==undefined){
			p=0;
		}
		jQuery("#offset").val(p);
		jQuery("#loader").show();
		
		jQuery.post("<?=base_url();?>ssi_info_alert/search_info_alert/"+p, jQuery("#frmsearch").serialize(),
			function(r)
			{
				jQuery("#loader").hide();
				jQuery("#result").html(r.html);		
				jQuery("#total").html(r.total);				
			}
			, "json"
		);
	}
	
	function field_onchange()
	{
		var v = jQuery("#field").val();

		jQuery("#keyword").hide();
		jQuery("#status").hide();
		jQuery("#group").hide();
		switch(v)
		{
			case "info_alert_group" :
				jQuery("#group").show();
				break;
			default:
				jQuery("#keyword").show();			
		}
	}
	
	function frmsearch_onsubmit()
	{
		page(0);
		return false;
	}

	function order(by)
	{						
		if (by == jQuery("#sortby").val())
		{
			if (jQuery("#orderby").val() == "asc")
			{
				jQuery("#orderby").val("desc");
			}
			else
			{
				jQuery("#orderby").val("asc");
			}
		}
		else
		{
			jQuery("#orderby").val('asc')
		}
		
		jQuery("#sortby").val(by);
		page(0);
	}
	function edit(id)
		{
			showdialog();
			jQuery.post('<?=base_url()?>ssi_info_alert/edit_info_alert/' + id, {},
				function(r)
				{
					if (r.error)
					{
						alert(r.message);
						return;
					}
					
					showdialog(r.html, 'FORM EDIT');
				}
				, "json"
			);			
		}
	function delete_data(id)
		{
			if (confirm("Are you sure delete this data?")) {
				jQuery.post('<?=base_url()?>ssi_info_alert/delete_info_alert/' + id, {}, function(r){
					if (r.error) {
						alert(r.message);
						return;
					}else{
						alert(r.message);
						page();
						return;
					}
				}, "json");
			}		
		}
	
</script>
<div style="position: absolute; margin: 0; padding: 0; z-index: 1000; width: 100%;"> 
<?=$navigation;?>
	<div id="main" style="margin: 20px;">
		<div class="block-border">
		<form class="block-content form" name="frmsearch" id="frmsearch" onsubmit="javascript:return frmsearch_onsubmit()">
		<h1>Info Alert List (<span id="total"></span>)</h1>
		<fieldset class="grey-bg required">
		<legend><?=$this->lang->line("lsearchby");?></legend>
			<input type="hidden" name="offset" id="offset" value="" />
			<input type="hidden" id="sortby" name="sortby" value="" />
			<input type="hidden" id="orderby" name="orderby" value="" />			
			<table width="100%" cellpadding="3" class="tablelist">
				<tr>
					
					<td>
						<select id="field" name="field" onchange="javascript:field_onchange()">
							
							<option value="info_alert_name">Name</option>
							<option value="info_alert_mobile">Mobile Phone</option>
							<!--<option value="info_alert_group">Area</option>-->
							<!--<option value="info_alert_email">Email</option>-->
						</select>
						<select id="group" name="group" style="display: none;">
								<option value="" selected='selected'>--Select Area--</option>
								<?php 
									$cg = count($group);

									for($i=0;$i<$cg;$i++){										
										echo "<option value='" . $group[$i]->group_id ."'>" . $group[$i]->group_name . "</option>";
									}
								?>
							</select>
						
						<input type="text" name="keyword" id="keyword" value="" class="formdefault" />
						<input type="text" name="cp" id="cp" value="" class="formdefault" style="display: none;" />
						<input type="submit" value="<?=$this->lang->line("lsearch");?>" />
						<img id="loader" src="<?=base_url();?>assets/images/ajax-loader.gif" border="0" alt="" title="" style="display:none;">
					</td>
				</tr>
			</table>
		</fieldset>
		</form>
		<br />
		<?php if ($this->sess->user_group == 0)  { ?>	
		[ <a href="<?=base_url();?>ssi_info_alert/add_info_alert"><font color="#0000ff"><?=$this->lang->line("ladd"); ?></font></a> ]
		<?php } ?>	
		</div>
		<div id="result"></div>		
	</div>
</div>

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
			jQuery("#alldate").attr("checked", true);
			jQuery("#displayperiode").hide();
			jQuery("#startdate").datepicker(
				{
							dateFormat: 'yy/mm/dd'
						, 	startDate: '1900/01/01'
						, 	showOn: 'button'
						//, changeYear: true
						//,	changeMonth: true
						, 	buttonImage: '<?php echo base_url(); ?>assets/images/calendar.gif'
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
							dateFormat: 'yy/mm/dd'
						, 	startDate: '1900/01/01'
						, 	showOn: 'button'
						//, changeYear: true
						//,	changeMonth: true
						, 	buttonImage: '<?php echo base_url(); ?>/assets/images/calendar.gif'
						, 	buttonImageOnly: true
						,	beforeShow: 
								function() 
								{	
									jQuery('#ui-datepicker-div').maxZIndex();
								}
				}
			);	
			jQuery("#periodedate").click(function(){
				jQuery("#startdate").attr("value", "");
				jQuery("#enddate").attr("value", "");
				jQuery("#displayperiode").show();
			});	
			jQuery("#alldate").click(function(){
				jQuery("#displayperiode").hide();
			});
			
			jQuery("#sortby").val('team_date');
			jQuery("#orderby").val('desc')
			
			//field_onchange();
			page(0);			
		}
	);
	
	function page(p)
	{
		if(p==undefined){
			p=0;
		}
		jQuery("#offset").val(p);
		jQuery("#loader2").show();
		jQuery.post("<?=base_url();?>ssi_team/search/"+p, jQuery("#frmsearch").serialize(),
			function(r)
			{
				jQuery("#loader2").hide();
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
		jQuery("#team_date").datepicker( "destroy" );
		
		switch(v)
		{
			case "team_status":
				jQuery("#status").show();
				break;
			case "team_date":
				jQuery("#startdate").datepicker(
					{
								dateFormat: 'dd-mm-yy'
							, 	startDate: '01-01-1900'
							, 	showOn: 'button'
							, 	changeYear: true
							,	changeMonth: true
							, 	buttonImage: '<?=base_url()?>assets/images/calendar.gif'
							, 	buttonImageOnly: true
							,	beforeShow: 
									function() 
									{	
										jQuery('#ui-datepicker-div').maxZIndex();
									}
					}
				);
				jQuery("#startdate").show();
				break;
			case "All":
				jQuery("#keyword").hide();
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
			jQuery.post('<?=base_url()?>ssi_team/edit/' + id, {},
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
				jQuery.post('<?=base_url()?>ssi_team/delete/' + id, {}, function(r){
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
		<h1>Team List (<span id="total"></span>)</h1>
		<fieldset class="grey-bg required">
		<legend><?=$this->lang->line("lsearchby");?></legend>
			<input type="hidden" name="offset" id="offset" value="" />
			<input type="hidden" id="sortby" name="sortby" value="" />
			<input type="hidden" id="orderby" name="orderby" value="" />			
			<table width="100%" cellpadding="3" class="tablelist">
				<tr>
					
					<td>
		
						<select id="field" name="field" onchange="javascript:field_onchange()">
							<option value="All">All</option>
							<option value="team_vehicle_no">Vehicle No</option>
							<option value="team_driver">Driver</option>
							<option value="team_staff">Staff</option>
						 </select>
						<select id="transporter_job_status" name="transporter_job_status" style="display: none;">
							<option value="2">Delivered</option>
							<option value="1">On Going</option>
						</select>
						
						<input type="text" name="keyword" id="keyword" value="" class="formdefault" />
						<b>Date:</b>
							<input type="radio" name="searchdate" id="alldate" value="all" checked="checked"/> All 
							<input type="radio" name="searchdate" id="periodedate" value="periode"/>by Date
							<span id="displayperiode" style="display:none;">
							   From <input type='text' readonly name="startdate" id="startdate" class="date-pick" value=""  maxlength='10'>
								To <input type='text' readonly name="enddate" id="enddate"  class="date-pick" value=""  maxlength='10'>
							</span> 
						
						<input type="text" name="cp" id="cp" value="" class="formdefault" style="display: none;" />
						<input class="btn_search2" id="btnsearchreport" type="submit" value="Search" />
						<!--<input class="btn_export" type="button" name="excel" id="btnexcelreport" value="Export To Excel" onclick="javascript:return excel_onsubmit()" />-->
						<img id="loader2" style="display: none;" src="<?php echo base_url();?>assets/images/ajax-loader.gif" />
					
					</td>
				</tr>
			</table>
		</fieldset>
		</form>		
		<br />
		[ <a href="<?=base_url();?>ssi_team/add"><font color="#0000ff"><?php echo "Add New Team" ?></font></a> ]
		</div>
		<div id="result"></div>
		<iframe id="frmexcel" style="display:none;"></iframe>		
	</div>
</div>

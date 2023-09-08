<!-- start sidebar menu -->
<div class="sidebar-container">
  <?=$sidebar;?>
</div>

<div class="page-content-wrapper">
  <div class="page-content" id="page-content-new">
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="panel" id="panel_form">
          <header class="panel-heading panel-heading-blue">In Out Geofence Report</header>
          <div class="panel-body" id="bar-parent10">
            <form class="block-content form" name="frmsearch" id="frmsearch" onsubmit="javascript:return frmsearch_onsubmit()">
        			<input type="hidden" name="offset" id="offset" value="" />
        			<input type="hidden" id="sortby" name="sortby" value="" />
        			<input type="hidden" id="orderby" name="orderby" value="" />
        			<table width="100%" class="table" style="font-size: 12px;">
        				<tr>
        					<td>Vehicle</td>
        					<td>
                    <div class="input-group col-md-12">
          						<select id="vehicle" name="vehicle" class="form-control select2">
          							<?php for($i=0; $i < count($vehicles); $i++) { ?>
          							<option value="<?php echo $vehicles[$i]->vehicle_device; ?>"><?php if ($this->sess->user_type != 2) { echo $vehicles[$i]->user_name." - "; } ?><?php echo $vehicles[$i]->vehicle_name; ?> - <?php echo $vehicles[$i]->vehicle_no; ?></option>
          							<?php } ?>
          						</select>
                    </div>
        					</td>
        				</tr>

        				<tr>
        					<td>Date</td>
        					<td style="width: 50%;">
                    <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd-mm-yyyy" data-link-format="dd-mm-yyyy">
                        <input type='text' name="startdate" id="startdate"  class="form-control" size="5" type="text" readonly value="<?=date('d-m-Y')?>" maxlength='10' />
                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                    </div>
                  </td>
                  <td style="width: 50%;">
                    <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd-mm-yyyy" data-link-format="dd-mm-yyyy">
                        <input type='text' name="enddate" id="enddate"  class="form-control" size="5" type="text" readonly value="<?=date('d-m-Y')?>" maxlength='10' />
                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                    </div>
        					</td>
        				</tr>
        			</table>
              <div class="text-right">
                <img id="loader2" style="display: none;" src="<?php echo base_url();?>assets/images/ajax-loader.gif" />
                <input class="btn btn-primary" id="btnsearchreport" type="submit" value="Search" />
                <input class="btn btn-success" type="button" name="excel" id="btnexcelreport" value="Export To Excel" onclick="javascript:return excel_onsubmit()" />
              </div>
        		</form>
          </div>
        </div>
      </div>
    </div>

    <div id="loader2" class="mdl-progress mdl-js-progress mdl-progress__indeterminate"></div>
    <div id="result" style="width:100%"></div>
  </div>
</div>

<script>
	function page(p)
	{
		if(p==undefined){
			p=0;
		}
		jQuery("#offset").val(p);
		jQuery("#result").html('<img src="<?php echo base_url();?>assets/transporter/images/loader2.gif">');
		jQuery("#loader").show();
		jQuery.post("<?=base_url();?>inoutgeofence/inout_geofence_detail_report/", jQuery("#frmsearch").serialize(),
			function(r)
			{
				jQuery("#loader").hide();
				jQuery("#result").html(r.html);
			}
			, "json"
		);
	}

	function frmsearch_onsubmit()
	{
		jQuery("#loader").show();
		page(0);
		return false;
	}
</script>

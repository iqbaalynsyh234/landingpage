<script src="<?php echo base_url()?>assets/js/assetsbaru/jquery.min.js"></script>
<!-- <link rel="stylesheet" href="<?php echo base_url()?>assets/js/assetsbaru/jquery-ui.css"> -->
<script src="<?php echo base_url()?>assets/js/assetsbaru/jquery-ui.min.js"></script>
<script src="<?php echo base_url()?>assets/js/assetsbaru/chosen.jquery.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/js/assetsbaru/chosen.min.css">

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

	function page(p)
	{
		if(p==undefined){
			p=0;
		}
		jQuery("#offset").val(p);
		jQuery("#result").html('<img src="<?php echo base_url();?>assets/transporter/images/loader2.gif">');
		jQuery("#loader").show();
		jQuery.post("<?=base_url();?>pbi_ritase/new_ritase_report", jQuery("#frmsearch").serialize(),
			function(r)
			{
        console.log("r : ", r);
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


	function excel_onsubmit(){
		jQuery("#loader2").show();
		jQuery.post("<?=base_url();?>report/ritase_report_excel", jQuery("#frmsearch").serialize(),
			function(r)
			{
				jQuery("#loader2").hide();
				if(r.success == true){
					jQuery("#frmreq").attr("src", r.filename);
				}else{
					alert(r.errMsg);
				}
			}
			, "json"
		);

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


</script>
<style media="screen">
	.ui-datepicker{
		z-index: 9999 !important;
	}

</style>

 			<div class="sidebar-container">
 				<?=$sidebar;?>
            </div>
            <div class="page-content-wrapper">
                <div class="page-content" style="width:100%;">
                    <div class="row">
          						<div class="col-md-12 col-sm-12">
                        <div class="panel" id="panel_form">
                            <header class="panel-heading panel-heading-blue">RITASE REPORT PBI</header>
                            <div class="panel-body" id="bar-parent10">
                              <form class="block-content form" name="frmsearch" id="frmsearch" onsubmit="javascript:return frmsearch_onsubmit()">
                          				 <table class="table" style="font-size: 12px;">
                          				 	<tr>
                          						<td>Vehicle</td>
                          						<td>
                          							<select id="vehicle" name="vehicle" class="select2" style="width: 80%;">
                          								<?php for($i=0; $i < count($vehicle); $i++) { ?>
                          								<option value="<?php echo $vehicle[$i]->vehicle_device; ?>">
            	                            <?php echo $vehicle[$i]->vehicle_no . " - " . $vehicle[$i]->vehicle_name; ?>
            	                            </option>
                          								<?php } ?>
                          							</select>
                          						</td>
                                    </tr>

                                    <tr>
                                      <td>Ritase</td>
                                      <td>
                                        <b>in</b>
                          							<select name="ritase" id="ritase"  class="select2" style="width: 100%;">
                          								<?php
                          									if (isset($geofence_name) && count($geofence_name)>0){
              										            for ($i=0;$i<count($geofence_name);$i++)
                        										{
          								                ?>
                      											<!-- <option value="<?php echo $ritase[$i]->ritase_id.",".$ritase[$i]->ritase_geofence_name;?>"><?php echo $ritase[$i]->ritase_geofence_name;?></option> -->
                      											<option value="<?php echo $geofence_name[$i]->geofence_name;?>"><?php echo $geofence_name[$i]->geofence_name;?></option>
                          								<?php
                          										}
                          									}
                          								?>
                          							</select>
                                      </td>

                                      <td>
                                        <b>Out</b>
                                        <select name="ritaseout" id="ritaseout" class="select2" style="width: 100%;">
                                          <?php
                                            if (isset($geofence_name) && count($geofence_name)>0)
                                            {
                                              for ($i=0;$i<count($geofence_name);$i++)
                                              {
                                          ?>
                                                <!-- <option value="<?php echo $ritase[$i]->ritase_id.",".$ritase[$i]->ritase_geofence_name;?>"><?php echo $ritase[$i]->ritase_geofence_name;?></option> -->
                                                <option value="<?php echo $geofence_name[$i]->geofence_name;?>"><?php echo $geofence_name[$i]->geofence_name;?></option>
                                          <?php
                                              }
                                            }
                                          ?>
                                        </select>
                                      </td>
                                    </tr>

                                    <tr>
                        							<td>Date</td>
                        							<td>
                                        <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd-mm-yyyy" data-link-format="dd-mm-yyyy">
                                            <input type='text' name="startdate" id="startdate"  class="form-control" size="5" type="text" readonly value="<?=date('d-m-Y')?>" maxlength='10' />
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                        </div>
                                      </td>
                                      <td>
                                        <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd-mm-yyyy" data-link-format="dd-mm-yyyy">
                                            <input type='text' name="enddate" id="enddate"  class="form-control" size="5" type="text" readonly value="<?=date('d-m-Y')?>" maxlength='10' />
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                        </div>
                        							</td>
                        						</tr>

                        						<tr>
                        							<td>Time</td>
                        							<td>
                        								<select style="font-size: 11px; width: 100px;" id="shour" name="shour">
          																		<option value="00:00">00:00</option>
          																		<option value="01:00">01:00</option>
          																		<option value="02:00">02:00</option>
          																		<option value="03:00">03:00</option>
          																		<option value="04:00">04:00</option>
          																		<option value="05:00">05:00</option>
          																		<option value="06:00">06:00</option>
          																		<option value="07:00">07:00</option>
          																		<option value="08:00">08:00</option>
          																		<option value="09:00">09:00</option>
          																		<option value="10:00">10:00</option>
          																		<option value="11:00">11:00</option>
          																		<option value="12:00">12:00</option>
          																		<option value="13:00">13:00</option>
          																		<option value="14:00">14:00</option>
          																		<option value="15:00">15:00</option>
          																		<option value="16:00">16:00</option>
          																		<option value="17:00">17:00</option>
          																		<option value="18:00">18:00</option>
          																		<option value="19:00">19:00</option>
          																		<option value="20:00">20:00</option>
          																		<option value="21:00">21:00</option>
          																		<option value="22:00">22:00</option>
          																		<option value="23:00">23:00</option>
          														 </select>
                      									<select style="font-size: 11px; width: 100px;" id="ehour" name="ehour">
        																				<option value="23:59">23:59</option>
        																				<option value="22:00">22:00</option>
        																				<option value="21:00">21:00</option>
        																				<option value="20:00">20:00</option>
        																				<option value="19:00">19:00</option>
        																				<option value="18:00">18:00</option>
        																				<option value="17:00">17:00</option>
        																				<option value="16:00">16:00</option>
        																				<option value="15:00">15:00</option>
        																				<option value="14:00">14:00</option>
        																				<option value="13:00">13:00</option>
        																				<option value="12:00">12:00</option>
        																				<option value="11:00">11:00</option>
        																				<option value="10:00">10:00</option>
        																				<option value="09:00">09:00</option>
        																				<option value="08:00">08:00</option>
        																				<option value="07:00">07:00</option>
        																				<option value="06:00">06:00</option>
        																				<option value="05:00">05:00</option>
        																				<option value="04:00">04:00</option>
        																				<option value="03:00">03:00</option>
        																				<option value="02:00">02:00</option>
        																				<option value="01:00">01:00</option>
        																				<option value="00:00">00:00</option>
        															 </select>
                      								</td>
                        						</tr>
                      					</table>
                                <div class="text-right">
                                  <input class="btn btn-primary" id="btnsearchreport" type="submit" value="Search" />
                                  <img id="loader2" style="display: none;" src="<?php echo base_url();?>assets/images/ajax-loader.gif" />
                                </div>
                          		</form>
                            </div>
          							</div>
                      </div>
                    </div>
                    <div id="loader2" class="mdl-progress mdl-js-progress mdl-progress__indeterminate"></div>
          					<div id="result"></div>
                  </div>
            </div>

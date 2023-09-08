<script src="<?php echo base_url()?>assets/js/assetsbaru/jquery.min.js"></script>
<!-- <link rel="stylesheet" href="<?php echo base_url()?>assets/js/assetsbaru/jquery-ui.css"> -->
<script src="<?php echo base_url()?>assets/js/assetsbaru/jquery-ui.min.js"></script>
<script src="<?php echo base_url()?>assets/js/assetsbaru/chosen.jquery.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/js/assetsbaru/chosen.min.css">

<script>
	jQuery(document).ready(
		function()
		{
			showclock();
			jQuery("#map").hide();
		}
	);

	$("#submiton").hide();
	$("#releaseon").hide();

  function submitOn() {
    // alert("submit on click");
    var integrationcustomer = document.getElementById('integration_customer').value;

    var checkboxes = document.getElementsByName('vehicle_checkbox_integration[]');
    var vals = "";
      for (var i=0, n=checkboxes.length;i<n;i++) {
        if (checkboxes[i].checked)
        {
            vals += ","+checkboxes[i].value;
        }
      }
      if (vals) vals = vals.substring(1);

			// var checkboxes2 = document.getElementsByName('vehicle_checkbox_integration[]');
    console.log("integrationcustomer : ", integrationcustomer);
    console.log("checkbox is checked : ", vals);
    // console.log("checkbox is unchecked : ", checkboxes2);

			jQuery.post("<?=base_url();?>integration/submitintegration", {
				integration : integrationcustomer,
				vals : vals
			}, function(r){
				console.log("response : ", r);
				if (r.code == 200) {
					if (confirm(r.msg)) {
						window.location = '<?php echo base_url() ?>integration'
					}
				}
			}, "json");
  }

	function ReleaseOn(){
		var checkboxes = document.getElementsByName('vehicle_checkbox_integration[]');
		console.log("release : ", checkboxes);
		var vals = "";
		var vals2 = "";
		for (var i = 0; i < checkboxes.length; i++) {
			console.log("checkboxes : ", checkboxes[0].defaultValue);
			if (checkboxes[i].checked)
			{
				vals += ","+checkboxes[i].value;
			}else {
				vals2 += ","+checkboxes[i].value;
			}
		}

		console.log("vals : ", vals);
		console.log("vals2 : ", vals2);

		jQuery.post("<?=base_url();?>integration/releaseintegration", {
			releasevehicle : vals2
		}, function(r){
			console.log("response : ", r);
			if (r.code == 200) {
				if (confirm(r.msg)) {
					window.location = '<?php echo base_url() ?>integration'
				}
			}
		}, "json");
	}

	function customeronchange(){
		var integration_customer = $("#integration_customer").val();
			if (integration_customer == "release") {
				$("#submiton").hide();
				$("#releaseon").show();
			}else if (integration_customer == "") {
				$("#submiton").hide();
				$("#releaseon").show();
			}else {
				$("#submiton").show();
				$("#releaseon").hide();
			}
	}
</script>

<div style="position: absolute; margin: 0; padding: 0; z-index: 1000; width: 100%;">
<?=$navigation;?>
  <div id="main" style="margin: 20px;">
    <div class="block-border">
      <form class="block-content form" name="frmsearch" id="frmsearch">
        <h1>Integration Modul</h1>
        <p>
          <i>
						<p style="font-size:10; color:red;"><b>Note :</b></p>
            <p style="font-size:10; color:red;">- Gunakan modul integrasi ini untuk mengirimkan data kendaraan yang sedang dalam pengiriman ke customer.</p>
						<table class="table">
							<tr>
								<td><p style="font-size:10; color:red;"><b>SUBMIT INTEGRASI</b></p></td>
								<td><p style="font-size:10; color:red;"><b>RELEASE INTEGRASI</b></p></td>
							</tr>
							<tr>
								<td><p style="font-size:10; color:red;">- Pilih Customer yang akan di integrasikan</p></td>
								<td><p style="font-size:10; color:red;">- Pilih Customer Set To Available</p></td>
							</tr>
							<tr>
								<td><p style="font-size:10; color:red;">- Cheklist kendaraan yang sedang dalam perjalanan</p></td>
								<td><p style="font-size:10; color:red;">- Uncheklist kendaraan yang sudah sampai atau selesai pengiriman.</p></td>
							</tr>
						</table>
          </i>
        </p>

        <table width="100%" cellpadding="3" class="tablelist" style="font-size:11px;">
          <tr>
            <td width="10%">
              <fieldset>
                <legend>
                  Customer
                </legend>
                <select class="form-control chosen" name="integration_customer" id="integration_customer" onchange="customeronchange();">
									<option value="">Pilih Customer</option>
                  <option value="release">Set To Available</option>
                  <?php for ($i=0; $i < sizeof($datacustomer); $i++) {?>
                    <option value="<?php echo $datacustomer[$i]['group_id'];?>"><?php echo $datacustomer[$i]['group_name'];?></option>
                  <?php } ?>
                </select>
                <!-- <br><br> -->

              </fieldset>
            </td>
          </tr>
        </table>

        <table class="table sortable no-margin" width="100%" cellpadding="3" class="tablelist" style="margin: 3px; font-size:11px;">
          <thead>
            <tr>
              <td>No</td>
              <td>Vehicle</td>
              <td>Status</td>
              <td>Checklist</td>
            </tr>
          </thead>
          <tbody>
            <?php for ($j=0; $j < sizeof($datavehicle); $j++) {?>
              <tr>
                <td><?php echo $j+1; ?></td>
                <td><?php echo $datavehicle[$j]['vehicle_no'].' - '.$datavehicle[$j]['vehicle_name']; ?></td>
                <td>
                  <?php
                    if ($datavehicle[$j]['vehicle_group'] == 0) {?>
                      <button type="button" class="btn btn-success" style="background-color:lightgreen;">Available</button>
                    <?php }else {?>
											<?php for ($k=0; $k < sizeof($datacustomer); $k++) {?>
												<?php if ($datacustomer[$k]['group_id'] == $datavehicle[$j]['vehicle_group']) {?>
													<button type="button" class="btn btn-red" style="background-color:red;">Already Set To Customer <?php echo $datacustomer[$k]['group_name']; ?></button>
												<?php } ?>
		                  <?php } ?>
                    <?php } ?>
                </td>
                <td>
                  <?php
                    if ($datavehicle[$j]['vehicle_group'] == 0) {?>
                      <input type="checkbox" name="vehicle_checkbox_integration[]" id="vehicle_checkbox_integration" value="<?php echo $datavehicle[$j]['vehicle_id'].'|'.$datavehicle[$j]['vehicle_group']; ?>">
                    <?php }else {?>
											<input type="checkbox" name="vehicle_checkbox_integration[]" id="vehicle_checkbox_integration" value="<?php echo $datavehicle[$j]['vehicle_id'].'|'.$datavehicle[$j]['vehicle_group']; ?>" checked>
                    <?php } ?>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>

        <div class="align-right">
          <button type="button" id="submiton" class="btn btn-primary" onclick="submitOn();" style="display: none;">Submit</button>
					<button type="button" id="releaseon" class="btn btn-primary" style="background:red; display: none;" onclick="ReleaseOn();">Release</button>
        </div>
      </form>
    </div>
  </div>

  <script type="text/javascript">
    $(".chosen").chosen();
  </script>

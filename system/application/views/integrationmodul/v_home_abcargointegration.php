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

  function submitOn() {
    var integration_vehicle  = $("#integration_vehicle").val();
    var integrationShipmentNo = $("#integrationShipmentNo").val();

    console.log("integration_vehicle : ", integration_vehicle);
    console.log("integrationShipmentNo : ", integrationShipmentNo);

    if (integration_vehicle == "") {
      alert("Pilih kendaraan terlebih dahulu");
    }else if(integrationShipmentNo == "") {
      alert("Harap isi Shipment Number terlebih dahulu");
    }else {
      jQuery.post("<?=base_url();?>abcargointegration/submitintegration", {
				integration_vehicle : integration_vehicle,
				integrationShipmentNo : integrationShipmentNo
			}, function(r){
				console.log("response : ", r);
				if (r.code == 200) {
					if (confirm(r.msg)) {
						window.location = '<?php echo base_url() ?>abcargointegration'
					}
				}
			}, "json");
    }
  }

  function changestatus(id){
    // console.log("integration id : ", id);
    if (confirm("Anda akan merubah status menjadi Completed ?")) {
      jQuery.post("<?=base_url();?>abcargointegration/changestatus", {
        integration_id : id,
        status : 1,
      }, function(r){
        console.log("response : ", r);
        if (r.code == 200) {
          if (confirm(r.msg)) {
            window.location = '<?php echo base_url() ?>abcargointegration'
          }
        }
      }, "json");
    }
  }

</script>

<div style="position: absolute; margin: 0; padding: 0; z-index: 1000; width: 100%;">
<?=$navigation;?>
  <div id="main" style="margin: 20px;">
    <div class="block-border">
      <form class="block-content form" name="frmsearch" id="frmsearch">
        <h1>Integration Modul</h1>
        <table width="100%" cellpadding="3" class="tablelist" style="font-size:11px;">
          <tr>
            <td width="10%">
              <fieldset>
                <legend>
                  Vehicle List
                </legend>
                <table class="table">
                  <tr>
                    <td>
                      <select class="form-control chosen" name="integration_vehicle" id="integration_vehicle">
      									<option value="">Pilih Kendaraan</option>
                          <option value="002100004823@T5|B9352SXR">B9352SXR MTS Canter</option>
													<option value="002100004826@T5|B9559I">B9559I MTS Canter</option>
													<option value="002100004138@T5|B9810DM">B9810DM Canter</option>
													<option value="002100003801@T5|B9140DG">B9140DG Mitsubishi Canter</option>
													<option value="006100001277@T5|B9781SCC">B9781SCC Mitsubishi Canter</option>
                      </select>
                    </td>
                    <td>
                      <label for="integrationShipmentNo">Shipment No</label>
                    </td>
                    <td>
                      <input type="text" name="integrationShipmentNo" id="integrationShipmentNo">
                    </td>
                    <td>
                      <button type="button" id="submiton" class="btn btn-primary" onclick="submitOn();">Submit</button>
                    </td>
                  </tr>
                </table>
              </fieldset>
            </td>
          </tr>
        </table>

        <table class="table sortable no-margin" width="100%" cellpadding="3" class="tablelist" style="margin: 3px; font-size:11px;">
          <thead>
            <tr>
              <td>No</td>
              <td>Shipment No</td>
              <td>Vehicle</td>
              <td>DateTime</td>
              <td>Status</td>
            </tr>
          </thead>
          <tbody>
            <?php for ($j=0; $j < sizeof($dataintegrasi); $j++) {?>
              <tr>
                <td><?php echo $j+1; ?></td>
                <td>
                  <?php echo $dataintegrasi[$j]['integration_shipment_no'] ?>
                </td>
  								<!-- <?php for ($k=0; $k < sizeof($datavehicle); $k++) {?> -->
  									<!-- <?php if ($dataintegrasi[$j]['integration_vehicle_device'] == $datavehicle[$k]['vehicle_device']) {?> -->
                      <td>
  											<!-- <?php echo $datavehicle[$j]['vehicle_no'].' - '.$datavehicle[$j]['vehicle_name'] ?> -->
												<?php echo $dataintegrasi[$j]['integration_vehicle_no'] ?>
                      </td>
  									<?php } ?>
                  <!-- <?php } ?> -->
                  <td>
                    <?php echo date("d F Y H:i:s", strtotime($dataintegrasi[$j]['integration_submit'])) ?>
                  </td>
                  <td>
                    <?php $status = $dataintegrasi[$j]['integration_status'] ?>
                    <?php if ($status == 0) {?>
                      <button type="button" class="btn btn-primary" onclick="changestatus(<?php echo $dataintegrasi[$j]['integration_id'] ?>)">Integration Start</button>
                    <?php }else {?>
                      <button type="button" class="btn red">Integration Completed</button>
                    <?php } ?>
                  </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
    </div>
  </div>

  <script type="text/javascript">
    $(".chosen").chosen();
  </script>

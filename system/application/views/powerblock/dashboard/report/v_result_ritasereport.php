<script type="text/javascript">
  jQuery(document).ready(function(){
    // EXPORT TO EXCEL
    jQuery("#btnexcelreport").click(function()
    {
      window.open('data:application/vnd.ms-excel,' + encodeURIComponent(jQuery('#exportthisecel').html()));
    });
  });
</script>


<div class="row">
	<div class="col-md-12 col-sm-12">
		<div class="panel">
			<header class="panel-heading panel-heading-blue">RITASE REPORT</header>
				<div class="panel-body" id="bar-parent10">
          <input class="btn btn-primary" name="excel" id="btnexcelreport" value="Export To Excel" onclick="javascript:void(0);" /><br><br>
					<div class="row">
            <div id="exportthisecel">
              <table class="table" style="width: 100%;">
          			<thead>
          				<tr>
          					<th>No</td>
          					<th>In</th>
          					<th>Out</th>
          					<th>Status Engine</th>
          					<th>Duration</th>
          					<th>Jarak</th>
          				</tr>
          			</thead>
          			<tbody>
                <?php if ($data != "empty") {?>
                  <?php for ($i=0; $i< sizeof($data); $i++){?>
                  <tr <?=($i%2) ? "class='odd'" : "";?>>
                    <td><?=$i+1;?></td>
                    <td>
                      <?=$data[$i]['trip_mileage_geofence_start'];?> <br>
                      <?=$data[$i]['trip_mileage_start_time'];?>
                    </td>
                    <td>
                      <?=$data[$i]['trip_mileage_geofence_end'];?><br>
                      <?=$data[$i]['trip_mileage_end_time'];?>
                    </td>
                    <td>
                      <?php
                      // $status_engine = ;
                      if ($data[$i]['trip_mileage_engine'] == 0) {
                        echo "Off";
                      }else {
                        echo "On";
                      }
                      ?><br>
                    </td>
                    <td>
                      <?=$data[$i]['trip_mileage_duration'];?>
                    </td>
                    <td>
                      <?php if ($data[$i]['kondisi1'] == "1") {?>
                        <?php echo $data[$i]['trip_mileage_trip_mileage2']." Km";?>
                      <?php }else {?>
                        <?php echo ($data[$i]['trip_mileage_trip_mileage']+$data[$i]['trip_mileage_trip_mileage2'])." Km";?>
                      <?php } ?>
                    </td>
                  </tr>
                  <?php }	?>
              <?php }else { ?>
                <table>
                  <?php echo "Data Not Available"; ?>
                </table>
              <?php } ?>
          			</tbody>
          		</table>
            </div>
					</div>
				</div>
		</div>
	</div>
</div>

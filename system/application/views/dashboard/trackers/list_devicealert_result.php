<script src="<?php echo base_url();?>assets/js/jsblong/jquery.table2excel.js"></script>
<script>
jQuery(document).ready(
		function()
		{
			jQuery("#export_xcel").click(function()
			{
				window.open('data:application/vnd.ms-excel,' + encodeURIComponent(jQuery('#isexport_xcel').html()));
			});
		}
	);
</script>

<div class="row">
	<div class="col-md-12 col-sm-12">
		<div class="panel">
			<header class="panel-heading panel-heading-blue">REPORT</header>
				<div class="panel-body" id="bar-parent10">
					<div class="row">
					<?php if (count($devicealert) == 0) {
							echo "<p>No Data</p>";
					}else{ ?>
						<div class="col-md-12 col-sm-12">

							<div class="col-lg-4 col-sm-4">
								<a href="javascript:void(0);" id="export_xcel" type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-circle btn-info"><small>Export to Excel</small></a>
							</div>

							<div id="isexport_xcel">
                <table style="font-size: 12px;" id="example4" class="table table-striped table-bordered table-hover full-width">
                  <thead>
                      <tr>
                        <th>No</th>
                        <th>Vehicle</th>
                        <th>Alert Name</th>
												<th width="40%">Information</th>
                        <th>Time</th>
                      </tr>
                  </thead>
                  <tbody>
										<?php $devicealertarray = array(
											"dt"    => "Cut Power Alert",
											"BO010" => "Cut Power Alert",
											"BO012" => "Panic Button (SOS)"
										);
										 ?>
                    <?php $no = 1; foreach ($devicealert as $rowalert) {?>
                      <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $rowalert['vehicle_name'].' '. $rowalert['vehicle_no'] ; ?></td>
                        <td>
													<?php
													 	if (in_array($rowalert['gps_alert'], $devicealertarray)) {
													 		echo $devicealertarray[$rowalert['gps_alert']]."<br>";
													 	}else {
													 		echo $rowalert['gps_alert']."<br>";
													 	}
													 ?>
												</td>
												<td>
													<?php echo $rowalert['address'].'<br>'; ?>
													<?php
														if ($rowalert['gps_status'] == "A") {
															echo "GPS OK"."<br>";
														}else {
															echo "GPS NOT OK"."<br>";
														}
													 ?>

													 <!-- <?php
 														if ($rowalert['gps_speed'] > 0) {
 															echo "Engine On"."<br>";
 														}else {
 															echo "Engine Off"."<br>";
 														}
 													 ?> -->
													 <?php echo "Speed : ".number_format($rowalert['gps_speed']*1.852, 0, "",".")." Kph </br>" ?>
													<a href="https://maps.google.com/?q=<?php echo $rowalert['vehicle_lat'].','.$rowalert['vehicle_lng'] ?>" target="_blank">
														<?php echo $rowalert['vehicle_lat'].','.$rowalert['vehicle_lng'] ?>
													</a>
												</td>
                        <td><?php echo $rowalert['vehicle_alert_datetime'] ?></td>
                      </tr>
                    <?php $no++; } ?>
                  </tbody>
                </table>
							</div>
						</div>
					<?php } ?>
					</div>
				</div>
		</div>
	</div>
</div>

<!-- <?php
  function getaddress($lat,$lng){
		 $key  		= "AIzaSyAYe-6_UE3rUgSHelcU1piLI7DIBnZMid4";
     $url 		= 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$lng.'&key='.$key;
		 // echo "url : ".$url.'<br>';
		 $json 		= @file_get_contents($url);
     $data		=json_decode($json);
     $status  = $data->status;
     if($status=="OK")
     {
       return $data->results[0]->formatted_address;
     }
     else
     {
       return false;
     }
  }
?> -->

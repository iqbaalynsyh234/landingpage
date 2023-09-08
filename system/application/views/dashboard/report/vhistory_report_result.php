<?php
if($mapview == 0){
	$sizedata = 0;
}else{
	$sizedata = sizeof($data);
}
  $finaldata = array();
  $perulanganmaksimal = "";
  if ($sizedata == 0) {
    $nodata = "No Data";
  }else {
    $index = 0;
	if($totaldata>0){
		foreach ($data as $datanya) {
		  array_push($finaldata, array(
			"latitude"        => $data[$index]->gps_latitude_real,
			"longitude"       => $data[$index]->gps_longitude_real,
		  ));
		  $index++;
		}
	}
    
    $finaldata_json = json_encode($finaldata);
    $perulanganmaksimal = $sizedata;
  }
?>
<?php
	$key = $this->config->item("GOOGLE_MAP_API_KEY");
	if(isset($key) && $key != "") { ?>
		<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $key;?>&callback=initialize"></script>
	<?php } else { ?>
		<script src="http://maps.google.com/maps/api/js?V=3.3&amp;sensor=false"></script> 
	<? } ?>
<script>
       var map;
       var coords;
       var perulanganmaksimal = '<?php echo $perulanganmaksimal?>';
       var JSONString = '<?php echo $finaldata_json ?>';
       var obj = JSON.parse(JSONString);
       console.log("obj : ", obj);

       coords = [];
          //for (var x = 1; x < obj.length; x++) {
		  for (var x = (obj.length-1); x >= 0; x--) {
	      //for ($i=($totaldata-1); $i>=0; $i--){
              coords.push({
                lat: obj[x].latitude,
                lng: obj[x].longitude
              });
              // console.log("x : ", x);
          }
      
        console.log("coords : ", coords);

       function initialize() {
           var markLAT = coords[0].lat;
           var markLNG = coords[0].lng;
           console.log("coords : ", coords);

           map = new google.maps.Map(document.getElementById("map"), {
             center: new google.maps.LatLng(markLAT, markLNG),
             zoom: 11,
             mapTypeId: google.maps.MapTypeId.ROADMAP
           });

           autoRefresh();
       }

       google.maps.event.addDomListener(window, 'load', initialize);
       var iconBase = '<?php echo base_url()?>assets/images/';
       var icon = new google.maps.MarkerImage(iconBase + "carnewmarker.png");

       function moveMarker(map, marker, lat, lon) {
           marker.setPosition(new google.maps.LatLng(lat, lon));
           map.panTo(new google.maps.LatLng(lat, lon));
       }

       function autoRefresh() {
           var i, route, marker;

           route = new google.maps.Polyline({
               path: [],
               geodesic : true,
               strokeColor: '#FF0000',
               strokeOpacity: 1.0,
               strokeWeight: 2,
               editable: false,
               map:map
           });

           marker=new google.maps.Marker({map:map,icon:icon});
           //for (i = 0; i < coords.length; i++) {
		   for (i = (coords.length-1); i >= 0; i--) {
		   //for ($i=($totaldata-1); $i>=0; $i--){
               setTimeout(function (coords)
               {
                   route.getPath().push(new google.maps.LatLng(coords.lat, coords.lng));
                   moveMarker(map, marker, coords.lat, coords.lng);
               }, 500 * i, coords[i]);
           }
       }
</script>
<style media="screen">
#map {
   height:300px;
   width:100%;
   
}
</style>
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

<?php if($mapview == 1){ ?>
<div class="row">
	<div class="col-md-12 col-sm-12">
		<div class="panel">
			<header class="panel-heading panel-heading-blue">MAP</header>
				<div class="panel-body" id="bar-parent10">
					<div class="row">	
					<div class="col-md-12 col-sm-12">
						<small>Total Data GPS (<?=$totalgps;?>)</small>
					</div>
				<?php if ($sizedata == 0) {
							echo "<p>".$nodata."</p>";
				}else{ ?>
					<div class="col-md-12 col-sm-12">
						<small>Total Coordinate (<?=$totaldata;?>)</small>
					</div>
					<div class="col-md-12 col-sm-12">
						<div id="map"></div>
					</div>
				<?php } ?>
					</div>
				</div>
		</div>
	</div>
</div>
<?php } ?>

							<div class="col-lg-6 col-sm-6">	
								<input id="btn_hide_form" class="btn btn-circle btn-danger" title="" type="button" value="Hide Form" onclick="javascript:return option_form('hide')" />
								<input id="btn_show_form" class="btn btn-circle btn-success" title="" type="button" value="Show Form" onClick="javascript:return option_form('show')" style="display:none"/>
							</div>
							<div class="col-lg-2 col-sm-2">	
							</div>
							<br />
							
<div class="row">
	<div class="col-md-12 col-sm-12">
		<div class="panel" style="display:none">
			<header class="panel-heading panel-heading-blue">REPORT</header>
				<div class="panel-body" id="bar-parent10">
					<div class="row">	
					<?php if (count($data) == 0) {
							echo "<p>".$nodata."</p>";
					}else{ ?>
						<div class="col-md-12 col-sm-12">
							
							<div class="col-lg-4 col-sm-4">	
								<a href="javascript:void(0);" id="export_xcel" type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-circle btn-info"><small>Export to Excel</small></a>
							</div>	
							
							<div id="isexport_xcel">
							<table class="table table-striped custom-table table-hover">
								<thead>
									<tr>
										<th style="text-align:center;" width="2%">No</td>
										<th style="text-align:center;" width="8%">Vehicle</th>
										<th style="text-align:center;" width="15%">Datetime</th>
										<th style="text-align:center;" width="25%">Position</th>
										<th style="text-align:center;" width="5%">GPS Status</th>
										<th style="text-align:center;" width="5%">Speed (km/jam)</th>
									<?php if (isset($vehicle_type) && (in_array(strtoupper($vehicle_type), $this->config->item("vehicle_gtp")))) { ?>
										<th style="text-align:center;" width="5%">Engine</th>		
										<th style="text-align:center;" width="7%">Odometer (km)</th>	
									<?php } ?>
										
										
										
									</tr>
								</thead>
								<tbody>
									
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

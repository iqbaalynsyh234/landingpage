<div id="mapsnya" style="width:100%; height:100%;"></div>

<?php
$key = $this->config->item("GOOGLE_MAP_API_KEY");
//$key = "AIzaSyAYe-6_UE3rUgSHelcU1piLI7DIBnZMid4";
// echo $key;
if(isset($key) && $key != "") { ?>
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $key;?>&callback=initialize" type="text/javascript"></script>
  <?php } else { ?>
    <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <?php } ?>

<script type="text/javascript">
  var marker, map;

  function initialize(){
    var data = '<?php echo json_encode($datafix)?>';
    var obj = JSON.parse(data);

    console.log("data : ", data);
    console.log("obj : ", obj);

      var bounds = new google.maps.LatLngBounds();
      var myLatLng = { lat: parseFloat(obj[0].gps_latitude_real_fmt), lng: parseFloat(obj[0].gps_longitude_real_fmt) };
      console.log("myLatLng : ", myLatLng);
      map = new google.maps.Map(document.getElementById("mapsnya"), {
        zoom: 14,
        center: myLatLng,
      });

        infowindow      = new google.maps.InfoWindow();
        marker = new google.maps.Marker({
          position: myLatLng,
          map : map,
          title: obj[0].vehicle_no + " " + obj[0].vehicle_name,
          id: obj[0].vehicle_no
        });

        var string = obj[0].vehicle_no
        var infowindow = new google.maps.InfoWindow({
          content: string,
          maxWidth: 160
        });
        infowindow.open(map, marker);
  }

</script>

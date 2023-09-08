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

      var bounds     = new google.maps.LatLngBounds();
      var boundsdest = new google.maps.LatLngBounds();

      var icondest = {
        url: "http://transporter.lacak-mobil.com/assets/images/markergif.gif", // url JIKA GIF
        // path: 'assets/images/iconpulsemarker.gif',
        // scale: .5,
        anchor: new google.maps.Point(25,10),
        scaledSize: new google.maps.Size(30,30)
      };

      var myLatLng = { lat: parseFloat(obj[0].gps_latitude_real_fmt), lng: parseFloat(obj[0].gps_longitude_real_fmt) };
      console.log("myLatLng : ", myLatLng);
      map = new google.maps.Map(document.getElementById("mapsnya"), {
        zoom: 14,
        center: myLatLng,
      });

        infowindow      = new google.maps.InfoWindow();
        infowindow2      = new google.maps.InfoWindow();
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

        var destcoord      = obj[0].customercoordinate;
        var destcoordsplit = destcoord.split(",");
        console.log("destcoordsplit : ", destcoordsplit);
        var positiondest   = new google.maps.LatLng(parseFloat(destcoordsplit[0]), parseFloat(destcoordsplit[1]));
        boundsdest.extend(positiondest);

        var markerdest = new google.maps.Marker({
          position: positiondest,
          map: map,
          icon: icondest,
          title: obj[0].customername,
          id: obj[0].customername,
          optimized: false
        });
        markerdest.setIcon(icondest);

        var string2 = obj[0].customername
        var infowindow2 = new google.maps.InfoWindow({
          content: string2,
          maxWidth: 160
        });
        infowindow2.open(map, markerdest);
  }

</script>

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
//     datacustomer
// datacurrentposition
    var dataposition = '<?php echo json_encode($datacurrentposition)?>';
    var obj          = JSON.parse(dataposition);
    var datacustomer = '<?php echo json_encode($datacustomer)?>';
    var objcustomer  = JSON.parse(datacustomer);
    console.log("obj : ", obj);
    console.log("objcustomer : ", objcustomer);

    var bounds         = new google.maps.LatLngBounds();
    var boundscustomer = new google.maps.LatLngBounds();

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

      var icondest = {
        url: "http://transporter.lacak-mobil.com/assets/images/markergif.gif", // url JIKA GIF
        // path: 'assets/images/iconpulsemarker.gif',
        // scale: .5,
        anchor: new google.maps.Point(25,10),
        scaledSize: new google.maps.Size(30,30)
      };

      for (var i = 0; i < objcustomer.length; i++) {
        var positiondest   = new google.maps.LatLng(parseFloat(objcustomer[i].customercoordlat), parseFloat(objcustomer[i].customercoordlng));
        boundscustomer.extend(positiondest);

        var markerdest = new google.maps.Marker({
          position: positiondest,
          map: map,
          icon: icondest,
          // title: objcustomer[i].customername,
          id: objcustomer[i].customername,
          optimized: false
        });
        markerdest.setIcon(icondest);

        // background:red; border:none; border-radius:4px;
        // background:blue; border:none; border-radius:4px;
        // background:green; border:none; border-radius:4px;

        if (objcustomer[i].status == 0) {
          var string2 = "<p style='color:red;'><b>"+objcustomer[i].nourut+". "+objcustomer[i].customername+"</b></p>";
        }else {
          var string2 = "<p style='color:green;'><b>"+objcustomer[i].nourut+". "+objcustomer[i].customername+"</b></p>";
        }

      // }else if (objcustomer[i].status == 1) {
      //   var string2 = "<p style='color:blue;'>"+objcustomer[i].nourut+". "+objcustomer[i].customername+"</p>";
      // }

        var infowindow2 = new google.maps.InfoWindow({
          content: string2,
          maxWidth: 200
        });
        infowindow2.open(map, markerdest);
      }
  }

</script>

<div class="row">
  <div class="col-md-12">
      <div class="card card-topline-yellow">
          <div class="card-head">
              <header>Information Detail</header>
              <div class="tools">
                  <!-- <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a> -->
                <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
                <button type="button" class="btn btn-danger" name="button" onclick="closemodallistofvehicle();">X</button>
              </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="card-box">
                <div class="card-body">
                  <table class="table table-striped" style="font-size:12px;">
                    <tr>
                      <td>
                        <i class="fa fa-car">
                          <span id="alertvehicle"><?php echo $content[0]['alarm_report_vehicle_no'].' '.$content[0]['alarm_report_vehicle_name'] ?></span>
                        </i>
                      </td>
                      <td>
                        <i class="fa fa-warning">
                          <span id="alerttype" style="color:red; font-size:14px;"><?php echo $content[0]['alarm_report_name'] ?></span>
                        </i>
                      </td>
                    </tr>

                    <tr>
                      <td>
                        <i class="fa fa-dashboard">
                          <span id="alertspeed"><?php echo $speed." (KM / H)" ?></span>
                        </i>
                      </td>
                      <td>
                        <i class="fa fa-clock-o">
                          <span id="alerttime"><?php echo date("d-m-Y H:i:s", strtotime($content[0]['alarm_report_end_time'])) ?></span>
                        </i>
                      </td>
                    </tr>

                    <tr>
                      <td>
                        <i class="fa fa-map-marker">
                          <span id="alertcoord"><a href='http://maps.google.com/maps?z=12&t=m&q=loc:<?php echo $content[0]['alarm_report_coordinate_start'] ?>' target='_blank'><?php echo $content[0]['alarm_report_coordinate_start'] ?></a></span>
                        </i>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card-box">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4">
                      <img src="<?php echo base_url() ?>assets/images/driver_photo.png" width="100px" height="150px;">
                    </div>
                    <div class="col-md-8">
                      <table class="table table-striped" style="font-size:12px;">
                        <tr>
                          <td>
                            <i class="fa fa-user">
                              <span id="alertdriver">Driver Name : </span>
                            </i>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <i class="fa fa-phone">
                              <span id="alertdriverphone">Contact Info :</span>
                            </i>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <i class="fa fa-drivers-license">
                              <span id="alertdriverlicense">License :</span>
                            </i>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="card-box">
                <div class="card-body">
                  <button type="button" class="btn btn-primary btn-sm" onclick="tablivestream();">
                    <span class="fa fa-file-video-o"></span>
                  </button>
                  <button type="button" class="btn btn-success btn-sm" onclick="tabcameraalert();">
                    <span class="fa fa-camera"></span>
                  </button>
                  <br><br>
                  <div class="row">
                    <div class="col-md-12" id="livestream" style="display:none;">
                      <div id="livestreamfix"></div>
                    </div>
                    <div class="col-md-12" id="cameraalert">
                      <div id="cameraalertfix">
                        <img src='<?php echo $content[0]['alarm_report_fileurl'] ?>' width='360px' height='230px;'>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card-box">
                <div class="card-body">
                  <div id="mapsnya" style="width:355px; height:250px;"></div>
                </div>
              </div>
            </div>
          </div>
      </div>
  </div>
</div>

<script type="text/javascript">
var datafixnya, map;
var marker;
var car = "M17.402,0H5.643C2.526,0,0,3.467,0,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759c3.116,0,5.644-2.527,5.644-5.644 V6.584C23.044,3.467,20.518,0,17.402,0z M22.057,14.188v11.665l-2.729,0.351v-4.806L22.057,14.188z M20.625,10.773 c-1.016,3.9-2.219,8.51-2.219,8.51H4.638l-2.222-8.51C2.417,10.773,11.3,7.755,20.625,10.773z M3.748,21.713v4.492l-2.73-0.349 V14.502L3.748,21.713z M1.018,37.938V27.579l2.73,0.343v8.196L1.018,37.938z M2.575,40.882l2.218-3.336h13.771l2.219,3.336H2.575z M19.328,35.805v-7.872l2.729-0.355v10.048L19.328,35.805z";

function initialize(){
    var vehicle = '<?php echo json_encode($content); ?>';
    var bounds  = new google.maps.LatLngBounds();
    var obj     = JSON.parse(vehicle);
    var coord   = obj[0].alarm_report_coordinate_start.split(',');

    console.log("obj : ", obj);
    console.log("coord 0 : ", coord[0]);
    console.log("coord 1 : ", coord[1]);

    var icon = {
      path: car,
      scale: .5,
      strokeColor: 'white',
      strokeWeight: .10,
      fillOpacity: 1,
      fillColor: '#00b300',
      offset: '5%'
    };

    map = new google.maps.Map(
     document.getElementById("mapsnya"), {
       center: new google.maps.LatLng(parseFloat(coord[0]), parseFloat(coord[1])),
       zoom: 15,
       mapTypeId: google.maps.MapTypeId.ROADMAP,
       options: {
         gestureHandling: 'greedy'
       }
     });

     var position = new google.maps.LatLng(parseFloat(coord[0]), parseFloat(coord[1]));
     marker = new google.maps.Marker({
       position: position,
       map: map,
       // icon: icon,
       title: obj[0].alarm_report_vehicle_no + " " + obj[0].alarm_report_vehicle_name,
       id: obj[0].alarm_report_id,
       optimized: false
     });
}

function tablivestream(){
  $("#livestream").show();
  $("#livestreamfix").html("<video width='360px' height='230px;' controls><source src='<?php echo $urlvideo ?>' type='video/mp4'></video>");
  $("#cameraalert").hide();

}
function tabcameraalert(){
  $("#livestream").hide();
  $("#cameraalertfix").html("<img src='<?php echo $content[0]['alarm_report_fileurl'] ?>' width='360px' height='230px;'>");
  $("#cameraalert").show();
}
</script>

<?php
$key = $this->config->item("GOOGLE_MAP_API_KEY");
//$key = "AIzaSyAYe-6_UE3rUgSHelcU1piLI7DIBnZMid4";

if(isset($key) && $key != "") { ?>
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $key;?>&callback=initialize" type="text/javascript"></script>
  <?php } else { ?>
    <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <?php } ?>

<style media="screen">
  #mapview {
    height: 350px;  /* The height is 400 pixels */
    width: 100%;  /* The width is the width of the web page */
   }

   #description {
    font-family: Roboto;
    font-size: 15px;
    font-weight: 300;
  }

  #infowindow-content .title {
    font-weight: bold;
  }

  #infowindow-content {
    display: none;
  }

  #mapview #infowindow-content {
    display: inline;
  }

  .pac-card {
    margin: 10px 10px 0 0;
    border-radius: 2px 0 0 2px;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    outline: none;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    background-color: #fff;
    font-family: Roboto;
  }

  #pac-container {
    padding-bottom: 12px;
    margin-right: 12px;
  }

  .pac-controls {
    display: inline-block;
    padding: 5px 11px;
  }

  .pac-controls label {
    font-family: Roboto;
    font-size: 13px;
    font-weight: 300;
  }

  #pac-input {
    background-color: #fff;
    font-family: Roboto;
    font-size: 15px;
    font-weight: 300;
    margin-left: 12px;
    padding: 0 11px 0 13px;
    text-overflow: ellipsis;
    width: 400px;
  }

  #pac-input:focus {
    border-color: #4d90fe;
  }

  #title {
    color: #fff;
    background-color: #4d90fe;
    font-size: 25px;
    font-weight: 500;
    padding: 6px 12px;
  }
</style>
<!-- start sidebar menu -->
<div class="sidebar-container">
  <?=$sidebar;?>
</div>
<!-- end sidebar menu -->

<!-- start page content -->
<div class="page-content-wrapper">
  <div class="page-content">
    <br>
    <?php if ($this->session->flashdata('notif')) {?>
      <div class="alert alert-success" id="notifnya" style="display: none;"><?php echo $this->session->flashdata('notif');?></div>
    <?php }?>
    <!--<div class="alert alert-success" id="notifnya2" style="display: none;"></div>-->
    <div class="row">
      <div class="col-md-12">
        <div class="panel" id="panel_form">
          <header class="panel-heading panel-heading-blue">Customer</header>
          <div class="panel-body" id="bar-parent10">
            <div id="tablecusttms">
              <table class="table table-bordered table-striped table-hover" id="example3" style="font-size:12px; width:100%;">
                <thead>
                  <tr>
                    <th>
                      <button type="button" class="btn btn-success btn-sm" id="btnAddNewCustomer" onclick="addnewcustomer();">
                        <span class="fa fa-plus"></span>
                      </button>
                      No
                    </th>
                    <th>Customer Code</th>
                    <th>Customer Code Area</th>
                    <th>Customer Name</th>
                    <th>Whatsapp</th>
                    <th>Coordinate</th>
                    <th>Address</th>
                    <th>Control</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (sizeof($custtms) > 0) {?>
                    <?php for ($i=0; $i < sizeof($custtms); $i++) {?>
                        <td><?php echo $i+1; ?></td>
                        <td><?php echo $custtms[$i]['customer_code']; ?></td>
                        <td><?php echo $custtms[$i]['customer_code_area']; ?></td>
                        <td><?php echo $custtms[$i]['customer_name']; ?></td>
                        <td><?php echo $custtms[$i]['customer_whatsapp']; ?></td>
                        <td><?php echo $custtms[$i]['customer_coordinate']; ?></td>
                        <td><?php echo $custtms[$i]['customer_address']; ?></td>
                        <td>
                          <a type="button" class="btn btn-success btn-xs" href="<?php echo base_url() ?>customer/edit/<?php echo $custtms[$i]['customer_id']; ?>">
                            <span class="fa fa-pencil"></span>
                          </a>

                          <a type="button" class="btn btn-danger btn-xs" onclick="deletethiscustomer('<?php echo $custtms[$i]['customer_id']; ?>')">
                            <span class="fa fa-trash"></span>
                          </a>
                        </td>
                      </tr>
                    <?php } ?>
                  <?php }else {?>
                    <tr>
                      <td>Data is Empty</td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  <?php } ?>

                </tbody>
              </table>
            </div>


            <div id="foraddcustomerdata" style="display: none;">
            <form class="form-horizontal" name="frmjoborder" id="frmjoborder" onsubmit="frmjoborder_onsubmit();">
              <input type="checkbox" name="geofencechecking" id="geofencechecking" value="0" onchange="checkgeofence();"> Geofence Checking
                <div id="mapnya">
                  <div class="pac-card" id="pac-card">
                    <div>
                      <div id="title">
                        Customer Pointing
                      </div>
                      <div id="type-selector" class="pac-controls">
                        <input type="radio" name="type" id="changetype-all" checked="checked" hidden>
                      </div>
                    </div>
                    <div id="pac-container">
                      <input id="pac-input" type="text" placeholder="Enter a location" class="form-control">
                    </div>
                  </div>
                  <div id="mapview"></div>
                  <div id="infowindow-content">
                    <img src="" width="16" height="16" id="place-icon">
                    <span id="place-name"  class="title"></span><br>
                    <span id="place-address"></span>
                  </div><br><br>

                  <tr>
                    <td>
                      <div class="form-group row">
                          <div id="geofenceinfo" style="display:none; color:red; font-size:12px">Geofence is Empty, please setting geofence first.</div>
                      </div>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <div class="form-group row">
                          <label for="horizontalFormEmail" class="col-sm-2 control-label">Customer Name</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control" id="joborder_customername" name="joborder_customername">
                          </div>
                      </div>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <div class="form-group row">
                          <label for="horizontalFormEmail" class="col-sm-2 control-label">Customer Code</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control" id="joborder_customercode" name="joborder_customercode">
                          </div>
                      </div>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <div class="form-group row">
                          <label for="horizontalFormEmail" class="col-sm-2 control-label">Customer Code Area</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control" id="joborder_customercodearea" name="joborder_customercodearea">
                          </div>
                      </div>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <div class="form-group row">
                          <label for="horizontalFormEmail" class="col-sm-2 control-label">Customer Whatsapp</label>
                          <div class="col-sm-10">
                              <input type="number" class="form-control" id="joborder_customerwhatsapp" name="joborder_customerwhatsapp" placeholder="Tanpa 0 didepan nomor. Ex. 85779897781">
                          </div>
                      </div>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <div class="form-group row">
                          <label for="horizontalFormEmail" class="col-sm-2 control-label">Geofence Name</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control" id="joborder_geofencename" name="joborder_geofencename" readonly>
                          </div>
                      </div>
                    </td>
                  </tr>

                    <tr>
                      <td>
                        <div class="form-group row">
                            <label for="horizontalFormEmail" class="col-sm-2 control-label">Latitude</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="latitude" name="latitude" readonly>
                            </div>
                        </div>
                      </td>

                      <td>
                        <div class="form-group row">
                            <label for="horizontalFormEmail" class="col-sm-2 control-label">Longitude</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="longitude" name="longitude" readonly>
                            </div>
                        </div>
                      </td>
                    </tr>

                    <tr>
                      <td>
                        <div class="form-group row">
                            <label for="horizontalFormEmail" class="col-sm-2 control-label">Address</label>
                            <div class="col-sm-10">
                              <textarea type="text" class="form-control" id="addressfix" name="addressfix" rows="8" cols="80" readonly>
                              </textarea>
                            </div>
                        </div>
                      </td>
                    </tr>
                </div>
            </form>
            <div class="col-md-12">
              <div class="text-right">
                <button class="btn btn-circle btn-warning" id="btncancel" type="button" onclick="cancelformjoborder();"/>Cancel</button>
                <button class="btn btn-circle btn-success" id="btnsavejoborder" type="button" onclick="frmjoborder_onsubmit();" disabled/>Save</button>
                <img src="<?php echo base_url();?>assets/transporter/images/loader2.gif" style="display: none;" id="loadernya">
              </div>
            </div>
          </div>
          </div>
        </div>
      </div>
    </div>

<script type="text/javascript">
  function addnewcustomer(){
    $("#tablecusttms").hide();
    $("#foraddcustomerdata").show();
  }

  function cancelformjoborder(){
    $("#tablecusttms").show();
    $("#foraddcustomerdata").hide();
  }

  function frmjoborder_onsubmit(){
    $("#resultreport").hide();
    var alarmtype = $("#alarmtype").val();
    $("#alarmfix").val(alarmtype);
    // console.log("alarmtype : ", alarmtype);
    $("#loadernya").show();
    $.post("<?php echo base_url() ?>customer/savecusttms", jQuery("#frmjoborder").serialize(), function(response){
      console.log("response : ", response);
      if (response.code == 400) {
        $("#loadernya").hide();
        alert(response.msg);
      }else if (response.code == 100) {
        $("#loadernya").hide();
        alert(response.msg);
        return false;
      }else {
        $("#loadernya").hide();
        if (confirm("Success save customer")) {
          window.location = '<?php echo base_url() ?>customer';
        }
      }
    }, "json");
  }

  var circles       = [];
  var newpopulation = 2.5;
  function initialize(){
    var map = new google.maps.Map(
      document.getElementById("mapview"), {
        center: new google.maps.LatLng(-6.1753924, 106.82715280000002),
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        options: {
          gestureHandling: 'greedy'
        }
      });

          var card = document.getElementById('pac-card');
          var input = document.getElementById('pac-input');
          var types = document.getElementById('type-selector');
          var strictBounds = document.getElementById('strict-bounds-selector');
          var geocoder = new google.maps.Geocoder();



          map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

          var autocomplete = new google.maps.places.Autocomplete(input);

          // Bind the map's bounds (viewport) property to the autocomplete object,
          // so that the autocomplete requests use the current map bounds for the
          // bounds option in the request.
          autocomplete.bindTo('bounds', map);

          // Set the data fields to return when the user selects a place.
          autocomplete.setFields(
              ['address_components', 'geometry', 'icon', 'name']);

          var infowindow = new google.maps.InfoWindow();
          var infowindowContent = document.getElementById('infowindow-content');
          infowindow.setContent(infowindowContent);

          var marker = new google.maps.Marker({
            draggable : true,
            map: map,
            anchorPoint: new google.maps.Point(0, -29)
          });

          // Add circle overlay and bind to marker
      //     var circle = new google.maps.Circle({
      //       strokeColor: '#FF0000',
      //       strokeOpacity: 0.8,
      //       strokeWeight: 2,
      //       fillColor: '#FF0000',
      //       fillOpacity: 0.35,
      //       map: map,
      //       radius: Math.sqrt(newpopulation) * 100,
      //       editable: true
      //       // radius: 1000    // 10 miles in metres
      //       // fillColor: '#AA0000'
      //     });
      //     circle.bindTo('center', marker, 'position');
      //     circles.push(circle);
      //     console.log("circles : ", circles);
      //     console.log("circles : ", circle.getRadius());
      //
      //     // add resize behaviour to the circle
      // circle.addListener('radius_changed', function(e){
      //      storeCircleRadius(circle);
      //  });

              google.maps.event.addListener(marker, 'dragend', function() {
                  geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                      if (results[0]) {
                        document.getElementById("addressfix").value = results[0].formatted_address;
                        var lat = marker.getPosition().lat();
                        var lng = marker.getPosition().lng();
                        document.getElementById("latitude").value = lat.toString().slice(0, 10);
                        document.getElementById("longitude").value = lng.toString().slice(0, 10);
                        // FOR GEOFENCE CHECK
                        var latforgeofencecheck = lat.toString().slice(0, 10);
                        var lngforgeofencecheck = lng.toString().slice(0, 10);
                        // document.getElementById("latitude").value = marker.getPosition().lat();
                        // document.getElementById("longitude").value = marker.getPosition().lng();
                        $("#geofenceinfo").hide();
                        var valuecheckthis = $("#geofencechecking").val();
                          if (valuecheckthis == 1) {
                            $.post("<?php echo base_url() ?>customer/checkthisgeofence/", {latlng : latforgeofencecheck+","+lngforgeofencecheck}, function(response){
                              console.log("getgeofence after dragged : ", response);
                              if (response != "" || response != 0) {
                                var geofencename = response;
                                $("#joborder_geofencename").val(geofencename);
                                document.getElementById("btnsavejoborder").disabled = false;
                              }else {
                                $("#joborder_geofencename").val("");
                                $("#geofenceinfo").show();
                                document.getElementById("btnsavejoborder").disabled = true;
                              }
                            }, "json");
                          }else {
                            document.getElementById("btnsavejoborder").disabled = false;
                          }
                    }
                  }
                });
              });

          autocomplete.addListener('place_changed', function() {
            infowindow.close();
            marker.setVisible(false);
            var place = autocomplete.getPlace();
            if (!place.geometry) {
              // User entered the name of a Place that was not suggested and
              // pressed the Enter key, or the Place Details request failed.
              window.alert("No details available for input: '" + place.name + "'");
              return;
            }

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
              map.fitBounds(place.geometry.viewport);
            } else {
              map.setCenter(place.geometry.location);
              map.setZoom(17);  // Why 17? Because it looks good.
            }
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);
              var lat = marker.getPosition().lat();
              var lng = marker.getPosition().lng();
            document.getElementById("latitude").value = lat.toString().slice(0, 10);
            document.getElementById("longitude").value = lng.toString().slice(0, 10);
            // FOR GEOFENCE CHECK
            var latforgeofencecheck = lat.toString().slice(0, 10);
            var lngforgeofencecheck = lng.toString().slice(0, 10);
            $("#geofenceinfo").hide();
            var valuecheckthis      = $("#geofencechecking").val();
              if (valuecheckthis == 1) {
                $.post("<?php echo base_url() ?>customer/checkthisgeofence/", {latlng : latforgeofencecheck+","+lngforgeofencecheck}, function(response){
                  console.log("getgeofence before dragged : ", response);
                  if (response != "" || response != 0) {
                    var geofencename = response;
                    $("#joborder_geofencename").val(geofencename);
                    document.getElementById("btnsavejoborder").disabled = false;
                  }else {
                    $("#joborder_geofencename").val("");
                    $("#geofenceinfo").show();
                    document.getElementById("btnsavejoborder").disabled = true;
                  }
                }, "json");
              }else {
                document.getElementById("btnsavejoborder").disabled = false;
              }

            var address = '';
            if (place.address_components) {
              address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
              ].join(' ');
            }

            infowindowContent.children['place-icon'].src = place.icon;
            infowindowContent.children['place-name'].textContent = place.name;
            infowindowContent.children['place-address'].textContent = address;
            jQuery("#addressfix").val(address);
            // jQuery("#allcordinates").val(circle.getRadius());
            // jQuery("#allcordinatesforview").val(Math.round(circle.getRadius()));
            infowindow.open(map, marker);
          });

          function postData(url,data,callback){
            jQuery.ajax({
              type:"POST",
              contentType: "application/json",
              dataType: 'json',
              url:url,
              data: JSON.stringify(data),
              success: function(response){
                  return callback(null,response);
              },
              error:function(err){
                 return callback(true,err);
              }
            });
          }

          // Sets a listener on a radio button to change the filter type on Places
          // Autocomplete.
          function setupClickListener(id, types) {
            var radioButton = document.getElementById('changetype-all');
            radioButton.addEventListener('click', function() {
              autocomplete.setTypes('changetype-all');
            });
          }

          setupClickListener('changetype-all', []);
          // setupClickListener('changetype-address', ['address']);
          // setupClickListener('changetype-establishment', ['establishment']);
          // setupClickListener('changetype-geocode', ['geocode']);

          // document.getElementById('use-strict-bounds')
          //     .addEventListener('click', function() {
          //       console.log('Checkbox clicked! New state=' + this.checked);
          //       autocomplete.setOptions({strictBounds: this.checked});
          //     });
  }

  // FOR DISABLE SUBMIT FORM
  jQuery(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });

  // FOR CHECK GEOFENCE OR NOT
  function checkgeofence(){
    var valuecheckthis = $("#geofencechecking").val();
    console.log("valuecheckthis : ", valuecheckthis);
      if (valuecheckthis == 0) {
        $("#geofencechecking").val(1);
      }else {
        $("#geofencechecking").val(0);
        document.getElementById("btnsavejoborder").disabled = false;
      }
  }

  function deletethiscustomer(custid){
    $.post("<?php echo base_url() ?>customer/delete/", {custid : custid}, function(response){
      console.log("response : ", response);
        if (response.code == 200) {
          if (confirm(response.msg)) {
            window.location = '<?php echo base_url()?>customer';
          }
        }
    }, "json");
  }
</script>

<?php
	$key = $this->config->item("GOOGLE_MAP_API_KEY");
	if(isset($key) && $key != "") { ?>
    <!-- <?php echo $key; ?> -->
		<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $key."&libraries=places,drawing&callback=initialize"?>" type="text/javascript"></script>
	<?php } else { ?>
		<script src="http://maps.google.com/maps/api/js?V=3.3&amp;sensor=false"></script>
	<?php } ?>

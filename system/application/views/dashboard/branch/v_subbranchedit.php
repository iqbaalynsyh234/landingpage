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
<div class="page-content-wrapper" style="width: 100%;">
  <div class="page-content">
    <br>
    <?php if ($this->session->flashdata('notif')) {?>
      <div class="alert alert-success" id="notifnya" style="display: none;"><?php echo $this->session->flashdata('notif');?></div>
    <?php }?>
    <div class="alert alert-success" id="notifnya2" style="display: none;"></div>
    <div class="row">
      <div class="col-md-12" id="formaddbranchoffice">
        <div class="panel" id="panel_form">
          <header class="panel-heading panel-heading-blue">Edit Sub Branch Office</header>
          <div class="panel-body" id="bar-parent10">
            <form class="form-horizontal" name="frmedit" id="frmedit" onsubmit="javascript:return frmedit_onsubmit()">
              <input type="hidden" name="subcompany_id" id="subcompany_id" value="<?php echo $data_subcompany[0]['subcompany_id'];?>" />
              <input type="hidden" name="origin_subbranchid" id="origin_subbranchid" value="<?php echo $subbranchoffice->origin_subbranch_id; ?>" />
                <table class="table sortable no-margin">
                  <tr>
                    <td>Current Branch Office</td>
                    <td>
                        <?php for ($i=0; $i < sizeof($data_subcompany); $i++) {?>
                          <?php
                            for ($x=0; $x < sizeof($data_company); $x++) {
                              if ($data_subcompany[0]['subcompany_parent'] == $data_company[$x]['company_id']) {?>
                                <input class="form-control" type="text" name="curbranchoffice" id="curbranchoffice" value="<?php echo $data_company[$x]['company_name'] ?>" readonly>
                              <?php } } ?>
                        <?php } ?>
                    </td>
                  </tr>
                  <tr>
                    <td>Branch Office</td>
                    <td>
                      <select class="select2" name="subcompany_parent" id="subcompany_parent">
                        <?php for ($i=0; $i < sizeof($data_company); $i++) {?>
                          <option value="<?php echo $data_company[$i]['company_id'] ?>"><?php echo $data_company[$i]['company_name'] ?></option>
                        <?php } ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Sub Branch Office Name</td>
                    <td><input class="form-control" type="text" size="35" name="subcompany_name" id="subcompany_name" value="<?php echo $data_subcompany[0]['subcompany_name'];?>"/></td>
                  </tr>
                </table>

                <table class="table">
                  <tr>
                    <div id="foraddcustomerdata">
                      <div id="mapnya">
                        <div class="pac-card" id="pac-card">
                          <div>
                            <div id="title">
                              Branch Office Address
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
                          <div class="form-group row">
                            <label for="horizontalFormEmail" class="col-sm-2 control-label">Latitude</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="latitude" name="latitude" value="<?php echo $subbranchoffice->origin_subbranch_lat; ?>" readonly>
                            </div>
                          </div>
                        </tr>
                        <tr>
                          <div class="form-group row">
                            <label for="horizontalFormEmail" class="col-sm-2 control-label">Longitude</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="longitude" name="longitude" value="<?php echo $subbranchoffice->origin_subbranch_lng; ?>" readonly>
                            </div>
                          </div>
                        </tr>

                        <tr>
                          <div class="form-group row">
                            <label for="horizontalFormEmail" class="col-sm-2 control-label">Address</label>
                            <div class="col-sm-10">
                              <textarea type="text" class="form-control" id="addressfix" name="addressfix" rows="8" cols="80" readonly>
                                <?php echo $subbranchoffice->origin_subbranch_address; ?>
                              </textarea>
                            </div>
                          </div>
                        </tr>
                      </div>
                    </div>
                  </tr>
                </table>
              <div class="text-right">
                <a type="button" class="btn btn-warning" href="<?php echo base_url()?>account/subbranchoffice"/> Cancel</a>
                <button type="submit" id="submit" name="submit" class="btn btn-success"/> Save</button>
                <img id="loader" src="<?=base_url();?>assets/images/ajax-loader.gif" border="0" style="display:none;"/>
              </div>
            </form>
          </div>
        </div>
      </div>
  </div>
</div>
</div>

<script type="text/javascript">
  function frmedit_onsubmit(){
    jQuery("#loader").show();
    jQuery.post("<?=base_url()?>account/updatesubbranchoffice", jQuery("#frmedit").serialize(),
      function(r)
      {
        jQuery("#loader").hide();
        if (r.error)
        {
          alert(r.message);
          return false;
        }

        alert(r.message);
        location = r.redirect;
      }
      , "json"
    );
    return false;
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

          // FOR DISABLE SUBMIT FORM
          jQuery(window).keydown(function(event){
            // console.log("event.keyCode : ", event.keyCode);
            if(event.keyCode == 13) {
              event.preventDefault();
              return false;
            }
          });
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

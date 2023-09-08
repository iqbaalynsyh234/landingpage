<style media="screen">
input#fixlink {
    opacity: 1%;
    margin-top: -3%;
}

input#fixlink2 {
    opacity: 1%;
    margin-top: -3%;
}

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
<div class="sidebar-container">
  <?=$sidebar;?>
</div>

<div class="page-content-wrapper">
  <div class="page-content" id="page-content-new">
    <div class="row">
      <div class="col-md-12 col-sm-12" id="reportfilter">
        <div class="panel" id="panel_form">
          <header class="panel-heading panel-heading-blue">JOB ORDER</header>
          <div class="panel-body" id="bar-parent10">
            <input type="text" id="fixlink" readonly>
            <input type="text" id="fixlink2" readonly>
            <div class="tbjoborder">
              <!-- style="display:none;" -->
              <table class="table table-bordered table-striped table-hover" style="font-size:12px; width:100%;">
                <thead>
                  <tr>
                    <th>
                      No
                      <button type="button" name="btnAddjoborder" id="btnAddjoborder" class="btn btn-success btn-xs" onclick="gotoformjoborder();">
                        <span class="fa fa-plus"></span>
                      </button>
                    </th>
                    <th>Order ID</th>
                    <th>Delivery DateTime</th>
                    <th>Customer</th>
                    <th>Vehicle</th>
                    <th>Destination</th>
                    <th>Coord</th>
                    <th>Status</th>
                    <th>Completed DateTime</th>
                    <th>Control</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (isset($datajob)) {
                    for ($i=0; $i < sizeof($datajob); $i++) {?>
                      <tr>
                        <td><?php echo $i+1; ?></td>
                        <td><?php echo $datajob[$i]['order_id']; ?></td>
                        <td><?php echo date("d-m-Y H:i:s", strtotime($datajob[$i]['order_datetime'])); ?></td>
                        <td>
                          <?php echo $datajob[$i]['order_jobordercust_name']; ?> <br>

                          <?php if ($datajob[$i]['order_status'] != 2) {?>
                            <a href="https://api.whatsapp.com/send?phone=+62<?php echo $datajob[$i]['order_jobordercust_whatsapp']; ?>&amp;text=<?php echo $datajob[$i]['order_sharelink']; ?>" target="_blank">
                              <button type="button" class="btn btn-circle btn-success"><i class="fa fa-whatsapp"></i> Share </button>
                            </a>
                          <?php } ?>
                        </td>
                        <td>
                          <a href="#" onclick="sharelinkforinternal('<?php echo $datajob[$i]['order_vehicle_id'].'||'.$datajob[$i]['order_datetime']?>');">
                            <?php echo $datajob[$i]['order_vehicle_no'].' - '.$datajob[$i]['order_vehicle_name']; ?>
                          </a>
                        </td>
                        <td><?php echo $datajob[$i]['order_jobordercust_address']; ?></td>
                        <td><?php echo $datajob[$i]['order_jobordercust_coordinate']; ?></td>
                        <td id="joborderstatus<?php echo $datajob[$i]['order_id'] ?>">
                          <?php
                          $status = $datajob[$i]['order_status'];
                            if ($status == 0) {?>
                              <button type="button" name="button" class="btn btn-sm btn-warning">Process</button>
                            <?php }elseif ($status == 1) {?>
                              <button type="button" name="button" class="btn btn-sm btn-success">On Trip</button>
                          <?php }else {?>
                              <?php if ($datajob[$i]['order_manual_status'] == "MANUAL") {?>
                                <button type="button" name="button" class="btn btn-sm btn-default">Completed (M)</button>
                              <?php }else {?>
                                <button type="button" name="button" class="btn btn-sm btn-default">Completed</button>
                              <?php } ?>
                            <?php } ?>
                        </td>
                        <td>

                          <?php
                          $status = $datajob[$i]['order_status'];
                            if ($status == 2) {?>
                              <?php echo date("d-m-Y H:i:s", strtotime($datajob[$i]['order_completed_datetime'])); ?>
                            <?php }?>
                        </td>

                        <td>
                          <?php if ($datajob[$i]['order_status'] != 2) {?>
                            <button type="button" name="button" class="btn btn-xs btn-success" title="Share Link" onclick="sharethislink('<?php echo $datajob[$i]['order_id'];?>');">
                              <span class="fa fa-share-alt"></span>
                            </button>

                            <button type="button" name="button" class="btn btn-xs btn-primary" title="Change Status" onclick="changestatus('<?php echo $datajob[$i]['order_id'];?>');">
                              <span class="fa fa-edit"></span>
                            </button>

                            <button type="button" name="button" class="btn btn-xs btn-danger" title="Cancel Job Order" onclick="canceljoborder('<?php echo $datajob[$i]['order_id'];?>');">
                              <span class="fa fa-trash"></span>
                            </button>
                          <?php } ?>
                        </td>
                      </tr>
                  <?php } } ?>
                </tbody>
              </table>
            </div>

            <div class="formjoborder" style="display:none;">
               <!-- style="display:none;" -->
              <div class="card">
                <div class="card-body">
                  <form class="form-horizontal" name="frmjoborder" id="frmjoborder" onsubmit="frmjoborder_onsubmit();">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="panel" id="panel_form">
                          <header class="panel-heading panel-heading-red">JOB ASSIGN</header>
                          <div class="panel-body" id="bar-parent10">
                            <span id="selisih"></span>
                            <table class="table">
                                  <?php
                                    $user_level      = $this->sess->user_level;//;//$this->sess->user_level;
                                    $user_id         = $this->sess->user_id;
                                    $user_company    = $this->sess->user_company;
                                    $user_subcompany = $this->sess->user_subcompany;
                                  ?>
                                  <?php
                                  if ($user_level != 3) {?>
                                    <tr>
                                      <td>Pickup</td>
                                      <td>:</td>
                                      <td>
                                        <?php
                                          if ($user_level == 1) {?>
                                            <select class="form-control select2" name="branchoffice" id="branchoffice" onchange="searchsubbranchoffice();">
                                              <option value="">--Select Branch Office--</option>
                                              <?php
                                                for ($loopbranchoffice=0; $loopbranchoffice < sizeof($dataforpickup); $loopbranchoffice++) {?>
                                                  <option value="<?php echo $dataforpickup[$loopbranchoffice]['branch_company_id']; ?>"><?php echo $dataforpickup[$loopbranchoffice]['branch_name']; ?></option>
                                               <?php } ?>
                                            </select> <br>

                                            <div id="showsubbranchoffice" style="display:none;"></div>
                                        <?php }elseif ($user_level == 2) {?>
                                          <select class="form-control select2" name="subbranchoffice" id="subbranchoffice" onchange="searchvehiclebysubbranchoffice();">
                                            <option value="">--Select Sub Branch Office--</option>
                                            <?php
                                              for ($loopsubbranchoffice=0; $loopsubbranchoffice < sizeof($dataforpickup); $loopsubbranchoffice++) {?>
                                                <option value="<?php echo $dataforpickup[$loopsubbranchoffice]['subcompany_id']; ?>"><?php echo $dataforpickup[$loopsubbranchoffice]['subcompany_name']; ?></option>
                                             <?php } ?>
                                          </select>
                                        <?php }else {?>
                                          <!-- USER LEVEL 3 -->
                                        <?php }?>
                                      </td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                    </tr>
                                  <?php }?>


                              <tr>
                                <td>
                                  Vehicle
                                </td>
                                <td>:</td>
                                <td>
                                  <div id="vehiclefix"></div>
                                  <?php if (sizeof($dataforpickup) < 1) {?>
                                    <select id="vehicle" name="vehicle" class="form-control select2" style="width:80%;">
                                      <option value="">--Select Vehicle--</option>
                                      <?php for ($i = 0; $i < sizeof($datavehicle); $i++) {?>
                                          <option value="<?php echo $datavehicle[$i]['vehicle_id']; ?>"><?php echo $datavehicle[$i]['vehicle_no'].' - '.$datavehicle[$i]['vehicle_name']; ?></option>
                                      <?php } ?>
                                    </select>
                                  <?php }?>

                                  <?php
                                    if ($user_level == 3) {?>
                                      <select id="vehicle" name="vehicle" class="form-control select2" style="width:80%;">
                                        <option value="">--Select Vehicle--</option>
                                        <?php for ($i = 0; $i < sizeof($datavehicle); $i++) {?>
                                            <option value="<?php echo $datavehicle[$i]['vehicle_id']; ?>"><?php echo $datavehicle[$i]['vehicle_no'].' - '.$datavehicle[$i]['vehicle_name']; ?></option>
                                        <?php } ?>
                                      </select>
                                    <?php }?>
                                </td>
                                <td>Delivery DateTime</td>
                                <td>:</td>
                                <td>
                                  <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd-mm-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="5" type="text" readonly name="orderdate" id="orderdate" value="<?=date('d-m-Y')?>">
                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                  </div>
                                  <input type="hidden" id="dtp_input2" value=""/>
                                  <div class="input-group date form_time col-md-8" data-date="" data-date-format="hh:ii" data-link-field="dtp_input3" data-link-format="hh:ii" onclick="houronclick();">
                                    <input class="form-control" size="5" type="text" readonly id="ordertime" name="ordertime" value="<?=date("H:i",strtotime("00:00:00"))?>">
                                    <span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
                                  </div>
                                  <input type="hidden" id="dtp_input3" value=""/>
                                </td>
                              </tr>

                              <tr>
                                <td>Customer</td>
                                <td>:</td>
                                <td>
                                  <?php if (isset($datacustomer)) {?>
                                    <select class="form-control select2" name="joborder_customer" id="joborder_customer" style="width:100%;" onchange="customerchange();">
                                      <option value="0000">Not Set</option>
                                      <?php for ($i=0; $i < sizeof($datacustomer); $i++) {?>
                                        <option value="<?php echo $datacustomer[$i]['customer_id']; ?>"><?php echo $datacustomer[$i]['customer_name']; ?></option>
                                      <?php } ?>
                                    </select>
                                  <?php }else {?>
                                    No Data Customer
                                  <?php } ?>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                              </tr>

                              <tr>
                                <td>Remark</td>
                                <td>:</td>
                                <td>
                                  <input type="text" name="memoremark" id="memoremark" class="form-control">
                                </td>

                                <td></td>
                                <td></td>
                                <td></td>
                              </tr>

                              <tr>
                                <td>No. Urut</td>
                                <td>:</td>
                                <td>
                                  <input type="number" name="memonourut" id="memonourut" class="form-control">
                                </td>

                                <td></td>
                                <td></td>
                                <td></td>
                              </tr>
                            </table> <br>

                          <button type="button" class="btn btn-primary btn-sm" id="btnAddNewCustomer" onclick="addnewcustomer();">Add Customer</button>
                          <button type="button" class="btn btn-warning btn-sm" id="btnCancelAddNewCustomer" onclick="canceladdnewcustomer();" style="display:none;">Close</button>
                          <input type="text" name="addnewcustomernya" id="addnewcustomernya" value="1" hidden>

                            <div id="foraddcustomerdata" style="display:none;">
                              <div id="mapnya">
                                <div class="pac-card" id="pac-card">
                                  <div>
                                    <div id="title">
                                      Customer Address
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
                                        <label for="horizontalFormEmail" class="col-sm-2 control-label">Customer Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="joborder_customername" name="joborder_customername" required>
                                        </div>
                                    </div>
                                  </td>
                                </tr>

                                <tr>
                                  <td>
                                    <div class="form-group row">
                                        <label for="horizontalFormEmail" class="col-sm-2 control-label">Whatsapp</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="joborder_customerwhatsapp" name="joborder_customerwhatsapp">
                                        </div>
                                    </div>
                                  </td>
                                </tr>

                                <!-- <tr>
                                  <td>
                                    <div class="form-group row">
                                        <label for="horizontalFormEmail" class="col-sm-2 control-label">Geofence Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="joborder_geofencename" name="joborder_geofencename" readonly>
                                        </div>
                                    </div>
                                  </td>
                                </tr> -->

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
                            </div>

                          </div>
                        </div>
                      </div>


                    </div>

                  </form>
                    <div class="col-md-12">
                      <div class="text-right">
                        <button class="btn btn-circle btn-warning" id="btncancel" type="button" onclick="cancelformjoborder();"/>Cancel</button>
                        <button class="btn btn-circle btn-success" id="btnsavejoborder" type="button" onclick="frmjoborder_onsubmit();"/>Save</button>
                        <img src="<?php echo base_url();?>assets/transporter/images/loader2.gif" style="display: none;" id="loadernya">
                      </div>
                    </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script type="text/javascript">
  function cancelformjoborder(){
    $(".formjoborder").hide();
    $(".tbjoborder").show();
  }

  function houronclick(){
		// console.log("ok");
		$(".switch").html("<?php echo date("Y F d")?>");
	}

  function gotoformjoborder(){
    $(".formjoborder").show();
    $(".tbjoborder").hide();
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

  function addnewcustomer(){
    $("#btnAddNewCustomer").hide();
    $("#foraddcustomerdata").show();
    $("#btnCancelAddNewCustomer").show();
  }

  function canceladdnewcustomer(){
    $("#latitude").val("");
    $("#longitude").val("");
    $("#addressfix").val("");
    $("#pac-input").val("");
    $("#addnewcustomernya").val("0");
    $("#btnAddNewCustomer").show();
    $("#foraddcustomerdata").hide();
    $("#btnCancelAddNewCustomer").hide();
  }

  function frmjoborder_onsubmit(){
    $("#loadernya").show();
    $.post("<?php echo base_url() ?>joborder/savetojoborder", jQuery("#frmjoborder").serialize(), function(response){
      console.log("response : ", response);
      if (response.code == 400) {
        $("#loadernya").hide();
        alert(response.msg);
        // if (confirm(response.msg)) {
        //   window.location = '<?php echo base_url() ?>memosales';
        // }
      }else if (response.code == 100) {
        $("#loadernya").hide();
        console.log("masuk");
        alert(response.msg);
        return false;
      }else {
        $("#loadernya").hide();
        if (confirm("Success save joborder")) {
          window.location = '<?php echo base_url() ?>joborder';
        }
      }
    }, "json");
  }

  function customerchange(){
    var value = $("#joborder_customer").val();
      if (value == 0000) {
        $("#addnewcustomernya").val(1);
        $("#btnAddNewCustomer").show();
        $("#foraddcustomerdata").hide();
        $("#btnCancelAddNewCustomer").hide();
      }else {
        $("#addnewcustomernya").val(0);
        $("#btnAddNewCustomer").hide();
        $("#foraddcustomerdata").hide();
        $("#btnCancelAddNewCustomer").hide();
      }
  }

  function sharethislink(orderid){
      console.log("id share: ", orderid);

      var myDate  = "<?php echo date("d-m-Y")?>";
      myDate      = myDate.split("-");
      var newDate = new Date( myDate[2], myDate[1] - 1, myDate[0]);
      // console.log("newDate : ", newDate);
      var unique  = newDate.getTime();
      // console.log("unique : ", unique);
      $.post("<?php echo base_url() ?>joborder/linkformat", {id: orderid, unique:unique}, function(response){
        console.log("response : ", response);
        $("#fixlink").val('<?php echo base_url() ?>share/realtime/'+response.unique);
        var copyText = document.getElementById("fixlink");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        alert("Copy To Clipboard");
      }, "json");
  }

  function sharelinkforinternal(value){
    // console.log("value : ", value);
    var data = value.split("||");
    // console.log("data : ", data);
    var myDate  = "<?php echo date("d-m-Y")?>";
    myDate      = myDate.split("-");
    var newDate = new Date( myDate[2], myDate[1] - 1, myDate[0]);
    // console.log("newDate : ", newDate);
    var unique  = newDate.getTime();
    // console.log("unique : ", unique);
    $.post("<?php echo base_url() ?>joborder/internalsharelink", {id: data[0], startdatetime:data[1], unique:unique}, function(response){
      console.log("response : ", response);
      $("#fixlink2").val('<?php echo base_url() ?>share/bynopol/'+response.unique);
      var copyText = document.getElementById("fixlink2");
      copyText.select();
      copyText.setSelectionRange(0, 99999);
      document.execCommand("copy");
      alert("Copy To Clipboard");
    }, "json");
  }

  function changestatus(id){
    console.log("id : ", id);
    if (confirm("Anda yakin akan menyelesaikan pengiriman ini?")) {
      $.post("<?php echo base_url() ?>joborder/changethisstatus", {id: id}, function(response){
        console.log("response : ", response);
        window.location = '<?php echo base_url()?>joborder';
      }, "json");
    }
  }

  function canceljoborder(id){
    console.log("id : ", id);
    if (confirm("Anda yakin akan membatalkan pengiriman ini?")) {
      $.post("<?php echo base_url() ?>joborder/cancelthisjoborder", {id: id}, function(response){
        console.log("response : ", response);
        window.location = '<?php echo base_url()?>joborder';
      }, "json");
    }
  }

  function searchsubbranchoffice(){
    $("#vehiclefix").html("");
    var branchofficeid = $("#branchoffice").val();
    console.log("branchofficeid : ", branchofficeid);
    $.post("<?php echo base_url() ?>joborder/subbranchofficebybranchid", {branchofficeid: branchofficeid}, function(response){
      console.log("response : ", response);
      // DATA PICKUP
        if (response.pickup.length > 0) {
          var htmlsubbranchoffice = "";
            htmlsubbranchoffice += '<select class="form-control select2" name="subbranchoffice" id="subbranchoffice" onchange="searchvehiclebysubbranchoffice();">';
              htmlsubbranchoffice += '<option value="">--Select Sub Branch Office--</option>';
              for (var i = 0; i < response.pickup.length; i++) {
                htmlsubbranchoffice += '<option value="'+response.pickup[i].subcompany_id+'">'+response.pickup[i].subcompany_name+'</option>';
              }
            htmlsubbranchoffice += '</select> <br>';
          $("#showsubbranchoffice").html(htmlsubbranchoffice);
          $("#showsubbranchoffice").show();
        }else {
          $("#showsubbranchoffice").html("");
          $("#showsubbranchoffice").hide();
        }

      // DATA VEHICLE
      if (response.vehicle.length > 0) {
        var htmlvehiclefix = "";
          htmlvehiclefix += '<select id="vehicle" name="vehicle" class="form-control select2" style="width:80%;">';
            htmlvehiclefix += '<option value="">--Select Vehicle--</option>';
            for (var j = 0; j < response.vehicle.length; j++) {
                htmlvehiclefix += '<option value="'+response.vehicle[j].vehicle_id+'">'+response.vehicle[j].vehicle_no+ ' - ' + response.vehicle[j].vehicle_name + '</option>';
            }
          htmlvehiclefix += '</select>';
        $("#vehiclefix").html(htmlvehiclefix);
      }else {
        $("#vehiclefix").html("Vehicle is Empty");
      }
    }, "json");
  }

  function searchvehiclebysubbranchoffice(){
    var subbranchoffice = $("#subbranchoffice").val();
    console.log("subbranchoffice : ", subbranchoffice);
    $.post("<?php echo base_url() ?>joborder/vehiclebysubbranchofficeid", {subbranchoffice: subbranchoffice}, function(response){
      console.log("response : ", response);
      // DATA VEHICLE
      if (response.vehicle.length > 0) {
        var htmlvehiclefix = "";
          htmlvehiclefix += '<select id="vehicle" name="vehicle" class="form-control select2" style="width:80%;">';
            htmlvehiclefix += '<option value="">--Select Vehicle--</option>';
            for (var j = 0; j < response.vehicle.length; j++) {
                htmlvehiclefix += '<option value="'+response.vehicle[j].vehicle_id+'">'+response.vehicle[j].vehicle_no+ ' - ' + response.vehicle[j].vehicle_name + '</option>';
            }
          htmlvehiclefix += '</select>';
        $("#vehiclefix").html(htmlvehiclefix);
      }else {
        $("#vehiclefix").html("Vehicle is Empty");
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
